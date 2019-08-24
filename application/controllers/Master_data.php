<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_data extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
        $this->load->model('Master_data_type_model', 'Master_data_type_model');
        $this->load->model('Master_data_model', 'Master_data_model');
        $this->load->model('Users_model', 'Users_model');
    }
    
    public function index(){
        $check = $this->Users_model->getRoleMenu('index.php/Master_data', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $this->load->helper('url');

            $data['page_name'] = 'Master Data Management';

            $data['content'] = $this->load->view('master_data/list', $data, TRUE);
            $this->load->view('themes/blank',$data);
            $this->load->view('themes/footer');
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
    
    
    
    function lists($type) {
        $this->load->helper('url');
        
        $data['type'] = $type;
        
        $new_type = str_replace('slash', '/', $type);
        
        $result = $this->Master_data_type_model->getMasterDataTypeById($new_type)->result();

        $data['type_name'] = $result[0]->type_name;

        
        $data['page_title'] = 'Master Data';
        $data['content'] = $this->load->view('master_data/list', $data, TRUE);
        $this->load->view('themes/blank',$data);
        $this->load->view('themes/footer');
    }
 
    public function ajax_list($type)
    {
        $type = str_replace('slash', '/', $type);
        $list = $this->Master_data_model->get_datatables($type);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $md) {

            $no++;
            $row = array();
            $row[] = $md->id;
            $row[] = $md->value;
            $row[] = $md->name;
 
            //add html for action
            $button = '<a href=\'#\' onclick="edit_md(\''.$md->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit Master Data\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_md(\''.$md->id.'\''.',\''.$md->name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete Master Data\'></a>';
            
            $row[] = $button;

            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Master_data_model->count_all($type),
                        "recordsFiltered" => $this->Master_data_model->count_filtered($type),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $data = $this->Master_data_model->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        if($this->input->post('value') == null || $this->input->post('value') == ''){
            
            $error_message = 'Value can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('name') == null || $this->input->post('name') == ''){
            
            $error_message = 'Name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    'type' => $this->input->post('type'),
                    'value' => $this->input->post('value'),
                    'name' => $this->input->post('name'),
                    'created_by' => $this->session->userdata("username"),
                    'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->Master_data_model->save($data);
            echo json_encode(array("status" => TRUE));
//            echo json_encode(array("status" => FALSE, 'error' => $this->input->post('menuName')));
        }
    }
 
    public function ajax_update()
    {
        if($this->input->post('value') == null || $this->input->post('value') == ''){
            
            $error_message = 'Value can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('name') == null || $this->input->post('name') == ''){
            
            $error_message = 'Name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    'type' => $this->input->post('type'),
                    'value' => $this->input->post('value'),
                    'name' => $this->input->post('name'),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->Master_data_model->update(array('id' => $this->input->post('id')), $data);
            
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_delete($id)
    {
        $id = str_replace('slash', '/', $id);
        $this->Master_data_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
    
    public function upload_detail(){
        $data['page_title'] = 'Upload Master Data';
        $data['content'] = $this->load->view('master_data/upload_md', $data, TRUE);
        $this->load->view('form_template', $data);
    }
    
    function getMasterDataList(){
        echo json_encode($this->Master_data_model->getMasterDataList());
    }
    
    public function download_template(){
        $data['data'] = $this->Master_data_model->getTemplatePrice();
        $data['title'] = 'Template Upload Master Data';
        $data['content'] = $this->load->view('master_data/template_md', $data, TRUE);
        $this->load->view('blank_page', $data);
    }
}
?>
