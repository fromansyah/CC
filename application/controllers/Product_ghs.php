<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_ghs extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->support_lib->check_login();
        $this->load->library('cart');
        $this->load->library('Custom_menu');
        $this->load->helper('file');
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Product_model', 'Product_model');
        $this->load->model('Product_ghs_model', 'Product_ghs_model');
        $this->load->model('Ghs_model', 'Ghs_model');
    }
    
    public function lists($product_id=''){
        $data['page_name'] = 'Product GHS Management';
        
        $role = $this->session->userdata("role");
        
        if($role == 1 || $role == 2){
            $data['product_id'] = $product_id;

            $product_result = $this->Product_model->getProductById($product_id)->result();
            
            $data['country'] = $product_result[0]->country;
            $data['product_name'] = $product_result[0]->product_name;
            
//            $data['list'] = $this->Product_ghs_model->get_datatables($product_id);
        
            $data['content'] = $this->load->view('product_ghs/list', $data, TRUE);
            $this->load->view('themes/blank',$data);
        }else{
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
        
        $this->load->view('themes/footer');
    }
    
    public function ajax_list($product_id='')
     {
         $list = $this->Product_ghs_model->get_datatables($product_id);
         $data = array();
         $no = $_POST['start'];
         foreach ($list as $ghs) {
             
             $ghs_res = $this->Ghs_model->getGhsById($ghs->ghs_id)->result();
             $ghs_name = $ghs_res[0]->desc;

             $no++;
             $row = array();
             $row[] = $ghs->id;
             $row[] = '['.$ghs->ghs_id.'] '.$ghs_name;
             
             //add html for action
            $button = '<a href=\'#\' onclick="edit_product_ghs(\''.$ghs->id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_edit.png\' title=\'Edit product\'></a>'.'&nbsp&nbsp&nbsp'.
                       '<a href=\'#\' onclick="delete_product_ghs(\''.$ghs->id.'\''.',\''.$ghs_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_delete.png\' title=\'Delete product\'></a>';


             $row[] = $button;

             $data[] = $row;
         }

         $output = array(
                         "draw" => $_POST['draw'],
                         "recordsTotal" => $this->Product_ghs_model->count_all($product_id),
                         "recordsFiltered" => $this->Product_ghs_model->count_filtered($product_id),
                         "data" => $data,
                 );
         //output to json format
         echo json_encode($output);
     }
     
     function ajax_edit($id){
         $data = $this->Product_ghs_model->get_by_id($id);
         echo json_encode($data);
     }
     
     function save_product_ghs(){
         $config['upload_path']          = './assets/uploads/pdf';
         $config['allowed_types']        = 'pdf';
         $config['max_size']             = 1000;
         $config['max_width']            = 1024;
         $config['max_height']           = 768;

         $this->load->library('upload', $config);
         $product = $this->input->post('productId');
         if($this->input->post('mode') == 0){
             $check = $this->Product_ghs_model->getProduct_ghsBy_ProductId($this->input->post('productId'), $this->input->post('country'))->result();
             if(count($check) > 0){
                 
                 $data['content'] = $this->load->view('error/error_2', null, TRUE);
                 $this->load->view('themes/error',$data);
                 
             }else{
                 
                 if ($this->upload->do_upload('berkas')){
                    $success = array('upload_data' => $this->upload->data());

                    $data = array(
                            'product_id' => $this->input->post('productId'),
                            'country' => $this->input->post('country'),
                            'url' => $success['upload_data']['full_path'],
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

                $this->Product_ghs_model->save($data);
                redirect("Product_ghs/lists/$product");
             }
         }else{
             $check = $this->Product_ghs_model->getProduct_ghsBy_ProductId_ForEdit($this->input->post('productId'), $this->input->post('country'),$this->input->post('id'))->result();
             if(count($check) > 0){
                 
                 $data['content'] = $this->load->view('error/error_2', null, TRUE);
                 $this->load->view('themes/error',$data);
                 
             }else{
                 
                 if ($this->upload->do_upload('berkas')){
                    $ghs_res = $this->Product_ghs_model->getProduct_ghsById($this->input->post('id'))->result();
                    
                    if(count($ghs_res)>0){
                        if($ghs_res[0]->url != null){
                            unlink($ghs_res[0]->url);
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

                $this->Product_ghs_model->update(array('id' => $this->input->post('id')), $data);
                redirect("Product_ghs/lists/$product");
             }
         }
     }
     
     function view_product_ghs($id){
         $this->load->helper('download');

         $product_ghs = $this->Product_ghs_model->getProduct_ghsById($id)->result();

         if(count($product_ghs) == 0){
             $name   = 'not_found.pdf';
         }else{
             $name   = $product_ghs[0]->file_name;
         }

         $file   = './assets/uploads/pdf/'.$name;

         $this->output
            ->set_content_type('application/pdf')
            ->set_output(file_get_contents($file));
     }
     
    public function ajax_delete($id)
    {
        $this->Product_ghs_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
    
    public function ajax_get_product_ghs($product_id){
        $result = $this->Product_ghs_model->getProductGhsByProductId($product_id);

        echo json_encode($result);
    }
 
    public function ajax_save()
    {
        $ghs_result = $this->Ghs_model->get_ghs_all();
        $name1 = 'productGhsId_';
        $name2 = 'ghs_';
        $product_id = $this->input->post('productId_ghs');
        foreach ($ghs_result as $row){
            
            $ghs_id = $this->input->post($name2.$row['ghs_id']);
            $product_ghs_id = $this->input->post($name1.$row['ghs_id']);
            
            if($ghs_id == null && $product_ghs_id != null){
                
                $this->Product_ghs_model->delete_by_id($product_ghs_id);
                
            }elseif($product_ghs_id == null && $ghs_id != null){
                
                $data = array(
                        'product_id' => $product_id,
                        'ghs_id' => $ghs_id,
                        'created_by' => $this->session->userdata("username"),
                        'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                        'last_update_by' => $this->session->userdata("username"),
                        'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                );

                $this->Product_ghs_model->save($data);
                
            }
        }
        
        echo json_encode(array("status" => TRUE));
    }
}
?>
