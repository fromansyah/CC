<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
        $this->load->library('cart');
        $this->load->library('Custom_menu');
        $this->load->helper('file');
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Category_model', 'Category_model');
    }
    
    public function index(){
        $this->lists();
    }
    
    function lists() {
        $this->load->helper('url');
        $data['test'] = '';
        
        $data['page_title'] = 'Category Management';
        $data['content'] = $this->load->view('category/list', $data, TRUE);
        //$this->load->view('themes/blank',$data);
        $this->load->view('themes/custom_theme',$data);
        $this->load->view('themes/footer');
    }
 
    public function ajax_list()
    {
        $list = $this->Category_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $category) {

            $no++;
            $row = array();
            $row[] = $category->category_id;
            $row[] = $category->category_name;
 
            //add html for action
            $button = '<a href=\'#\' onclick="edit_category(\''.$category->category_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_edit.png\' title=\'Edit Program\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="view_sub_category(\''.$category->category_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/view-details.png\' title=\'View Program Type\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_category(\''.$category->category_id.'\''.',\''.$category->category_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_delete.png\' title=\'Delete Program\'></a>'.'&nbsp&nbsp&nbsp';
            
            $row[] = $button;

            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Category_model->count_all(),
                        "recordsFiltered" => $this->Category_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $data = $this->Category_model->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        if($this->input->post('category') == null || $this->input->post('category') == ''){
            $error_message = 'Category can not empty.';
            
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
        }else{
            $data = array(
                    'category_name' => $this->input->post('category'),
                    'created_by' => $this->session->userdata("username"),
                    'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $insert = $this->Category_model->save($data);
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_update()
    {
        if($this->input->post('category') == null || $this->input->post('category') == ''){
            $error_message = 'Category can not empty.';
            
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
        }else{
            $data = array(
                    'category_name' => $this->input->post('category'),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->Category_model->update(array('category_id' => $this->input->post('id')), $data);
            
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_delete($id)
    {
        $this->Category_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
    

    function load_data() {
        $valid_fields = array('id', 'category');

	$this->flexigrid->validate_post('id','ASC',$valid_fields);
        
	$records = $this->Category_model->get_category_flexigrid();

	$this->output->set_header($this->config->item('json_header'));

        $record_items = array();

	foreach ($records['records']->result() as $row)
	{
            $button = '<a href=\'#\' onclick="edit_category(\''.$row->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/b_edit.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="view_category(\''.$row->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/046.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_category(\''.$row->id.'\''.',\''.$row->category.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/Delete.png\'></a>';
            
            $record_items[] = array(
                $row->id,
                $row->id,
                $row->category,
                $button
			);
        }
	//Print please
	$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
    }
    
    function add()
    {
        $data['page_title'] = 'Add Menu';
        $data['content'] = $this->load->view('category/add', $data, TRUE);
        $this->load->view($this->session->userdata("template"), $data);
    }

    function save_category_ajax() {
        $category = $this->input->post('category', TRUE);

        $data = array(
            'category' => $category,
            'created_by' => $this->session->userdata("username"),
            'created_date' => date('Y-m-d H:i:s', strtotime('now')),
            'last_update_by' => $this->session->userdata("username"),
            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
        );

        $this->Category_model->insertCategory($data);
        echo json_encode(array('result' => 'true'));
    }

    function edit($id = '')
    {
        $hasil = $this->Category_model->getCategoryById($id)->result();
        
        $data['id'] = $hasil[0]->id;
        $data['category'] = $hasil[0]->category;
        
        $data['page_title'] = 'Edit Category';
        $data['content'] = $this->load->view('Category/edit', $data, TRUE);
        $this->load->view($this->session->userdata("template"), $data);
    }

    function update_category_ajax() {
        $id = $this->input->post('id', TRUE);
        $category = $this->input->post('category', TRUE);

        $data = array(
            'category' => $category,
            'last_update_by' => $this->session->userdata("username"),
            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
        );

        $result = $this->Category_model->updateCategory(array('id' => $id), $data);
        if ($result > 0) {
          echo json_encode(array('result' => 'true'));
        } else {
          echo json_encode(array('result' => 'false'));
        }
    }

    function delete($id)
    {
        $this->Category_model->deleteCategory($id);
        redirect("Category", 'refresh');
    }
    
    function ajax_get_category($category_id){
        $result = $this->Category_model->getCategoryById($category_id)->result();
        
        echo json_encode(array('result' => 'true', 'category_name' => $result[0]->category_name));
    }
}
?>
