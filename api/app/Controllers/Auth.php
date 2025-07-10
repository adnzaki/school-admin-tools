<?php

namespace App\Controllers;

use App\Models\AuthModel;
use App\Models\UserModel;

class Auth extends BaseController
{
    private $model;

    public function __construct()
    {
        $this->model = new AuthModel();
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
            'oldPassword'   => ['required' => $this->messages['required']],
            'newPassword'   => ['required' => $this->messages['required']],
            'confirmNewPassword'   => ['required' => $this->messages['required'], 'matches' => $this->messages['matches']]
        ];

        return (object)['rules' => $rules, 'messages' => $messages];
    }

    public function validateLogin()
    {
        $credentials = $this->request->getPost(['username', 'password']);

        // $rememberMe = $this->request->getPost('rememberMe') === 1 ? true : false;

        $getToken = $this->model->validateLogin($credentials);
        if ($getToken['status'] !== false) {
            return $this->response->setJSON([
                'status'    => 'success',
                'token'     => $getToken['token'],
                'user'      => auth()->getProvider()->findById(auth()->id())
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
        delete_cookie('sisauang_api_session');

        return $this->response->setJSON(['status' => 'success']);
    }

    public function logout()
    {
        if (auth()->loggedIn()) {
            auth()->logout();
            delete_cookie('sisauang_api_session');
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function validatePageRequest()
    {
        $status = valid_access() ? 200 : 503;

        return $this->response->setJSON($this->setStatus($status));
    }
}
