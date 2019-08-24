<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_data_type extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
        $this->load->model('Master_data_type_model', 'Master_data_type_model');
        $this->load->model('Users_model', 'Users_model');
    }
    
    public function index(){
        $check = $this->Users_model->getRoleMenu('index.php/Master_data_type', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $this->load->helper('url');

            $data['page_name'] = 'Master Data Type Management';

            $data['content'] = $this->load->view('master_data_type/list', $data, TRUE);
            $this->load->view('themes/blank',$data);
            $this->load->view('themes/footer');
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
 
    public function ajax_list()
    {
        $list = $this->Master_data_type_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $periode) {

            $no++;
            $row = array();
            $row[] = $periode->type;
            $row[] = $periode->type_name;
 
            //add html for action
            $button = '<a href=\'#\' onclick="edit_mdt(\''.$periode->type.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit Master Data Type\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="view_md(\''.$periode->type.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/view-details.png\' title=\'View Master Data\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_mdt(\''.$periode->type.'\''.',\''.$periode->type_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete Master Data Type\'></a>';
            
            $row[] = $button;

            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Master_data_type_model->count_all(),
                        "recordsFiltered" => $this->Master_data_type_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $id = str_replace('slash', '/', $id);
        $data = $this->Master_data_type_model->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        if($this->input->post('type') == null || $this->input->post('type') == ''){
            
            $error_message = 'Type can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('typeName') == null || $this->input->post('typeName') == ''){
            
            $error_message = 'Type name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    'type' => $this->input->post('type'),
                    'type_name' => $this->input->post('typeName'),
                    'created_by' => $this->session->userdata("username"),
                    'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->Master_data_type_model->save($data);
            echo json_encode(array("status" => TRUE));
//            echo json_encode(array("status" => FALSE, 'error' => $this->input->post('menuName')));
        }
    }
 
    public function ajax_update()
    {
        if($this->input->post('type') == null || $this->input->post('type') == ''){
            
            $error_message = 'Type can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('typeName') == null || $this->input->post('typeName') == ''){
            
            $error_message = 'Type name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    'type_name' => $this->input->post('typeName'),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->Master_data_type_model->update(array('type' => $this->input->post('type')), $data);
            
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_delete($id)
    {
        $id = str_replace('slash', '/', $id);
        $this->Master_data_type_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
    
    public function upload_mdt(){
        $data['page_title'] = 'Upload Master Data Type';
        $data['content'] = $this->load->view('master_data_type/upload_mdt', $data, TRUE);
        $this->load->view('form_template', $data);
    }
    
    public function download_template(){
        $data['title'] = 'Template Upload Master Data Type';
        $data['content'] = $this->load->view('master_data_type/template_mdt', $data, TRUE);
        $this->load->view('blank_page', $data);
    }
}
?>
