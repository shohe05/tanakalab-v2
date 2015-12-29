<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface as UserRepository;
use App\Services\Contracts\UserServiceInterface;
use Validator;

class UserService implements UserServiceInterface
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param $input
     * @return \Illuminate\Validation\Validator
     */
    public function validator($input)
    {
        return Validator::make($input, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * @param $input
     * @return mixed
     */
    public function create($input)
    {
        return $this->userRepository->create([
            'name' => array_get($input, 'name'),
            'email' => array_get($input, 'email'),
            'password' => bcrypt(array_get($input, 'password')),
        ]);
    }

    /**
     * @param $id
     * @return int
     */
    public function delete($id)
    {
        return $this->userRepository->delete($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * @param $user
     * @return array
     */
    public function formatForShow($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
            'articles' => $user->articles,
            'comments' => $user->comments,
            'clips' => $user->clips,
        ];
    }
}