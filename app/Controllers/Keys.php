<?php

namespace App\Controllers;

use App\Models\HistoryModel;
use App\Models\KeysModel;
use App\Models\UserModel;
use App\Models\PackageModel;
use Config\Services;

class Keys extends BaseController
{
    protected $userModel, $model, $user, $packageModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->user = $this->userModel->getUser();
        $this->model = new KeysModel();
        $this->packageModel = new PackageModel();
        $this->time = new \CodeIgniter\I18n\Time;

        // Load duration and price from key_pricing table
        $this->duration = [];
        $this->price = [];
        $db = \Config\Database::connect();
        try {
            // Ensure table exists
            $db->query("CREATE TABLE IF NOT EXISTS `key_pricing` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `duration_hours` int(11) NOT NULL,
                `price` bigint(20) NOT NULL DEFAULT '0',
                `description` varchar(255) DEFAULT NULL,
                `status` tinyint(1) NOT NULL DEFAULT '1',
                `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
                `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `duration_hours` (`duration_hours`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

            // Insert defaults if empty
            $checkExists = $db->query("SELECT COUNT(*) as cnt FROM key_pricing")->getRow();
            if ($checkExists && $checkExists->cnt == 0) {
                $db->query("INSERT INTO `key_pricing` (`duration_hours`, `price`, `description`, `status`) VALUES
                    (24, 10000, '1 ngày', 1),
                    (168, 50000, '7 ngày', 1),
                    (720, 150000, '30 ngày', 1)");
            }

            $rows = $db->query("SELECT * FROM key_pricing WHERE status = 1 ORDER BY duration_hours ASC")->getResult();
            foreach ($rows as $row) {
                $h = (int)$row->duration_hours;
                $p = (int)$row->price;
                $desc = $row->description ?: $h . ' giờ';
                $this->duration[$h] = $desc . ' — ' . number_format($p, 0, ',', '.') . '₫';
                $this->price[$h] = $p;
            }
        } catch (\Exception $e) {
            log_message('error', 'Duration load failed: ' . $e->getMessage());
            // Fallback
            $this->duration = [
                24 => '1 ngày — 10.000₫',
                168 => '7 ngày — 50.000₫',
                720 => '30 ngày — 150.000₫',
            ];
            $this->price = [
                24 => 10000,
                168 => 50000,
                720 => 150000,
            ];
        }
    }

    public function index()
    {
        $model = $this->model;
        $user = $this->user;

        if ($user->level != 1) {
            $keys = $model->where('registrator', $user->username)
                ->findAll();
        } else {
            $keys = $model->findAll();
        }

        $data = [
            'title' => 'Keys',
            'user' => $user,
            'keylist' => $keys,
            'time' => $this->time,
        ];
        return view('Keys/list', $data);
    }

    public function api_get_keys()
    {
        // ? API for DataTable Keys
        $model = $this->model;
        return $model->API_getKeys();
    }
    
    public function deleteExpired(){
    echo  date('Y-m-d H:i:s');
    $model=$this->model;
    $data=$model->where('expired_date <',  date('Y-m-d H:i:s'))->delete();
    return redirect()->back()->with('msgSuccess', 'success');
}
//delete wasted keys
public function deleteUnused(){
    echo  date('Y-m-d H:i:s');
    $model=$this->model;
    $data=$model->where('expired_date ='.null)->delete();
    return redirect()->back()->with('msgSuccess', 'success');
    
}

    public function devices()
    {
        $model = $this->model;
        $user = $this->user;

        if ($user->level != 1) {
            $devices = $model->where('registrator', $user->username)
                ->where('devices IS NOT', null)
                ->findAll();
        } else {
            $devices = $model->where('devices IS NOT', null)
                ->findAll();
        }

        $data = [
            'title' => 'Device Management',
            'user' => $user,
            'devices' => $devices,
        ];
        return view('Keys/devices', $data);
    }

    public function api_key_reset()
    {
        sleep(1);
        $model = $this->model;
        $keys = $this->request->getGet('userkey');
        $reset = $this->request->getGet('reset');
        $db_key = $model->getKeys($keys);

        $rules = [];
        if ($db_key) {
            $total = $db_key->devices ? explode(',', $db_key->devices) : [];
            $rules = ['devices_total' => count($total), 'devices_max' => (int) $db_key->max_devices];
            $user = $this->user;
            if ($db_key->devices and $reset) {
                if ($user->level == 1 or $db_key->registrator == $user->username) {
                    $model->set('devices', NULL)
                        ->where('user_key', $keys)
                        ->update();
                    $rules = ['reset' => true, 'devices_total' => 0, 'devices_max' => $db_key->max_devices];
                }
            } else {
            }
        }

        $data = [
            'registered' => $db_key ? true : false,
            'keys' => $keys,
        ];

        $real_response = array_merge($data, $rules);
        return $this->response->setJSON($real_response);
    }

    public function edit_key($key = false)
    {
        if ($this->request->getPost()) return $this->edit_key_action();
        $msgDanger = "The user key no longer exists.";
        if ($key) {
            $dKey = $this->model->getKeys($key, 'id_keys');
            $user = $this->user;
            if ($dKey) {
                if ($user->level == 1 or $dKey->registrator == $user->username) {
                    $validation = Services::validation();

                    // Get active packages for dropdown
                    $package_list = $this->packageModel->getAllPackagesList();

                    $data = [
                        'title' => 'Key',
                        'user' => $user,
                        'key' => $dKey,
                        'packages' => $package_list,
                        'time' => $this->time,
                        'key_info' => getDevice($dKey->devices),
                        'messages' => setMessage('Please carefuly edit information'),
                        'validation' => $validation,
                    ];
                    return view('Keys/key_edit', $data);
                } else {
                    $msgDanger = "Restricted to this user key.";
                }
            }
        }
        return redirect()->to('keys')->with('msgDanger', $msgDanger);
    }

    private function edit_key_action()
    {
        $keys = $this->request->getPost('id_keys');
        $user = $this->user;
        $dKey = $this->model->getKeys($keys, 'id_keys');

        // Get active packages for game validation
        $activePackages = $this->packageModel->getActivePackages();
        $packageNames = [];
        if (is_array($activePackages)) {
            foreach ($activePackages as $p) {
                $name = is_object($p) ? $p->package_name : ($p['package_name'] ?? '');
                if ($name) $packageNames[] = $name;
            }
        }
        $gameList = implode(",", $packageNames);

        if (!$dKey) {
            $msgDanger = "The user key no longer exists~";
        } else {
            if ($user->level == 1 or $dKey->registrator == $user->username) {
                $form_reseller = [
                    'status' => [
                        'label' => 'status',
                        'rules' => 'required|integer|in_list[0,1]',
                        'erros' => [
                            'integer' => 'Invalid {field}.',
                            'in_list' => 'Choose between list.'
                        ]
                    ]
                ];
                $form_admin = [
                    'id_keys' => [
                        'label' => 'keys',
                        'rules' => 'required|is_not_unique[keys_code.id_keys]|numeric',
                        'errors' => [
                            'is_not_unique' => 'Invalid keys.'
                        ],
                    ],
                    'game' => [
                        'label' => 'Games',
                        'rules' => "required|alpha_numeric_space|in_list[$gameList]",
                        'errors' => [
                            'alpha_numeric_space' => 'Invalid characters.'
                        ],
                    ],
                    'user_key' => [
                        'label' => 'User keys',
                        'rules' => "required|is_unique[keys_code.user_key,user_key,$dKey->user_key]|alpha_numeric",
                        'errors' => [
                            'is_unique' => '{field} has been taken.'
                        ],
                    ],
                    'duration' => [
                        'label' => 'duration',
                        'rules' => 'required|numeric|greater_than_equal_to[1]',
                        'errors' => [
                            'greater_than_equal_to' => 'Minimum {field} is invalid.',
                            'numeric' => 'Invalid Hours {field}.'
                        ]
                    ],
                    'max_devices' => [
                        'label' => 'devices',
                        'rules' => 'required|numeric|greater_than_equal_to[1]',
                        'errors' => [
                            'greater_than_equal_to' => 'Minimum {field} is invalid.',
                            'numeric' => 'Invalid max of {field}.'
                        ]
                    ],
                    'registrator' => [
                        'label' => 'registrator',
                        'rules' => 'permit_empty|alpha_numeric_space|min_length[4]'
                    ],
                    'expired_date' => [
                        'label' => 'expired',
                        'rules' => 'permit_empty|valid_date[Y-m-d H:i:s]',
                        'errors' => [
                            'valid_date' => 'Invalid {field} date.',
                        ]
                    ],
                    'devices' => [
                        'label' => 'device list',
                        'rules' => 'permit_empty'
                    ]
                ];

                if ($user->level == 1) {
                    // Admin full rules.
                    $form_rules = array_merge($form_reseller, $form_admin);
                    $devices = $this->request->getPost('devices');
                    $max_devices = $this->request->getPost('max_devices');

                    $data_saves = [
                        'game' => $this->request->getPost('game'),
                        'user_key' => $this->request->getPost('user_key'),
                        'duration' => $this->request->getPost('duration'),
                        'max_devices' => $max_devices,
                        'status' => $this->request->getPost('status'),
                        'registrator' => $this->request->getPost('registrator'),
                        'expired_date' => $this->request->getPost('expired_date') ?: NULL,
                        'devices' => setDevice($devices, $max_devices),
                    ];
                } else {
                    // Reseller just status rules, you can set manually later.
                    $form_rules = $form_reseller;
                    $data_saves = ['status' => $this->request->getPost('status')];
                }

                if (!$this->validate($form_rules)) {
                    return redirect()->back()->withInput()->with('msgDanger', 'Failed! Please check the error');
                } else {
                    // * Data Updates
                    $this->model->update($dKey->id_keys, $data_saves);
                    return redirect()->back()->with('msgSuccess', 'User key successfuly updated!');
                }
            } else {
                $msgDanger = "Restricted to this user key~";
            }
        }
        return redirect()->to('keys')->with('msgDanger', $msgDanger);
    }

    public function generate()
    {
        if ($this->request->getPost())
            return $this->generate_action();

        $user = $this->user;
        $validation = Services::validation();

        $message = setMessage("<i class='bi bi-wallet'></i> Total Saldo - " . number_format($user->saldo, 0, ',', '.') . "₫");
        if ($user->saldo <= 0) {
            $message = setMessage("Please top up to your beloved admin.", 'warning');
        }

        // Get active packages
        $package_list = $this->packageModel->getAllPackagesList();

        $data = [
            'title' => 'Generate',
            'user' => $user,
            'time' => $this->time,
            'packages' => $package_list,
            'duration' => $this->duration,
            'price' => json_encode($this->price),
            'messages' => $message,
            'validation' => $validation,
        ];
        return view('Keys/generate', $data);
    }
    

    private function generate_action()
    {

        $user = $this->user;
        $package_id = $this->request->getPost('package_id');
        $maxd = $this->request->getPost('max_devices');
        $drtn = $this->request->getPost('duration');
        $getPrice = getPrice($this->price, $drtn, $maxd);

        $loopcount =  $this->request->getPost('loopcount');

        if ($loopcount == "1"){
        $loopcount = 6;

        }

        else if ($loopcount == "2"){
        $loopcount = 11;

        }

        else if ($loopcount == "3"){
        $loopcount = 51;

        }
        else if ($loopcount == "4"){
        $loopcount = 101;

        }




          $form_rules = [
              'package_id' => [
                  'label' => 'Package',
                  'rules' => "required|numeric|is_not_unique[packages.id_package]",
                  'errors' => [
                      'is_not_unique' => 'Invalid package selected.'
                  ],
              ],
              'duration' => [
                  'label' => 'duration',
                  'rules' => 'required|numeric|greater_than_equal_to[1]',
                  'errors' => [
                     'greater_than_equal_to' => 'Minimum {field} is invalid.',
                      'numeric' => 'Invalid hour {field}.'
                  ]
              ],
              'max_devices' => [
                  'label' => 'devices',
                  'rules' => 'required|numeric|greater_than_equal_to[1]',
                  'errors' => [
                      'greater_than_equal_to' => 'Minimum {field} is invalid.',
                      'numeric' => 'Invalid max of {field}.'
                  ]
              ],
          ];

          $validation = Services::validation();
          $reduceCheck = ($user->saldo - $getPrice);
          // dd($reduceCheck);
          if ($reduceCheck < 0) {
              $validation->setError('duration', 'Insufficient balance');
              return redirect()->back()->withInput()->with('msgWarning', 'Please top up to your beloved admin.');
          } else {
              if (!$this->validate($form_rules)) {
                  return redirect()->back()->withInput()->with('msgDanger', 'Failed! Please check the error');
              } else {
                
                 //================================================//
                
           
            
    
                //for($i = 1; $i < $loopcount; $i++) {
                
              //}
            
            
                    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
                    $randomPart = '';
                    for ($i = 0; $i < 6; $i++) {
                        $randomPart .= $chars[rand(0, strlen($chars) - 1)];
                    }
                    $license = $user->username . '_' . $randomPart;

                   // echo "$license  <br><br>";




                  //================================================//


                      $msg = "Successfuly Generated.";



                  // Get package info
                  $package = $this->packageModel->find($package_id);
                  $packageName = is_object($package) ? $package->package_name : ($package['package_name'] ?? 'Unknown');
                  $packageIdValue = is_object($package) ? $package->package_id : ($package['package_id'] ?? '');

                  $data_response = [
                      'game' => $packageIdValue ?: $packageName,
                      'package_id' => $package_id,
                      'user_key' => $license,
                      'duration' => $drtn,
                      'max_devices' => $maxd,
                      'registrator' => $user->username,
                  ];

                 // * reseller reduce saldo
                  $idKeys = $this->model->insert($data_response);

                  $this->userModel->update(session('userid'), ['saldo' => $reduceCheck]);

                  $history = new HistoryModel();
                  $history->insert([
                      'keys_id' => $idKeys,
                      'user_do' => $user->username,
                      'info' => $packageName . "|" . $license . "|$drtn|$maxd"
                  ]);

                  $other_response = [
                      'fees' => $getPrice
                  ];

                  session()->setFlashdata(array_merge($data_response, $other_response));


                  return redirect()->back()->with('msgSuccess', $msg);
                
              }
          }
     }
 
}
