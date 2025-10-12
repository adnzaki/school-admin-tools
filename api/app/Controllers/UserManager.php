<?php

namespace App\Controllers;

class UserManager extends BaseController
{
    private $model;

    private $message = ['status' => 'failed', 'message' => 'You are not authorized to access this feature.'];

    public function __construct()
    {
        $this->model = new \App\Models\UserModel();
    }

    public function createUser()
    {
        if ($this->validateUser()) {
            $data = $this->request->getPost(['username', 'email', 'password', 'institusi_id']);
            if($this->model->checkUserExists($data['username'], $data['email'])) return $this->response->setJSON(['status' => 'failed', 'message' => 'User already exists.']);
            $this->model->create($data);

            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON($this->message);
        }
    }

    public function updateUser($id = null)
    {
        if ($this->validateUser()) {
            $data = $this->request->getPost(['username', 'email', 'password', 'institusi_id']);
            $this->model->update($data, $id);

            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON($this->message);
        }
    }

    public function deleteUser()
    {
        if ($this->validateUser()) {
            $username = $this->request->getPost('username');
            $this->model->deleteUser($username);

            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON($this->message);
        }
    }

    private function validateUser()
    {
        $credentials = $this->request->getPost(['dev_username', 'dev_password']);

        return $credentials['dev_username'] === env('dev_username') && $credentials['dev_password'] === env('dev_password');
    }
}
