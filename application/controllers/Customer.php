<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
        $this->load->library('cart');
        $this->load->library('Custom_menu');
        $this->load->helper('file');
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Customer_model', 'Customer_model');
        $this->load->model('Country_model', 'Country_model');
    }
    
    public function index($country=''){
        $this->lists($country);
    }
    
    function lists($country='') {
        $this->load->helper('url');
        
        $role = $this->session->userdata("role");
        
        if($role == 1 || $role == 2){
            if($role == 1){
                $country_list = $this->Country_model->get_country_all();
            }else{
                $country_list = $this->User_country_model->get_user_country_list($this->session->userdata("user_id"));
            }
            
            $data['country'] = $country_list;
            $country_array = array();
            foreach($country_list as $row){
                array_push($country_array, $row['country_iso_code']);
            }
            
            $data['pick_country'] = 'NULL';
            $data['country_flag'] = '';
            
            if($country != ''){
                if(in_array($country, $country_array)) {
                        $country_result = $this->Country_model->getCountryById($country)->result();

                        $pick_country = $country_result[0]->country_iso_code;
                        $country_flag = $country_result[0]->image_name;

                        $data['pick_country'] = $pick_country;
                        $data['country_flag'] = $country_flag;
                        $data['content'] = $this->load->view('customer/list', $data, TRUE);
                        //$this->load->view('themes/clearfix_customer_theme',$data);
                        $this->load->view('themes/custom_theme',$data);
                }else{
                    $data['content'] = $this->load->view('error/error_1', null, TRUE);
                    $this->load->view('themes/error',$data);
                }
            }else{
                $data['content'] = $this->load->view('customer/list', $data, TRUE);
                //$this->load->view('themes/clearfix_customer_theme',$data);
                $this->load->view('themes/custom_theme',$data);
            }
        }else{
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
//        $data['test'] = '';
//        
//        $data['page_title'] = 'Customer Management';
//        $data['content'] = $this->load->view('customer/list', $data, TRUE);
//        $this->load->view('themes/blank',$data);
//        $this->load->view('themes/footer');
    }
 
    public function ajax_list($country='')
    {
        $list = $this->Customer_model->get_datatables($country);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $customer) {

            $no++;
            $row = array();
            $row[] = $customer->cust_id;
            $row[] = $customer->cust_name;
            $row[] = '<img src="'.base_url().'assets/uploads/images/'.$customer->image_name.'" height="75" />';
 
            //add html for action
            $button = '<a href=\'#\' onclick="edit_customer(\''.$customer->cust_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_edit.png\' title=\'Edit Program\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_customer(\''.$customer->cust_id.'\''.',\''.$customer->cust_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_delete.png\' title=\'Delete Program\'></a>'.'&nbsp&nbsp&nbsp';
            
            $row[] = $button;

            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Customer_model->count_all($country),
                        "recordsFiltered" => $this->Customer_model->count_filtered($country),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $data = $this->Customer_model->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        if($this->input->post('customer') == null || $this->input->post('customer') == ''){
            $error_message = 'Customer can not empty.';
            
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
        }else{
            $data = array(
                    'country' => $this->input->post('country'),
                    'cust_name' => $this->input->post('customer'),
                    'created_by' => $this->session->userdata("username"),
                    'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $insert = $this->Customer_model->save($data);
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_update()
    {
        if($this->input->post('customer') == null || $this->input->post('customer') == ''){
            $error_message = 'Customer can not empty.';
            
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
        }else{
            $data = array(
                    'cust_name' => $this->input->post('customer'),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->Customer_model->update(array('cust_id' => $this->input->post('id')), $data);
            
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_delete($id)
    {
        $this->Customer_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
    

    function load_data() {
        $valid_fields = array('id', 'customer');

	$this->flexigrid->validate_post('id','ASC',$valid_fields);
        
	$records = $this->Customer_model->get_customer_flexigrid();

	$this->output->set_header($this->config->item('json_header'));

        $record_items = array();

	foreach ($records['records']->result() as $row)
	{
            $button = '<a href=\'#\' onclick="edit_customer(\''.$row->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/b_edit.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="view_customer(\''.$row->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/046.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_customer(\''.$row->id.'\''.',\''.$row->customer.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/Delete.png\'></a>';
            
            $record_items[] = array(
                $row->id,
                $row->id,
                $row->customer,
                $button
			);
        }
	//Print please
	$this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
    }
    
    function add()
    {
        $data['page_title'] = 'Add Menu';
        $data['content'] = $this->load->view('customer/add', $data, TRUE);
        $this->load->view($this->session->userdata("template"), $data);
    }

    function save_customer_ajax() {
        $customer = $this->input->post('customer', TRUE);

        $data = array(
            'customer' => $customer,
            'created_by' => $this->session->userdata("username"),
            'created_date' => date('Y-m-d H:i:s', strtotime('now')),
            'last_update_by' => $this->session->userdata("username"),
            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
        );

        $this->Customer_model->insertCustomer($data);
        echo json_encode(array('result' => 'true'));
    }

    function edit($id = '')
    {
        $hasil = $this->Customer_model->getCustomerById($id)->result();
        
        $data['id'] = $hasil[0]->id;
        $data['customer'] = $hasil[0]->customer;
        
        $data['page_title'] = 'Edit Customer';
        $data['content'] = $this->load->view('Customer/edit', $data, TRUE);
        $this->load->view($this->session->userdata("template"), $data);
    }

    function update_customer_ajax() {
        $id = $this->input->post('id', TRUE);
        $customer = $this->input->post('customer', TRUE);

        $data = array(
            'customer' => $customer,
            'last_update_by' => $this->session->userdata("username"),
            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
        );

        $result = $this->Customer_model->updateCustomer(array('id' => $id), $data);
        if ($result > 0) {
          echo json_encode(array('result' => 'true'));
        } else {
          echo json_encode(array('result' => 'false'));
        }
    }

    function delete($id)
    {
        $this->Customer_model->deleteCustomer($id);
        redirect("Customer", 'refresh');
    }
    
    public function save_customer(){
        $config['upload_path']          = './assets/uploads/images';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 20000;
        $config['max_width']            = 20000;
        $config['max_height']           = 20000;

        $this->load->library('upload', $config);
        
        if($this->input->post('mode') == 0){
            $image = -1;
            if ($this->upload->do_upload('berkas')){
                $success = array('upload_data' => $this->upload->data());
                $image = 1;
                $data['image_url'] = $success['upload_data']['full_path'];
                $data['image_name'] = $success['upload_data']['file_name'];

                $data = array(
                        'country' => $this->input->post('country'),
                        'cust_name' => $this->input->post('custName'),
                        'url' => base_url().'assets/upload/images/'.$success['upload_data']['file_name'],
                        'image_name' => $success['upload_data']['file_name'],
                        'created_by' => $this->session->userdata("username"),
                        'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                        'last_update_by' => $this->session->userdata("username"),
                        'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                    );
            }else{
                $image = 0;
                $data = array(
                        'country' => $this->input->post('country'),
                        'cust_name' => $this->input->post('custName'),
                        'created_by' => $this->session->userdata("username"),
                        'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                        'last_update_by' => $this->session->userdata("username"),
                        'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                    );
            }
            
            $this->Customer_model->save($data);
        }else{
            $cust_result = $this->Customer_model->getCustomerById($this->input->post('custId'))->result();
            
            $image = -2;
            if ($this->upload->do_upload('berkas')){
                
                if($cust_result[0]->url != null && $cust_result[0]->url != ''){
                    unlink($cust_result[0]->url);
                }
                
                $success = array('upload_data' => $this->upload->data());
                $image = 2;
                $data['image_url'] = $success['upload_data']['full_path'];
                $data['image_name'] = $success['upload_data']['file_name'];

                $data = array(
                        'country' => $this->input->post('country'),
                        'cust_name' => $this->input->post('custName'),
                        'url' => base_url().'assets/upload/images/'.$success['upload_data']['file_name'],
                        'image_name' => $success['upload_data']['file_name'],
                        'last_update_by' => $this->session->userdata("username"),
                        'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                    );
            }else{
                $image = -3;
                $data = array(
                        'country' => $this->input->post('country'),
                        'cust_name' => $this->input->post('custName'),
                        'last_update_by' => $this->session->userdata("username"),
                        'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                    );
            }
            
            $this->Customer_model->update(array('cust_id' => $this->input->post('custId')), $data);
        }
        
        
//        $data['data'] = $this->input->post('custName');
//        $data['data'] = $image;
//        $this->load->view('Customer/test',$data);
        $country = $this->input->post('country');
        redirect("Customer/index/$country");
    }
    
}
?>
