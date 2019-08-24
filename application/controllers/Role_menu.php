<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_menu extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
        $this->load->library('encrypt');
        $this->load->model('Role_model', 'Role_model');
        $this->load->model('Role_menu_model', 'Role_menu_model');
        $this->load->model('Dyn_menu_model', 'Dyn_menu_model');
        $this->load->model('Users_model', 'Users_model');
    }
    
    public function index(){
        $this->lists();
    }
    
    function lists($role_id) {
//        $check = $this->Users_model->getRoleMenu('Role_menu', $this->session->userdata("role"));
        
        
        if($this->session->userdata("role") == 1 || $this->session->userdata("role") == 2){
            
            $role_id = str_replace(array('-','_','~'), array('=','+','/'), $role_id);
            $role_id = $this->encrypt->decode($role_id);
        
            $this->load->helper('url');

            $data['menu_list'] = $this->Dyn_menu_model->getMenuList();

            $result = $this->Role_model->getRoleById($role_id)->result();

            $data['role_id'] = $role_id;
            $data['role_name'] = $result[0]->role;
            
            $data['encr_id'] = str_replace(array('=','+','/'), array('-','_','~'), $this->encrypt->encode($role_id));

            $data['page_title'] = 'Role Menu Management';
            $data['content'] = $this->load->view('role_menu/list', $data, TRUE);
            $this->load->view('themes/blank',$data);
            $this->load->view('themes/footer');
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
 
    public function ajax_list($role_id='')
    {   
        //$role_id = str_replace(array('-','_','~'), array('=','+','/'), $role_id);
        $role_id = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $role_id));
        
        $list = $this->Role_menu_model->get_datatables($role_id);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $role_menu) {

            $menu = $this->Dyn_menu_model->getMenuById($role_menu->menu_id)->result();
            
            $button = '<a href=\'#\' onclick="edit_role_menu(\''.$role_menu->role_id.'\''.',\''.$role_menu->menu_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_role_menu(\''.$role_menu->role_id.'\''.',\''.$role_menu->menu_id.'\''.',\''.$menu[0]->title.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\'></a>';
            
            $no++;
            $row = array();
            $row[] = '['.$role_menu->menu_id.'] '.$menu[0]->title;
 
            //add html for action
            $row[] = $button;
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Role_menu_model->count_all($role_id),
                        "recordsFiltered" => $this->Role_menu_model->count_filtered($role_id),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_add()
    {
        $data = array(
                'role_id' => $this->input->post('roleId'),
                'menu_id' => $this->input->post('menuId'),
                'created_by' => $this->session->userdata("username"),
                'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                'last_update_by' => $this->session->userdata("username"),
                'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
            );
        
        $insert = $this->Role_menu_model->save($data);
        echo json_encode(array("status" => TRUE));
    }
    
    public function ajax_update()
    {
        $role_id = $this->input->post('roleId');
        $old_menu_id = $this->input->post('oldMenuId');
        $menu_id = $this->input->post('menuId');
        
        $data = array(
                'role_id' => $role_id,
                'menu_id' => $menu_id,
                'last_update_by' => $this->session->userdata("username"),
                'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
            );
        
        $this->Role_menu_model->update(array('role_id' => $role_id, 
                                             'menu_id' => $old_menu_id), 
                                       $data);
        echo json_encode(array("status" => TRUE));
    }
    
    public function ajax_edit($role_id, $menu_id)
    {
        $data = $this->Role_menu_model->get_by_id($role_id, $menu_id);
        echo json_encode($data);
    }
    
    public function ajax_delete($role_id, $menu_id)
    {
        $this->Role_menu_model->delete_by_id($role_id, $menu_id);
        echo json_encode(array("status" => TRUE));
    }


    function load_data($role_id) {
        $valid_fields = array('role_id', 'menu_item_id');

	$this->flexigrid->validate_post('menu_item_id','ASC',$valid_fields);
        
	$records = $this->Role_menu_model->get_roleMenu_flexigrid($role_id);

	$this->output->set_header($this->config->item('json_header'));

        $record_items = array();

	foreach ($records['records']->result() as $row)
	{
            $hasil = $this->Menu_item_model->getMenuItemById($row->menu_item_id)->result();
            
            $button = //'<a href=\'#\' onclick="edit_role_menu(\''.$row->role_id.'\''.',\''.$row->menu_item_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/b_edit.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_role_menu(\''.$row->role_id.'\''.',\''.$row->menu_item_id.'\''.',\''.$hasil[0]->menu_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/Delete.png\'></a>';
            
            $record_items[] = array(
                $row->menu_item_id,
                '['.$row->menu_item_id.'] - '.$hasil[0]->menu_name,
                $button
			);
        }
	//Print please
	$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
    }
    
    function add($role_id)
    {
        $role = $this->Role_model->getRoleById($role_id)->result();
        $data['role_id'] = $role_id;
        $data['role_name'] = $role[0]->role;
        $data['menu_list'] = $this->Menu_item_model->getAllMenuItemList();
        $data['page_title'] = 'Add Role Menu';
        $data['content'] = $this->load->view('role_menu/add', $data, TRUE);
        $this->load->view($this->session->userdata("template"), $data);
    }

    function save_roleMenu_ajax() {
        $role_id = $this->input->post('role_id', TRUE);
        $menu_item_id = $this->input->post('menu_item_id', TRUE);

        $data = array(
            'role_id' => $role_id,
            'menu_item_id' => $menu_item_id,
            'created_by' => $this->session->userdata("username"),
            'created_date' => date('Y-m-d H:i:s', strtotime('now')),
            'last_update_by' => $this->session->userdata("username"),
            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
        );

        $this->Role_menu_model->insertRole_menu($data);
        echo json_encode(array('result' => 'true'));
    }

    function edit($role_id = '', $menu_item_id = '')
    {
        //$hasil = $this->Role_menu_model->getRoleMenuById($role_id, $menu_item_id)->result();
        
        $data['role_id'] = $role_id;
        $data['menu_item_id'] = $menu_item_id;
        
        $role = $this->Role_model->getRoleById($role_id)->result();
        $data['role_name'] = $role[0]->role;
        
        $data['menu_list'] = $this->Menu_item_model->getAllMenuItemList();
        
        $data['page_title'] = 'Edit Role Menu';
        $data['content'] = $this->load->view('Role_menu/edit', $data, TRUE);
        $this->load->view($this->session->userdata("template"), $data);
    }

    function update_roleMenu_ajax() {
        $role_id = $this->input->post('role_id', TRUE);
        $menu_item_id = $this->input->post('menu_item_id', TRUE);

        $data = array(
            'role_id' => $role_id,
            'menu_item_id' => $menu_item_id,
            'last_update_by' => $this->session->userdata("username"),
            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
        );

        $result = $this->Role_menu_model->updateRoleMenu(array('role_id' => $role_id, 'menu_item_id' => $menu_item_id), $data);
        if ($result > 0) {
          echo json_encode(array('result' => 'true'));
        } else {
          echo json_encode(array('result' => 'false'));
        }
    }

    function delete($role_id, $menu_item_id)
    {
        $this->Role_menu_model->deleteRoleMenu($role_id, $menu_item_id);
        redirect("Role_menu/lists/$role_id", 'refresh');
    }
}
?>
