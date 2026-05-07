<?php

namespace App\Controllers;

use App\Models\CodeModel;
use App\Models\UserModel;
use CodeIgniter\Config\Services;

class Auth extends BaseController
{
    protected $user;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return redirect()->to('dashboard');
    }

    public function login()
    {
        if (session()->has('userid'))
            return redirect()->to('dashboard');

        if ($this->request->getPost())
            return $this->login_action();

        $data = [
            'title' => 'Login',
            'validation' => Services::validation(),
        ];
        return view('Auth/login', $data);
    }

    public function register()
    {
        if (session()->has('userid'))
            return redirect()->to('dashboard');

        if ($this->request->getPost())
            return $this->register_action();
        $data = [
            'title' => 'Register',
            'validation' => Services::validation(),
        ];
        return view('Auth/register', $data);
    }

    private function login_action()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $stay_log = $this->request->getPost('stay_log');

        $form_rules = [
            'username' => [
                'label' => 'username',
                'rules' => 'required|alpha_numeric|min_length[4]|max_length[25]|is_not_unique[users.username]',
                'errors' => [
                    'is_not_unique' => 'The {field} is not registered.'
                ]
            ],
            'password' => [
                'label' => 'password',
                'rules' => 'required|min_length[6]|max_length[45]',
            ],
            'stay_log' => [
                'rules' => 'permit_empty|max_length[3]'
            ]
        ];

        if (!$this->validate($form_rules)) {
            return redirect()->route('login')->withInput()->with('msgDanger', '<strong>Failed</strong> Please check the form.');
        }

        $cekUser = $this->userModel->getUser($username, 'username');
        if (!$cekUser) {
            return redirect()->route('login')->withInput()->with('msgDanger', '<strong>Failed</strong> Username không tồn tại.');
        }

        $hashPassword = create_password($password, false);
        if (!password_verify($hashPassword, $cekUser->password)) {
            return redirect()->route('login')->withInput()->with('msgDanger', '<strong>Failed</strong> Sai mật khẩu.');
        }

        $time = new \CodeIgniter\I18n\Time;
        $sessionData = [
            'userid'   => $cekUser->id_users,
            'unames'   => $cekUser->username,
            'time_login' => $time::now()->addHours($stay_log ? 24 : 12),
            'time_since' => $time::now(),
        ];
        session()->set($sessionData);
        return redirect()->to('dashboard');
    }

    public function register_action()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $referral = $this->request->getPost('referral');

        $form_rules = [
            'username' => [
                'label' => 'username',
                'rules' => 'required|alpha_numeric|min_length[4]|max_length[25]|is_unique[users.username]',
                'errors' => [
                    'is_unique' => 'The {field} has been taken.'
                ]
            ],
            'password' => [
                'label' => 'password',
                'rules' => 'required|min_length[6]|max_length[45]',
            ],
            'password2' => [
                'label' => 'password',
                'rules' => 'required|min_length[6]|max_length[45]|matches[password]',
                'errors' => [
                    'matches' => '{field} not match, check the {field}.'
                ]
            ],
            'referral' => [
                'label' => 'referral',
                'rules' => 'permit_empty|min_length[6]|alpha_numeric',
            ]
        ];

        if (!$this->validate($form_rules)) {
            return redirect()->route('register')->withInput()->with('msgDanger', '<strong>Failed</strong> Please check the form.');
        }

        $hashPassword = create_password($password);
        $validation = Services::validation();

        // Check if referral code provided
        if (!empty($referral)) {
            $mCode = new CodeModel();
            $rCheck = $mCode->checkCode($referral);

            if (!$rCheck) {
                $validation->setError('referral', 'Invalid referral code.');
                return redirect()->route('register')->withInput()->with('msgDanger', '<strong>Failed</strong> Invalid referral code.');
            }

            if ($rCheck->used_by) {
                $validation->setError('referral', "Referral code has been used by $rCheck->used_by.");
                return redirect()->route('register')->withInput()->with('msgDanger', '<strong>Failed</strong> Referral code already used.');
            }

            // Register with referral bonus
            $data_register = [
                'username' => $username,
                'password' => $hashPassword,
                'saldo' => $rCheck->set_saldo ?: 0,
                'uplink' => $rCheck->created_by
            ];

            $ids = $this->userModel->insert($data_register, true);
            if ($ids) {
                $mCode->useReferral($referral);
                $msg = "Register Successfully! You received " . number_format($rCheck->set_saldo, 0, ',', '.') . "₫ bonus.";
                return redirect()->to('login')->with('msgSuccess', $msg);
            }
        } else {
            // Register without referral (default user)
            $data_register = [
                'username' => $username,
                'password' => $hashPassword,
                'saldo' => 0,
                'uplink' => null
            ];

            $ids = $this->userModel->insert($data_register, true);
            if ($ids) {
                $msg = "Register Successfully!";
                return redirect()->to('login')->with('msgSuccess', $msg);
            }
        }

        return redirect()->route('register')->withInput()->with('msgDanger', '<strong>Failed</strong> Something went wrong.');
    }

    public function logout()
    {
        if (session()->has('userid')) {
            $unset = ['userid', 'unames', 'time_login', 'time_since'];
            session()->remove($unset);
            session()->setFlashdata('msgSuccess', 'Logout successfuly.');
        }
        return redirect()->to('login');
    }
}
