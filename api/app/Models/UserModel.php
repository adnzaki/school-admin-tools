<?php

namespace App\Models;

use CodeIgniter\Shield\Entities\User;
use App\Models\UserInstitusiModel;

class UserModel
{
    public function create(array $data)
    {
        // Get the User Provider (UserModel by default)
        $users = auth()->getProvider();

        $user = new User([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'active'   => 1
        ]);
        $users->save($user);

        // To get the complete user object with ID, we need to get from the database
        $user = $users->findById($users->getInsertID());

        // insert to user_institusi table
        $userInstitusiModel = new UserInstitusiModel();
        $userInstitusiModel->insert([
            'user_id' => $user->id,
            'institusi_id' => $data['institusi_id']
        ]);

        // Add to default group
        $users->addToDefaultGroup($user);
    }

    public function update(array $data, $id = null)
    {
        // Get the User Provider (UserModel by default)
        $users = auth()->getProvider();

        if ($id === null) {
            $user = $users->findById(auth()->id());
        } else {
            $user = $users->findById($id);
        }

        $user->fill([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);
        $users->save($user);

        $userInstitusiModel = new UserInstitusiModel();
        $userInstitusiModel->update([
            'institusi_id' => $data['institusi_id']
        ], ['user_id' => $user->id]);
    }

    public function deleteUser(string $username)
    {
        // Get the User Provider (UserModel by default)
        $users = auth()->getProvider();
        $user = $users->findByCredentials(['username' => $username]);

        $users->delete($user->id, true);
    }

    public function checkUserExists(string $username, string $email): bool
    {
        // Get the User Provider (UserModel by default)
        $users = auth()->getProvider();

        return $users->findByCredentials(['username' => $username]) !== null || $users->findByCredentials(['email' => $email]) !== null;
    }
}
