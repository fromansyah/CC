<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contract extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
//        $this->load->library('Dynamic_menu');
        $this->load->library('encrypt');
        $this->load->helper('file');
        $this->load->helper('security');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Contract_model', 'Contract_model');
        $this->load->model('Employee_model', 'Employee_model');
        $this->load->model('Division_model', 'Division_model');
        $this->load->model('Company_model', 'Company_model');
        $this->load->model('Master_data_model', 'Master_data_model');
        $this->load->model('Users_model', 'Users_model');
    }
    
    public function index($division=''){
        $check = $this->Users_model->getRoleMenu('index.php/Contract', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $this->load->helper('url');
            $dec_div = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $division));
            $div_result = $this->Division_model->getDivisionById($dec_div)->result();
            
            $data['divId'] = 0;
            $data['divName'] = '';
            $data['enc_div'] = $division;
            if(count($div_result) != 0){
                $data['divId'] = $div_result[0]->division_id;
                $data['divName'] = $div_result[0]->division_name;
            }
            
        
            $data['page_name'] = 'Contract Management';
            $data['employee_list'] = $this->Employee_model->getAllEmployeeList();
            $data['division_list'] = $this->Division_model->getAllDivisionList();
            $data['op_list'] = $this->Contract_model->getOtherPartyList();
            
            $division_list = $this->Division_model->get_division_all();
            $data['division'] = $division_list;
            
            $company_list = $this->Company_model->get_company_all();
            $data['company'] = $company_list;
            $branch_company = array();
            foreach($company_list as $row){
                $branch_list = $this->Division_model->get_Division_by_company($row['company_id']);
                
//                foreach($branch_list as $branch){
                    $branch_company[$row['company_id']] = $branch_list;
//                }
                
            }
//            $branch_list = $this->Division_model->get_Division_by_company(1);
            $data['branch'] = $branch_company;
            
            $data['company_list'] = $this->Company_model->getAllCompanyList();
            
            $data['status_list'] = $this->Master_data_model->getMasterDataList('CONTRACT_STATUS');
            
            $data['content'] = $this->load->view('contract/list', $data, TRUE);
            $this->load->view('themes/clearfix_contract_theme',$data);
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
        
    }
 
    public function ajax_list()
    {
        $list = $this->Contract_model->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $contract) {            
            $subscribe = 'No';
            if($contract->subscribe == 1){
                $subscribe = 'Yes';
            }

            $no++;
            $row = array();
            $row[] = '<div style="background-color:lightblue;">&nbsp&nbsp&nbsp'.$contract->contract_no.'</div>';
            $row[] = $contract->other_party;
            $row[] = $contract->description;
            $row[] = $contract->exp_date;
            $row[] = $contract->check_name;
            $row[] = $contract->check_name;
 
            //add html for action
	    $button = '';
            if($this->session->userdata("role") == 1 || $this->session->userdata("role") == 2){
		    $button = '<a href=\'#\' onclick="edit_emp(\''.$contract->contract_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit Contract\'></a>'.'&nbsp&nbsp&nbsp'.
			      '<a href=\'#\' onclick="delete_emp(\''.$contract->contract_id.'\''.',\''.$contract->contract_no.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete Contract\'></a>';
	    }
            $row[] = $button;
            $row[] = $contract->due_date;

            $data[] = $row;
        }
 
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Contract_model->count_all(),
                        "recordsFiltered" => $this->Contract_model->count_filtered(),
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
            $row[] = '';
            $row[] = '';
            $row[] = '100';
    
            $data[] = $row;
    
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => 0,
                            "recordsFiltered" => 0,
                            "data" => $data
                    );
                    
            echo json_encode($output);
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
 
    public function ajax_division_list($division)
    {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
            $division = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $division));
            $list = $this->Contract_model->get_datatables($division);
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $contract) {     
                
                $enc_id = str_replace(array('=','+','/'), array('-','_','~'), $this->encrypt->encode($contract->contract_id));       
                //add html for action
    	    $button = '<a href=\'#\' onclick="summary(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/summ_icon.png\' title=\'Contract summary\'></a>'.'&nbsp&nbsp&nbsp'.
                          '<a href=\'#\' onclick="download(\''.$contract->contract_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/059.png\' title=\'View Document\'></a>';
                
                if($this->session->userdata("role") == 1 || $this->session->userdata("role") == 2){
    //		    $button = '<a href=\'#\' onclick="edit_contract(\''.$contract->contract_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit Contract\'></a>'.'&nbsp&nbsp&nbsp'.
    //                              '<a href=\'#\' onclick="upload(\''.$contract->contract_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/pdf_icon.png\' title=\'Upload PDF\'></a>'.'&nbsp&nbsp&nbsp'.
    //                              '<a href=\'#\' onclick="summary(\''.$contract->contract_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/summ_icon.png\' title=\'Contract summary\'></a>'.'&nbsp&nbsp&nbsp<br/>'.
    //                              '<a href=\'#\' onclick="view_doc(\''.$contract->contract_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/059.png\' title=\'View Document\'></a>'.'&nbsp&nbsp&nbsp'.
    //			      '<a href=\'#\' onclick="delete_contract(\''.$contract->contract_id.'\''.',\''.$contract->contract_no.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete Contract\'></a>'.'&nbsp&nbsp&nbsp'.
    //                              '<a href=\'#\' onclick="on_progress(\''.$contract->contract_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/renew_prog_icon.png\' title=\'On Renewal Process\'></a>'.'&nbsp&nbsp&nbsp<br/>'.
    //                              '<a href=\'#\' onclick="renewed(\''.$contract->contract_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/renewed_icon.png\' title=\'Renewed\'></a>'.'&nbsp&nbsp&nbsp'.
    //                              '<a href=\'#\' onclick="expired(\''.$contract->contract_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/expired_icon.png\' title=\'Expired\'></a>'.'&nbsp&nbsp&nbsp'.
    //                              '<a href=\'#\' onclick="terminated(\''.$contract->contract_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/terminated_icon.png\' title=\'Terminated\'></a>'.'&nbsp&nbsp&nbsp';
                    
                    $button = '<div class="btn-group dropleft">
                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown"> Menu</button>
                            <ul class="dropdown-menu">
                              <li><a href=\'#\' onclick="edit_contract(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit Contract\'>&nbsp;Edit</a></li>
                              <li><a href=\'#\' onclick="upload(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/pdf_icon.png\' title=\'Upload PDF\'>&nbsp;Upload PDF</a></li>
                              <li><a href=\'#\' onclick="summary(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/summ_icon.png\' title=\'Contract summary\'>&nbsp;Contract Summary</a></li>
                              <li><a href=\'#\' onclick="view_doc(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/059.png\' title=\'View Document\'>&nbsp;View Document</a></li>
                              <li><a href=\'#\' onclick="on_progress(\''.$contract->contract_id.'\''.',\''.$contract->contract_no.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/renew_prog_icon.png\' title=\'On Renewal Process\'>&nbsp;On Renewal Process</a></li>
                              <li><a href=\'#\' onclick="renewed(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/renewed_icon.png\' title=\'Renewed\'>&nbsp;Renewed</a></li>
                              <li><a href=\'#\' onclick="expired(\''.$contract->contract_id.'\''.',\''.$contract->contract_no.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/expired_icon.png\' title=\'Expired\'>&nbsp;Expired</a></li>
                              <li><a href=\'#\' onclick="terminated(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/terminated_icon.png\' title=\'Terminated\'>&nbsp;Terminated</a></li>
                              <li><a href=\'#\' onclick="delete_contract(\''.$contract->contract_id.'\''.',\''.$contract->contract_no.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete Contract\'>&nbsp;Delete Contract</a></li>
                            </ul>
                          </div>';
                    
                    if($contract->status == 1){
                        $button = '<div class="btn-group dropleft">
                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown"> Menu</button>
                            <ul class="dropdown-menu">
                              <li><a href=\'#\' onclick="edit_contract(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit Contract\'>&nbsp;Edit</a></li>
                              <li><a href=\'#\' onclick="upload(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/pdf_icon.png\' title=\'Upload PDF\'>&nbsp;Upload PDF</a></li>
                              <li><a href=\'#\' onclick="summary(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/summ_icon.png\' title=\'Contract summary\'>&nbsp;Contract Summary</a></li>
                              <li><a href=\'#\' onclick="view_doc(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/059.png\' title=\'View Document\'>&nbsp;View Document</a></li>
                              <li><a href=\'#\' onclick="renewed(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/renewed_icon.png\' title=\'Renewed\'>&nbsp;Renewed</a></li>
                              <li><a href=\'#\' onclick="expired(\''.$contract->contract_id.'\''.',\''.$contract->contract_no.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/expired_icon.png\' title=\'Expired\'>&nbsp;Expired</a></li>
                              <li><a href=\'#\' onclick="terminated(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/terminated_icon.png\' title=\'Terminated\'>&nbsp;Terminated</a></li>
                              <li><a href=\'#\' onclick="delete_contract(\''.$contract->contract_id.'\''.',\''.$contract->contract_no.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete Contract\'>&nbsp;Delete Contract</a></li>
                            </ul>
                          </div>';
                    }elseif($contract->status == 2 || $contract->status == 3 || $contract->status == 4){
                        $button = '<div class="btn-group dropleft">
                            <button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown"> Menu</button>
                            <ul class="dropdown-menu">
                              <li><a href=\'#\' onclick="edit_contract(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\' title=\'Edit Contract\'>&nbsp;Edit</a></li>
                              <li><a href=\'#\' onclick="upload(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/pdf_icon.png\' title=\'Upload PDF\'>&nbsp;Upload PDF</a></li>
                              <li><a href=\'#\' onclick="summary(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/summ_icon.png\' title=\'Contract summary\'>&nbsp;Contract Summary</a></li>
                              <li><a href=\'#\' onclick="view_doc(\''.$contract->contract_id.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/059.png\' title=\'View Document\'>&nbsp;View Document</a></li>
                              <li><a href=\'#\' onclick="delete_contract(\''.$contract->contract_id.'\''.',\''.$contract->contract_no.'\''.',\''.$enc_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\' title=\'Delete Contract\'>&nbsp;Delete Contract</a></li>
                            </ul>
                          </div>';
                    }
                }
                
                $no++;
                $row = array();
                $row[] = $button;
                $row[] = $contract->contract_no;
                $row[] = $contract->other_party;
                $row[] = $contract->description;
                $row[] = $contract->exp_date;
                $row[] = $contract->check_name;
                $row[] = $contract->req_name;
                $row[] = $contract->req_name_2;
                $row[] = $contract->doc_name;
                $row[] = $contract->name;
     
                
                $row[] = $contract->due_date;
    
                $data[] = $row;
            }
     
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->Contract_model->count_all($division),
                            "recordsFiltered" => $this->Contract_model->count_filtered($division),
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
        $data = $this->Contract_model->get_by_id($id);
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

        echo json_encode(array("status" => FALSE, "error" => $this->input->post('contractNo')));
    }
 
    public function ajax_add()
    {
        
        if($this->input->post('contractNo') == null || $this->input->post('contractNo') == ''){
            
            $error_message = 'Contract number can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('agrDate') == null || $this->input->post('agrDate') == ''){
            
            $error_message = 'Agreement date can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('effDate') == null || $this->input->post('effDate') == ''){
            
            $error_message = 'Effective date can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('expDate') == null || $this->input->post('expDate') == ''){
            
            $error_message = 'Expired date can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('fpRep') == '0'){
            
            $error_message = 'First party representative can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $this->input->post('fpRep')));
            
        }elseif($this->input->post('op_check') == null && ($this->input->post('otherParty_new') == null || $this->input->post('otherParty_new') == '')){

            $error_message = 'Other party can not empty. 1';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
 
        }elseif($this->input->post('op_check') != null && ($this->input->post('otherParty') == null || $this->input->post('otherParty') == '0')){
                
            $error_message = 'Other party can not empty. 2';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('opRep') == null || $this->input->post('opRep') == ''){
            
            $error_message = 'Other Party Authorized Representative can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('opTitle') == null || $this->input->post('opTitle') == ''){
            
            $error_message = 'Other Party Authorized Representative Title can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('opAddress') == null || $this->input->post('opAddress') == ''){
            
            $error_message = 'Other Party Address can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('description') == null || $this->input->post('description') == ''){
            
            $error_message = 'Description can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('prodLoc') == null || $this->input->post('prodLoc') == ''){
            
            $error_message = 'Production Location can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('tog') == null || $this->input->post('tog') == ''){
            
            $error_message = 'Type of Goods can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('qty') == null || $this->input->post('qty') == ''){
            
            $error_message = 'Quantity can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('spec') == null || $this->input->post('spec') == ''){
            
            $error_message = 'Specifications can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('kpi') == null || $this->input->post('kpi') == ''){
            
            $error_message = 'Key Performance Indicator can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('price') == null || $this->input->post('price') == ''){
            
            $error_message = 'Price can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('estValue') == null || $this->input->post('estValue') == ''){
            
            $error_message = 'Estimated Value can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('top') == null || $this->input->post('top') == ''){
            
            $error_message = 'Term of Payment can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('delTime') == null || $this->input->post('delTime') == ''){
            
            $error_message = 'Delivery Time can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('termination') == null || $this->input->post('termination') == ''){
            
            $error_message = 'Termination can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('penalty') == null || $this->input->post('penalty') == ''){
            
            $error_message = 'Penalty can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('forceMajeur') == null || $this->input->post('forceMajeur') == ''){
            
            $error_message = 'Force Majeur can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('dispute') == null || $this->input->post('dispute') == ''){
            
            $error_message = 'Dispute Resolution can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('govLaw') == null || $this->input->post('govLaw') == ''){
            
            $error_message = 'Governing Law can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('conf') == null || $this->input->post('conf') == ''){
            
            $error_message = 'Confidentiality can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('checkBy') == '0'){
            
            $error_message = 'Checked by can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('ackBy') == '0'){
            
            $error_message = 'Acknowledged by can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('requestor') == '0'){
            
            $error_message = 'Requestor can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('appBy') == '0'){
            
            $error_message = 'Approved by can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $filed_at_legal = 0;
            if($this->input->post('filed') == 1){
                $filed_at_legal = 1;
            }
            
            $other_party = '';
            if($this->input->post('op_check') == null){
                $other_party = $this->input->post('otherParty_new');
            }else{
                $other_party = $this->input->post('otherParty');
            }
            
            $data = array(
                    'division_id' => $this->input->post('division'),
                    'contract_no' => $this->security->xss_clean($this->input->post('contractNo')),
                    'agr_date' => $this->security->xss_clean($this->input->post('agrDate')),
                    'eff_date' => $this->security->xss_clean($this->input->post('effDate')),
                    'exp_date' => $this->security->xss_clean($this->input->post('expDate')),
                    'first_party' => $this->security->xss_clean($this->input->post('firstParty')),
                    'fp_rep' => $this->security->xss_clean($this->input->post('fpRep')),
                    'other_party' => $this->security->xss_clean($other_party),
                    'op_rep' => $this->security->xss_clean($this->input->post('opRep')),
                    'op_title' => $this->security->xss_clean($this->input->post('opTitle')),
                    'op_address' => $this->security->xss_clean($this->input->post('opAddress')),
                    'description' => $this->security->xss_clean($this->input->post('description')),
                    'prod_loc' => $this->security->xss_clean($this->input->post('prodLoc')),
                    'tog' => $this->security->xss_clean($this->input->post('tog')),
                    'quantity' => $this->security->xss_clean($this->input->post('qty')),
                    'specs' => $this->security->xss_clean($this->input->post('spec')),
                    'kpi' => $this->security->xss_clean($this->input->post('kpi')),
                    'price' => $this->security->xss_clean($this->input->post('price')),
                    'est_value' => $this->security->xss_clean($this->input->post('estValue')),
                    'term_of_payment' => $this->security->xss_clean($this->input->post('top')),
                    'delivery_time' => $this->security->xss_clean($this->input->post('delTime')),
                    'termination' => $this->security->xss_clean($this->input->post('termination')),
                    'penalty' => $this->security->xss_clean($this->input->post('penalty')),
                    'force_majeure' => $this->security->xss_clean($this->input->post('forceMajeur')),
                    'dispute' => $this->security->xss_clean($this->input->post('dispute')),
                    'gov_law' => $this->security->xss_clean($this->input->post('govLaw')),
                    'conf' => $this->security->xss_clean($this->input->post('conf')),
                    'others' => $this->security->xss_clean($this->input->post('others')),
                    'check_by' => $this->input->post('checkBy'),
                    'ack_by' => $this->input->post('ackBy'),
                    'requestor' => $this->input->post('requestor'),
                    'req_2' => $this->input->post('req_2'),
                    'req_3' => $this->input->post('req_3'),
                    'req_4' => $this->input->post('req_4'),
                    'req_5' => $this->input->post('req_5'),
                    'approved_by' => $this->input->post('appBy'),
                    'filed_at_legal' => $filed_at_legal,
                    'status' => 0,
                    'created_by' => $this->session->userdata("username"),
                    'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->Contract_model->save($data);
            echo json_encode(array("status" => TRUE));
//            echo json_encode(array("status" => FALSE, 'error' => "OK!"));
        }
    }
    
    public function upload_contract(){
        if($this->input->post('upload_contractId') != NULL){
            $config['upload_path']          = './assets/uploads/pdf';
            $config['allowed_types']        = 'pdf';
            $config['max_size']             = 200000;
            // $config['max_width']            = 1024;
            // $config['max_height']           = 768;
    
            $this->load->library('upload', $config);
            
            $division = $this->input->post('upload_division');
            $enc_div = str_replace(array('=','+','/'), array('-','_','~'), $this->encrypt->encode($division));
            
            if ($this->upload->do_upload('berkas')){
                $contract = $this->Contract_model->getContractById($this->input->post('upload_contractId'))->result();
                if($contract[0]->doc_name != null){
                    unlink($contract[0]->doc_url);
                }
                
                $success = array('upload_data' => $this->upload->data());
    
                $data = array(
                    'doc_url' => $success['upload_data']['full_path'],
                    'doc_name' => $success['upload_data']['file_name'],
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
             }
             else{
                 $contract = $this->Contract_model->getContractById($this->input->post('upload_contractId'))->result();
                if($contract[0]->doc_name != null){
                    unlink($contract[0]->doc_url);
                }
                
                $data = array(
                    'doc_url' => null,
                    'doc_name' => null,
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
             }
             
             $this->Contract_model->update(array('contract_id' => $this->input->post('upload_contractId')), $data);
             redirect("index.php/Contract/index/$enc_div");
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
 
    public function ajax_update()
    {
        if($this->input->post('contractNo') == null || $this->input->post('contractNo') == ''){
            
            $error_message = 'Contract number can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('agrDate') == null || $this->input->post('agrDate') == ''){
            
            $error_message = 'Agreement date can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('effDate') == null || $this->input->post('effDate') == ''){
            
            $error_message = 'Effective date can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('expDate') == null || $this->input->post('expDate') == ''){
            
            $error_message = 'Expired date can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('fpRep') == '0'){
            
            $error_message = 'First party representative can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $this->input->post('fpRep')));
            
        }elseif($this->input->post('op_check') == null && ($this->input->post('otherParty_new') == null || $this->input->post('otherParty_new') == '')){

            $error_message = 'Other party can not empty. 1';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
 
        }elseif($this->input->post('op_check') != null && ($this->input->post('otherParty') == null || $this->input->post('otherParty') == '0')){
                
            $error_message = 'Other party can not empty. 2';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('opRep') == null || $this->input->post('opRep') == ''){
            
            $error_message = 'Other Party Authorized Representative can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('opTitle') == null || $this->input->post('opTitle') == ''){
            
            $error_message = 'Other Party Authorized Representative Title can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('opAddress') == null || $this->input->post('opAddress') == ''){
            
            $error_message = 'Other Party Address can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('description') == null || $this->input->post('description') == ''){
            
            $error_message = 'Description can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('prodLoc') == null || $this->input->post('prodLoc') == ''){
            
            $error_message = 'Production Location can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('tog') == null || $this->input->post('tog') == ''){
            
            $error_message = 'Type of Goods can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('qty') == null || $this->input->post('qty') == ''){
            
            $error_message = 'Quantity can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('spec') == null || $this->input->post('spec') == ''){
            
            $error_message = 'Specifications can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('kpi') == null || $this->input->post('kpi') == ''){
            
            $error_message = 'Key Performance Indicator can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('price') == null || $this->input->post('price') == ''){
            
            $error_message = 'Price can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('estValue') == null || $this->input->post('estValue') == ''){
            
            $error_message = 'Estimated Value can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('top') == null || $this->input->post('top') == ''){
            
            $error_message = 'Term of Payment can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('delTime') == null || $this->input->post('delTime') == ''){
            
            $error_message = 'Delivery Time can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('termination') == null || $this->input->post('termination') == ''){
            
            $error_message = 'Termination can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('penalty') == null || $this->input->post('penalty') == ''){
            
            $error_message = 'Penalty can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('forceMajeur') == null || $this->input->post('forceMajeur') == ''){
            
            $error_message = 'Force Majeur can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('dispute') == null || $this->input->post('dispute') == ''){
            
            $error_message = 'Dispute Resolution can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('govLaw') == null || $this->input->post('govLaw') == ''){
            
            $error_message = 'Governing Law can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('conf') == null || $this->input->post('conf') == ''){
            
            $error_message = 'Confidentiality can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('checkBy') == '0'){
            
            $error_message = 'Checked by can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('ackBy') == '0'){
            
            $error_message = 'Acknowledged by can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('requestor') == '0'){
            
            $error_message = 'Requestor can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }elseif($this->input->post('appBy') == '0'){
            
            $error_message = 'Approved by can not empty.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
            
        }else{
            $filed_at_legal = 0;
            if($this->input->post('filed') == 1){
                $filed_at_legal = 1;
            }
            
            $other_party = '';
            if($this->input->post('op_check') == null){
                $other_party = $this->input->post('otherParty_new');
            }else{
                $other_party = $this->input->post('otherParty');
            }
            
            $data = array(
                    'division_id' => $this->input->post('division'),
                    'contract_no' => $this->security->xss_clean($this->input->post('contractNo')),
                    'agr_date' => $this->security->xss_clean($this->input->post('agrDate')),
                    'eff_date' => $this->security->xss_clean($this->input->post('effDate')),
                    'exp_date' => $this->security->xss_clean($this->input->post('expDate')),
                    'first_party' => $this->security->xss_clean($this->input->post('firstParty')),
                    'fp_rep' => $this->security->xss_clean($this->input->post('fpRep')),
                    'other_party' => $this->security->xss_clean($other_party),
                    'op_rep' => $this->security->xss_clean($this->input->post('opRep')),
                    'op_title' => $this->security->xss_clean($this->input->post('opTitle')),
                    'op_address' => $this->security->xss_clean($this->input->post('opAddress')),
                    'description' => $this->security->xss_clean($this->input->post('description')),
                    'prod_loc' => $this->security->xss_clean($this->input->post('prodLoc')),
                    'tog' => $this->security->xss_clean($this->input->post('tog')),
                    'quantity' => $this->security->xss_clean($this->input->post('qty')),
                    'specs' => $this->security->xss_clean($this->input->post('spec')),
                    'kpi' => $this->security->xss_clean($this->input->post('kpi')),
                    'price' => $this->security->xss_clean($this->input->post('price')),
                    'est_value' => $this->security->xss_clean($this->input->post('estValue')),
                    'term_of_payment' => $this->security->xss_clean($this->input->post('top')),
                    'delivery_time' => $this->security->xss_clean($this->input->post('delTime')),
                    'termination' => $this->security->xss_clean($this->input->post('termination')),
                    'penalty' => $this->security->xss_clean($this->input->post('penalty')),
                    'force_majeure' => $this->security->xss_clean($this->input->post('forceMajeur')),
                    'dispute' => $this->security->xss_clean($this->input->post('dispute')),
                    'gov_law' => $this->security->xss_clean($this->input->post('govLaw')),
                    'conf' => $this->security->xss_clean($this->input->post('conf')),
                    'others' => $this->security->xss_clean($this->input->post('others')),
                    'check_by' => $this->input->post('checkBy'),
                    'ack_by' => $this->input->post('ackBy'),
                    'requestor' => $this->input->post('requestor'),
                    'req_2' => $this->input->post('req_2'),
                    'req_3' => $this->input->post('req_3'),
                    'req_4' => $this->input->post('req_4'),
                    'req_5' => $this->input->post('req_5'),
                    'approved_by' => $this->input->post('appBy'),
                    'filed_at_legal' => $filed_at_legal,
                    'status' => $this->input->post('status'),
                    'new_contract_no' => $this->security->xss_clean($this->input->post('newContractNo')),
                    'note' => $this->security->xss_clean($this->input->post('note')),
                    'last_update_by' => $this->session->userdata("username"),
                    'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );
            
            $this->Contract_model->update(array('contract_id' => $this->input->post('contractId')), $data);
            
            echo json_encode(array("status" => TRUE));
        }
    }
 
    public function ajax_delete($id)
    {
        $id = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $id));
        
        $contract = $this->Contract_model->getContractById($id)->result();
        
        $this->Contract_model->delete_by_id($id);
        
        if($contract[0]->doc_name != null){
            unlink($contract[0]->doc_url);
        }
        
        echo json_encode(array("status" => TRUE));
    }
    
    public function upload_emp(){
        $data['page_title'] = 'Upload Contract';
        $data['content'] = $this->load->view('contract/upload_emp', $data, TRUE);
        $this->load->view('form_template', $data);
    }
    
    function getmenuList(){
        echo json_encode($this->Contract_model->getContractList());
    }
    
    public function download_template(){
        $data['title'] = 'Template Upload Contract';
        $data['content'] = $this->load->view('contract/template_emp', $data, TRUE);
        $this->load->view('blank', $data);
    }
    
    public function new_upload_emp(){
        $check = $this->Users_model->getRoleMenu('index.php/Contract');
        
        if(count($check) > 0){
            $data['page_title'] = 'Upload Contract';
            $data['content'] = $this->load->view('contract/new_upload_emp', $data, TRUE);
            $this->load->view('form_template', $data);
        }else{
            $data['title'] = 'Error Page';
        	$data["content"] = $this->load->view('error',$data,true);
            $this->load->view("blank", $data);
        }
        
    }
    
    public function ajax_upload()
    {
        $ViewData=read_file("./csv/log_contract.txt");
        write_file("./csv/log_contract.txt", $ViewData."\n\n".date('Y-m-d H:i:s', strtotime('now'))." by ".$this->session->userdata("username"));
 
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
                    $contract_id = trim($field[0]);
                    $contract_name = $field[1];
                    $email = $field[2];
                    $position = $field[3];
                    $note = $field[4];
                    $subscribe = $field[5];
                    $cc1 = $field[6];
                    $cc2 = $field[7];
                    $cc3 = $field[8];
                    $cc4 = $field[9];
                    $cc5 = $field[10];

                    $data_contract = array(
                            'contract_id' => $contract_id,
                            'contract_name' => $contract_name,
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

                    $check = $this->Contract_model->getContractById($contract_id)->result();

                    if(count($check) == 0){
                        $this->Contract_model->save($data_contract);
                            $success[$num_success] = $row;
                            $success_emp[$num_success] = $contract_id;
                            $num_success++;
                    }else{
                        $fail[$num_fail] = $row;
                        $fail_emp[$num_fail] = $contract_id;
                        $fail_note[$num_fail] = 'Contract ID already exist.';
                        $num_fail++;

                        $ViewData=read_file("./csv/log_contract.txt");
                        $text = $ViewData."\n Line ".$row.'. '.$contract_id.' Contract ID already exist.';
                        write_file("./csv/log_contract.txt", $text);
                    }
                }
                $row++;
            }
        }
        
        if($num_fail == 0){
            $ViewData=read_file("./csv/log_contract.txt");
            write_file("./csv/log_contract.txt", $ViewData."\n Upload success.\n".$num_success." row(s) has been uploaded.\n");
        }else{
            $ViewData=read_file("./csv/log_contract.txt");
            write_file("./csv/log_contract.txt", $ViewData."\n".$num_success." row(s) has been uploaded.\n".$num_fail.' row(s) fail to upload.');
        }
        
        $text = $num_success." row(s) has been uploaded.\n".$num_fail." row(s) fail to upload.\nSee log file for detail.";
        
        echo json_encode(array("status" => TRUE, "text" => $text));
        
    }
    
    function view_contract($id=null){
        $id = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $id));
        
         $this->load->helper('download');

         $contract = $this->Contract_model->getContractById($id)->result();

         if($contract[0]->doc_name == null || $contract[0]->doc_name == ''){
             $name   = 'not_found.pdf';
         }else{
             $name   = $contract[0]->doc_name;
         }

         $file   = './assets/uploads/pdf/'.$name;

         $this->output
            ->set_content_type('application/pdf')
            ->set_output(file_get_contents($file));
     }
     
     function summary($contract_id=null){
         $contract_id = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $contract_id));
         
         $contract_result = $this->Contract_model->getContractSummary($contract_id)->result();
         
         if(count($contract_result) > 0){
             $data['contract'] = $this->Contract_model->getContractSummary($contract_id)->result();
             $data['content'] = $this->load->view('contract/summary', $data, TRUE);
             $this->load->view('themes/blank_page',$data);
         }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
         }
     }
     
     function ajax_on_progress($id){
         $id = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $id));
         
         $data = array(
             'status' => 1,
             'last_update_by' => $this->session->userdata("username"),
             'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
         );
         
         $this->Contract_model->update(array('contract_id' => $id), $data);
         echo json_encode(array("status" => TRUE));
     }
     
     function ajax_renew(){
         $data = array(
             'status' => 2,
             'new_contract_no' => $this->input->post('renew_newContractNo'),
             'last_update_by' => $this->session->userdata("username"),
             'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
         );
         
         $this->Contract_model->update(array('contract_id' => $this->input->post('renew_contractId')), $data);
         echo json_encode(array("status" => TRUE));
     }
     
     function ajax_expire($id){
         $id = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $id));
         
         $data = array(
             'status' => 3,
             'last_update_by' => $this->session->userdata("username"),
             'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
         );
         
         $this->Contract_model->update(array('contract_id' => $id), $data);
         echo json_encode(array("status" => TRUE));
     }
     
     function ajax_terminate(){
         $data = array(
             'status' => 4,
             'note' => $this->input->post('terminate_note'),
             'last_update_by' => $this->session->userdata("username"),
             'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
         );
         
         $this->Contract_model->update(array('contract_id' => $this->input->post('terminate_contractId')), $data);
         echo json_encode(array("status" => TRUE));
     }
}
?>
