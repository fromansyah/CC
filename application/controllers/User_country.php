<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_country extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
        $this->load->library('cart');
        $this->load->library('Custom_menu');
        $this->load->helper('file');
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->model('User_country_model', 'User_country_model');
        $this->load->model('User_model', 'User_model');
        $this->load->model('Country_model', 'Country_model');
    }
    
    public function index(){
        $this->lists();
    }
    
    function lists($user_id) {
        $this->load->helper('url');
        
        $result = $this->User_model->getUserById($user_id)->result();
        $data['user_id'] = $user_id;
        $data['user_name'] = $result[0]->username;
        
        $data['country_list'] = $this->Country_model->getCountryList();
        
        $data['page_title'] = 'User Country Management';
        $data['content'] = $this->load->view('user_country/list', $data, TRUE);
        //$this->load->view('themes/blank',$data);
        $this->load->view('themes/custom_theme',$data);
        $this->load->view('themes/footer');
    }
 
    public function ajax_list($user_id='')
    {
        $list = $this->User_country_model->get_datatables($user_id);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $user_country) {
            
            $country = $this->Country_model->getCountryById($user_country->country)->result();

            $button = '<a href=\'#\' onclick="edit_user_country(\''.$user_country->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_edit.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_user_country(\''.$user_country->id.'\''.',\''.$user_country->country.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_delete.png\'></a>';
            
            $no++;
            $row = array();
            $row[] = $user_country->id;
            $row[] = '['.$user_country->country.'] '.$country[0]->country_name;
 
            //add html for action
            $row[] = $button;
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->User_country_model->count_all($user_id),
                        "recordsFiltered" => $this->User_country_model->count_filtered($user_id),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_add()
    {
        $data = array(
                'user_id' => $this->input->post('userId'),
                'country' => $this->input->post('country'),
                'created_by' => $this->session->userdata("username"),
                'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                'last_update_by' => $this->session->userdata("username"),
                'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
            );
        
        $insert = $this->User_country_model->save($data);
        echo json_encode(array("status" => TRUE));
    }
    
    public function ajax_update()
    {
        $id = $this->input->post('id');
        
        if($this->input->post('oldCountry') != $this->input->post('country')){
            $data = array(
                    'country' => $this->input->post('country'),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );

            $this->User_country_model->update(array('id' => $id), $data);
        }
        echo json_encode(array("status" => TRUE));
    }
    
    public function ajax_edit($sub_user_id)
    {
        $data = $this->User_country_model->get_by_id($sub_user_id);
        echo json_encode($data);
    }
    
    public function ajax_delete($sub_user_id)
    {
        $this->User_country_model->delete_by_id($sub_user_id);
        echo json_encode(array("status" => TRUE));
    }


    function load_data($user_id) {
        $valid_fields = array('user_id', 'menu_item_id');

	$this->flexigrid->validate_post('menu_item_id','ASC',$valid_fields);
        
	$records = $this->User_country_model->get_categoryMenu_flexigrid($user_id);

	$this->output->set_header($this->config->item('json_header'));

        $record_items = array();

	foreach ($records['records']->result() as $row)
	{
            $hasil = $this->Menu_item_model->getMenuItemById($row->menu_item_id)->result();
            
            $button = //'<a href=\'#\' onclick="edit_user_country(\''.$row->user_id.'\''.',\''.$row->menu_item_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/b_edit.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_user_country(\''.$row->user_id.'\''.',\''.$row->menu_item_id.'\''.',\''.$hasil[0]->menu_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/Delete.png\'></a>';
            
            $record_items[] = array(
                $row->menu_item_id,
                '['.$row->menu_item_id.'] - '.$hasil[0]->menu_name,
                $button
			);
        }
	//Print please
	$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
    }
    
    function add($user_id)
    {
        $category = $this->User_model->getUserById($user_id)->result();
        $data['user_id'] = $user_id;
        $data['user_name'] = $category[0]->category;
        $data['menu_list'] = $this->Menu_item_model->getAllMenuItemList();
        $data['page_title'] = 'Add User Menu';
        $data['content'] = $this->load->view('user_country/add', $data, TRUE);
        $this->load->view($this->session->userdata("template"), $data);
    }

    function save_categoryMenu_ajax() {
        $user_id = $this->input->post('user_id', TRUE);
        $menu_item_id = $this->input->post('menu_item_id', TRUE);

        $data = array(
            'user_id' => $user_id,
            'menu_item_id' => $menu_item_id,
            'created_by' => $this->session->userdata("username"),
            'created_date' => date('Y-m-d H:i:s', strtotime('now')),
            'last_update_by' => $this->session->userdata("username"),
            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
        );

        $this->User_country_model->insertUser_country($data);
        echo json_encode(array('result' => 'true'));
    }

    function edit($user_id = '', $menu_item_id = '')
    {
        //$hasil = $this->User_country_model->getUserMenuById($user_id, $menu_item_id)->result();
        
        $data['user_id'] = $user_id;
        $data['menu_item_id'] = $menu_item_id;
        
        $category = $this->User_model->getUserById($user_id)->result();
        $data['user_name'] = $category[0]->category;
        
        $data['menu_list'] = $this->Menu_item_model->getAllMenuItemList();
        
        $data['page_title'] = 'Edit User Menu';
        $data['content'] = $this->load->view('User_country/edit', $data, TRUE);
        $this->load->view($this->session->userdata("template"), $data);
    }

    function update_categoryMenu_ajax() {
        $user_id = $this->input->post('user_id', TRUE);
        $menu_item_id = $this->input->post('menu_item_id', TRUE);

        $data = array(
            'user_id' => $user_id,
            'menu_item_id' => $menu_item_id,
            'last_update_by' => $this->session->userdata("username"),
            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
        );

        $result = $this->User_country_model->updateUserMenu(array('user_id' => $user_id, 'menu_item_id' => $menu_item_id), $data);
        if ($result > 0) {
          echo json_encode(array('result' => 'true'));
        } else {
          echo json_encode(array('result' => 'false'));
        }
    }

    function delete($user_id, $menu_item_id)
    {
        $this->User_country_model->deleteUserMenu($user_id, $menu_item_id);
        redirect("User_country/lists/$user_id", 'refresh');
    }
}
?>
