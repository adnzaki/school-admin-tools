<?php namespace App\Controllers;

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
        if($this->validateUser()) {
            $data = $this->request->getPost(['username', 'email', 'password']);
            $this->model->create($data);
    
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON($this->message);
        }
    }

    public function updateUser($id = null)
    {
        if ($this->validateUser()) {
            $data = $this->request->getPost(['username', 'email', 'password']);
            $this->model->update($data, $id);
    
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON($this->message);
        }
    }

    public function deleteUser()
    {
        if ($this->validateUser()) {
            $email = $this->request->getPost('email');
            $this->model->deleteUser($email);    
    
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