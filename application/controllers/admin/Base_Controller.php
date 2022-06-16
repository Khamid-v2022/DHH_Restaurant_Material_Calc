<?php
class Base_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('America/Los_Angeles');
        if(!isset($this->session->admin_data)){
            redirect('admin/login');
            return;
        }
    }

    public function generate_json($msg, $status = true){
        $resp['status'] = $status;
        $resp['msg'] = $msg;
        echo json_encode($resp);
    }

    public function changeDateFormat($date, $flag=true){
        if($flag)
            $newDate = date("d/m/Y", strtotime($date));  
        else{
            $date = str_replace('/', '-', $date);  
            $newDate = date("Y-m-d", strtotime($date)); 
        }
        return $newDate; 
    }
}