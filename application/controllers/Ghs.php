<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ghs extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
        $this->load->library('cart');
        $this->load->library('Custom_menu');
        $this->load->helper('file');
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Ghs_model', 'Ghs_model');
    }
    
    public function index(){
        $this->load->helper('url');
        
        $data['page_name'] = 'Ghs Management';
        
        $data['content'] = $this->load->view('ghs/list', $data, TRUE);
        //$this->load->view('themes/blank',$data);
        $this->load->view('themes/custom_theme',$data);
        $this->load->view('themes/footer');
    }
 
    public function ajax_list()
    {
        $list = $this->Ghs_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $ghs) {
            
            $no++;
            $row = array();
            $row[] = $ghs->ghs_id;
            $row[] = $ghs->desc;
            $row[] = '<img src="'.base_url().'assets/uploads/images/'.$ghs->image_name.'" width="75" height="75" />';
 
            //add html for action
            $button = '<a href=\'#\' onclick="edit_ghs(\''.$ghs->ghs_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_edit.png\'></a>'.'&nbsp&nbsp&nbsp'.
                      '<a href=\'#\' onclick="delete_ghs(\''.$ghs->ghs_id.'\''.',\''.$ghs->desc.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_delete.png\'></a>';
            
            $row[] = $button;

            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Ghs_model->count_all(),
                        "recordsFiltered" => $this->Ghs_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function ajax_edit($id)
    {
        $data = $this->Ghs_model->get_by_id($id);
        echo json_encode($data);
    }
 
    public function ajax_add()
    {
        if($this->input->post('ghsName') == null || $this->input->post('ghsName') == ''){
            
            $error_message = 'Menu name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('pageNumber') == null || $this->input->post('pageNumber') == ''){
            
            $error_message = 'Page number can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    'title' => $this->input->post('ghsName'),
                    'url' => $this->input->post('ghsUrl'),
                    'parent_id' => $this->input->post('parentId'),
                    'link_type' => 'page',
                    'page_id' => $this->input->post('pageNumber'),
                    'module_name' => null,
                    'uri' => null,
                    'dyn_group_id' => 1,
                    'position' => 0,
                    'target' => null,
                    'is_parent' => 0,
                    'show_ghs' => 1
                );
            
            $this->Ghs_model->save($data);
            echo json_encode(array("status" => TRUE));
//            echo json_encode(array("status" => FALSE, 'error' => $this->input->post('ghsName')));
        }
    }
 
    public function ajax_update()
    {
        if($this->input->post('ghsName') == null || $this->input->post('ghsName') == ''){
            
            $error_message = 'Menu name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('pageNumber') == null || $this->input->post('pageNumber') == ''){
            
            $error_message = 'Page number can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $data = array(
                    'title' => $this->input->post('ghsName'),
                    'url' => $this->input->post('ghsUrl'),
                    'parent_id' => $this->input->post('parentId'),
                    'page_id' => $this->input->post('pageNumber')
                );
            
            $this->Ghs_model->update(array('id' => $this->input->post('ghsId')), $data);
            
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_delete($id)
    {
        $ghs_result = $this->Ghs_model->getGhsById($id)->result();
        if($ghs_result[0]->url != null && $ghs_result[0]->url != ''){
            unlink($ghs_result[0]->url);
        }
        
        $this->Ghs_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
    
    function getghsList(){
        echo json_encode($this->Ghs_model->getMenuList());
    }
    
    public function save_ghs(){
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
                        'desc' => $this->input->post('desc'),
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
                        'desc' => $this->input->post('desc'),
                        'created_by' => $this->session->userdata("username"),
                        'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                        'last_update_by' => $this->session->userdata("username"),
                        'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                    );
            }
            
            $this->Ghs_model->save($data);
        }else{
            $ghs_result = $this->Ghs_model->getGhsById($this->input->post('ghsId'))->result();
            
            $image = -2;
            if ($this->upload->do_upload('berkas')){
                
                if($ghs_result[0]->url != null && $ghs_result[0]->url != ''){
                    unlink($ghs_result[0]->url);
                }
                
                $success = array('upload_data' => $this->upload->data());
                $image = 2;
                $data['image_url'] = $success['upload_data']['full_path'];
                $data['image_name'] = $success['upload_data']['file_name'];

                $data = array(
                        'desc' => $this->input->post('desc'),
                        'url' => base_url().'assets/upload/images/'.$success['upload_data']['file_name'],
                        'image_name' => $success['upload_data']['file_name'],
                        'last_update_by' => $this->session->userdata("username"),
                        'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                    );
            }else{
                $image = -3;
                $data = array(
                        'desc' => $this->input->post('desc'),
                        'last_update_by' => $this->session->userdata("username"),
                        'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                    );
            }
            
            $this->Ghs_model->update(array('ghs_id' => $this->input->post('ghsId')), $data);
        }
        
        
//        $data['data'] = $this->input->post('ghsName');
//        $data['data'] = $image;
//        $this->load->view('Ghs/test',$data);
        redirect("Ghs");
    }
    
    public function ajax_get_ghs(){
        $result = $this->Ghs_model->get_ghs_all();

        echo json_encode($result);
    }
    
}
?>
