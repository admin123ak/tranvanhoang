<?php

namespace App\Controllers;

use App\Models\UserModel;

class Server extends BaseController
{
    protected $userModel, $user;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->user = $this->userModel->getUser();
    }

    public function index()
    {
        // Check if user is admin
        if ($this->user->level != 1) {
            return redirect()->to('dashboard')->with('msgDanger', 'Access denied');
        }

        $data = [
            'title' => 'Server Control',
            'user' => $this->user,
            'validation' => \Config\Services::validation()
        ];

        // Handle form submissions
        if ($this->request->getPost()) {
            include(APPPATH . 'Controllers/conn.php');

            // Handle Server Status form
            if ($this->request->getPost('status_form')) {
                $radios = $this->request->getPost('radios');
                $myInput = $this->request->getPost('myInput');
                $status = ($radios == 1) ? 'on' : 'off';

                $sql = "UPDATE onoff SET status='$status', myinput='$myInput' WHERE id=11";
                if (mysqli_query($conn, $sql)) {
                    return redirect()->back()->with('msgSuccess', 'Server status updated successfully');
                }
            }

            // Handle Mod Name form
            if ($this->request->getPost('modname_form')) {
                $modname = $this->request->getPost('modname');

                if (!$this->validate(['modname' => 'required|min_length[1]|max_length[100]'])) {
                    $data['validation'] = $this->validator;
                } else {
                    $sql = "UPDATE modname SET modname='$modname' WHERE id=1";
                    if (mysqli_query($conn, $sql)) {
                        return redirect()->back()->with('msgSuccess', 'Mod name updated successfully');
                    }
                }
            }

            // Handle Floating Text form
            if ($this->request->getPost('_ftext')) {
                $ftextr = $this->request->getPost('_ftextr');
                $ftextInput = $this->request->getPost('_ftextInput');
                $status = ($ftextr == 1) ? 'on' : 'off';

                if (!$this->validate(['_ftextInput' => 'required|min_length[1]|max_length[100]'])) {
                    $data['validation'] = $this->validator;
                } else {
                    $sql = "UPDATE _ftext SET _status='$status', _ftext='$ftextInput' WHERE id=1";
                    if (mysqli_query($conn, $sql)) {
                        return redirect()->back()->with('msgSuccess', 'Floating text updated successfully');
                    }
                }
            }
        }

        return view('Server/Server', $data);
    }
}
