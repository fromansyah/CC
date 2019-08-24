<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Division extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
//        $this->load->library('Dynamic_menu');
        $this->load->library('encrypt');
        $this->load->helper('file');
        $this->load->helper('security');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Division_model', 'Division_model');
        $this->load->model('Company_model', 'Company_model');
        $this->load->model('Master_data_model', 'Master_data_model');
        $this->load->model('Users_model', 'Users_model');
    }
    
    public function index(){
        $check = $this->Users_model->getRoleMenu('index.php/Division', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $this->load->helper('url');
        
            $data['page_name'] = 'Division Management';
            
            $data['content'] = $this->load->view('division/list', $data, TRUE);
            $this->load->view('themes/blank',$data);
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
        
    }
    
    function lists($company_id) {
        if($this->session->userdata("role") == 1 || $this->session->userdata("role") == 2){
            $this->load->helper('url');

            $result = $this->Company_model->getCompanyById($company_id)->result();

            $data['company_id'] = $company_id;
            $data['company_name'] = $result[0]->company_name;

            $data['page_title'] = 'Division Management';
            $data['content'] = $this->load->view('division/list', $data, TRUE);
            $this->load->view('themes/blank',$data);
            $this->load->view('themes/footer');
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
 
    public function ajax_list($company_id='')
    {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $list = $this->Division_model->get_datatables($company_id);
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $div) {            
    
                $no++;
                $row = array();
                $row[] = $div->division_id;
                $row[] = $div->division_name;
                $row[] = $div->note;
     
                //add html for action
    	    $button = '';
                if($this->session->userdata("role") == 1 || $this->session->userdata("role") == 2){
    		    $button = '<a href=\'#\' onclick="edit_div(\''.$div->division_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit Division\'></a>'.'&nbsp&nbsp&nbsp'.
    			      '<a href=\'#\' onclick="delete_div(\''.$div->division_id.'\''.',\''.$div->division_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete Division\'></a>';
    	    }
                $row[] = $button;
    
                $data[] = $row;
            }
     
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->Division_model->count_all($company_id),
                            "recordsFiltered" => $this->Division_model->count_filtered($company_id),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
 
    public function ajax_edit($id)
    {
        $id = str_replace('slash', '/', $id);
        $data = $this->Division_model->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        if($this->input->post('divName') == null || $this->input->post('divName') == ''){
            
            $error_message = 'Division name can not divty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    //'division_id' => $this->input->post('divId'),
                    'company_id' => $this->input->post('companyId'),
                    'division_name' => $this->input->post('divName'),
                    'note' => $this->input->post('note'),
                    'created_by' => $this->session->userdata("username"),
                    'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->Division_model->save($data);
            echo json_encode(array("status" => TRUE));
//            echo json_encode(array("status" => FALSE, 'error' => $this->input->post('menuName')));
        }
    }
 
    public function ajax_update()
    {
        if($this->input->post('divName') == null || $this->input->post('divName') == ''){
            
            $error_message = 'Division name can not divty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    'division_id' => $this->input->post('divId'),
                    'division_name' => $this->input->post('divName'),
                    'note' => $this->input->post('note'),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->Division_model->update(array('division_id' => $this->input->post('divId')), $data);
            
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_delete($id)
    {
        $id = str_replace('slash', '/', $id);
        $this->Division_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
    
    public function upload_div(){
        $data['page_title'] = 'Upload Division';
        $data['content'] = $this->load->view('division/upload_div', $data, TRUE);
        $this->load->view('form_tdivlate', $data);
    }
    
    function getmenuList(){
        echo json_encode($this->Division_model->getDivisionList());
    }
    
    public function download_tdivlate(){
        $data['title'] = 'Tdivlate Upload Division';
        $data['content'] = $this->load->view('division/tdivlate_div', $data, TRUE);
        $this->load->view('blank', $data);
    }
    
    public function new_upload_div(){
        $check = $this->Users_model->getRoleMenu('index.php/Division');
        
        if(count($check) > 0){
            $data['page_title'] = 'Upload Division';
            $data['content'] = $this->load->view('division/new_upload_div', $data, TRUE);
            $this->load->view('form_tdivlate', $data);
        }else{
            $data['title'] = 'Error Page';
        	$data["content"] = $this->load->view('error',$data,true);
            $this->load->view("blank", $data);
        }
        
    }
    
    public function ajax_upload()
    {
        $ViewData=read_file("./csv/log_division.txt");
        write_file("./csv/log_division.txt", $ViewData."\n\n".date('Y-m-d H:i:s', strtotime('now'))." by ".$this->session->userdata("username"));
 
        $data = $this->input->post('data');
        
        $row = 1;
        $num_success = 0;
        $num_fail = 0;
        $success = array();
        $fail = array(); 
        $success_div = array();
        $fail_div = array();
        $fail_note = array();
        foreach($data as $key=>$field){
            
            if($key > 0){
                if(count($field) == 11){
                    $division_id = trim($field[0]);
                    $division_name = $field[1];
                    $email = $field[2];
                    $position = $field[3];
                    $note = $field[4];
                    $subscribe = $field[5];
                    $cc1 = $field[6];
                    $cc2 = $field[7];
                    $cc3 = $field[8];
                    $cc4 = $field[9];
                    $cc5 = $field[10];

                    $data_division = array(
                            'division_id' => $division_id,
                            'division_name' => $division_name,
                            'email' => $email,
                            'position' => $position,
                            'note' => $note,
                            'subscribe' => $subscribe,
                            'cc_1' => $cc1,
                            'cc_2' => $cc2,
                            'cc_3' => $cc3,
                            'cc_4' => $cc4,
                            'cc_5' => $cc5,
                            'created_by' => $this->session->userdata("username"),
                            'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                            'last_update_by' => $this->session->userdata("username"),
                            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                    );

                    $check = $this->Division_model->getDivisionById($division_id)->result();

                    if(count($check) == 0){
                        $this->Division_model->save($data_division);
                            $success[$num_success] = $row;
                            $success_div[$num_success] = $division_id;
                            $num_success++;
                    }else{
                        $fail[$num_fail] = $row;
                        $fail_div[$num_fail] = $division_id;
                        $fail_note[$num_fail] = 'Division ID already exist.';
                        $num_fail++;

                        $ViewData=read_file("./csv/log_division.txt");
                        $text = $ViewData."\n Line ".$row.'. '.$division_id.' Division ID already exist.';
                        write_file("./csv/log_division.txt", $text);
                    }
                }
                $row++;
            }
        }
        
        if($num_fail == 0){
            $ViewData=read_file("./csv/log_division.txt");
            write_file("./csv/log_division.txt", $ViewData."\n Upload success.\n".$num_success." row(s) has been uploaded.\n");
        }else{
            $ViewData=read_file("./csv/log_division.txt");
            write_file("./csv/log_division.txt", $ViewData."\n".$num_success." row(s) has been uploaded.\n".$num_fail.' row(s) fail to upload.');
        }
        
        $text = $num_success." row(s) has been uploaded.\n".$num_fail." row(s) fail to upload.\nSee log file for detail.";
        
        echo json_encode(array("status" => TRUE, "text" => $text));
        
    }
    
    public function log_file(){
        $data['title'] = 'Log File Division';
        $data['content'] = $this->load->view('division/log_file', $data, TRUE);
        $this->load->view('form_tdivlate', $data);
    }
}
?>