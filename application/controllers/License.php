<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class License extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
//        $this->load->library('Dynamic_menu');
        $this->load->library('encrypt');
        $this->load->helper('file');
        $this->load->helper('security');
        $this->load->helper(array('form', 'url'));
        $this->load->model('License_model', 'License_model');
        $this->load->model('Employee_model', 'Employee_model');
        $this->load->model('Division_model', 'Division_model');
        $this->load->model('Company_model', 'Company_model');
        $this->load->model('Branch_model', 'Branch_model');
        $this->load->model('Master_data_model', 'Master_data_model');
        $this->load->model('Users_model', 'Users_model');
    }
    
    public function index($branch=''){
        $check = $this->Users_model->getRoleMenu('index.php/License', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $this->load->helper('url');
            $dec_branch = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $branch));
            $branch_result = $this->Branch_model->getBranchById($dec_branch)->result();
            
            $data['branchId'] = 0;
            $data['branchName'] = '';
            $data['enc_branch'] = $branch;
            if(count($branch_result) != 0){
                $data['branchId'] = $branch_result[0]->branch_id;
                $data['branchName'] = $branch_result[0]->branch_name;
            }
            
            $data['companyId'] = 0;
            $data['companyName'] = '';
            
            if(count($branch_result) > 0){
                $comp_result = $this->Company_model->getCompanyById($branch_result[0]->company_id)->result();
                if(count($comp_result) != 0){
                    $data['companyId'] = $comp_result[0]->company_id;
                    $data['companyName'] = $comp_result[0]->company_name;
                }
            }
            
            
            $data['page_name'] = 'License Management';
            $data['employee_list'] = $this->Employee_model->getAllEmployeeList();
            $data['branch_list'] = $this->Division_model->getAllDivisionList();
            $data['company_list'] = $this->Company_model->getAllCompanyList();
            $data['ib_list'] = $this->License_model->getOtherPartyList();
            
            $company_list = $this->Company_model->get_company_all();
            $data['company'] = $company_list;
            
            $data['status_list'] = $this->Master_data_model->getMasterDataList('LICENSE_STATUS');
            
            
            $branch_company = array();
            foreach($company_list as $row){
                $branch_list = $this->Branch_model->get_Branch_by_company($row['company_id']);
                
//                foreach($branch_list as $branch){
                    $branch_company[$row['company_id']] = $branch_list;
//                }
                
            }
            
            $data['branch'] = $branch_company;
            
            $data['content'] = $this->load->view('license/list', $data, TRUE);
            $this->load->view('themes/clearfix_license_theme',$data);
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
        
    }
 
    public function ajax_list()
    {
        $list = $this->License_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $license) {            
            $subscribe = 'No';
            if($license->subscribe == 1){
                $subscribe = 'Yes';
            }

            $no++;
            $row = array();
            $row[] = $license->license_no;
            $row[] = $license->other_party;
            $row[] = $license->description;
            $row[] = $license->exp_date;
            $row[] = $license->emp_name;
 
            //add html for action
	    $button = '';
            if($this->session->userdata("role") == 1 || $this->session->userdata("role") == 2){
		    $button = '<a href=\'#\' onclick="edit_emp(\''.$license->license_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit License\'></a>'.'&nbsp&nbsp&nbsp'.
			      '<a href=\'#\' onclick="delete_emp(\''.$license->license_id.'\''.',\''.$license->license_no.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete License\'></a>';
	    }
            $row[] = $button;

            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->License_model->count_all(),
                        "recordsFiltered" => $this->License_model->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    public function ajax_list_null()
    {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $data = array();
            $no = 0;
            $row = array();
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '100';
    
            $data[] = $row;
    
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => 0,
                            "recordsFiltered" => 0,
                            "data" => $data,
                    );
            echo json_encode($output);
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
 
    public function ajax_branch_list($branch)
    {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $branch = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $branch));
            $list = $this->License_model->get_datatables($branch);
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $license) {      
                
                $enc_id = str_replace(array('=','+','/'), array('-','_','~'), $this->encrypt->encode($license->license_id));      
                //add html for action
    	    $button = '<a href=\'#\' onclick="download(\''.$license->license_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/059.png\' title=\'View Document\'></a>';
                
                if($this->session->userdata("role") == 1 || $this->session->userdata("role") == 2){
    //		    $button = '<a href=\'#\' onclick="edit_license(\''.$license->license_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit License\'></a>'.'&nbsp&nbsp&nbsp'.
    //                              '<a href=\'#\' onclick="upload(\''.$license->license_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/pdf_icon.png\' title=\'Upload PDF\'></a>'.'&nbsp&nbsp&nbsp'.
    //                              '<a href=\'#\' onclick="view_doc(\''.$license->license_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/059.png\' title=\'View Document\'></a>'.'&nbsp&nbsp&nbsp'.
    //			      '<a href=\'#\' onclick="delete_license(\''.$license->license_id.'\''.',\''.$license->license_no.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete License\'></a>';
    	    
                    $button = '<div class="dropdown dropleft">
                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown"> Menu</button>
                            <ul class="dropdown-menu">
                              <li><a href=\'#\' onclick="edit_license(\''.$license->license_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit License\'>&nbsp;Edit</a></li>
                              <li><a href=\'#\' onclick="upload(\''.$license->license_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/pdf_icon.png\' title=\'Upload PDF\'>&nbsp;Upload PDF</a></li>
                              <li><a href=\'#\' onclick="view_doc(\''.$license->license_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/059.png\' title=\'View Document\'>&nbsp;View Document</a></li>
                              <li><a href=\'#\' onclick="on_progress(\''.$license->license_id.'\''.',\''.$license->license_no.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/renew_prog_icon.png\' title=\'On Renewal Process\'>&nbsp;On Renewal Process</a></li>
                              <li><a href=\'#\' onclick="renewed(\''.$license->license_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/renewed_icon.png\' title=\'Renewed\'>&nbsp;Renewed</a></li>
                              <li><a href=\'#\' onclick="expired(\''.$license->license_id.'\''.',\''.$license->license_no.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/expired_icon.png\' title=\'Expired\'>&nbsp;Expired</a></li>
                              <li><a href=\'#\' onclick="terminated(\''.$license->license_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/terminated_icon.png\' title=\'Terminated\'>&nbsp;Terminated</a></li>
                              <li><a href=\'#\' onclick="delete_license(\''.$license->license_id.'\''.',\''.$license->license_no.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete License\'>&nbsp;Delete License</a></li>
                            </ul>
                          </div>';
                    
                    if($license->status == 1){
                        $button = '<div class="dropdown dropleft">
                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown"> Menu</button>
                            <ul class="dropdown-menu">
                              <li><a href=\'#\' onclick="edit_license(\''.$license->license_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit License\'>&nbsp;Edit</a></li>
                              <li><a href=\'#\' onclick="upload(\''.$license->license_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/pdf_icon.png\' title=\'Upload PDF\'>&nbsp;Upload PDF</a></li>
                              <li><a href=\'#\' onclick="view_doc(\''.$license->license_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/059.png\' title=\'View Document\'>&nbsp;View Document</a></li>
                              <li><a href=\'#\' onclick="renewed(\''.$license->license_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/renewed_icon.png\' title=\'Renewed\'>&nbsp;Renewed</a></li>
                              <li><a href=\'#\' onclick="expired(\''.$license->license_id.'\''.',\''.$license->license_no.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/expired_icon.png\' title=\'Expired\'>&nbsp;Expired</a></li>
                              <li><a href=\'#\' onclick="terminated(\''.$license->license_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/terminated_icon.png\' title=\'Terminated\'>&nbsp;Terminated</a></li>
                              <li><a href=\'#\' onclick="delete_license(\''.$license->license_id.'\''.',\''.$license->license_no.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete License\'>&nbsp;Delete License</a></li>
                            </ul>
                          </div>';
                    }elseif($license->status == 2 || $license->status == 3 || $license->status == 4){
                        $button = '<div class="dropdown dropleft">
                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown"> Menu</button>
                            <ul class="dropdown-menu">
                              <li><a href=\'#\' onclick="edit_license(\''.$license->license_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit License\'>&nbsp;Edit</a></li>
                              <li><a href=\'#\' onclick="upload(\''.$license->license_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/pdf_icon.png\' title=\'Upload PDF\'>&nbsp;Upload PDF</a></li>
                              <li><a href=\'#\' onclick="view_doc(\''.$license->license_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/059.png\' title=\'View Document\'>&nbsp;View Document</a></li>
                              <li><a href=\'#\' onclick="delete_license(\''.$license->license_id.'\''.',\''.$license->license_no.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete License\'>&nbsp;Delete License</a></li>
                            </ul>
                          </div>';
                    }
                }
                
                $no++;
                $row = array();
                $row[] = $button;
                $row[] = $license->license_no;
                $row[] = $license->license_name;
                $row[] = $license->description;
                $row[] = $license->issued_by;
                $row[] = $license->exp_date;
                $row[] = $license->doc_name;
                $row[] = $license->name;
     
                
                $row[] = $license->due_date;
    
                $data[] = $row;
            }
     
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->License_model->count_all($branch),
                            "recordsFiltered" => $this->License_model->count_filtered($branch),
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
 
    public function ajax_edit($id)
    {
        $id = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $id));
        $data = $this->License_model->get_by_id($id);
        echo json_encode($data);
    }
    
    function ajax_save(){
//        $data = array();
//
//        if(isset($_GET['files']))
//        {  
//            $error = false;
//            $files = array();
//
//            $uploaddir = './assets/uploads/pdf';
//            foreach($_FILES as $file)
//            {
//                if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
//                {
//                    $files[] = $uploaddir .$file['name'];
//                }
//                else
//                {
//                    $error = true;
//                }
//            }
//            $data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files);
//        }
//        else
//        {
//            $data = array('success' => 'Form was submitted', 'formData' => $_POST);
//        }

        echo json_encode(array("status" => FALSE, "error" => $this->input->post('licenseNo')));
    }
 
    public function ajax_add()
    {
        
        if($this->input->post('licenseNo') == null || $this->input->post('licenseNo') == ''){
            
            $error_message = 'License number can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('licenseName') == null || $this->input->post('licenseName') == ''){
            
            $error_message = 'License name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('description') == null || $this->input->post('description') == ''){
            
            $error_message = 'Description date can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('ib_check') == null && ($this->input->post('issuedBy_new') == null || $this->input->post('issuedBy_new') == '')){

            $error_message = 'Issued by can not empty. 1';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
 
        }elseif($this->input->post('ib_check') != null && ($this->input->post('issuedBy') == null || $this->input->post('issuedBy') == '0')){
                
            $error_message = 'Issued by  can not empty. 2';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('issuedDate') == null || $this->input->post('issuedDate') == ''){
            
            $error_message = 'Issued date can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('expDate') == null || $this->input->post('expDate') == ''){
            
            $error_message = 'Expired date can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            
            $issued_by = '';
            if($this->input->post('ib_check') == null){
                $issued_by = $this->input->post('issuedBy_new');
            }else{
                $issued_by = $this->input->post('issuedBy');
            }
            
            $data = array(
                    'company_id' => $this->input->post('company'),
                    'branch_id' => $this->input->post('branch'),
                    'license_no' => $this->security->xss_clean($this->input->post('licenseNo')),
                    'license_name' => $this->security->xss_clean($this->input->post('licenseName')),
                    'description' => $this->security->xss_clean($this->input->post('description')),
                    'issued_by' => $this->security->xss_clean($issued_by),
                    'issued_date' => $this->security->xss_clean($this->input->post('issuedDate')),
                    'exp_date' => $this->security->xss_clean($this->input->post('expDate')),
                    'remarks' => $this->security->xss_clean($this->input->post('remark')),
                    'status' => 0,
                    'created_by' => $this->session->userdata("username"),
                    'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->License_model->save($data);
            echo json_encode(array("status" => TRUE));
//            echo json_encode(array("status" => FALSE, 'error' => "OK!"));
        }
    }
    
    public function upload_license(){
        ini_set('max_execution_time', '0');
        
        if($this->input->post('upload_licenseId') != NULL){
            $config['upload_path']          = './assets/uploads/pdf';
            $config['allowed_types']        = 'pdf';
            $config['max_size']             = '2048000000000';
            // $config['max_width']            = 1024;
            // $config['max_height']           = 768;
    
            $this->load->library('upload', $config);
            
            $branch = $this->input->post('upload_branch');
            $enc_branch = str_replace(array('=','+','/'), array('-','_','~'), $this->encrypt->encode($branch));
            
            $data = '';
            
            if ($this->upload->do_upload('berkas')){
                $license = $this->License_model->getLicenseById($this->input->post('upload_licenseId'))->result();
                if($license[0]->doc_name != null){
                    unlink($license[0]->doc_url);
                }
                
                $success = array('upload_data' => $this->upload->data());
    
                $data = array(
                    'doc_url' => $success['upload_data']['full_path'],
                    'doc_name' => $success['upload_data']['file_name'],
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
             
                $this->License_model->update(array('license_id' => $this->input->post('upload_licenseId')), $data);
             }
             
             redirect("index.php/License/index/$enc_branch");
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
 
    public function ajax_update()
    {
        if($this->input->post('licenseNo') == null || $this->input->post('licenseNo') == ''){
            
            $error_message = 'License number can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('licenseName') == null || $this->input->post('licenseName') == ''){
            
            $error_message = 'License name can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('description') == null || $this->input->post('description') == ''){
            
            $error_message = 'Description date can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('ib_check') == null && ($this->input->post('issuedBy_new') == null || $this->input->post('issuedBy_new') == '')){

            $error_message = 'Issued by can not empty. 1';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
 
        }elseif($this->input->post('ib_check') != null && ($this->input->post('issuedBy') == null || $this->input->post('issuedBy') == '0')){
                
            $error_message = 'Issued by  can not empty. 2';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('issuedDate') == null || $this->input->post('issuedDate') == ''){
            
            $error_message = 'Issued date can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('expDate') == null || $this->input->post('expDate') == ''){
            
            $error_message = 'Expired date can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            
            $issued_by = '';
            if($this->input->post('ib_check') == null){
                $issued_by = $this->input->post('issuedBy_new');
            }else{
                $issued_by = $this->input->post('issuedBy');
            }
            
            $data = array(
                    'company_id' => $this->input->post('company'),
                    'branch_id' => $this->input->post('branch'),
                    'license_no' => $this->security->xss_clean($this->input->post('licenseNo')),
                    'license_name' => $this->security->xss_clean($this->input->post('licenseName')),
                    'description' => $this->security->xss_clean($this->input->post('description')),
                    'issued_by' => $this->security->xss_clean($issued_by),
                    'issued_date' => $this->security->xss_clean($this->input->post('issuedDate')),
                    'exp_date' => $this->security->xss_clean($this->input->post('expDate')),
                    'remarks' => $this->security->xss_clean($this->input->post('remark')),
                    'status' => $this->input->post('status'),
                    'new_license_no' => $this->security->xss_clean($this->input->post('newLicenseNo')),
                    'note' => $this->security->xss_clean($this->input->post('note')),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->License_model->update(array('license_id' => $this->input->post('licenseId')), $data);
            
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_delete($id)
    {
        $id = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $id));
        
        $license = $this->License_model->getLicenseById($id)->result();
        
        $this->License_model->delete_by_id($id);
        
        if($license[0]->doc_name != null){
            unlink($license[0]->doc_url);
        }
        
        echo json_encode(array("status" => TRUE));
    }
    
    public function upload_emp(){
        $data['page_title'] = 'Upload License';
        $data['content'] = $this->load->view('license/upload_emp', $data, TRUE);
        $this->load->view('form_template', $data);
    }
    
    function getmenuList(){
        echo json_encode($this->License_model->getLicenseList());
    }
    
    public function download_template(){
        $data['title'] = 'Template Upload License';
        $data['content'] = $this->load->view('license/template_emp', $data, TRUE);
        $this->load->view('blank', $data);
    }
    
    public function new_upload_emp(){
        $check = $this->Users_model->getRoleMenu('index.php/License');
        
        if(count($check) > 0){
            $data['page_title'] = 'Upload License';
            $data['content'] = $this->load->view('license/new_upload_emp', $data, TRUE);
            $this->load->view('form_template', $data);
        }else{
            $data['title'] = 'Error Page';
        	$data["content"] = $this->load->view('error',$data,true);
            $this->load->view("blank", $data);
        }
        
    }
    
    public function ajax_upload()
    {
        $ViewData=read_file("./csv/log_license.txt");
        write_file("./csv/log_license.txt", $ViewData."\n\n".date('Y-m-d H:i:s', strtotime('now'))." by ".$this->session->userdata("username"));
 
        $data = $this->input->post('data');
        
        $row = 1;
        $num_success = 0;
        $num_fail = 0;
        $success = array();
        $fail = array(); 
        $success_emp = array();
        $fail_emp = array();
        $fail_note = array();
        foreach($data as $key=>$field){
            
            if($key > 0){
                if(count($field) == 11){
                    $license_id = trim($field[0]);
                    $license_name = $field[1];
                    $email = $field[2];
                    $position = $field[3];
                    $note = $field[4];
                    $subscribe = $field[5];
                    $cc1 = $field[6];
                    $cc2 = $field[7];
                    $cc3 = $field[8];
                    $cc4 = $field[9];
                    $cc5 = $field[10];

                    $data_license = array(
                            'license_id' => $license_id,
                            'license_name' => $license_name,
                            'email' => $email,
                            'position' => $position,
                            'note' => $note,
                            'subscribe' => $subscribe,
                            'cc_1' => $cc1,
                            'cc_2' => $cc2,
                            'cc_3' => $cc3,
                            'cc_4' => $cc4,
                            'cc_5' => $cc5,
                            'created_by' => $this->session->userdata("username"),
                            'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                            'last_update_by' => $this->session->userdata("username"),
                            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                    );

                    $check = $this->License_model->getLicenseById($license_id)->result();

                    if(count($check) == 0){
                        $this->License_model->save($data_license);
                            $success[$num_success] = $row;
                            $success_emp[$num_success] = $license_id;
                            $num_success++;
                    }else{
                        $fail[$num_fail] = $row;
                        $fail_emp[$num_fail] = $license_id;
                        $fail_note[$num_fail] = 'License ID already exist.';
                        $num_fail++;

                        $ViewData=read_file("./csv/log_license.txt");
                        $text = $ViewData."\n Line ".$row.'. '.$license_id.' License ID already exist.';
                        write_file("./csv/log_license.txt", $text);
                    }
                }
                $row++;
            }
        }
        
        if($num_fail == 0){
            $ViewData=read_file("./csv/log_license.txt");
            write_file("./csv/log_license.txt", $ViewData."\n Upload success.\n".$num_success." row(s) has been uploaded.\n");
        }else{
            $ViewData=read_file("./csv/log_license.txt");
            write_file("./csv/log_license.txt", $ViewData."\n".$num_success." row(s) has been uploaded.\n".$num_fail.' row(s) fail to upload.');
        }
        
        $text = $num_success." row(s) has been uploaded.\n".$num_fail." row(s) fail to upload.\nSee log file for detail.";
        
        echo json_encode(array("status" => TRUE, "text" => $text));
        
    }
    
    function view_license($id=null){
        $id = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $id));
        
         $this->load->helper('download');

         $license = $this->License_model->getLicenseById($id)->result();

         if($license[0]->doc_name == null || $license[0]->doc_name == ''){
             $name   = 'not_found.pdf';
         }else{
             $name   = $license[0]->doc_name;
         }

         $file   = './assets/uploads/pdf/'.$name;

         $this->output
            ->set_content_type('application/pdf')
            ->set_output(file_get_contents($file));
     }
     
     function ajax_on_progress($id){
         $id = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $id));
         
         $data = array(
             'status' => 1,
             'last_update_by' => $this->session->userdata("username"),
             'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
         );
         
         $this->License_model->update(array('license_id' => $id), $data);
         echo json_encode(array("status" => TRUE));
     }
     
     function ajax_renew(){
         $data = array(
             'status' => 2,
             'new_license_no' => $this->input->post('renew_newLicenseNo'),
             'last_update_by' => $this->session->userdata("username"),
             'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
         );
         
         $this->License_model->update(array('license_id' => $this->input->post('renew_licenseId')), $data);
         echo json_encode(array("status" => TRUE));
     }
     
     function ajax_expire($id){
         $id = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $id));
         
         $data = array(
             'status' => 3,
             'last_update_by' => $this->session->userdata("username"),
             'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
         );
         
         $this->License_model->update(array('license_id' => $id), $data);
         echo json_encode(array("status" => TRUE));
     }
     
     function ajax_terminate(){
         $data = array(
             'status' => 4,
             'note' => $this->input->post('terminate_note'),
             'last_update_by' => $this->session->userdata("username"),
             'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
         );
         
         $this->License_model->update(array('license_id' => $this->input->post('terminate_licenseId')), $data);
         echo json_encode(array("status" => TRUE));
     }
}
?>
