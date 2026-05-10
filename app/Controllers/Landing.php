<?php

namespace App\Controllers;

class Landing extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'AuthTool - Công cụ quản lý License Key',
        ];
        return view('landing', $data);
    }
}