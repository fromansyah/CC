<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->support_lib->check_login();
        $this->load->library('encrypt');
        $this->load->helper('security');
        $this->load->library('cart');
        $this->load->library('Custom_menu');
        $this->load->helper('file');
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->load->model('Users_model', 'Users_model');
        $this->load->model('User_country_model', 'User_country_model');
        $this->load->model('Category_model', 'Category_model');
        $this->load->model('Customer_model', 'Customer_model');
        $this->load->model('Sub_category_model', 'Sub_category_model');
        $this->load->model('Country_model', 'Country_model');
        $this->load->model('Product_model', 'Product_model');
        $this->load->model('Cat_sheet_model', 'Cat_sheet_model');
        $this->load->model('Product_label_model', 'Product_label_model');
        $this->load->model('Safety_model', 'Safety_model');
        $this->load->model('Ghs_model', 'Ghs_model');
        $this->load->model('Product_safety_model', 'Product_safety_model');
        $this->load->model('Product_ghs_model', 'Product_ghs_model');
        $this->load->model('Master_data_model', 'Master_data_model');
    }
    
    public function index($country=''){
        $this->load->helper('url');
        
        
//        $data['test'] = $this->Product_ghs_model->getProductGhsByProductId(183);
        
        $data['page_name'] = 'Product Management';
        $data['category_list'] = $this->Category_model->getCategoryList();
        $data['sub_category_list'] = array();
        
        $data['safety_list'] = $this->Safety_model->get_safety_all();
        
        $data['ghs_list'] = $this->Ghs_model->get_ghs_all();
        
        $data['rating_list'] = $this->Master_data_model->getMasterDataList("RATING");
        
        $data['sub_type_list'] = $this->Master_data_model->getMasterDataList("SUB_PRODUCT_TYPE");

        $role = $this->session->userdata("role");
        
        if($role == 1 || $role == 2){
            if($role == 1){
                $country_list = $this->Country_model->get_country_all();
            }else{
                $country_list = $this->User_country_model->get_user_country_list($this->session->userdata("user_id"));
            }
            
            $data['country'] = $country_list;
            $country_array = array();
            foreach($country_list as $row){
                array_push($country_array, $row['country_iso_code']);
            }
            
            $data['pick_country'] = 'NULL';
            $data['country_flag'] = '';
            
            if($country != ''){
                if(in_array($country, $country_array)) {
                        $country_result = $this->Country_model->getCountryById($country)->result();

                        $pick_country = $country_result[0]->country_iso_code;
                        $country_flag = $country_result[0]->image_name;

                        $data['pick_country'] = $pick_country;
                        $data['country_flag'] = $country_flag;
                        $data['content'] = $this->load->view('product/list', $data, TRUE);
                        $this->load->view('themes/custom_theme',$data);
                }else{
                    $data['content'] = $this->load->view('error/error_1', null, TRUE);
                    $this->load->view('themes/error',$data);
                }
            }else{
                $data['content'] = $this->load->view('product/list', $data, TRUE);
                //$this->load->view('themes/clearfix_product_theme',$data);
                $this->load->view('themes/custom_theme',$data);
            }
        }else{
            $data['content'] = $this->load->view('error/error_1', null, TRUE);
            $this->load->view('themes/error',$data);
        }
        
        $this->load->view('themes/footer');
    }
    
    public function product_lists($country){
        
        $data['country'] = $country;
        
        $data['product'] = $this->Product_model->get_all_product_by_country($country);
        $data['category'] = $this->Category_model->get_category_all();
        $data['customer'] = $this->Customer_model->getCustomerListByCountry($country);
        $data['cart'] = $this->cart->contents();
        $sub_category[0] = 'All';
        $data['sub_category_list'] = $sub_category;
        
        $category_result = $this->Category_model->get_category_all();
        
        $sub_ctgr = array();
        foreach($category_result as $row){
            $sub_ctgr[$row['category_id']] = $this->Sub_category_model->get_sub_category_by_category($row['category_id']);
        }
        
        $data['sub_category'] = $sub_ctgr;
        
        $data['subTypeList'] = $this->Master_data_model->getMasterDataListForSearch("SUB_PRODUCT_TYPE");
        
        $country_result = $this->Country_model->getCountryById($country)->result();
        
        $language_list['ALL'] = 'All Language';
        $language_list['ENG'] = 'English';
        $language_list[$country_result[0]->country_iso_code] = $country_result[0]->country_name;
        $data['language_list'] = $language_list;
        
        $data['flag'] = $country_result[0]->image_name;
        
        $this->load->view('themes/clearfix_theme',$data);
        $this->load->view('product/product_list',$data);
        $this->load->view('themes/footer');
    }
    
    public function new_product_lists($country, $change){
        $country = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $country));
        $change = $this->encrypt->decode(str_replace(array('-','_','~'), array('=','+','/'), $change));
        
        if($change == 1){
            $this->cart->destroy();
        }
        
        $this->session->set_userdata('country',$country);
        
        $data['country'] = $country;
        
        $data['product'] = $this->Product_model->get_all_product_by_country($country);
        $data['category'] = $this->Category_model->get_category_all();
        $data['customer'] = $this->Customer_model->getCustomerListByCountry($country);
        $data['cart'] = $this->cart->contents();
        $sub_category[0] = 'All';
        $data['sub_category_list'] = $sub_category;
        
        $category_result = $this->Category_model->get_category_all();
        
        $sub_ctgr = array();
        foreach($category_result as $row){
            $sub_ctgr[$row['category_id']] = $this->Sub_category_model->get_sub_category_by_category($row['category_id']);
        }
        
        $data['sub_category'] = $sub_ctgr;
        
        //$data['test'] = $this->Product_model->get_product_search_new('VNM',3,0,0,0,0,0,0,0,1,7,0,null);
        
        $sub_category_result = $this->Sub_category_model->get_sub_category_all();
        $sub_type = array();
        foreach($sub_category_result as $sub_row){
            $sub_type[$sub_row['sub_category_id']] = $this->Master_data_model->getProductSubTypeList($country, $sub_row['sub_category_id']);
        }
        
        $data['sub_type'] = $sub_type;
        
        $data['subTypeList'] = $this->Master_data_model->getMasterDataListForSearch("SUB_PRODUCT_TYPE");
        
        $country_result = $this->Country_model->getCountryById($country)->result();
        
        $language_list['ALL'] = 'All Language';
        $language_list['ENG'] = 'English';
        $language_list[$country_result[0]->country_iso_code] = $country_result[0]->country_name;
        $data['language_list'] = $language_list;
        
        $data['flag'] = $country_result[0]->image_name;
        
        $this->session->set_userdata('flag',$country_result[0]->image_name);
        
//        $this->load->view('themes/clearfix_theme_2',$data);
//        $this->load->view('product/new_product_list',$data);
        $data['content'] = $this->load->view('product/new_product_list', $data, TRUE);
        //$this->load->view('themes/clearfix_theme_2',$data);
        $this->load->view('themes/custom_theme',$data);
        $this->load->view('themes/footer');
    }
    
    
//    public function product_lists_test($country, $category, $sub_category, $beverage, $brewery, $dairy, $food, $seafood, $poultry, $pharma, $keyword=null){
//        $keyword = urldecode($keyword);
//        $data['test'] = $this->Product_model->get_product_search($country, $category, $sub_category, $keyword);
//        $data['keyword'] = $keyword;
//        
//        $data['country'] = $country;
//        $data['product'] = $this->Product_model->get_all_product_by_country($country);
//        $data['category'] = $this->Category_model->get_category_all();
//        $data['cart'] = $this->cart->contents();
//        $sub_category[0] = 'All';
//        $data['sub_category_list'] = $sub_category;
//        
//        $country_result = $this->Country_model->getCountryById($country)->result();
//        
//        $language_list['ALL'] = 'All Language';
//        $language_list['ENG'] = 'English';
//        $language_list[$country_result[0]->country_iso_code] = $country_result[0]->country_name;
//        $data['language_list'] = $language_list;
//        
//        $this->load->view('themes/clearfix_theme',$data);
//        $this->load->view('product/product_list',$data);
//        $this->load->view('themes/footer');
//    }

    public function ajax_list($country){
        echo json_encode($this->Product_model->get_all_product_by_country($country));
    }

    public function ajax_list_by_category($country, $category, $order_by=null){
        echo json_encode($this->Product_model->get_product_category($country, $category, $order_by));
    }

    public function ajax_list_by_sub_category($country, $sub_category){
        echo json_encode($this->Product_model->get_product_sub_category($country, $sub_category));
    }

    public function ajax_list_search($country, $category, $sub_category, $subType, $beverage, $brewery, $dairy, $food, $seafood, $poultry, $pharma, $keyword=null){
        echo json_encode($this->Product_model->get_product_search($country, $category, $sub_category, $subType, $keyword, $beverage, $brewery, $dairy, $food, $seafood, $poultry, $pharma));
    }

    public function ajax_list_search_new(/*$country, $category, $beverage, $brewery, $dairy, $food, $seafood, $poultry, $pharma, $sort_by, $sub_category=null, $subType=null, $keyword=null*/){
        $new_country = $this->input->post('country');
        $new_category = $this->input->post('category');
        $new_beverage = $this->input->post('beverage');
        $new_brewery = $this->input->post('brewery');
        $new_dairy = $this->input->post('dairy');
        $new_food = $this->input->post('food');
        $new_seafood = $this->input->post('seafood');
        $new_poultry = $this->input->post('poultry');
        $new_pharma = $this->input->post('pharma');
        $new_sort_by = $this->input->post('sort_by');
        $new_sub_category = $this->input->post('sub_category');
        $new_subType = $this->input->post('subType');
        $new_keyword = $this->input->post('keyword');
        
        echo json_encode($this->Product_model->get_product_search_new($new_country, $new_category, $new_sub_category, $new_subType, $new_keyword, $new_beverage, $new_brewery, $new_dairy, $new_food, $new_seafood, $new_poultry, $new_pharma, $new_sort_by));
    }


    public function ajax_list_selected_product(){
        echo json_encode($this->cart->contents());
    }


    public function ajax_list_selected_product_wallchart(){
        
        $cart = $this->cart->contents();
        
        $products = array();
        $row_id = array();
        foreach ($cart as $row){
            //$product_result = $this->Product_model->get_by_id($row['id']);
            //$product_result->rowid = $row['rowid'];
            $row_id[$row['id']] = $row['rowid'];
            //array_push($products, $product_result);
        }
        
        $data['cart'] = $this->cart->contents();
        $data['products'] = $products;
        $data['row_id'] = $row_id;
        
        $product_id_list = '';
        $dilution = array();
        foreach($cart as $row){
            $product_id_list = $product_id_list.$row['id'].',';
            $dilution[$row['id']] = $this->input->post('dilution_'.$row['id']);
        }

        $product_id_list = substr($product_id_list, 0, -1);

        $product_result = $this->Product_model->print_selected_product($this->input->post('country'), $this->input->post('language'), $product_id_list);
        
        $data['product_result'] = $product_result;
        
        //echo json_encode($this->cart->contents());
        echo json_encode(array('products' => $product_result, 'row_id' => $row_id, 'cart' => $cart));
    }

    public function ajax_add(){
        $cart = $this->cart->contents();
        $row_id = md5($this->input->post('id'));
        
        if(isset($cart[$row_id]['qty'])){
            echo json_encode(array("status" => FALSE, "error" => $this->input->post('nama')));
        }else{
            $data_product= array('id' => $this->input->post('id'),
                                'name' => $this->input->post('nama'),
                                'price' => $this->input->post('harga'),
                                'image' => $this->input->post('gambar'),
                                'prod_no' => $this->input->post('prod_no'),
                                'qty' =>$this->input->post('qty')
                         );
             $this->cart->insert($data_product);
             echo json_encode(array("status" => TRUE, "error" => $this->input->post('nama')));
             //echo json_encode(array("status" => TRUE));
        }    
    }
   
    public function ajax_edit($id)
    {
        $data = $this->Product_model->get_by_id($id);
        echo json_encode($data);
    }
   
    public function ajax_delete($rowid){

        if ($rowid=="all"){
            $this->cart->destroy();
        }else{
            $data = array('rowid' => $rowid,
                          'qty' =>0);
                          $this->cart->update($data);
        }

        echo json_encode(array("status" => TRUE));
    }

    public function save_download(){ 

        $config['upload_path']          = './assets/uploads/logo';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 5000;
        $config['max_width']            = 2000;
        $config['max_height']           = 2000;

        $this->load->library('upload', $config);

        
        
        if($this->input->post('customer') == null){
            delete_files('./assets/uploads/logo');
            if ($this->upload->do_upload('berkas')){
                $success = array('upload_data' => $this->upload->data());
                $image = 1;
                $data['image'] = 1;
                $data['image_url'] = $success['upload_data']['full_path'];
                $data['image_name'] = base_url().'assets/uploads/logo/'.$success['upload_data']['file_name'];
                $image_name = base_url().'assets/uploads/logo/'.$success['upload_data']['file_name'];
            }else{
                $data['image'] = 0;
                $image = 0;
            }
        }else{
            $data['image'] = 1;
            $image = 1;
            $cust = $this->Customer_model->getCustomerById($this->input->post('customer'))->result();
            $data['image_name'] = base_url().'assets/uploads/images/'.$cust[0]->image_name;
            $image_name = base_url().'assets/uploads/images/'.$cust[0]->image_name;
        }
        

        $cart = $this->cart->contents();

        $product_id_list = '';
        $dilution = array();
        foreach($cart as $row){
            $product_id_list = $product_id_list.$row['id'].',';
            $dilution[$row['id']] = $this->input->post('dilution_'.$row['id']);
        }

        $product_id_list = substr($product_id_list, 0, -1);

        $product_result = $this->Product_model->print_selected_product($this->input->post('country'), $this->input->post('language'), $product_id_list);

        $data['cart'] = $cart;
        $data['dilution'] = $dilution;
        $data['language'] = $this->input->post('language');
        $data['dilution_mode'] = 'show';
        $data['country'] = $this->input->post('country');
        $data['category'] = $this->input->post('category');
        
        $data['sub_category'] = $this->input->post('sub_category');
        $data['sales_name_1'] = $this->input->post('sales-name-1');
        $data['sales_tel_1'] = $this->input->post('sales-tel-1');
        $data['sales_name_2'] = $this->input->post('sales-name-2');
        $data['sales_tel_2'] = $this->input->post('sales-tel-2');
        
        $sales_count = $this->input->post('sales_count');
        $contact_name = array();
        $contact_number = array();
        $j = 0;
        for($i=1;$i<=$sales_count;$i++){
            $cname = 'sales-name-'.$i;
            $cnumber = 'sales-tel-'.$i;
            
            if($this->input->post($cname) != '' && $this->input->post($cnumber) != ''){
                $contact_name[$j] = $this->input->post($cname);
                $contact_number[$j] = $this->input->post($cnumber);
                $j++;
            }
        }
        
        $data['contact_name'] = $contact_name;
        $data['contact_number'] = $contact_number;
        
        $data['product_id_list'] = $product_id_list;
        $data['product_result'] = $product_result;
        $data['product_list'] = $this->Product_model->get_product_all();
        
        $data['safety_list'] = $this->Safety_model->get_safety_all();
        $safety_list = $this->Safety_model->get_safety_all();
        
        $data['ghs_list'] = $this->Ghs_model->get_ghs_all();
        $ghs_list = $this->Ghs_model->get_ghs_all();

        $this->load->library('m_pdf');
        $pdfFilePath ="mypdfName-".time()."-download.pdf";

        $html=$this->load->view('Product/pdf_file',$data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
        $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
        $html = iconv('UTF-8', 'UTF-8//IGNORE', $html);
        $html = iconv('UTF-8', 'UTF-8//TRANSLIT', $html);

        $pdf = $this->m_pdf->load();
        
        $pdf->SetHTMLHeader('<table width="100%" border="0">
            <tr>
                <td height="40" width="30"></td>
                <td width="70"><img height="30" src="'.base_url().'images/index.png"></td>
                <td valign="middle" align="center"><b><font face="arial" size="3">PRODUCT INFORMATION AND SAFETY</font></b></td>
                <td  width="130" align="right">
                    <img height="40" src="'.$image_name.'"></td>
                <td width="30"></td>
            </tr>
            </table>');
        
        $footer = '';
        
        foreach($safety_list as $safety){
            $footer.='<img height="20" src="'.base_url().'assets/uploads/images/'.$safety['image_name'].'">
                                &nbsp;<font face="arial" style="font-size:70%;">'.$safety['desc'].'</font>';
        }
        $footer .= '<br/><br/>';
        foreach($ghs_list as $ghs){
            $footer.='<img height="20" src="'.base_url().'assets/uploads/images/'.$ghs['image_name'].'">
                                &nbsp;<font face="arial" style="font-size:70%;">'.$ghs['desc'].'</font>';
        }
        
        $pdf->SetHTMLFooter('<table width="100%" border="0">
            <tr>
                <td height="40" width="30"></td>
                <td>'.$footer.'</td>
                <td width="30">{PAGENO}</td>
            </tr>
            </table>');
        
        $pdf->AddPage('L', // L - landscape, P - portrait
         '', '', '', '',
         20, // margin_left
         10, // margin right
         20, // margin top
         30, // margin bottom
         5, // margin header
         10); 
//        $pdf->SetHeader('| |');
//        $pdf->setFooter('{PAGENO}'); 
        $pdf->charset_in='UTF-8';
        $pdf->SetAutoFont();
        $pdf->allow_charset_conversion = true;
        $pdf->autoScriptToLang = true;
        $pdf->autoLangToFont = true;
        $pdf->useLang = true;
        $pdf->useAdobeCJK = true;
        $pdf->shrink_tables_to_fit = 0;
//        $pdf->use_kwt = true;
//        $pdf->autoPageBreak = false;
        
        $pdf->WriteHTML($html);

         //offer it to user via browser download! (The PDF won't be saved on your server HDD)
        $pdf->Output($pdfFilePath, "I"); //tampilkan file pdf di browser
        //$pdf->Output($pdfFilePath, "D"); //langsung download file pdf
        //$this->load->view('Product/pdf_file',$data);
     }

     function ajax_get_sub_category($category){
         if($category > 0){
             echo json_encode($this->Sub_category_model->getSub_categoryList($category));
         }else{
             $sub_category[0] = 'All';
             echo json_encode($sub_category);
         }

     }

     public function selected_product(){
         delete_files('./assets/uploads/logo');

         $config['upload_path']          = './assets/uploads/logo';
         $config['allowed_types']        = 'gif|jpg|png';
         $config['max_size']             = 500;
         $config['max_width']            = 1024;
         $config['max_height']           = 768;

         $this->load->library('upload', $config);

         if ($this->upload->do_upload('berkas')){
             $success = array('upload_data' => $this->upload->data());
             $data['image'] = 1;
             $data['image_url'] = $success['upload_data']['full_path'];
             $data['image_name'] = $success['upload_data']['file_name'];
         }else{
             $data['image'] = 0;
         }

         $cart = $this->cart->contents();

        $product_id_list = '';
        foreach($cart as $row){
            $product_id_list = $product_id_list.$row['id'].',';
        }

        $product_id_list = substr($product_id_list, 0, -1);

        $product_result = $this->Product_model->print_selected_product($this->input->post('country'), $this->input->post('language'), $product_id_list);

        $data['cart'] = $cart;
        $data['language'] = $this->input->post('language');
        $data['dilution_mode'] = $this->input->post('dilution_mode');
        $data['country'] = $this->input->post('country');
        $data['category'] = $this->input->post('category');
        $data['sub_category'] = $this->input->post('sub_category');
        $data['sales_name_1'] = $this->input->post('sales-name-1');
        $data['sales_tel_1'] = $this->input->post('sales-tel-1');
        $data['sales_name_2'] = $this->input->post('sales-name-2');
        $data['sales_tel_2'] = $this->input->post('sales-tel-2');
        $data['product_id_list'] = $product_id_list;
        $data['product_result'] = $product_result;
        $data['product_list'] = $this->Product_model->get_product_all();
        $data['safety_list'] = $this->Safety_model->get_safety_all();

         $this->load->view('product/pdf_file',$data);
     }

     function cat_sheet($product_id, $lang){
         $this->load->helper('download');

         $cat_sheet = $this->Cat_sheet_model->getCat_sheetBy_ProductId($product_id, $lang)->result();

         if(count($cat_sheet) == 0){
             redirect(base_url().'assets/uploads/pdf/not_found.pdf');
         }else{
             redirect($cat_sheet[0]->url);
         }
         
//         if(count($cat_sheet) == 0){
//             $name   = 'not_found.pdf';
//         }else{
//             $name   = $cat_sheet[0]->file_name;
//         }
//
//         $file   = './assets/uploads/pdf/'.$name;
//
//         $this->output
//            ->set_content_type('application/pdf')
//            ->set_output(file_get_contents($file));
     }

     function label($product_id, $lang){
         $this->load->helper('download');

         $label = $this->Product_label_model->getProduct_labelBy_ProductId($product_id, $lang)->result();
         
         if(count($label) == 0){
             redirect(base_url().'assets/uploads/pdf/not_found.pdf');
         }else{
             redirect($label[0]->url);
         }
//         if(count($label) == 0){
//             $name   = 'not_found.pdf';
//         }else{
//             $name   = $label[0]->file_name;
//         }
//
//         $file   = './assets/uploads/pdf/'.$name;
//
//         $this->output
//            ->set_content_type('application/pdf')
//            ->set_output(file_get_contents($file));
     }

     public function ajax_list_product($country)
     {
         $new_country = array();
         if($country == 'ALL'){
             $country_result = $this->User_country_model->get_user_country_list($this->session->userdata("user_id"));
             foreach($country_result as $row){
                 array_push($new_country, $row['country_iso_code']);
             }
         }else{
             array_push($new_country, $country);
         }

         $list = $this->Product_model->get_datatables($new_country);
         $data = array();
         $no = $_POST['start'];
         foreach ($list as $product) {

             $no++;
             $row = array();
             $row[] = $product->product_id;
             $row[] = $product->product_no;
             $row[] = $product->product_name;
             $row[] = '<img src="'.base_url().'assets/images/'.$product->image_name.'" width="75" height="75" />';

             //add html for action
             $button = '<a href=\'#\' onclick="edit_product(\''.$product->product_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_edit.png\' title=\'Edit product\'></a>'.'&nbsp&nbsp&nbsp'.
                       '<a href=\'#\' onclick="language(\''.$product->product_id.'\''.',\''.$product->product_no.'\''.',\''.$product->product_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/lang_icon.png\' title=\'Add/Edit local language\'></a>'.'&nbsp&nbsp&nbsp'.
                       '<a href=\'#\' onclick="safety(\''.$product->product_id.'\''.',\''.$product->product_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/safety_icon.jpg\' title=\'Product safety\'></a>'.'&nbsp&nbsp&nbsp'.
                       '<a href=\'#\' onclick="ghs(\''.$product->product_id.'\''.',\''.$product->product_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/ghs_icon.png\' title=\'Product GHS\'></a>'.'&nbsp&nbsp&nbsp<br/>'.
                       '<a href=\'#\' onclick="cat_sheet(\''.$product->product_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/doc_icon.jpg\' title=\'CAT sheet\'></a>'.'&nbsp&nbsp&nbsp'.
                       '<a href=\'#\' onclick="label(\''.$product->product_id.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/059.png\' title=\'Label\'></a>'.'&nbsp&nbsp&nbsp'.
                       '<a href=\'#\' onclick="delete_product(\''.$product->product_id.'\''.',\''.$product->product_name.'\')"><img border=\'0\' src=\''.$this->config->item('base_url').'images/file_delete.png\' title=\'Delete product\'></a>';

             $row[] = $button;

             $data[] = $row;
         }

         $output = array(
                         "draw" => $_POST['draw'],
                         "recordsTotal" => $this->Product_model->count_all($new_country),
                         "recordsFiltered" => $this->Product_model->count_filtered($new_country),
                         "data" => $data,
                 );
         //output to json format
         echo json_encode($output);
     }

     function save_product(){
         $config['upload_path']          = './assets/images';
         $config['allowed_types']        = 'gif|jpg|png';
         $config['max_size']             = 500;
         $config['max_width']            = 1024;
         $config['max_height']           = 768;

         $this->load->library('upload', $config);

         if($this->input->post('mode') == 0){
             if ($this->upload->do_upload('berkas')){
                 $success = array('upload_data' => $this->upload->data());
                 $image = 1;
                 $data['image_url'] = $success['upload_data']['full_path'];
                 $data['image_name'] = $success['upload_data']['file_name'];

                 $data = array(
                         'product_no' => $this->input->post('productNo'),
                         'product_name' => $this->input->post('productName'),
                         'category' => $this->input->post('category'),
                         'sub_category' => $this->input->post('subCategory'),
                         'type' => $this->input->post('subType'),
                         'kg_package' => $this->input->post('kgPackage'),
                         'url' => base_url().'assets/images/'.$success['upload_data']['file_name'],
                         'image_name' => $success['upload_data']['file_name'],
                         'country' => $this->input->post('country'),
                         'application' => $this->input->post('application'),
                         'property' => $this->input->post('property'),
                         'dilution' => $this->input->post('dilution'),
                         'how_to_use' => $this->input->post('howToUse'),
                         'first_aid' => $this->input->post('firstAid'),
                         'beverage' => $this->input->post('beverage'),
                         'brewery' => $this->input->post('brewery'),
                         'dairy' => $this->input->post('dairy'),
                         'food' => $this->input->post('food'),
                         'seafood' => $this->input->post('seafood'),
                         'poultry' => $this->input->post('poultry'),
                         'pharma' => $this->input->post('pharma'),
                         'rating' => $this->input->post('rating'),
                         'desc' => $this->input->post('desc'),
                         'created_by' => $this->session->userdata("username"),
                         'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                         'last_update_by' => $this->session->userdata("username"),
                         'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                     );
             }else{
                 $image = 2;
                 $data = array(
                         'product_no' => $this->input->post('productNo'),
                         'product_name' => $this->input->post('productName'),
                         'category' => $this->input->post('category'),
                         'sub_category' => $this->input->post('subCategory'),
                         'type' => $this->input->post('subType'),
                         'kg_package' => $this->input->post('kgPackage'),
                         'country' => $this->input->post('country'),
                         'application' => $this->input->post('application'),
                         'property' => $this->input->post('property'),
                         'dilution' => $this->input->post('dilution'),
                         'how_to_use' => $this->input->post('howToUse'),
                         'first_aid' => $this->input->post('firstAid'),
                         'beverage' => $this->input->post('beverage'),
                         'brewery' => $this->input->post('brewery'),
                         'dairy' => $this->input->post('dairy'),
                         'food' => $this->input->post('food'),
                         'seafood' => $this->input->post('seafood'),
                         'poultry' => $this->input->post('poultry'),
                         'pharma' => $this->input->post('pharma'),
                         'rating' => $this->input->post('rating'),
                         'desc' => $this->input->post('desc'),
                         'created_by' => $this->session->userdata("username"),
                         'created_date' => date('Y-m-d H:i:s', strtotime('now')),
                         'last_update_by' => $this->session->userdata("username"),
                         'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                     );
             }

             $this->Product_model->save($data);
         }else{
             $product_result = $this->Product_model->getProductById($this->input->post('productId'))->result();
             
             if ($this->upload->do_upload('berkas')){
                 
                 if($product_result[0]->url != null && $product_result[0]->url != ''){
                     unlink($product_result[0]->url);
                 }
                
                 $success = array('upload_data' => $this->upload->data());
                 $image = 3;
                 $data['image_url'] = $success['upload_data']['full_path'];
                 $data['image_name'] = $success['upload_data']['file_name'];

                 $data = array(
                         'product_no' => $this->input->post('productNo'),
                         'product_name' => $this->input->post('productName'),
                         'category' => $this->input->post('category'),
                         'sub_category' => $this->input->post('subCategory'),
                         'type' => $this->input->post('subType'),
                         'kg_package' => $this->input->post('kgPackage'),
                         'url' => $success['upload_data']['full_path'],
                         'image_name' => $success['upload_data']['file_name'],
                         'country' => $this->input->post('country'),
                         'application' => $this->input->post('application'),
                         'property' => $this->input->post('property'),
                         'dilution' => $this->input->post('dilution'),
                         'how_to_use' => $this->input->post('howToUse'),
                         'first_aid' => $this->input->post('firstAid'),
                         'beverage' => $this->input->post('beverage'),
                         'brewery' => $this->input->post('brewery'),
                         'dairy' => $this->input->post('dairy'),
                         'food' => $this->input->post('food'),
                         'seafood' => $this->input->post('seafood'),
                         'poultry' => $this->input->post('poultry'),
                         'pharma' => $this->input->post('pharma'),
                         'rating' => $this->input->post('rating'),
                         'desc' => $this->input->post('desc'),
                         'last_update_by' => $this->session->userdata("username"),
                         'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                     );
             }else{
                 $image = 4;
                 $data = array(
                         'product_no' => $this->input->post('productNo'),
                         'product_name' => $this->input->post('productName'),
                         'category' => $this->input->post('category'),
                         'sub_category' => $this->input->post('subCategory'),
                         'type' => $this->input->post('subType'),
                         'kg_package' => $this->input->post('kgPackage'),
                         'country' => $this->input->post('country'),
                         'application' => $this->input->post('application'),
                         'property' => $this->input->post('property'),
                         'dilution' => $this->input->post('dilution'),
                         'how_to_use' => $this->input->post('howToUse'),
                         'first_aid' => $this->input->post('firstAid'),
                         'beverage' => $this->input->post('beverage'),
                         'brewery' => $this->input->post('brewery'),
                         'dairy' => $this->input->post('dairy'),
                         'food' => $this->input->post('food'),
                         'seafood' => $this->input->post('seafood'),
                         'poultry' => $this->input->post('poultry'),
                         'pharma' => $this->input->post('pharma'),
                         'rating' => $this->input->post('rating'),
                         'desc' => $this->input->post('desc'),
                         'last_update_by' => $this->session->userdata("username"),
                         'last_update_date' => date('Y-m-d H:i:s', strtotime('now'))
                     );
             }

             $this->Product_model->update(array('product_id' => $this->input->post('productId')), $data);
         }

         $country = $this->input->post('country');
         redirect("Product/index/$country");
     }
     
     public function ajax_delete_product($id)
    {
        $product_result = $this->Product_model->getProductById($id)->result();
        if($product_result[0]->url != null && $product_result[0]->url != ''){
            $url = './assets/images/'.$product_result[0]->image_name;
            
            unlink($url);
        }
        
        $this->Product_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }
    
    function ajax_desc($product_id){
        $product_result = $this->Product_model->getProductById($product_id)->result();
        echo json_encode(array("status" => TRUE, "product_name"=> $product_result[0]->product_name, "desc" => $product_result[0]->desc));
    }
    
    function wallchart(){
        
        $cart = $this->cart->contents();
        
        if(count($cart) > 0){
            $products = array();
            $row_id = array();
            foreach ($cart as $row){
                $product_result = $this->Product_model->get_by_id($row['id']);
                $product_result->rowid = $row['rowid'];
                $row_id[$row['id']] = $row['rowid'];
                array_push($products, $product_result);
            }

            $data['cart'] = $this->cart->contents();
            $data['products'] = $products;
            $data['row_id'] = $row_id;

            $product_id_list = '';
            $dilution = array();
            foreach($cart as $row){
                $product_id_list = $product_id_list.$row['id'].',';
                $dilution[$row['id']] = $this->input->post('dilution_'.$row['id']);
            }

            $product_id_list = substr($product_id_list, 0, -1);

            $product_result = $this->Product_model->print_selected_product($this->input->post('country'), $this->input->post('language'), $product_id_list);

            $data['product_result'] = $product_result;
            $data['cart'] = $cart;

            $country = $this->session->userdata('country');

            $data['customer'] = $this->Customer_model->getCustomerListByCountry($country);

            $country_result = $this->Country_model->getCountryById($country)->result();

            $language_list['ALL'] = 'All Language';
            $language_list['ENG'] = 'English';
            $language_list[$country_result[0]->country_iso_code] = $country_result[0]->country_name;
            $data['language_list'] = $language_list;

            $data['flag'] = $country_result[0]->image_name;

            $data['content'] = $this->load->view('product/wallchart', $data, TRUE);
            //$this->load->view('themes/clearfix_theme_2',$data);
            $this->load->view('themes/custom_theme',$data);
            $this->load->view('themes/footer');
        }else{
            $data['title'] = 'My Wall Chart';
            $data['content'] = $this->load->view('error/error_3', $data, TRUE);
            //$this->load->view('themes/clearfix_theme_2',$data);
            $this->load->view('themes/custom_theme',$data);
            $this->load->view('themes/footer');
        }
        
        
    }
}
?>
