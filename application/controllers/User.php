<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
        $this->load->helper('flexigrid');
        $this->load->model('User_model', 'User_model');
        $this->load->model('Role_model', 'Role_model');
        $this->load->model('Users_model', 'Users_model');
    }
    
    public function index(){
        $check = $this->Users_model->getRoleMenu('index.php/User', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $this->load->helper('url');

            $data['page_name'] = 'User Management';
            $data['role_list'] = $this->Role_model->getRoleList();
            $data['list'] = null;

            $data['content'] = $this->load->view('user/list', $data, TRUE);
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
        $list = $this->User_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $user) {
            $role_name = $this->Role_model->getRoleName($user->role);
            
            $no++;
            $row = array();
            $row[] = $user->username;
            $row[] = $user->fullname;
            $row[] = $user->email;
            $row[] = '['.$user->role.'] '.$role_name;
 
            //add html for action
            $button = '<a href=\'#\' onclick="edit_user(\''.$user->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_user(\''.$user->id.'\''.',\''.$user->username.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\'></a>'.'&nbsp&nbsp&nbsp';
                      // '<a href=\'#\' onclick="view_country(\''.$user->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/view-details.png\' title=\'View Country\'></a>'.'&nbsp&nbsp&nbsp'.
                      //'<a href=\'#\' onclick="reset_user(\''.$user->id.'\''.',\''.$user->username.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/reset.png\'></a>';
            
            $row[] = $button;

            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->User_model->count_all(),
                        "recordsFiltered" => $this->User_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $data = $this->User_model->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        if($this->input->post('userName') == null || $this->input->post('userName') == ''){
            
            $error_message = 'User name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('fullName') == null || $this->input->post('fullName') == ''){
            
            $error_message = 'Full name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            
            if($this->input->post('role') == 1){
                $user_group = 'ADMIN';
            }else{
                $user_group = 'USER';
            }
            
            $data = array(
                    'fullname' => $this->input->post('fullName'),
                    'email' => $this->input->post('email'),
                    'username' => $this->input->post('userName'),
                    'password' => md5($this->input->post('userName')),
                    'role' => $this->input->post('role'),
                    'user_group' => $user_group,
                    'created_by' => $this->session->userdata("username"),
                    'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->User_model->save($data);
            echo json_encode(array("status" => TRUE));
//            echo json_encode(array("status" => FALSE, 'error' => $this->input->post('parentId')));
        }
    }
 
    public function ajax_update()
    {
        if($this->input->post('userName') == null || $this->input->post('userName') == ''){
            
            $error_message = 'User name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('fullName') == null || $this->input->post('fullName') == ''){
            
            $error_message = 'Full name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            
            if($this->input->post('role') == 1){
                $user_group = 'ADMIN';
            }else{
                $user_group = 'USER';
            }
            
            $data = array(
                    'fullname' => $this->input->post('fullName'),
                    'email' => $this->input->post('email'),
                    'username' => $this->input->post('userName'),
                    'role' => $this->input->post('role'),
                    'user_group' => $user_group,
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->User_model->update(array('id' => $this->input->post('userId')), $data);

            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_delete($id)
    {
        $this->User_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
    
    public function ajax_reset($id)
    {
        $user = $this->User_model->getUserById($id)->result();
        
        $data = array(
                    'password' => md5($user[0]->username),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
        $this->User_model->update(array('id' => $id), $data);

        echo json_encode(array("status" => TRUE));
    }
    
    function getuserList(){
        echo json_encode($this->User_model->getUserList());
    }
    
    function change_password(){
        $data = null;
        $data['content'] = $this->load->view('user/change_password', $data, TRUE);
        $this->load->view($this->session->userdata("template"), $data);
    }
    
    function ajax_change_password(){
      $id = $this->session->userdata("user_id");
      $old_password = md5($this->input->post('oldPassword'));
      $password = md5($this->input->post('newPassword'));
      
      $hasil = $this->User_model->getUserById($id)->result();
      
      
      if($hasil[0]->password == $old_password){
//          echo json_encode(array('result' => 'false', 'error' => $this->input->post('oldPassword')));
          
          $data = array(
            'password' => $password,
            'last_update_by' => $this->session->userdata("username"),
            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
            );

            $result = $this->User_model->updateUsers(array('id' => $id), $data);

            if ($result > 0) {
              echo json_encode(array('status' => TRUE));
            } else {
              echo json_encode(array('status' => TRUE));
            }
      }else{
          echo json_encode(array('status' => FALSE, 'error' => 'Old password is wrong!'));
      }
    }
}
?>
