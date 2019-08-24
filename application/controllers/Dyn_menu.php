<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dyn_menu extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
        $this->load->helper('flexigrid');
        $this->load->model('Dyn_menu_model', 'Dyn_menu_model');
        $this->load->model('Users_model', 'Users_model');
    }
    
    public function index(){
        $check = $this->Users_model->getRoleMenu('index.php/Dyn_menu', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $this->load->helper('url');

            $data['page_name'] = 'Menu Management';
            $data['menu_list'] = $this->Dyn_menu_model->getMenuList();

            $data['content'] = $this->load->view('dyn_menu/list', $data, TRUE);
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
        $list = $this->Dyn_menu_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $menu) {
            
            $parent_name = '';
            if($menu->parent_id != 0){
                $parent_menu = $this->Dyn_menu_model->getMenuById($menu->parent_id)->result();
                $parent_name = $parent_menu[0]->title;
            }
            
            $no++;
            $row = array();
            $row[] = $menu->id;
            $row[] = $menu->title;
            $row[] = $menu->url;
            $row[] = $menu->page_id;
            $row[] = '['.$menu->parent_id.'] '.$parent_name;
 
            //add html for action
            $button = '<a href=\'#\' onclick="edit_menu(\''.$menu->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_menu(\''.$menu->id.'\''.',\''.$menu->title.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\'></a>';
            
            $row[] = $button;

            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Dyn_menu_model->count_all(),
                        "recordsFiltered" => $this->Dyn_menu_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $data = $this->Dyn_menu_model->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        if($this->input->post('menuName') == null || $this->input->post('menuName') == ''){
            
            $error_message = 'Menu name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('pageNumber') == null || $this->input->post('pageNumber') == ''){
            
            $error_message = 'Page number can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    'title' => $this->input->post('menuName'),
                    'url' => $this->input->post('menuUrl'),
                    'parent_id' => $this->input->post('parentId'),
                    'link_type' => 'page',
                    'page_id' => $this->input->post('pageNumber'),
                    'module_name' => null,
                    'uri' => null,
                    'dyn_group_id' => 1,
                    'position' => 0,
                    'target' => null,
                    'is_parent' => 0,
                    'show_menu' => 1
                );
            
            $this->Dyn_menu_model->save($data);
            echo json_encode(array("status" => TRUE));
//            echo json_encode(array("status" => FALSE, 'error' => $this->input->post('menuName')));
        }
    }
 
    public function ajax_update()
    {
        if($this->input->post('menuName') == null || $this->input->post('menuName') == ''){
            
            $error_message = 'Menu name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('pageNumber') == null || $this->input->post('pageNumber') == ''){
            
            $error_message = 'Page number can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    'title' => $this->input->post('menuName'),
                    'url' => $this->input->post('menuUrl'),
                    'parent_id' => $this->input->post('parentId'),
                    'page_id' => $this->input->post('pageNumber')
                );
            
            $this->Dyn_menu_model->update(array('id' => $this->input->post('menuId')), $data);
            
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_delete($id)
    {
        $this->Dyn_menu_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
    
    function getmenuList(){
        echo json_encode($this->Dyn_menu_model->getMenuList());
    }
}
?>
