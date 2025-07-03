<?php namespace App\Models;

use CodeIgniter\Shield\Entities\User;

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

        // Add to default group
        $users->addToDefaultGroup($user);
    }

    public function update(array $data, $id = null)
    {
        // Get the User Provider (UserModel by default)
        $users = auth()->getProvider();

        if($id === null) {
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
    }

    public function deleteUser(string $email)
    {
        // Get the User Provider (UserModel by default)
        $users = auth()->getProvider();
        $user = $users->findByCredentials(['email' => $email]);

        $users->delete($user->id, true);
    }
}