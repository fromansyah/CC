<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
        $this->load->library('Dynamic_menu');
        $this->load->library('encrypt');
        $this->load->model('Role_model', 'Role_model');
        $this->load->model('Users_model', 'Users_model');
    }
    
    public function index(){
        $this->lists();
    }
    
    function lists() {
        $check = $this->Users_model->getRoleMenu('index.php/Role', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $this->load->helper('url');
            $data['test'] = '';

            $data['page_title'] = 'Role Management';
            $data['content'] = $this->load->view('role/list', $data, TRUE);
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
        $list = $this->Role_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $role) {

            $no++;
            $row = array();
            $row[] = $role->id;
            $row[] = $role->role;
            
            $id = str_replace(array('=','+','/'), array('-','_','~'), $this->encrypt->encode($role->id));
            //add html for action
            $button = '<a href=\'#\' onclick="edit_role(\''.$role->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit Role\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="view_role_menu(\''.$id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/view-details.png\' title=\'View Menu\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_role(\''.$role->id.'\''.',\''.$role->role.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete Role\'></a>'.'&nbsp&nbsp&nbsp';
            
            $row[] = $button;

            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Role_model->count_all(),
                        "recordsFiltered" => $this->Role_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $data = $this->Role_model->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        if($this->input->post('role') == null || $this->input->post('role') == ''){
            $error_message = 'Role can not empty.';
            
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
        }else{
            $data = array(
                    'role' => $this->input->post('role'),
                    'created_by' => $this->session->userdata("username"),
                    'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $insert = $this->Role_model->save($data);
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_update()
    {
        if($this->input->post('role') == null || $this->input->post('role') == ''){
            $error_message = 'Role can not empty.';
            
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
        }else{
            $data = array(
                    'role' => $this->input->post('role'),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->Role_model->update(array('id' => $this->input->post('id')), $data);
            
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_delete($id)
    {
        $this->Role_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
    

    function load_data() {
        $valid_fields = array('id', 'role');

	$this->flexigrid->validate_post('id','ASC',$valid_fields);
        
	$records = $this->Role_model->get_role_flexigrid();

	$this->output->set_header($this->config->item('json_header'));

        $record_items = array();

	foreach ($records['records']->result() as $row)
	{
            $button = '<a href=\'#\' onclick="edit_role(\''.$row->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/b_edit.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="view_role(\''.$row->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/046.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_role(\''.$row->id.'\''.',\''.$row->role.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/Delete.png\'></a>';
            
            $record_items[] = array(
                $row->id,
                $row->id,
                $row->role,
                $button
			);
        }
	//Print please
	$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
    }
    
    function add()
    {
        $data['page_title'] = 'Add Menu';
        $data['content'] = $this->load->view('role/add', $data, TRUE);
        $this->load->view($this->session->userdata("template"), $data);
    }

    function save_role_ajax() {
        $role = $this->input->post('role', TRUE);

        $data = array(
            'role' => $role,
            'created_by' => $this->session->userdata("username"),
            'created_date' => date('Y-m-d H:i:s', strtotime('now')),
            'last_update_by' => $this->session->userdata("username"),
            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
        );

        $this->Role_model->insertRole($data);
        echo json_encode(array('result' => 'true'));
    }

    function edit($id = '')
    {
        $hasil = $this->Role_model->getRoleById($id)->result();
        
        $data['id'] = $hasil[0]->id;
        $data['role'] = $hasil[0]->role;
        
        $data['page_title'] = 'Edit Role';
        $data['content'] = $this->load->view('Role/edit', $data, TRUE);
        $this->load->view($this->session->userdata("template"), $data);
    }

    function update_role_ajax() {
        $id = $this->input->post('id', TRUE);
        $role = $this->input->post('role', TRUE);

        $data = array(
            'role' => $role,
            'last_update_by' => $this->session->userdata("username"),
            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
        );

        $result = $this->Role_model->updateRole(array('id' => $id), $data);
        if ($result > 0) {
          echo json_encode(array('result' => 'true'));
        } else {
          echo json_encode(array('result' => 'false'));
        }
    }

    function delete($id)
    {
        $this->Role_model->deleteRole($id);
        redirect("Role", 'refresh');
    }
}
?>
