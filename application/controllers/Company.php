<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
//        $this->load->library('Dynamic_menu');
        $this->load->library('encrypt');
        $this->load->helper('file');
        $this->load->helper('security');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Company_model', 'Company_model');
        $this->load->model('Master_data_model', 'Master_data_model');
        $this->load->model('Users_model', 'Users_model');
    }
    
    public function index(){
        $check = $this->Users_model->getRoleMenu('index.php/Company', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $this->load->helper('url');
        
            $data['page_name'] = 'Company Management';
            $data['company_list'] = $this->Company_model->getAllCompanyList();
            $data['post_list'] = $this->Master_data_model->getMasterDataList('POSITION');
            
            $subscribe = array();
            $subscribe[0] ='No';
            $subscribe[1] ='Yes';
            $data['subscribe_list'] = $subscribe;
            
            $data['content'] = $this->load->view('company/list', $data, TRUE);
            $this->load->view('themes/blank',$data);
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
        
    }
 
    public function ajax_list()
    {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $list = $this->Company_model->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $com) {            
    
                $no++;
                $row = array();
                $row[] = $com->company_id;
                $row[] = $com->company_name;
                $row[] = $com->address;
                $row[] = $com->note;
     
                //add html for action
    	    $button = '';
                if($this->session->userdata("role") == 1 || $this->session->userdata("role") == 2){
    		    $button = '<a href=\'#\' onclick="edit_com(\''.$com->company_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit Company\'></a>'.'&nbsp&nbsp&nbsp'.
                                  '<a href=\'#\' onclick="view_branch(\''.$com->company_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/view-details.png\' title=\'View Branch\'></a>'.'&nbsp&nbsp&nbsp'.
                                  '<a href=\'#\' onclick="view_division(\''.$com->company_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/188.png\' title=\'View Division\'></a>'.'&nbsp&nbsp&nbsp'.
                                  '<a href=\'#\' onclick="delete_com(\''.$com->company_id.'\''.',\''.$com->company_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete Company\'></a>';
    	    }
                $row[] = $button;
    
                $data[] = $row;
            }
     
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->Company_model->count_all(),
                            "recordsFiltered" => $this->Company_model->count_filtered(),
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
        $data = $this->Company_model->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        if($this->input->post('comName') == null || $this->input->post('comName') == ''){
            
            $error_message = 'Company name can not comty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    'company_id' => $this->input->post('comId'),
                    'company_name' => $this->input->post('comName'),
                    'address' => $this->input->post('address'),
                    'note' => $this->input->post('note'),
                    'created_by' => $this->session->userdata("username"),
                    'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->Company_model->save($data);
            echo json_encode(array("status" => TRUE));
//            echo json_encode(array("status" => FALSE, 'error' => $this->input->post('menuName')));
        }
    }
 
    public function ajax_update()
    {
        if($this->input->post('comName') == null || $this->input->post('comName') == ''){
            
            $error_message = 'Company name can not comty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    'company_id' => $this->input->post('comId'),
                    'company_name' => $this->input->post('comName'),
                    'address' => $this->input->post('address'),
                    'note' => $this->input->post('note'),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->Company_model->update(array('company_id' => $this->input->post('comId')), $data);
            
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_delete($id)
    {
        $id = str_replace('slash', '/', $id);
        $this->Company_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
    
    public function upload_com(){
        $data['page_title'] = 'Upload Company';
        $data['content'] = $this->load->view('company/upload_com', $data, TRUE);
        $this->load->view('form_tcomlate', $data);
    }
    
    function getmenuList(){
        echo json_encode($this->Company_model->getCompanyList());
    }
    
    public function download_tcomlate(){
        $data['title'] = 'Tcomlate Upload Company';
        $data['content'] = $this->load->view('company/tcomlate_com', $data, TRUE);
        $this->load->view('blank', $data);
    }
    
    public function new_upload_com(){
        $check = $this->Users_model->getRoleMenu('index.php/Company');
        
        if(count($check) > 0){
            $data['page_title'] = 'Upload Company';
            $data['content'] = $this->load->view('company/new_upload_com', $data, TRUE);
            $this->load->view('form_tcomlate', $data);
        }else{
            $data['title'] = 'Error Page';
        	$data["content"] = $this->load->view('error',$data,true);
            $this->load->view("blank", $data);
        }
        
    }
    
    public function ajax_upload()
    {
        $ViewData=read_file("./csv/log_company.txt");
        write_file("./csv/log_company.txt", $ViewData."\n\n".date('Y-m-d H:i:s', strtotime('now'))." by ".$this->session->userdata("username"));
 
        $data = $this->input->post('data');
        
        $row = 1;
        $num_success = 0;
        $num_fail = 0;
        $success = array();
        $fail = array(); 
        $success_com = array();
        $fail_com = array();
        $fail_note = array();
        foreach($data as $key=>$field){
            
            if($key > 0){
                if(count($field) == 11){
                    $company_id = trim($field[0]);
                    $company_name = $field[1];
                    $email = $field[2];
                    $position = $field[3];
                    $note = $field[4];
                    $subscribe = $field[5];
                    $cc1 = $field[6];
                    $cc2 = $field[7];
                    $cc3 = $field[8];
                    $cc4 = $field[9];
                    $cc5 = $field[10];

                    $data_company = array(
                            'company_id' => $company_id,
                            'company_name' => $company_name,
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

                    $check = $this->Company_model->getCompanyById($company_id)->result();

                    if(count($check) == 0){
                        $this->Company_model->save($data_company);
                            $success[$num_success] = $row;
                            $success_com[$num_success] = $company_id;
                            $num_success++;
                    }else{
                        $fail[$num_fail] = $row;
                        $fail_com[$num_fail] = $company_id;
                        $fail_note[$num_fail] = 'Company ID already exist.';
                        $num_fail++;

                        $ViewData=read_file("./csv/log_company.txt");
                        $text = $ViewData."\n Line ".$row.'. '.$company_id.' Company ID already exist.';
                        write_file("./csv/log_company.txt", $text);
                    }
                }
                $row++;
            }
        }
        
        if($num_fail == 0){
            $ViewData=read_file("./csv/log_company.txt");
            write_file("./csv/log_company.txt", $ViewData."\n Upload success.\n".$num_success." row(s) has been uploaded.\n");
        }else{
            $ViewData=read_file("./csv/log_company.txt");
            write_file("./csv/log_company.txt", $ViewData."\n".$num_success." row(s) has been uploaded.\n".$num_fail.' row(s) fail to upload.');
        }
        
        $text = $num_success." row(s) has been uploaded.\n".$num_fail." row(s) fail to upload.\nSee log file for detail.";
        
        echo json_encode(array("status" => TRUE, "text" => $text));
        
    }
    
    public function log_file(){
        $data['title'] = 'Log File Company';
        $data['content'] = $this->load->view('company/log_file', $data, TRUE);
        $this->load->view('form_tcomlate', $data);
    }
}
?>