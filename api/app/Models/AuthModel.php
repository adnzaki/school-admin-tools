<?php namespace App\Models;

use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class AuthModel extends UserModel
{
    /**
     * Validate user credentials
     *
     * @param array $credentials 
     * 
     * @return array
     */
    public function validateLogin(array $credentials)
    {
        if (auth()->loggedIn()) {
            auth()->logout();
        }
        
        $loginAttempt = auth('session')->attempt($credentials);

        if ($loginAttempt->isOK()) {
            $user = new UserModel();
            $userData = $user->find(auth()->id());
            $token = $userData->generateAccessToken('SisaUang API');

            return ['status' => true, 'token' => $token->raw_token];
        } else {
            return ['status' => false, 'reason' => $loginAttempt->reason()];
        }
    }
}