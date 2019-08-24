<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {
    public function __construct()
	{	
		parent::__construct();
		$this->load->library('cart');
		$this->load->model('keranjang_model');
	}
	public function index()
		{
			$data['product'] = $this->keranjang_model->get_product_all();
			$data['category'] = $this->keranjang_model->get_category_all();
			$this->load->view('themes/header',$data);
			$this->load->view('shopping/list_product',$data);
			$this->load->view('themes/footer');
		}
	public function tentang()
		{
			$data['category'] = $this->keranjang_model->get_category_all();
			$this->load->view('themes/header',$data);
			$this->load->view('pages/tentang',$data);
			$this->load->view('themes/footer');
		}	
	public function cara_bayar()
		{
			$data['category'] = $this->keranjang_model->get_category_all();
			$this->load->view('themes/header',$data);
			$this->load->view('pages/cara_bayar',$data);
			$this->load->view('themes/footer');
		}
       
    public function product(){
        $data['product'] = $this->keranjang_model->get_product_all();
        $data['category'] = $this->keranjang_model->get_category_all();
        $data['cart'] = $this->cart->contents();
//        $this->load->view('themes/template',$data);
        $this->load->view('themes/clearfix_theme',$data);
        $this->load->view('shopping/product_list',$data);
        $this->load->view('themes/footer');
   }
   
   public function ajax_list(){
       echo json_encode($this->keranjang_model->get_all_product());
   }
   
   public function ajax_list_by_category($category){
       echo json_encode($this->keranjang_model->get_product_category($category));
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
            echo json_encode(array("status" => TRUE));
       }    
   }
   
   public function ajax_list_selected_product(){
       echo json_encode($this->cart->contents());;
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
   
   public function selected_product(){
       $data['cart'] = $this->cart->contents();
       $this->load->view('shopping/pdf_file',$data);
   }
   
   public function save_download(){ 
       $this->load->library('m_pdf');
       
       $data['title']="MY PDF TITLE 1.";
       $data['description']="";
       $data['description']=$this->official_copies;
       
       $pdfFilePath ="mypdfName-".time()."-download.pdf";
       
       $data['cart'] = $this->cart->contents();
       $html=$this->load->view('shopping/pdf_file',$data, true); //load the pdf_output.php by passing our data and get all data in $html varriable.
       $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
       $html = iconv('UTF-8', 'UTF-8//IGNORE', $html);
       $html = iconv('UTF-8', 'UTF-8//TRANSLIT', $html);
    
       $pdf = $this->m_pdf->load('+aCJK','A4','','',15,10,16,10,10,10);
       $pdf->SetHeader('| |');
       $pdf->setFooter('{PAGENO}'); 
       $pdf->charset_in='UTF-8';
       $pdf->SetAutoFont();
       $pdf->allow_charset_conversion = true;
       $pdf->autoScriptToLang = true;
       $pdf->autoLangToFont = true;
       $pdf->useLang = true;
       $pdf->useAdobeCJK = true;
       
       $pdf->WriteHTML($html);
       
	//offer it to user via browser download! (The PDF won't be saved on your server HDD)
       $pdf->Output($pdfFilePath, "I"); //tampilkan file pdf di browser
       //$pdf->Output($pdfFilePath, "D"); //langsung download file pdf
       
       /*$mpdf = new \Mpdf\Mpdf();
       //$data = $this->load->view('hasilPrint', [], TRUE);
       $data['cart'] = $this->cart->contents();
       $html=$this->load->view('shopping/pdf_file',$data, true);
       $mpdf->autoScriptToLang = true;
       $mpdf->autoLangToFont = true;
       $mpdf->WriteHTML($html);
       $mpdf->Output();*/
    }
    
    function pdf(){
        $this->load->library('m_pdf');
        $html = '
        Most of this text is in English, but has occasional words in Chinese:其貢獻在 or 
        Vietnamese: Một khảo sát mới cho biết, or maybe even Arabic: البرادعی

        البرادعی -12- البرادعی
กรณีสัมผัสทางผิวหนัง
        其貢獻在國際間亦備受肯定，2005年
        ';
        
        $mpdf = $this->m_pdf->load('-aCJK', 'A4', '', '', 0, 0, 0, 0, 0, 0);

        $mpdf->charset_in='UTF-8';
       $mpdf->SetAutoFont(AUTOFONT_ALL);
       $mpdf->allow_charset_conversion = true;
       $mpdf->autoScriptToLang = true;
       $mpdf->autoLangToFont = true;
       $mpdf->useLang = true;

        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }
    
    function test(){
        //$this->load->view('welcome_message');
        
        // Get output html
        $data['cart'] = $this->cart->contents();
        $html=$this->load->view('shopping/pdf_file',$data, true);
        $html = utf8_decode($html);
        $html = iconv('UTF-8','Windows-1250',$html);
        // Load pdf library
        $this->load->library('pdf');
        
        // Load HTML content
        $this->dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation
        $this->dompdf->setPaper('A4', 'landscape');
        
        // Render the HTML as PDF
        $this->dompdf->render();
        
        // Output the generated PDF (1 = download and 0 = preview)
        $this->dompdf->stream("welcome.pdf", array("Attachment"=>0));
    }
}
