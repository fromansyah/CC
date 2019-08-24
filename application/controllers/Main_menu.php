<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Main_menu extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->support_lib->check_login();
	    $this->load->library('aad_auth');
        $this->load->library('cart');
        $this->load->library('encrypt');
        $this->load->helper('file');
        $this->load->helper('security');
        $this->load->helper(array('form', 'url'));
//        $this->load->library('Custom_menu');
        $this->load->model('Users_model', 'Users_model');
        $this->load->model('Report_model', 'Report_model');
//        $this->load->model('Category_model', 'Category_model');
//        $this->load->model('Country_model', 'Country_model');
    }
    
    function index()
    {
        $this->cart->destroy();
        if ($this->session->userdata("username")):
            $data['notification'] = $this->Report_model->getNotificationList();
            
            $data['content'] = $this->load->view('Main_menu/home', $data, TRUE);
            $this->load->view('themes/clearfix_home_theme',$data);
            $this->load->view('themes/footer');
        else:
            $data["title"] = "Login";
            $data["content"] = $this->load->view('vlogin',$data,true);
            $this->load->view("login", $data);
        endif;
    }
    
    function logout()
    {
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('password');
        $this->session->unset_userdata('user_group');
        $this->session->unset_userdata('mode');
        $this->session->unset_userdata('nik');
        $this->session->unset_userdata('emp_id');
        
        $return_to = $this->config->item('base_url');
        $this->aad_auth->logout($return_to === NULL ? site_url() : $return_to);
        
        $this->session->unset_userdata('admin_menu');
        $this->session->unset_userdata('menu_item_admin');
        $this->session->unset_userdata('user_menu');
        $this->session->unset_userdata('menu_name');
        $this->session->unset_userdata('menu_link');

        $this->session->unset_userdata('role');
        $this->session->unset_userdata('mode');
        $this->session->unset_userdata('template');
        $this->session->unset_userdata('edit_template');
        
        redirect('Welcome', 'refresh');
    }
    
    public function get_csrf()
    {
        $error['csrf_token'] = $this->security->get_csrf_hash();
        $error['csrf_name'] = $this->security->get_csrf_token_name();
        echo json_encode($error);
        die();
    }
}
?>