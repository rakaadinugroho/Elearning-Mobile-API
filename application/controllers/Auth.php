<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Auth extends REST_Controller
{
    # Signin Endpoint
    # Parameter ( Username, Password)
    # Method POST
    public function signin_post()
    {
        $this->load->model('model_user','user');
        $params = array(
            'username'  => $this->post('username'),
            'password'  => md5($this->post('password'))
        );
        $result  = $this->user->user_check($params);
        if ($result){
            if ($result->level == "siswa"){
                $tokenData  = array();
                $tokenData['id']    = $result->id;
                # Load detail siswa
                $studentparams  = array(
                    'id'    => $result->kon_id,
                );
                $resultstudent  = $this->user->siswa_check($studentparams);

                $response   = array(
                    "status"    => true,
                    "message"   => "Authentication successfully",
                    "auth"      => array(
                        "userid"    => (int)$result->id,
                        "userkon"   => (int)$result->kon_id,
                        "fullname"  => $resultstudent->nama,
                        "token"     => AUTHORIZATION::generateToken($tokenData),
                        "username"  => $result->username
                    )
                );
                $this->set_response($response, REST_Controller::HTTP_OK);

            }else{
                $response   = array(
                    "status"    => false,
                    "message"   => "This features does'nt exist for your Account"
                );
                $this->set_response($response, REST_Controller::HTTP_OK);
            }
        }
        else{
            $response   = array(
                "status"    => false,
                "message"   => "Username or Password invalid"
            );
            $this->set_response($response, REST_Controller::HTTP_OK);
        }
    }

    # Register Endpoint
    # Parameter ( Username, Password, Fullname)
    # Method POST
    public function signup_post()
    {
        $this->load->model('model_user','user');
        $params = array(
            'nama'  => $this->post('fullname'),
            'jurusan'   => $this->post('stage'),
            'nim'   => strtoupper(substr(uniqid(), 0, 5))
        );
        $result = $this->user->siswa_create($params);
        if ($result){
            $checkdata  = $this->user->siswa_check(array('nim' => $params['nim']));
            $userparams = array(
                'username'  => $this->post('username'),
                'password'  => md5($this->post('password')),
                'level' => 'siswa',
                'kon_id'    => $checkdata->id
            );
            $resultuser = $this->user->user_create($userparams);
            if ($resultuser){
                $response   = array(
                    "status"    => true,
                    "message"   => "Register Successfully"
                );
                $this->set_response($response, REST_Controller::HTTP_OK);
            }else{
                $response   = array(
                    "status"    => false,
                    "message"   => "Error, Signup failed user cannot catch"
                );
                $this->set_response($response, REST_Controller::HTTP_OK);
            }
        }else{
            $response   = array(
                "status"    => false,
                "message"   => "Error, Signup failed"
            );
            $this->set_response($response, REST_Controller::HTTP_OK);
        }
    }

    # Password Change
    # Parameter ( Token, Username, Oldpassword, Newpassword)
    # Method POST
    public function change_post()
    {
        $this->load->model('model_user', 'user');
        $headers = $this->input->request_headers();

        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
                if (!is_null($decodedToken->id)) {
                    $params = array(
                        'username'  => $this->post('username'),
                        'oldpassword'   => md5($this->post('oldpassword')),
                        'newpassword'   => md5($this->post('newpassword'))
                    );

                    $result = $this->user->change_password($params);
                    if ($result){
                        $response   = array(
                            "status"    => false,
                            "message"   => "Change password successfully"
                        );
                        $this->set_response($response, REST_Controller::HTTP_OK);
                    }else{
                        $response   = array(
                            "status"    => false,
                            "message"   => "Error, Change password failed"
                        );
                        $this->set_response($response, REST_Controller::HTTP_OK);
                    }
                }
                return;
            }
        }

        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
    }
}