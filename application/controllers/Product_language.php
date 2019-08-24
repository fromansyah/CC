<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_language extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->support_lib->check_login();
        $this->load->library('cart');
        $this->load->library('Custom_menu');
        $this->load->helper('file');
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Users_model', 'Users_model');
        $this->load->model('User_country_model', 'User_country_model');
        $this->load->model('Category_model', 'Category_model');
        $this->load->model('Sub_category_model', 'Sub_category_model');
        $this->load->model('Country_model', 'Country_model');
        $this->load->model('Product_model', 'Product_model');
        $this->load->model('Cat_sheet_model', 'Cat_sheet_model');
        $this->load->model('Safety_model', 'Safety_model');
        $this->load->model('Product_safety_model', 'Product_safety_model');
        $this->load->model('Product_language_model', 'Product_language_model');
    }
    
    public function ajax_get_product_language($product_id, $country){
        $data = $this->Product_language_model->get_by_product_country($product_id, $country);
        echo json_encode($data);
    }
    
    public function ajax_save(){
        $id = $this->input->post('productLangId');
        
        if($id == null){
//            echo json_encode(array("status" => FALSE, "error" => 'ADD '.$id));
            $data = array(
                'product_id' => $this->input->post('productId_lang'),
                'product_no' => $this->input->post('productNo_lang'),
                'product_name' => $this->input->post('productName_lang'),
                'country' => $this->input->post('country_lang'),
                'type' => $this->input->post('type_lang'),
                'desc' => $this->input->post('desc_lang'),
                'application' => $this->input->post('application_lang'),
                'property' => $this->input->post('property_lang'),
                'dilution' => $this->input->post('dilution_lang'),
                'how_to_use' => $this->input->post('howToUse_lang'),
                'first_aid' => $this->input->post('firstAid_lang'),
                'created_by' => $this->session->userdata("username"),
                'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                'last_update_by' => $this->session->userdata("username"),
                'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
            );
        
            $this->Product_language_model->save($data);
        }else{
//            echo json_encode(array("status" => FALSE, "error" => 'EDIT '.$id));
            
            $data = array(
                'product_id' => $this->input->post('productId_lang'),
                'product_no' => $this->input->post('productNo_lang'),
                'product_name' => $this->input->post('productName_lang'),
                'country' => $this->input->post('country_lang'),
                'type' => $this->input->post('type_lang'),
                'desc' => $this->input->post('desc_lang'),
                'application' => $this->input->post('application_lang'),
                'property' => $this->input->post('property_lang'),
                'dilution' => $this->input->post('dilution_lang'),
                'how_to_use' => $this->input->post('howToUse_lang'),
                'first_aid' => $this->input->post('firstAid_lang'),
                'last_update_by' => $this->session->userdata("username"),
                'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
            );
            
            $this->Product_language_model->update(array('id' => $id), $data);
        }
//        $data = array(
//            'role' => $this->input->post('role'),
//            'created_by' => $this->session->userdata("username"),
//            'created_date' => date('Y-m-d H:i:s', strtotime('now')),
//            'last_update_by' => $this->session->userdata("username"),
//            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
//        );
//        
//        $this->Role_model->save($data);
        echo json_encode(array("status" => TRUE));
    }
}
?>
