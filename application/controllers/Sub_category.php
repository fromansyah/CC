<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sub_category extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
        $this->load->library('cart');
        $this->load->library('Custom_menu');
        $this->load->helper('file');
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Sub_category_model', 'Sub_category_model');
        $this->load->model('Category_model', 'Category_model');
    }
    
    public function index(){
        $this->lists();
    }
    
    function lists($category_id) {
        $this->load->helper('url');
        
        $result = $this->Category_model->getCategoryById($category_id)->result();
        
        $data['category_id'] = $category_id;
        $data['category_name'] = $result[0]->category_name;

        $data['page_title'] = 'Category Menu Management';
        $data['content'] = $this->load->view('sub_category/list', $data, TRUE);
        //$this->load->view('themes/blank',$data);
        $this->load->view('themes/custom_theme',$data);
        $this->load->view('themes/footer');
    }
 
    public function ajax_list($category_id='')
    {
        $list = $this->Sub_category_model->get_datatables($category_id);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $sub_category) {

            $button = '<a href=\'#\' onclick="edit_sub_category(\''.$sub_category->sub_category_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_edit.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_sub_category(\''.$sub_category->sub_category_id.'\''.',\''.$sub_category->sub_category_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_delete.png\'></a>';
            
            $no++;
            $row = array();
            $row[] = $sub_category->sub_category_id;
            $row[] = $sub_category->sub_category_name;
 
            //add html for action
            $row[] = $button;
 
            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Sub_category_model->count_all($category_id),
                        "recordsFiltered" => $this->Sub_category_model->count_filtered($category_id),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_add()
    {
        $data = array(
                'category_id' => $this->input->post('categoryId'),
                'sub_category_name' => $this->input->post('SubCategory'),
                'created_by' => $this->session->userdata("username"),
                'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                'last_update_by' => $this->session->userdata("username"),
                'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
            );
        
        $insert = $this->Sub_category_model->save($data);
        echo json_encode(array("status" => TRUE));
    }
    
    public function ajax_update()
    {
        $sub_category_id = $this->input->post('id');
        
        $data = array(
                'sub_category_name' => $this->input->post('SubCategory'),
                'last_update_by' => $this->session->userdata("username"),
                'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
            );
        
        $this->Sub_category_model->update(array('sub_category_id' => $sub_category_id), $data);
        echo json_encode(array("status" => TRUE));
    }
    
    public function ajax_edit($sub_category_id)
    {
        $data = $this->Sub_category_model->get_by_id($sub_category_id);
        echo json_encode($data);
    }
    
    public function ajax_delete($sub_category_id)
    {
        $this->Sub_category_model->delete_by_id($sub_category_id);
        echo json_encode(array("status" => TRUE));
    }


    function load_data($category_id) {
        $valid_fields = array('category_id', 'menu_item_id');

	$this->flexigrid->validate_post('menu_item_id','ASC',$valid_fields);
        
	$records = $this->Sub_category_model->get_categoryMenu_flexigrid($category_id);

	$this->output->set_header($this->config->item('json_header'));

        $record_items = array();

	foreach ($records['records']->result() as $row)
	{
            $hasil = $this->Menu_item_model->getMenuItemById($row->menu_item_id)->result();
            
            $button = //'<a href=\'#\' onclick="edit_sub_category(\''.$row->category_id.'\''.',\''.$row->menu_item_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/b_edit.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_sub_category(\''.$row->category_id.'\''.',\''.$row->menu_item_id.'\''.',\''.$hasil[0]->menu_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/Delete.png\'></a>';
            
            $record_items[] = array(
                $row->menu_item_id,
                '['.$row->menu_item_id.'] - '.$hasil[0]->menu_name,
                $button
			);
        }
	//Print please
	$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
    }
    
    function add($category_id)
    {
        $category = $this->Category_model->getCategoryById($category_id)->result();
        $data['category_id'] = $category_id;
        $data['category_name'] = $category[0]->category;
        $data['menu_list'] = $this->Menu_item_model->getAllMenuItemList();
        $data['page_title'] = 'Add Category Menu';
        $data['content'] = $this->load->view('sub_category/add', $data, TRUE);
        $this->load->view($this->session->userdata("template"), $data);
    }

    function save_categoryMenu_ajax() {
        $category_id = $this->input->post('category_id', TRUE);
        $menu_item_id = $this->input->post('menu_item_id', TRUE);

        $data = array(
            'category_id' => $category_id,
            'menu_item_id' => $menu_item_id,
            'created_by' => $this->session->userdata("username"),
            'created_date' => date('Y-m-d H:i:s', strtotime('now')),
            'last_update_by' => $this->session->userdata("username"),
            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
        );

        $this->Sub_category_model->insertSub_category($data);
        echo json_encode(array('result' => 'true'));
    }

    function edit($category_id = '', $menu_item_id = '')
    {
        //$hasil = $this->Sub_category_model->getCategoryMenuById($category_id, $menu_item_id)->result();
        
        $data['category_id'] = $category_id;
        $data['menu_item_id'] = $menu_item_id;
        
        $category = $this->Category_model->getCategoryById($category_id)->result();
        $data['category_name'] = $category[0]->category;
        
        $data['menu_list'] = $this->Menu_item_model->getAllMenuItemList();
        
        $data['page_title'] = 'Edit Category Menu';
        $data['content'] = $this->load->view('Sub_category/edit', $data, TRUE);
        $this->load->view($this->session->userdata("template"), $data);
    }

    function update_categoryMenu_ajax() {
        $category_id = $this->input->post('category_id', TRUE);
        $menu_item_id = $this->input->post('menu_item_id', TRUE);

        $data = array(
            'category_id' => $category_id,
            'menu_item_id' => $menu_item_id,
            'last_update_by' => $this->session->userdata("username"),
            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
        );

        $result = $this->Sub_category_model->updateCategoryMenu(array('category_id' => $category_id, 'menu_item_id' => $menu_item_id), $data);
        if ($result > 0) {
          echo json_encode(array('result' => 'true'));
        } else {
          echo json_encode(array('result' => 'false'));
        }
    }

    function delete($category_id, $menu_item_id)
    {
        $this->Sub_category_model->deleteCategoryMenu($category_id, $menu_item_id);
        redirect("Sub_category/lists/$category_id", 'refresh');
    }
    
    public function getSubCategoryList($category){
        echo json_encode($this->Sub_category_model->getSub_categoryList($category));
    }
}
?>
