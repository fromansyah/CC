<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branch extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
        $this->load->model('Branch_model', 'Branch_model');
        $this->load->model('Company_model', 'Company_model');
        $this->load->model('Users_model', 'Users_model');
    }
    
    public function index(){
        $this->lists();
    }
    
    function lists($company_id) {
//        $check = $this->Users_model->getRoleMenu('Branch', $this->session->userdata("role"));
        
        if($this->session->userdata("role") == 1 || $this->session->userdata("role") == 2){
            $this->load->helper('url');

            $result = $this->Company_model->getCompanyById($company_id)->result();

            $data['company_id'] = $company_id;
            $data['company_name'] = $result[0]->company_name;

            $data['page_title'] = 'Branch Management';
            $data['content'] = $this->load->view('branch/list', $data, TRUE);
            $this->load->view('themes/blank',$data);
            $this->load->view('themes/footer');
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
 
    public function ajax_list($company_id='')
    {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $list = $this->Branch_model->get_datatables($company_id);
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $branch) {
    
                $button = '<a href=\'#\' onclick="edit_branch(\''.$branch->branch_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\'></a>'.'&nbsp&nbsp&nbsp'.
                          '<a href=\'#\' onclick="delete_branch(\''.$branch->branch_id.'\''.',\''.$branch->branch_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\'></a>';
                
                $no++;
                $row = array();
                $row[] = $branch->branch_id;
                $row[] = $branch->branch_name;
                $row[] = $branch->address;
                $row[] = $branch->note;
     
                //add html for action
                $row[] = $button;
     
                $data[] = $row;
            }
     
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->Branch_model->count_all($company_id),
                            "recordsFiltered" => $this->Branch_model->count_filtered($company_id),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
 
    public function ajax_add()
    {
        $data = array(
                'company_id' => $this->input->post('companyId'),
                'branch_name' => $this->input->post('branch'),
                'address' => $this->input->post('address'),
                'note' => $this->input->post('note'),
                'created_by' => $this->session->userdata("username"),
                'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                'last_update_by' => $this->session->userdata("username"),
                'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
            );
        
        $this->Branch_model->save($data);
        echo json_encode(array("status" => TRUE));
    }
    
    public function ajax_update()
    {
        $branch_id = $this->input->post('id');
        
        $data = array(
                'branch_name' => $this->input->post('branch'),
                'address' => $this->input->post('address'),
                'note' => $this->input->post('note'),
                'last_update_by' => $this->session->userdata("username"),
                'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
            );
        
        $this->Branch_model->update(array('branch_id' => $branch_id), $data);
        echo json_encode(array("status" => TRUE));
    }
    
    public function ajax_edit($branch_id)
    {
        $data = $this->Branch_model->get_by_id($branch_id);
        echo json_encode($data);
    }
    
    public function ajax_delete($branch_id)
    {
        $this->Branch_model->delete_by_id($branch_id);
        echo json_encode(array("status" => TRUE));
    }


    function delete($company_id, $menu_item_id)
    {
        $this->Branch_model->deleteCompanyMenu($company_id, $menu_item_id);
        redirect("index.php/Branch/lists/$company_id", 'refresh');
    }
    
    public function getbranchList($company){
        echo json_encode($this->Branch_model->getBranchList($company));
    }
}
?>
