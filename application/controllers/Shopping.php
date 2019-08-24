<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Shopping extends CI_Controller {

	public function __construct()
	{	
		parent::__construct();
		$this->load->library('cart');
		$this->load->model('keranjang_model');
	}

	public function index()
	{
		$category=($this->uri->segment(3))?$this->uri->segment(3):0;
		$data['product'] = $this->keranjang_model->get_product_category($category);
		$data['category'] = $this->keranjang_model->get_category_all();
		$this->load->view('themes/header',$data);
		$this->load->view('shopping/list_product',$data);
		$this->load->view('themes/footer');
	}
	public function tampil_cart()
	{
		$data['category'] = $this->keranjang_model->get_category_all();
		$this->load->view('themes/header',$data);
		$this->load->view('shopping/tampil_cart',$data);
		$this->load->view('themes/footer');
	}
	
	public function check_out()
	{
		$data['category'] = $this->keranjang_model->get_category_all();
		$this->load->view('themes/header',$data);
		$this->load->view('shopping/check_out',$data);
		$this->load->view('themes/footer');
	}
	
	public function detail_product()
	{
		$id=($this->uri->segment(3))?$this->uri->segment(3):0;
		$data['category'] = $this->keranjang_model->get_category_all();
		$data['detail'] = $this->keranjang_model->get_product_id($id)->row_array();
		$this->load->view('themes/header',$data);
		$this->load->view('shopping/detail_product',$data);
		$this->load->view('themes/footer');
	}
	
	
	function tambah()
	{
		$data_product= array('id' => $this->input->post('id'),
                                     'name' => $this->input->post('nama'),
				     'price' => $this->input->post('harga'),
				     'gambar' => $this->input->post('gambar'),
				     'qty' =>$this->input->post('qty')
				);
		$this->cart->insert($data_product);
		redirect('shopping');
	}

	function hapus($rowid) 
	{
		if ($rowid=="all")
			{
				$this->cart->destroy();
			}
		else
			{
				$data = array('rowid' => $rowid,
			  				  'qty' =>0);
				$this->cart->update($data);
			}
		redirect('shopping/tampil_cart');
	}

	function ubah_cart()
	{
		$cart_info = $_POST['cart'] ;
		foreach( $cart_info as $id => $cart)
		{
			$rowid = $cart['rowid'];
			$price = $cart['price'];
			$gambar = $cart['gambar'];
			$amount = $price * $cart['qty'];
			$qty = $cart['qty'];
			$data = array('rowid' => $rowid,
							'price' => $price,
							'gambar' => $gambar,
							'amount' => $amount,
							'qty' => $qty);
			$this->cart->update($data);
		}
		redirect('shopping/tampil_cart');
	}

	public function proses_order()
	{
		//-------------------------Input data pelanggan--------------------------
		$data_pelanggan = array('nama' => $this->input->post('nama'),
							'email' => $this->input->post('email'),
							'alamat' => $this->input->post('alamat'),
							'telp' => $this->input->post('telp'));
		$id_pelanggan = $this->keranjang_model->tambah_pelanggan($data_pelanggan);
		//-------------------------Input data order------------------------------
		$data_order = array('tanggal' => date('Y-m-d'),
					   		'pelanggan' => $id_pelanggan);
		$id_order = $this->keranjang_model->tambah_order($data_order);
		//-------------------------Input data detail order-----------------------		
		if ($cart = $this->cart->contents())
			{
				foreach ($cart as $item)
					{
						$data_detail = array('order_id' =>$id_order,
										'product' => $item['id'],
										'qty' => $item['qty'],
										'harga' => $item['price']);			
						$proses = $this->keranjang_model->tambah_detail_order($data_detail);
					}
			}
		//-------------------------Hapus shopping cart--------------------------		
		$this->cart->destroy();
		$data['category'] = $this->keranjang_model->get_category_all();
		$this->load->view('themes/header',$data);
		$this->load->view('shopping/sukses',$data);
		$this->load->view('themes/footer');
	}
        
        function ajax_list(){
            $list = $this->cart->contents();
            $data = array();
            $no = 1;
            foreach ($list as $product) {

                $no++;
                $row = array();
                $row[] = $product->$no++;
                $row[] = '<img border=\'0\' src=\''.$this->config->item('base_url').'/images/'.$product->gambar.'\' title=\'Edit Plan\'>';
                $row[] = $product->name;
                $row[] = $product->price;
                $row[] = $product->qty;
                $row[] = $product->subtotal;
                $row[] = '<a href=\''.base_url().'shopping/hapus/'.$product->rowid.'\' class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a>';

                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => count($list),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }
}
?>