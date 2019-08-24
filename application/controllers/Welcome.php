<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome extends CI_Controller {
    
    public function __construct()
    {
	    parent::__construct();
        //$this->support_lib->check_login();
        $this->load->library('session');
        $this->load->library('encrypt');
	    $this->load->library('aad_auth');
        $this->load->library('cart');
        $this->load->library('Custom_menu');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('file');
        $this->load->helper('security');
        $this->load->model('Users_model', 'Users_model');
        
    }

    function index()
    {
        if (!$this->aad_auth->is_logged_in())
        {
            //$this->aad_auth->login();
	        $return_to = $this->config->item('base_url').'/index.php/Welcome';
            $this->aad_auth->login($return_to === NULL ? site_url() : $return_to);
        }
        else
        {
	    $token = $this->aad_auth->id_token();
            $data = array(
                'user_info' => $this->aad_auth->user_info(),
                'id_token'  => $token->unique_name,
            );
	    
	    if ($this->Users_model->email_login($token->unique_name)) {
                redirect('index.php/Main_menu');
            } else {
                //$this->session->set_flashdata('err_message', 'You are not allowed to access this page.');
                //redirect('index.php/Welcome', 'refresh');
        		$data['title'] = 'Error Page';
        		$data["content"] = $this->load->view('error',$data,true);
            	$this->load->view("blank", $data);
            }
		
            //$data["content"] = $this->load->view('vlogin',$data,true);
            //$this->load->view("login", $data);
        }
    }

    function login(/*$username, $password*/)
    {
        $username = $this->input->post("id_user");
        $password = md5($this->input->post("password"));

        if (!($username && $password)) {
            $this->session->set_flashdata('err_message', 'ID. User dan Password harus diisi.');
            redirect('Welcome', 'refresh');
        } else {
            if ($this->Users_model->login($username, $password)) {
                redirect('Menu_utama');
            } else {
                $this->session->set_flashdata('err_message', 'ID. User dan Password yang anda masukkan salah.');
                redirect('Welcome', 'refresh');
            }
        }
    }

    function check_login() {
        $username = $this->input->post('id_user', TRUE);
        $password = $this->input->post('password', TRUE);

        if ($this->Users_model->login($username, md5($password))) {
            $result = 'true';
        } else {
            $result = 'false';
        }

        $arr = array("result" => $result);
        echo json_encode($arr);
    }

    function logout()
    {
        $this->cart->destroy();
        
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

        
        $this->session->unset_userdata('mode');
        $this->session->unset_userdata('template');
        $this->session->unset_userdata('edit_template');
        
        redirect('Welcome', 'refresh');
    }
}
