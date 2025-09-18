<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Models\UserModel;
use App\Models\InstitusiModel;
use App\Models\UserInstitusiModel;

class Auth extends BaseController
{
    private $model;
    private $institusiModel;
    private $userInstitusiModel;

    public function __construct()
    {
        $this->model = new AuthModel();
        $this->institusiModel = new InstitusiModel();
        $this->userInstitusiModel = new UserInstitusiModel();
    }

    public function updatePassword()
    {
        if (valid_access()) {
            $validation = $this->formValidation();
            $data = $this->request->getPost(array_keys($validation->rules));

            if (! $this->validateData($data, $validation->rules, $validation->messages)) {
                return $this->response->setJSON([
                    'code'  => 500,
                    'msg'   => $this->validator->getErrors(),
                ]);
            } else {
                $credentials = [
                    'username' => auth()->user()->username,
                    'password' => $data['oldPassword']
                ];

                $loginAttempt = auth('session')->attempt($credentials);
                if ($loginAttempt->isOK()) {
                    $userModel = new UserModel;
                    $fillUser = [
                        'username' => auth()->user()->username,
                        'email' => auth()->user()->email,
                        'password' => $data['newPassword']
                    ];

                    $userModel->update($fillUser);

                    return $this->response->setJSON([
                        'code'  => 200,
                        'msg'   => 'Password berhasil diubah',
                    ]);
                } else {
                    return $this->response->setJSON([
                        'code'  => 503,
                        'msg'   => 'Password lama yang anda masukkan salah.',
                    ]);
                }
            }
        }
    }

    public function formValidation()
    {
        $rules = [
            'oldPassword'   => ['label' => 'password saat ini', 'rules' => 'required'],
            'newPassword'   => ['label' => 'password baru', 'rules' => 'required'],
            'confirmNewPassword'   => ['label' => 'konfirmasi password', 'rules' => 'required|matches[newPassword]']
        ];

        $messages = [
            'oldPassword'   => ['required' => lang('Validation.required')],
            'newPassword'   => ['required' => lang('Validation.required')],
            'confirmNewPassword'   => ['required' => lang('Validation.required'), 'matches' => lang('Validation.matches')]
        ];

        return (object)['rules' => $rules, 'messages' => $messages];
    }

    public function validateLogin()
    {
        $credentials = $this->request->getPost(['username', 'password']);

        // cek dulu, apakah institusi dari user yg akan login tersebut aktif?
        $user = auth()->getProvider()->findByCredentials(['username' => $credentials['username']]);
        if ($user === null) {
            return $this->response->setJSON([
                'status'    => 'notfound',
                'reason'    => lang('General.userNotFound')
            ]);
        }

        $institusiId = $this->userInstitusiModel->getInstitusiIdByCurrentUser($user->id);
        $isActive = $this->institusiModel->isInstitusiAktif($institusiId);
        if (! $isActive) {
            return $this->response->setJSON([
                'status'    => 'blocked',
                'reason'    => lang('General.inactiveInstitute')
            ]);
        }

        $getToken = $this->model->validateLogin($credentials);
        if ($getToken['status'] !== false) {
            $user = auth()->getProvider()->findById(auth()->id());

            return $this->response->setJSON([
                'status'    => 'success',
                'token'     => $getToken['token'],
                'user'      => [
                    'id'    => encrypt($user->id, env('encryption_key')),
                    'name'  => $user->username
                ]
            ]);
        } else {
            return $this->response->setJSON([
                'status'    => 'failed',
                'reason'    => $getToken['reason']
            ]);
        }
    }

    public function deleteDefaultCookie()
    {
        delete_cookie('sakola_session');

        return $this->response->setJSON(['status' => 'success']);
    }

    public function logout()
    {
        if (auth()->loggedIn()) {
            auth()->logout();
            delete_cookie('sakola_session');
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function validatePageRequest()
    {
        $status = valid_access() ? 200 : 503;

        return $this->response->setJSON($this->setStatus($status));
    }
}
