<?php

/**
 * Creator: Raka Adi Nugroho
 * Mail: nugrohoraka@gmail.com
 * Date: 5/25/17
 * Time: 12:00 AM
 */
require APPPATH . '/libraries/REST_Controller.php';
class Package extends REST_Controller
{
    public function index_get()
    {
        $this->load->model('model_package', 'package');
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
                if (!is_null($decodedToken->id)) {
                    # Params Package
                    $packageparams  = array(
                        'id'    => $this->get('id'),
                    );
                    $resultpackage  = $this->package->package_detail($packageparams);
                    if ($resultpackage) {
                        # Params Question
                        $questionparams = array(
                            'id_guru' => $resultpackage->id_guru,
                            'id_mapel' => $resultpackage->id_mapel,
                        );
                        $resultquestion = $this->package->question_by_package($questionparams);
                        $dataquestion = array();
                        foreach ($resultquestion as $value) {
                            array_push($dataquestion, array(
                                'question' => $value->soal,
                                'question_id' => $value->id,
                                'question_correct' => $value->jawaban,
                                'question_weight' => $value->bobot,
                                'answer' => array(
                                    'A' => $this->clean($value->opsi_a),
                                    'B' => $this->clean($value->opsi_b),
                                    'C' => $this->clean($value->opsi_c),
                                    'D' => $this->clean($value->opsi_d)
                                )

                            ));
                        }
                        $response = array(
                            'status' => true,
                            'exam_id' => $resultpackage->id,
                            'exam_title' => $resultpackage->nama_ujian,
                            'exam_time' => $resultpackage->waktu,
                            'exam_thumbnail' => $resultpackage->thumbnail,
                            'exam_detail' => $resultpackage->detil_jenis,
                            'exam_total' => $resultpackage->jumlah_soal,
                            'exam_token' => $resultpackage->token,
                            'examination' => $dataquestion
                        );

                        $this->set_response($response, REST_Controller::HTTP_OK);
                    }else{
                        $this->set_response(array('status' => false), REST_Controller::HTTP_OK);
                    }
                }
                return;
            }
        }

        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
    }
    public function latest_get()
    {
        $this->load->model('model_package','package');
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
                if (!is_null($decodedToken->id)) {
                    $resultlatest   = $this->package->package_latest();
                    $data   = array();
                    foreach ($resultlatest as $value){
                        array_push($data, array(
                            'exam_id'       => $value->id,
                            'exam_title'    => $value->nama_ujian,
                            'exam_time'     => $value->waktu,
                            'exam_detail'   => $value->detil_jenis,
                            'exam_total'    => $value->jumlah_soal,
                            'exam_token'    => $value->token,
                            'exam_thumbnail'    => $value->thumbnail
                            ));
                    }
                    $response   = array(
                        'status'    => true,
                        'message'   => 'Latest Examination',
                        'package_latest'    => $data,
                        );
                    
                    $this->set_response($response, REST_Controller::HTTP_OK);
                }
                return;
            }
        }
        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
    }
    public function test_get(){
                        $this->set_response(array("status" => false,"message" => "Terjadi Kesalahan"), REST_Controller::HTTP_OK);
    }
    public function history_get(){
        $this->load->model('model_package', 'package');
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
                if (!is_null($decodedToken->id)) {
                    $params = array(
                            'tr_ikut_ujian.id_user' => $this->get('user')
                        );
                    $data   = $this->package->history_pass($params);
                    
                    $response   = array();
                    foreach($data as $value){
                        array_push($response, array(
                                'exam_title' => $value->nama_ujian,
                                'exam_date' => $value->tgl_selesai,
                                'exam_weight'   => $value->nilai_bobot,
                                'exam_correct'  => $value->jml_benar
                            ));
                    }
                    
                    $this->set_response(array('status' => true, 'message' => 'Daftar Ujian Saya', 'history' => $response), REST_Controller::HTTP_OK);
                }
                return;
            }
        }
        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
    }
    public function passexam_get(){
        $this->load->model('model_package','package');
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
                if (!is_null($decodedToken->id)) {
                    $params = array(
                        'id_user'   => $this->get('user'),
                        'id_tes'   => $this->get('exam')
                    );
                    $result = $this->package->package_pass($params);
                    
                    $data   = array();
                    foreach($result as $value){
                        array_push($data, array(
                            'list_exam' => $value->list_soal,
                            'list_answer' => $value->list_jawaban,
                            'correct' => $value->jml_benar,
                            'exam_value'    => $value->nilai,
                            'exam_weight'   => $value->nilai_bobot,
                            'exam_start'    => $value->tgl_mulai,
                            'exam_end'  => $value->tgl_selesai,
                            'exam_status'   => $value->status,
                            )
                        );
                    }
                    $this->set_response(array('status' => true, 'message' => 'List You Passed Examination', 'pass_exam' => $data), REST_Controller::HTTP_OK);
                }
                return;
            }
        }
        $this->set_response("Unauthorised", REST_Controller::HTTP_UNAUTHORIZED);
    }
    function clean($string) {
       $string = str_replace('#', '', $string); // Replaces all spaces with hyphens.
       //return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
       return strip_tags($string);
    }
    public function passexam_post()
    {
        /*
         * Json Data
         * {
         *  user_id: n,
         *  exam_id: n,
         *  hasil : [{jawab:,soal_id:,}]
         * }
         */
        $headers = $this->input->request_headers();
        if (array_key_exists('Authorization', $headers) && !empty($headers['Authorization'])) {
            $decodedToken = AUTHORIZATION::validateToken($headers['Authorization']);
            if ($decodedToken != false) {
                if (!is_null($decodedToken->id)) {
                    $data           = json_decode(file_get_contents('php://input'));
                    // Jumlah Soal
                    $totalcorrect   = 0;

                    $update_        = "";
                    $updatesoal_    = "";
                    $arraybobot     = array();
                    $arraynilai     = array();
                    foreach ($data->hasil as $value){
                        $jawaban    = empty($value->jawab) ? "" : $value->jawab;
                        $soalid     = $value->soal_id;
                        $cekanswer  = $this->db->query("SELECT bobot, jawaban FROM m_soal WHERE id = '".$soalid."'")->row();

                        $bobot      = $cekanswer->bobot;
                        $arraybobot[$bobot] = empty($arraybobot[$bobot]) ? 1 : $arraybobot[$bobot]+1;

                        $qu_soal    = "";
                        # Benar
                        if ($cekanswer->jawaban == $jawaban){
                            $totalcorrect++;
                            $arraynilai[$bobot] = empty($arraynilai[$bobot]) ? 1 : $arraynilai[$bobot]+1;
                            $qu_soal    = "UPDATE m_soal SET jml_benar = jml_benar + 1 WHERE id = '".$soalid."'";
                        }else{
                            # Salah
                            $arraynilai[$bobot] = empty($arraynilai[$bobot]) ? 0 : $arraynilai[$bobot];
                            $qu_soal    = "UPDATE m_soal SET jml_salah = jml_salah + 1 WHERE id = '".$soalid."'";
                        }
                        $update_	.= "".$soalid.":".$jawaban.",";
                        $updatesoal_    .= "".$soalid.",";
                    }

                    // Perhitungan Nilai Bobot
                    ksort($arraybobot);
                    ksort($arraynilai);

                    $nilaibobotbenar    = 0;
                    $nilaibobottotal    = 0;

                    foreach ($arraybobot as $key => $value){
                        $nilaibobotbenar = $nilaibobotbenar + ($key * $arraynilai[$key]);
                        $nilaibobottotal = $nilaibobottotal + ($key * $arraybobot[$key]);
                    }
                    $update_    = substr($update_, 0, -1);
                    $updatesoal_    = substr($updatesoal_, 0, -1);
                    $nilai      = ($totalcorrect/(count($data->hasil)-1)) * 100;
                    $nilai_bobot    = ($nilaibobotbenar/$nilaibobottotal)*100;

                    /**
                     * Cek Data
                    echo 'exam_id '.$data->exam_id;
                    echo 'user_id'.$data->user_id;
                    echo 'totaldata'.count($data->hasil);
                    echo 'update '.$update_;
                    echo 'nilai '.$nilai;
                    echo 'nilai_bobot'.$nilai_bobot;
                    */
                    // Update Data
                    $time_mulai		= date('Y-m-d H:i:s');
                    $query = $this->db->query("INSERT INTO tr_ikut_ujian SET jml_benar = ".$totalcorrect.", nilai_bobot = ".$nilai_bobot.", nilai = '".$nilai."', list_jawaban = '".$update_."', status = 'N', id_tes = '".$data->exam_id."', id_user = '".$data->user_id."', tgl_mulai='".$time_mulai."', tgl_selesai='".$time_mulai."', list_soal='".$updatesoal_."'");
                    if ($query){
                        $response   = array(
                            "status"    => true,
                            "message"   => "Success",
                            "examreport"    => array(
                                "grade" => $nilai,
                                "grade_weight"  => $nilai_bobot,
                                "correct"   => $totalcorrect,
                                "total_exam"    => count($data->hasil)
                            )
                        );
                        $this->set_response($response, REST_Controller::HTTP_OK);
                    }else{
                        $this->set_response(array("status" => false,"message" => "Terjadi Kesalahan"), REST_Controller::HTTP_OK);
                    }
                }
                return;
            }
        }
        $this->set_response(array("status" => false,"message" => "Terjadi Kesalahan"), REST_Controller::HTTP_OK);
    }
}