<?php

namespace App\Services;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserService
{
    const USERS_CACHE_KEY = 'all_users';

    public function listUser()
    {
        $page = request('page', 1);
        $cacheKey = self::USERS_CACHE_KEY . '_page_' . $page;
        return Cache::remember($cacheKey, 60 * 60, function () {
            return User::paginate(10);
        });
    }

    public function createUser(array $data)
    {
        
        if (User::where('email', $data['email'])->exists()) 
            throw new \Exception('Já existe um usuário cadastrado com este e-mail.');
        

        if (User::where('registration_number', $data['registration_number'])->exists())
            throw new \Exception('Já existe um usuário cadastrado com este número de matrícula.');
        

        $user = User::create($data);
        Cache::forget(self::USERS_CACHE_KEY);
        return $user;
    }

    public function getUserById(string $id)
    {

        $user = User::find($id);

        if (!$user) {
            throw new \Exception('Usuário não encontrado.');
        }

        return $user;
    }

    public function updateUser(string $id, array $data)
    {
        if (!User::where('id', $id)->exists())
            throw new \Exception('Usuário não encontrado.');
        
        if (isset($data['email']) && User::where('email', $data['email'])->where('id', '!=', $id)->exists())  throw new \Exception('Já existe um usuário cadastrado com este e-mail.');

        if (isset($data['registration_number']) 
            && User::where('registration_number', $data['registration_number'])->where('id', '!=', $id)->exists())
            throw new \Exception('Já existe um usuário cadastrado com este número de matrícula.');
        
        $user = User::findOrFail($id);
        $user->update($data);
        Cache::forget(self::USERS_CACHE_KEY);
        return $user;
    }
    public function deleteUser(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        Cache::forget(self::USERS_CACHE_KEY);
        return true;
    }
} 