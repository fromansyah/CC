<!--<table class="table">
<tr id= "main_heading">
<td width="2%">No</td>
<td width="10%">Gambar</td>
<td width="33%">Item</td>
<td width="17%">Harga</td>
<td width="8%">Qty</td>
<td width="20%">Jumlah</td>
<td width="10%">Hapus</td>
</tr>
<?php
// Create form and send all values in "shopping/update_cart" function.
$cart = $this->cart->contents();
$grand_total = 0;
$i = 1;

foreach ($cart as $item):
$grand_total = $grand_total + $item['subtotal'];
?>
<input type="hidden" name="cart[<?php echo $item['id'];?>][id]" value="<?php echo $item['id'];?>" />
<input type="hidden" name="cart[<?php echo $item['id'];?>][rowid]" value="<?php echo $item['rowid'];?>" />
<input type="hidden" name="cart[<?php echo $item['id'];?>][name]" value="<?php echo $item['name'];?>" />
<input type="hidden" name="cart[<?php echo $item['id'];?>][price]" value="<?php echo $item['price'];?>" />
<input type="hidden" name="cart[<?php echo $item['id'];?>][gambar]" value="<?php echo $item['gambar'];?>" />
<input type="hidden" name="cart[<?php echo $item['id'];?>][qty]" value="<?php echo $item['qty'];?>" />
<tr>
<td><?php echo $i++; ?></td>
<td><img class="img-responsive" src="<?php echo base_url() . 'assets/images/'.$item['gambar']; ?>"/></td>
<td><?php echo $item['name']; ?></td>
<td><?php echo number_format($item['price'], 0,",","."); ?></td>
<td><input type="text" class="form-control input-sm" name="cart[<?php echo $item['id'];?>][qty]" value="<?php echo $item['qty'];?>" /></td>
<td><?php echo number_format($item['subtotal'], 0,",",".") ?></td>
<td><a href="<?php echo base_url()?>shopping/hapus/<?php echo $item['rowid'];?>" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a></td>
<?php endforeach; ?>
</tr>
<tr>
<td colspan="3"><b>Order Total: Rp <?php echo number_format($grand_total, 0,",","."); ?></b></td>
<td colspan="4" align="right">
<a data-toggle="modal" data-target="#myModal"  class ='btn btn-sm btn-danger'>Kosongkan Cart</a>
<button class='btn btn-sm btn-success'  type="submit">Update Cart</button>
<a href="<?php echo base_url()?>shopping/check_out"  class ='btn btn-sm btn-primary'>Check Out</a>
</tr>

</table>-->


<h2>Daftar Produk</h2>
<?php
	foreach ($product as $row) {
?>
            <div class="col-lg-4 col-md-6 mb-4">
              <div class="kotak">
              <form method="post" action="<?php echo base_url();?>shopping/tambah" method="post" accept-charset="utf-8">
                <a href="#"><img class="img-thumbnail" src="<?php echo base_url() . 'assets/images/'.$row['image_name']; ?>"/></a>
                <div class="card-body">
                  <h4 class="card-title">
                    <a href="#"><?php echo $row['product_no'];?></a>
                  </h4>
                  <h5><?php echo $row['product_name'];?></h5>
                  <p class="card-text"><?php echo $row['desc'];?></p>
                </div>
                <div class="card-footer">
                  <a href="<?php echo base_url();?>shopping/detail_produk/<?php echo $row['product_no'];?>" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-search"></i> Detail</a> 
                  
                  <input type="hidden" name="id" value="<?php echo $row['product_id']; ?>" />
                  <input type="hidden" name="nama" value="<?php echo $row['product_name']; ?>" />
                  <input type="hidden" name="harga" value="1000" />
                  <input type="hidden" name="gambar" value="<?php echo $row['image_name']; ?>" />
                  <input type="hidden" name="qty" value="1" />
                  <button type="submit" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-shopping-cart"></i> Add</button>
                </div>
                </form>
              </div>
            </div>
<?php
	}
?>