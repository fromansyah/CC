<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Country extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
        $this->load->library('cart');
        $this->load->library('Custom_menu');
        $this->load->helper('file');
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Country_model', 'Country_model');
    }
    
    public function index(){
        $this->load->helper('url');
        
        $data['page_name'] = 'Country Management';
        
        $data['content'] = $this->load->view('country/list', $data, TRUE);
        //$this->load->view('themes/blank',$data);
        $this->load->view('themes/custom_theme',$data);
        $this->load->view('themes/footer');
    }
 
    public function ajax_list()
    {
        $list = $this->Country_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $country) {
            
            $no++;
            $row = array();
            $row[] = $country->country_iso_code;
            $row[] = $country->country_name;
            $row[] = '<img src="'.base_url().'assets/uploads/images/'.$country->image_name.'" width="75" height="75" />';
 
            //add html for action
            $button = '<a href=\'#\' onclick="edit_country(\''.$country->country_iso_code.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_edit.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_country(\''.$country->country_iso_code.'\''.',\''.$country->country_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_delete.png\'></a>';
            
            $row[] = $button;

            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Country_model->count_all(),
                        "recordsFiltered" => $this->Country_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $data = $this->Country_model->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        if($this->input->post('countryName') == null || $this->input->post('countryName') == ''){
            
            $error_message = 'Menu name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('pageNumber') == null || $this->input->post('pageNumber') == ''){
            
            $error_message = 'Page number can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    'title' => $this->input->post('countryName'),
                    'url' => $this->input->post('countryUrl'),
                    'parent_id' => $this->input->post('parentId'),
                    'link_type' => 'page',
                    'page_id' => $this->input->post('pageNumber'),
                    'module_name' => null,
                    'uri' => null,
                    'dyn_group_id' => 1,
                    'position' => 0,
                    'target' => null,
                    'is_parent' => 0,
                    'show_country' => 1
                );
            
            $this->Country_model->save($data);
            echo json_encode(array("status" => TRUE));
//            echo json_encode(array("status" => FALSE, 'error' => $this->input->post('countryName')));
        }
    }
 
    public function ajax_update()
    {
        if($this->input->post('countryName') == null || $this->input->post('countryName') == ''){
            
            $error_message = 'Menu name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('pageNumber') == null || $this->input->post('pageNumber') == ''){
            
            $error_message = 'Page number can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    'title' => $this->input->post('countryName'),
                    'url' => $this->input->post('countryUrl'),
                    'parent_id' => $this->input->post('parentId'),
                    'page_id' => $this->input->post('pageNumber')
                );
            
            $this->Country_model->update(array('id' => $this->input->post('countryId')), $data);
            
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_delete($id)
    {
        $country_result = $this->Country_model->getCountryById($id)->result();
        if($country_result[0]->url != null && $country_result[0]->url != ''){
            unlink($country_result[0]->url);
        }
        
        $this->Country_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
    
    function getcountryList(){
        echo json_encode($this->Country_model->getMenuList());
    }
    
    public function save_country(){
        $config['upload_path']          = './assets/uploads/images';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 500;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;

        $this->load->library('upload', $config);
        
        if($this->input->post('mode') == 0){
            $image = -1;
            if ($this->upload->do_upload('berkas')){
                $success = array('upload_data' => $this->upload->data());
                $image = 1;
                $data['image_url'] = $success['upload_data']['full_path'];
                $data['image_name'] = $success['upload_data']['file_name'];

                $data = array(
                        'country_iso_code' => $this->input->post('countryId'),
                        'country_name' => $this->input->post('countryName'),
                        'url' => $success['upload_data']['full_path'],
                        'image_name' => $success['upload_data']['file_name'],
                        'created_by' => $this->session->userdata("username"),
                        'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                        'last_update_by' => $this->session->userdata("username"),
                        'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                    );
            }else{
                $image = 0;
                $data = array(
                        'country_iso_code' => $this->input->post('countryId'),
                        'country_name' => $this->input->post('countryName'),
                        'created_by' => $this->session->userdata("username"),
                        'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                        'last_update_by' => $this->session->userdata("username"),
                        'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                    );
            }
            
            $this->Country_model->save($data);
        }else{
            $country_result = $this->Country_model->getCountryById($this->input->post('countryId'))->result();
            
            $image = -2;
            if ($this->upload->do_upload('berkas')){
                
                if($country_result[0]->url != null && $country_result[0]->url != ''){
                    unlink($country_result[0]->url);
                }
                
                $success = array('upload_data' => $this->upload->data());
                $image = 2;
                $data['image_url'] = $success['upload_data']['full_path'];
                $data['image_name'] = $success['upload_data']['file_name'];

                $data = array(
                        'country_iso_code' => $this->input->post('countryId'),
                        'country_name' => $this->input->post('countryName'),
                        'url' => $success['upload_data']['full_path'],
                        'image_name' => $success['upload_data']['file_name'],
                        'last_update_by' => $this->session->userdata("username"),
                        'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                    );
            }else{
                $image = -3;
                $data = array(
                        'country_iso_code' => $this->input->post('countryId'),
                        'country_name' => $this->input->post('countryName'),
                        'last_update_by' => $this->session->userdata("username"),
                        'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                    );
            }
            
            $this->Country_model->update(array('country_iso_code' => $this->input->post('countryId')), $data);
        }
        
        
//        $data['data'] = $this->input->post('countryName');
//        $data['data'] = $image;
//        $this->load->view('Country/test',$data);
        redirect("Country");
    }
}
?>
