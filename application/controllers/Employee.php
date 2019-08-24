<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
//        $this->load->library('Dynamic_menu');
        $this->load->helper('file');
        $this->load->helper('security');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Employee_model', 'Employee_model');
        $this->load->model('Master_data_model', 'Master_data_model');
        $this->load->model('Users_model', 'Users_model');
    }
    
    public function index(){
        $check = $this->Users_model->getRoleMenu('index.php/Employee', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $this->load->helper('url');
        
            $data['page_name'] = 'Employee Management';
            $data['emp_list'] = $this->Employee_model->getAllEmployeeList();
            $data['post_list'] = $this->Master_data_model->getMasterDataList('POSITION');
            
            $subscribe = array();
            $subscribe[0] ='No';
            $subscribe[1] ='Yes';
            $data['subscribe_list'] = $subscribe;
            
            $data['content'] = $this->load->view('employee/list', $data, TRUE);
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
            $list = $this->Employee_model->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $emp) {            
                $subscribe = 'No';
                if($emp->subscribe == 1){
                    $subscribe = 'Yes';
                }
    
                $no++;
                $row = array();
                $row[] = $emp->emp_id;
                $row[] = $emp->emp_name;
                $row[] = $emp->email;
                $row[] = $emp->position;
                $row[] = '['.$emp->subscribe.'] '.$subscribe;
     
                //add html for action
    	    $button = '';
                if($this->session->userdata("role") == 1 || $this->session->userdata("role") == 2){
    		    $button = '<a href=\'#\' onclick="edit_emp(\''.$emp->emp_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit Employee\'></a>'.'&nbsp&nbsp&nbsp'.
    			      '<a href=\'#\' onclick="delete_emp(\''.$emp->emp_id.'\''.',\''.$emp->emp_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete Employee\'></a>';
    	    }
                $row[] = $button;
    
                $data[] = $row;
            }
     
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->Employee_model->count_all(),
                            "recordsFiltered" => $this->Employee_model->count_filtered(),
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
        $data = $this->Employee_model->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        if($this->input->post('empId') == null || $this->input->post('empId') == ''){
            
            $error_message = 'Employee ID can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('empName') == null || $this->input->post('empName') == ''){
            
            $error_message = 'Employee name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    'emp_id' => $this->input->post('empId'),
                    'emp_name' => $this->input->post('empName'),
                    'email' => $this->input->post('email'),
                    'position' => $this->input->post('position'),
                    'subscribe' => $this->input->post('subscribe'),
                    'created_by' => $this->session->userdata("username"),
                    'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->Employee_model->save($data);
            echo json_encode(array("status" => TRUE));
//            echo json_encode(array("status" => FALSE, 'error' => $this->input->post('menuName')));
        }
    }
 
    public function ajax_update()
    {
        if($this->input->post('empId') == null || $this->input->post('empId') == ''){
            
            $error_message = 'Employee ID can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('empName') == null || $this->input->post('empName') == ''){
            
            $error_message = 'Employee name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    'emp_id' => $this->input->post('empId'),
                    'emp_name' => $this->input->post('empName'),
                    'email' => $this->input->post('email'),
                    'position' => $this->input->post('position'),
                    'subscribe' => $this->input->post('subscribe'),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->Employee_model->update(array('emp_id' => $this->input->post('empId')), $data);
            
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_delete($id)
    {
        $id = str_replace('slash', '/', $id);
        $this->Employee_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
    
    public function upload_emp(){
        $data['page_title'] = 'Upload Employee';
        $data['content'] = $this->load->view('employee/upload_emp', $data, TRUE);
        $this->load->view('form_template', $data);
    }
    
    function getmenuList(){
        echo json_encode($this->Employee_model->getEmployeeList());
    }
    
    public function download_template(){
        $data['title'] = 'Template Upload Employee';
        $data['content'] = $this->load->view('employee/template_emp', $data, TRUE);
        $this->load->view('blank', $data);
    }
    
    public function new_upload_emp(){
        $check = $this->Users_model->getRoleMenu('index.php/Employee');
        
        if(count($check) > 0){
            $data['page_title'] = 'Upload Employee';
            $data['content'] = $this->load->view('employee/new_upload_emp', $data, TRUE);
            $this->load->view('form_template', $data);
        }else{
            $data['title'] = 'Error Page';
        	$data["content"] = $this->load->view('error',$data,true);
            $this->load->view("blank", $data);
        }
        
    }
    
    public function ajax_upload()
    {
        $ViewData=read_file("./csv/log_employee.txt");
        write_file("./csv/log_employee.txt", $ViewData."\n\n".date('Y-m-d H:i:s', strtotime('now'))." by ".$this->session->userdata("username"));
 
        $data = $this->input->post('data');
        
        $row = 1;
        $num_success = 0;
        $num_fail = 0;
        $success = array();
        $fail = array(); 
        $success_emp = array();
        $fail_emp = array();
        $fail_note = array();
        foreach($data as $key=>$field){
            
            if($key > 0){
                if(count($field) == 11){
                    $emp_id = trim($field[0]);
                    $emp_name = $field[1];
                    $email = $field[2];
                    $position = $field[3];
                    $note = $field[4];
                    $subscribe = $field[5];
                    $cc1 = $field[6];
                    $cc2 = $field[7];
                    $cc3 = $field[8];
                    $cc4 = $field[9];
                    $cc5 = $field[10];

                    $data_employee = array(
                            'emp_id' => $emp_id,
                            'emp_name' => $emp_name,
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

                    $check = $this->Employee_model->getEmployeeById($emp_id)->result();

                    if(count($check) == 0){
                        $this->Employee_model->save($data_employee);
                            $success[$num_success] = $row;
                            $success_emp[$num_success] = $emp_id;
                            $num_success++;
                    }else{
                        $fail[$num_fail] = $row;
                        $fail_emp[$num_fail] = $emp_id;
                        $fail_note[$num_fail] = 'Employee ID already exist.';
                        $num_fail++;

                        $ViewData=read_file("./csv/log_employee.txt");
                        $text = $ViewData."\n Line ".$row.'. '.$emp_id.' Employee ID already exist.';
                        write_file("./csv/log_employee.txt", $text);
                    }
                }
                $row++;
            }
        }
        
        if($num_fail == 0){
            $ViewData=read_file("./csv/log_employee.txt");
            write_file("./csv/log_employee.txt", $ViewData."\n Upload success.\n".$num_success." row(s) has been uploaded.\n");
        }else{
            $ViewData=read_file("./csv/log_employee.txt");
            write_file("./csv/log_employee.txt", $ViewData."\n".$num_success." row(s) has been uploaded.\n".$num_fail.' row(s) fail to upload.');
        }
        
        $text = $num_success." row(s) has been uploaded.\n".$num_fail." row(s) fail to upload.\nSee log file for detail.";
        
        echo json_encode(array("status" => TRUE, "text" => $text));
        
    }
    
    public function log_file(){
        $data['title'] = 'Log File Employee';
        $data['content'] = $this->load->view('employee/log_file', $data, TRUE);
        $this->load->view('form_template', $data);
    }
}
?>
