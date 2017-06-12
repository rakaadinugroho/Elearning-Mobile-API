<?php
/**
 * Creator: Raka Adi Nugroho
 * Mail: nugrohoraka@gmail.com
 * Date: 5/24/17
 * Time: 11:09 PM
 */
require APPPATH . '/libraries/REST_Controller.php';
class Category extends REST_Controller {
    # Get All Category
    public function index_get()
    {
        $this->load->model('model_category', 'category');
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
                if (!is_null($decodedToken->id)) {
                    $result = $this->category->category_all();
                
                    $data   = array();
                    foreach ($result as $value){
                        array_push($data,array(
                            'category_id'   => $value->id,
                            'category_name' => $value->nama,
                            'category_detail'   => $value->detail,
                            'category_thumbnail'    => $value->thumbnail,
                        ));
                    }
                    $response   = array(
                        'status'    => true,
                        'category'  => $data
                    );
                    $this->set_response($response, REST_Controller::HTTP_OK);
                }
                return;
            }
        }

        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
    }
    public function detail_get()
    {
        $this->load->model('model_package', 'package');
        $this->load->model('model_category', 'category');
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
                if (!is_null($decodedToken->id)) {
                    $params = array(
                        'id_mapel'  => $this->get('id')
                    );
                    $result = $this->package->package_by_category($params);
                    $paramsdetail   = array(
                        'id'    => $this->get('id')
                    );
                    $resultdetail   = $this->category->category_get($paramsdetail);
                    
                    $data   = array();
                    foreach ($result as $value){
                        array_push($data,array(
                            'exam_id'   => $value->id,
                            'exam_title' => $value->nama_ujian,
                            'exam_time'   => $value->waktu,
                            'exam_detail'    => $value->detil_jenis,
                            'exam_total'    => $value->jumlah_soal
                        ));
                    }
                    $response   = array(
                        'status'    => true,
                        'category_name' => $resultdetail->nama,
                        'category_detail' => $resultdetail->detail,
                        'category_thumbnail'    => $resultdetail->thumbnail,
                        'list_package'  => $data
                    );
                    $this->set_response($response, REST_Controller::HTTP_OK);
                }
                return;
            }
        }

        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
    }
}