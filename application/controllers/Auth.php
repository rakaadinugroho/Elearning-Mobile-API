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
            'password'  => sha1($this->post('password'))
        );
        $result  = $this->user->user_check($params);
        if ($result){
            $tokenData  = array();
            $tokenData['id']    = $result->user_id;

            $response   = array(
                "status"    => true,
                "message"   => "Authentication successfully",
                "auth"      => array(
                    "fullname"  => $result->namalengkap,
                    "token"     => AUTHORIZATION::generateToken($tokenData),
                    "username"  => $result->username
                )
            );

            $this->set_response($response, REST_Controller::HTTP_OK);
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
            'username'  => $this->post('username'),
            'password'  => sha1($this->post('password')),
            'namalengkap'   => $this->post('fullname')
        );

        $result = $this->user->user_create($params);
        if ($result){
            $response   = array(
                "status"    => true,
                "message"   => "Register Successfully"
            );
            $this->set_response($response, REST_Controller::HTTP_OK);
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
                        'oldpassword'   => $this->post('oldpassword'),
                        'newpassword'   => $this->post('newpassword')
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