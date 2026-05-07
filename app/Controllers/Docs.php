<?php

namespace App\Controllers;

use App\Models\UserModel;

class Docs extends BaseController
{
    protected $userModel, $user;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->user = $this->userModel->getUser();
    }

    public function api()
    {
        $data = [
            'title' => 'API Documentation',
            'user' => $this->user,
        ];
        return view('Docs/api', $data);
    }
}
