<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report extends CI_Controller {
    
    public function __construct()
    {
	parent::__construct();
        $this->support_lib->check_login();
//        $this->load->library('Dynamic_menu');
        $this->load->library('encrypt');
        $this->load->helper('file');
        $this->load->helper('security');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Report_model', 'Report_model');
        $this->load->model('Contract_model', 'Contract_model');
        $this->load->model('License_model', 'License_model');
        $this->load->model('Users_model', 'Users_model');
        $this->load->model('Master_data_model', 'Master_data_model');
    }
    
    public function index(){
        $this->lists();
    }
    
    function lists() {
        $check = $this->Users_model->getRoleMenu('index.php/Report', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $this->load->helper('url');
            
            $data['report_list'] = $this->Report_model->getReportList();
            
            $data['status_list'] = $this->Master_data_model->getMasterDataListForReport('CONTRACT_STATUS');
            $data['status_license_list'] = $this->Master_data_model->getMasterDataListForReport('LICENSE_STATUS');

            $data['content'] = $this->load->view('Report/new_list', $data, TRUE);
            $this->load->view('themes/blank',$data);
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
    
    function new_lists() {
        $check = $this->Users_model->getRoleMenu('index.php/Report/new_lists', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $this->load->helper('url');
            
            $data['report_list'] = $this->Report_model->getReportList();
            
            $data['status_list'] = $this->Master_data_model->getMasterDataListForReport('CONTRACT_STATUS');
            $data['status_license_list'] = $this->Master_data_model->getMasterDataListForReport('LICENSE_STATUS');

            $data['content'] = $this->load->view('Report/list', $data, TRUE);
            $this->load->view('themes/blank',$data);
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }

    public function ajax_list()
    {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $base_url = base_url();
            $base_url_explode = explode('/', $base_url);
            $host = $base_url_explode[0].'//'.$base_url_explode[2];
            $list = $this->Report_model->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $report) {
    
                $no++;
                $row = array();
                $row[] = $report->name;
                $row[] = $report->group;
                
                $button='';
                //add html for action
                if($this->session->userdata("role") == 1){
                    $button = '<a href=\'#\' onclick="edit_report(\''.$report->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_edit.png\'></a>'.'&nbsp&nbsp&nbsp'.
                              '<a href=\'#\' onclick="'.$report->url.'"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/Go.png\'></a>'.'&nbsp&nbsp&nbsp'.
    //                          '<a target="_blank" href="'.$host.':8080/birt/frameset?__report='.$report->url.'"><img border=\'0\' src=\''.$this->config->item('base_url').'images/Go.png\'></a>'.'&nbsp&nbsp&nbsp'.
                              '<a href=\'#\' onclick="delete_report(\''.$report->id.'\''.',\''.$report->name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/file_delete.png\'></a>';
                }else{
                    $button = '<a href=\'#\' onclick="'.$report->url.'"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/Go.png\'></a>'.'&nbsp&nbsp&nbsp';
                }
                
                
                $row[] = $button;
    
                $data[] = $row;
            }
     
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->Report_model->count_all(),
                            "recordsFiltered" => $this->Report_model->count_filtered(),
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
        $data = $this->Report_model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add()
    {
        if($this->session->userdata("role") == 1){
            $url = $this->input->post('url');
            $newstring = substr($url, -2);
            
            if($this->input->post('name') == null || $this->input->post('name') == ''){
                $error_message = 'Report name can not empty.';
                
                echo json_encode(array("status" => FALSE, 'error' => $error_message));
            }elseif($this->input->post('url') == null || $this->input->post('url') == ''){
                $error_message = 'Report URL can not empty.';
                
                echo json_encode(array("status" => FALSE, 'error' => $error_message));
            }elseif(count(explode(' ', $url)) > 1){
                $error_message = '';
                
                echo json_encode(array("status" => FALSE, 'error' => $error_message));
            }elseif($newstring != '()'){
                $error_message = '';
                
                echo json_encode(array("status" => FALSE, 'error' => $error_message));
            }else{
                $data = array(
                        'name' => $this->security->xss_clean($this->input->post('name')),
                        'url' => $this->security->xss_clean($this->input->post('url')),
                        'group' => $this->input->post('group'),
                        'created_by' => $this->session->userdata("username"),
                        'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                        'last_update_by' => $this->session->userdata("username"),
                        'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                    );
                
                $insert = $this->Report_model->save($data);
                echo json_encode(array("status" => TRUE));
            }
        }else{
            $error_message = 'You have no authority to update data.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
        }
    }
 
    public function ajax_update()
    {
        if($this->session->userdata("role") == 1){
            $url = $this->input->post('url');
            $newstring = substr($url, -2);
            
             if($this->input->post('name') == null || $this->input->post('name') == ''){
                $error_message = 'Report name can not empty.';
                
                echo json_encode(array("status" => FALSE, 'error' => $error_message));
            }elseif($this->input->post('url') == null || $this->input->post('url') == ''){
                $error_message = 'Report URL can not empty.';
                
                echo json_encode(array("status" => FALSE, 'error' => $error_message));
            }elseif(count(explode(' ', $url)) > 1){
                $error_message = '';
                
                echo json_encode(array("status" => FALSE, 'error' => $error_message));
            }elseif($newstring != '()'){
                $error_message = '';
                
                echo json_encode(array("status" => FALSE, 'error' => $error_message));
            }else{
                $data = array(
                        'name' => $this->security->xss_clean($this->input->post('name')),
                        'url' => $this->security->xss_clean($this->input->post('url')),
                        'group' => $this->input->post('group'),
                        'last_update_by' => $this->session->userdata("username"),
                        'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                    );
                
                $this->Report_model->update(array('id' => $this->input->post('id')), $data);
                
                echo json_encode(array("status" => TRUE));
            }
        }else{
            $error_message = 'You have no authority to update data.';
            echo json_encode(array("status" => FALSE, 'error' => $error_message));
        }
    }
 
    public function ajax_delete($id)
    {
        $this->Report_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
    
    public function contract_reminder(){
//        $day = 90;
//        $reminder = '';
//        if($day == 90){
//             $reminder = '1st';
//        }elseif($day == 60){
//            $reminder = '2nd';
//        }elseif($day == 30){
//            $reminder = '3rd';
//        }elseif($day == 0){
//             $reminder = 'Expired';
//        }
//        
//        $data['name'] = $reminder;
//        $data['day'] = $day;
        
        $data['contract'] = $this->Contract_model->getContractReminder()->result();
        $data['content'] = $this->load->view('report/contract_reminder', $data, TRUE);
        $this->load->view('themes/blank_page',$data);
    }
    
    public function license_reminder(){
//        $day = 90;
//        $reminder = '';
//        if($day == 90){
//             $reminder = '1st';
//        }elseif($day == 60){
//            $reminder = '2nd';
//        }elseif($day == 30){
//            $reminder = '3rd';
//        }elseif($day == 0){
//             $reminder = 'Expired';
//        }
//        
//        $data['name'] = $reminder;
//        $data['day'] = $day;
        
        $data['license'] = $this->License_model->getLicenseReminder()->result();
        $data['content'] = $this->load->view('report/license_reminder', $data, TRUE);
        $this->load->view('themes/blank_page',$data);
    }
    
    // function ajax_run_contract_summary($status, $from, $to, $cutOff){
    //     $check = $this->Users_model->getRoleMenu('index.php/Report', $this->session->userdata("role"));
        
    //     if(count($check) > 0){
    //         $data['from'] = $from;
    //         $data['to'] = $to;
    //         $data['cutoff'] = $cutOff;
    //         $data['contract'] = $this->Contract_model->getContractSummaryReport($status, $from, $to, $cutOff)->result();
    //         $data['content'] = $this->load->view('report/contract_summary', $data, TRUE);
    //         $this->load->view('themes/blank_page',$data);
    //     }else{
    //         $data['title'] = 'Error Page';
    //         $data['content'] = $this->load->view('error/error_1', null, TRUE);
    //         $this->load->view('themes/error',$data);
    //     }
    // }
    
    function ajax_run_contract_summary_new(){
        $status = $this->input->post('status');
        $from = $this->input->post('expDateFrom');
        $to = $this->input->post('expDateTo');
        $cutOff = $this->input->post('cutOffDate');
        
        $check = $this->Users_model->getRoleMenu('index.php/Report', $this->session->userdata("role"));
        $check_2 = $this->Users_model->getRoleMenu('index.php/Report/new_lists', $this->session->userdata("role"));
        
        if(count($check) > 0 || count($check_2) > 0){
            if($status != NULL && $from != NULL && $to != NULL && $cutOff != NULL){
                if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$from) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$to) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$cutOff) ){
                    $data['from'] = $from;
                    $data['to'] = $to;
                    $data['cutoff'] = $cutOff;
                    $data['contract'] = $this->Contract_model->getContractSummaryReport($status, $from, $to, $cutOff)->result();
                    $data['content'] = $this->load->view('report/contract_summary', $data, TRUE);
                    $this->load->view('themes/blank_page',$data);
                }else{
                    $data['title'] = 'Error Page';
                    $data['url'] = 'index.php/Report/cs_page';
                    $data['content'] = $this->load->view('error/error_5', $data, TRUE);
                    $this->load->view('themes/error',$data);
                }
            }else{
                $data['title'] = 'Error Page';
                $data['url'] = 'index.php/Report/cs_page';
                $data['content'] = $this->load->view('error/error_5', $data, TRUE);
                $this->load->view('themes/error',$data);
            }
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
    
    // function ajax_run_license_summary($status, $from, $to, $cutOff){
    //     $check = $this->Users_model->getRoleMenu('index.php/Report', $this->session->userdata("role"));
        
    //     if(count($check) > 0){
    //         $data['from'] = $from;
    //         $data['to'] = $to;
    //         $data['cutoff'] = $cutOff;
    //         $data['license'] = $this->License_model->getLicenseSummaryReport($status, $from, $to, $cutOff)->result();
    //         $data['content'] = $this->load->view('report/license_summary', $data, TRUE);
    //         $this->load->view('themes/blank_page',$data);
    //     }else{
    //         $data['title'] = 'Error Page';
    //         $data['content'] = $this->load->view('error/error_1', null, TRUE);
    //         $this->load->view('themes/error',$data);
    //     }
    // }
    
    function ajax_run_license_summary_new(){
        $status = $this->input->post('status_license');
        $from = $this->input->post('expDateFrom_license');
        $to = $this->input->post('expDateTo_license');
        $cutOff = $this->input->post('cutOffDate_license');
        
        $check = $this->Users_model->getRoleMenu('index.php/Report', $this->session->userdata("role"));
        $check_2 = $this->Users_model->getRoleMenu('index.php/Report/new_lists', $this->session->userdata("role"));
        
        if(count($check) > 0 || count($check_2) > 0){
            if($status != NULL && $from != NULL && $to != NULL && $cutOff != NULL){
                if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$from) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$to) && preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$cutOff) ){
                    $data['from'] = $from;
                    $data['to'] = $to;
                    $data['cutoff'] = $cutOff;
                    $data['license'] = $this->License_model->getLicenseSummaryReport($status, $from, $to, $cutOff)->result();
                    $data['content'] = $this->load->view('report/license_summary', $data, TRUE);
                    $this->load->view('themes/blank_page',$data);
                }else{
                    $data['title'] = 'Error Page';
                    $data['url'] = 'index.php/Report/ls_page';
                    $data['content'] = $this->load->view('error/error_5', $data, TRUE);
                    $this->load->view('themes/error',$data);
                }
            }else{
                $data['title'] = 'Error Page';
                $data['url'] = 'index.php/Report/ls_page';
                $data['content'] = $this->load->view('error/error_5', $data, TRUE);
                $this->load->view('themes/error',$data);
            }
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
    
    // public function ajax_run_contract_due_date($status, $day, $cutOff){
    //     $check = $this->Users_model->getRoleMenu('index.php/Report', $this->session->userdata("role"));
        
    //     if(count($check) > 0){
    //         $data['day'] = $day;
    //         $data['cutoff'] = $cutOff;
    //         $data['contract'] = $this->Contract_model->getContractDueDateReport($status, $day, $cutOff)->result();
    //         $data['content'] = $this->load->view('report/contract_due_date', $data, TRUE);
    //         $this->load->view('themes/blank_page',$data);
    //     }else{
    //         $data['title'] = 'Error Page';
    //         $data['content'] = $this->load->view('error/error_1', null, TRUE);
    //         $this->load->view('themes/error',$data);
    //     }
    // }
    
    public function ajax_run_contract_due_date_new(){
        $status = $this->input->post('con_status_dd');
        $day = $this->input->post('con_day_dd');
        $cutOff = $this->input->post('con_cutOffDate_dd');
        
        $check = $this->Users_model->getRoleMenu('index.php/Report', $this->session->userdata("role"));
        $check_2 = $this->Users_model->getRoleMenu('index.php/Report/new_lists', $this->session->userdata("role"));
        
        if(count($check) > 0 || count($check_2) > 0){
            if($status != NULL && $day != NULL && $cutOff != NULL){
                if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$cutOff) ){
                    $data['day'] = $day;
                    $data['cutoff'] = $cutOff;
                    $data['contract'] = $this->Contract_model->getContractDueDateReport($status, $day, $cutOff)->result();
                    $data['content'] = $this->load->view('report/contract_due_date', $data, TRUE);
                    $this->load->view('themes/blank_page',$data);
                }else{
                    $data['title'] = 'Error Page';
                    $data['url'] = 'index.php/Report/cdd_page';
                    $data['content'] = $this->load->view('error/error_5', $data, TRUE);
                    $this->load->view('themes/error',$data);
                }
            }else{
                $data['title'] = 'Error Page';
                $data['url'] = 'index.php/Report/cdd_page';
                $data['content'] = $this->load->view('error/error_5', $data, TRUE);
                $this->load->view('themes/error',$data);
            }
            
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
    
    // public function ajax_run_license_due_date($status, $day, $cutOff){
    //     $check = $this->Users_model->getRoleMenu('index.php/Report', $this->session->userdata("role"));
        
    //     if(count($check) > 0){
    //         $data['day'] = $day;
    //         $data['cutoff'] = $cutOff;
    //         $data['license'] = $this->License_model->getLicenseDueDateReport($status, $day, $cutOff)->result();
    //         $data['content'] = $this->load->view('report/license_due_date', $data, TRUE);
    //         $this->load->view('themes/blank_page',$data);
    //     }else{
    //         $data['title'] = 'Error Page';
    //         $data['content'] = $this->load->view('error/error_1', null, TRUE);
    //         $this->load->view('themes/error',$data);
    //     }
    // }
    
    public function ajax_run_license_due_date_new(){
        $status = $this->input->post('status_license_dd');
        $day = $this->input->post('day_license_dd');
        $cutOff = $this->input->post('cutOffDate_license_dd');
        
        $check = $this->Users_model->getRoleMenu('index.php/Report', $this->session->userdata("role"));
        $check_2 = $this->Users_model->getRoleMenu('index.php/Report/new_lists', $this->session->userdata("role"));
        
        if(count($check) > 0 || count($check_2) > 0){
            if($status != NULL && $day != NULL && $cutOff != NULL){
                if(preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$cutOff) ){
                    $data['day'] = $day;
                    $data['cutoff'] = $cutOff;
                    $data['license'] = $this->License_model->getLicenseDueDateReport($status, $day, $cutOff)->result();
                    $data['content'] = $this->load->view('report/license_due_date', $data, TRUE);
                    $this->load->view('themes/blank_page',$data);
                }else{
                    $data['title'] = 'Error Page';
                    $data['url'] = 'index.php/Report/ldd_page';
                    $data['content'] = $this->load->view('error/error_5', $data, TRUE);
                    $this->load->view('themes/error',$data);
                }
            }else{
                $data['title'] = 'Error Page';
                $data['url'] = 'index.php/Report/ldd_page';
                $data['content'] = $this->load->view('error/error_5', $data, TRUE);
                $this->load->view('themes/error',$data);
            }
        }else{
            $data['title'] = 'Error Page';
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
    }
    
    /*public function test($status, $day, $cutOff){
        $data['day'] = $day;
        $data['cutoff'] = $cutOff;
        $data['contract'] = $this->Contract_model->getContractDueDateReport($status, $day, $cutOff)->result();
        $data['content'] = $this->load->view('report/contract_due_date', $data, TRUE);
        $this->load->view('themes/error',$data);
    }*/
    
    public function cdd_page(){
        $check = $this->Users_model->getRoleMenu('index.php/Report', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $data['page_title'] = 'Contract Due Date Report';
            $data['status_list'] = $this->Master_data_model->getMasterDataListForReport('CONTRACT_STATUS');
            $data['status_license_list'] = $this->Master_data_model->getMasterDataListForReport('LICENSE_STATUS');
            
            $data['content'] = $this->load->view('Report/cdd_page', $data, TRUE);
            $this->load->view('themes/blank',$data);
        }
    }
    
    public function cs_page(){
        $check = $this->Users_model->getRoleMenu('index.php/Report', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $data['page_title'] = 'Contract Summary Report';
            $data['status_list'] = $this->Master_data_model->getMasterDataListForReport('CONTRACT_STATUS');
            $data['status_license_list'] = $this->Master_data_model->getMasterDataListForReport('LICENSE_STATUS');
            
            $data['content'] = $this->load->view('Report/cs_page', $data, TRUE);
            $this->load->view('themes/blank',$data);
        }
    }
    
    public function ls_page(){
        $check = $this->Users_model->getRoleMenu('index.php/Report', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $data['page_title'] = 'License Summary Report';
            $data['status_list'] = $this->Master_data_model->getMasterDataListForReport('CONTRACT_STATUS');
            $data['status_license_list'] = $this->Master_data_model->getMasterDataListForReport('LICENSE_STATUS');
            
            $data['content'] = $this->load->view('Report/ls_page', $data, TRUE);
            $this->load->view('themes/blank',$data);
        }
    }
    
    public function ldd_page(){
        $check = $this->Users_model->getRoleMenu('index.php/Report', $this->session->userdata("role"));
        
        if(count($check) > 0){
            $data['page_title'] = 'License Due Date Report';
            $data['status_list'] = $this->Master_data_model->getMasterDataListForReport('CONTRACT_STATUS');
            $data['status_license_list'] = $this->Master_data_model->getMasterDataListForReport('LICENSE_STATUS');
            
            $data['content'] = $this->load->view('Report/ldd_page', $data, TRUE);
            $this->load->view('themes/blank',$data);
        }
    }
}
?>
