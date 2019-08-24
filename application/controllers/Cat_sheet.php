<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cat_sheet extends CI_Controller {
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
        $this->load->model('Country_model', 'Country_model');
        $this->load->model('Product_model', 'Product_model');
        $this->load->model('Cat_sheet_model', 'Cat_sheet_model');
    }
    
    public function lists($product_id=''){
        $data['page_name'] = 'CAT Sheet Management';
        
        $role = $this->session->userdata("role");
        
        if($role == 1 || $role == 2){
            $data['product_id'] = $product_id;
            
            $country_list = array();
        
            $product_result = $this->Product_model->getProductById($product_id)->result();
            $country_result = $this->Country_model->getCountryById($product_result[0]->country)->result();

            $country_list['ENG'] = 'English';
            $country_list[$product_result[0]->country] = $country_result[0]->country_name;

            $data['country_list'] = $country_list;
            
            $data['country'] = $product_result[0]->country;
            $data['product_name'] = $product_result[0]->product_name;
        
            $data['content'] = $this->load->view('cat_sheet/list', $data, TRUE);
            $this->load->view('themes/blank',$data);
        }else{
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
        
        $this->load->view('themes/footer');
    }
    
    public function ajax_list($product_id='')
     {
         $list = $this->Cat_sheet_model->get_datatables($product_id);
         $data = array();
         $no = $_POST['start'];
         foreach ($list as $catsheet) {

             $no++;
             $row = array();
             $row[] = $catsheet->id;
             $row[] = $catsheet->country;
             $row[] = $catsheet->file_name;

             //add html for action
             if($catsheet->url != null && $catsheet->url !=''){
                 $button = '<a href=\'#\' onclick="edit_cat_sheet(\''.$catsheet->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_edit.png\' title=\'Edit product\'></a>'.'&nbsp&nbsp&nbsp'.
                       '<a href=\'#\' onclick="cat_sheet(\''.$catsheet->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'/images/059.png\' title=\'View Document\'></a>'.'&nbsp&nbsp&nbsp'.
                       '<a href=\'#\' onclick="delete_cat_sheet(\''.$catsheet->id.'\''.',\''.$catsheet->file_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_delete.png\' title=\'Delete product\'></a>';
             }else{
                 $button = '<a href=\'#\' onclick="edit_cat_sheet(\''.$catsheet->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_edit.png\' title=\'Edit product\'></a>'.'&nbsp&nbsp&nbsp'.
                       '<a href=\'#\' onclick="delete_cat_sheet(\''.$catsheet->id.'\''.',\''.$catsheet->file_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_delete.png\' title=\'Delete product\'></a>';
             }
             

             $row[] = $button;

             $data[] = $row;
         }

         $output = array(
                         "draw" => $_POST['draw'],
                         "recordsTotal" => $this->Cat_sheet_model->count_all($product_id),
                         "recordsFiltered" => $this->Cat_sheet_model->count_filtered($product_id),
                         "data" => $data,
                 );
         //output to json format
         echo json_encode($output);
     }
     
     function ajax_edit($id){
         $data = $this->Cat_sheet_model->get_by_id($id);
         echo json_encode($data);
     }
     
     function save_cat_sheet(){
         $config['upload_path']          = './assets/uploads/pdf';
         $config['allowed_types']        = 'pdf';
         $config['max_size']             = 1000;
         $config['max_width']            = 1024;
         $config['max_height']           = 768;

         $this->load->library('upload', $config);
         $product = $this->input->post('productId');
         if($this->input->post('mode') == 0){
             $check = $this->Cat_sheet_model->getCat_sheetBy_ProductId($this->input->post('productId'), $this->input->post('country'))->result();
             if(count($check) > 0){
                 
                 $data['content'] = $this->load->view('error/error_2', null, TRUE);
                 $this->load->view('themes/error',$data);
                 
             }else{
                 
                 if ($this->upload->do_upload('berkas')){
                    $success = array('upload_data' => $this->upload->data());

                    $data = array(
                            'product_id' => $this->input->post('productId'),
                            'country' => $this->input->post('country'),
                            'url' => base_url().'assets/upload/pdf/'.$success['upload_data']['file_name'],
                            'file_name' => $success['upload_data']['file_name'],
                            'created_by' => $this->session->userdata("username"),
                            'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                            'last_update_by' => $this->session->userdata("username"),
                            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                        );
                }else{
                    $data = array(
                            'product_id' => $this->input->post('productId'),
                            'country' => $this->input->post('country'),
                            'created_by' => $this->session->userdata("username"),
                            'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                            'last_update_by' => $this->session->userdata("username"),
                            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                        );

                }

                $this->Cat_sheet_model->save($data);
                redirect("Cat_sheet/lists/$product");
             }
         }else{
             $check = $this->Cat_sheet_model->getCat_sheetBy_ProductId_ForEdit($this->input->post('productId'), $this->input->post('country'),$this->input->post('id'))->result();
             if(count($check) > 0){
                 
                 $data['content'] = $this->load->view('error/error_2', null, TRUE);
                 $this->load->view('themes/error',$data);
                 
             }else{
                 
                 if ($this->upload->do_upload('berkas')){
                    $catsheet_res = $this->Cat_sheet_model->getCat_sheetById($this->input->post('id'))->result();
                    
                    if(count($catsheet_res)>0){
                        if($catsheet_res[0]->url != null){
                            unlink($catsheet_res[0]->url);
                        }
                    }
                     
                    $success = array('upload_data' => $this->upload->data());

                    $data = array(
                            'product_id' => $this->input->post('productId'),
                            'country' => $this->input->post('country'),
                            'url' => $success['upload_data']['full_path'],
                            'file_name' => $success['upload_data']['file_name'],
                            'last_update_by' => $this->session->userdata("username"),
                            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                        );
                }else{
                    $data = array(
                            'product_id' => $this->input->post('productId'),
                            'country' => $this->input->post('country'),
                            'last_update_by' => $this->session->userdata("username"),
                            'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                        );

                }

                $this->Cat_sheet_model->update(array('id' => $this->input->post('id')), $data);
                redirect("Cat_sheet/lists/$product");
             }
         }
     }
     
     function view_cat_sheet($id){
         $this->load->helper('download');

         $cat_sheet = $this->Cat_sheet_model->getCat_sheetById($id)->result();

         if(count($cat_sheet) == 0){
             $name   = 'not_found.pdf';
         }else{
             $name   = $cat_sheet[0]->file_name;
         }

         $file   = './assets/uploads/pdf/'.$name;

         $this->output
            ->set_content_type('application/pdf')
            ->set_output(file_get_contents($file));
     }
     
    public function ajax_delete($id)
    {
        $catsheet_res = $this->Cat_sheet_model->getCat_sheetById($id)->result();
                
        $this->Cat_sheet_model->delete_by_id($id);

        if($catsheet_res[0]->url != null){
            $url = './assets/uploads/pdf/'.$catsheet_res[0]->file_name;
            unlink($url);
        }
        
        echo json_encode(array("status" => TRUE));
    }
}
?>
