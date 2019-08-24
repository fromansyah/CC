
<script>
    var _base_url = '<?= base_url() ?>';
    var save_method; //for save method string
    var table;

    $(document).ready(function() {
        var post_url = _base_url + 'Page/ajax_list';
        $.ajax({
            type: "POST",
            url: post_url,
            success: function(products)
            {
                $('#product_list').empty();
                var data = eval('(' + products + ')')
                $.each(data,function(row,note)
                {
                    $product_id = note["product_id"];
                    $product_no = note["product_no"];
                    $product_name = note["product_name"];
                    $image_name = note["image_name"];
                    $category= note["category"];
                    $('#product_list').append('\
                        <div class="col-lg-3 col-md-6 mb-4">\n\
                            <div class="kotak" id="product">\n\
                                <a href="#" onclick="add('+$product_id+')" title=\'Select this product\'><img class="img-thumbnail" src="'+_base_url+'assets/images/'+$image_name+'"/></a>\n\
                                <div class="card-body">\n\
                                    <h6 class="card-title"><a href="#">'+$product_no+'</a></h6>\n\
                                    <h6>'+$product_name+'</h6>\n\
                                    <p class="card-text"></p>\n\
                                </div>\n\
                                <div class="card-footer">\n\
                                    <form id="form_'+$product_id+'" method="post" action="#" method="post" accept-charset="utf-8">\n\
                                    <input type="hidden" name="id" value="'+$product_id+'" />\n\
                                    <input type="hidden" name="prod_no" value="'+$product_no+'" />\n\
                                    <input type="hidden" name="nama" value="'+$product_name+'" />\n\
                                    <input type="hidden" name="harga" value="1000" />\n\
                                    <input type="hidden" name="gambar" value="'+$image_name+'" />\n\
                                    <input type="hidden" name="qty" value="1" />\n\
                                    </form>\n\
                                    <h6><img width=20 height=20 src="'+_base_url+'images/pdf.png"/> CAT Sheet <a href="#">en</a> | <a href="#">th</a></h6>\n\
                                    <!--<a href="'+_base_url+'shopping/detail_produk/'+$product_id+'" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-search"></i> Detail</a>-->\n\
                                    <!--<button type="button" onclick="add('+$product_id+')" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-plus"></i> Add</button>-->\n\
                                </div>\n\
                            </div>\n\
                        </div>');
                });
            }
        });
        
        var post_url_2 = _base_url + 'Page/ajax_list_selected_product';
        $.ajax({
            type: "POST",
            url: post_url_2,
            success: function(selected_products)
            {
                $('#tb').empty();
                var $i = 1;
                var data = eval('(' + selected_products + ')')
                $.each(data,function(row,note)
                {
                    $product_id = note["id"]
                    $product_no = note["prod_no"];
                    $product_name = note["name"];
                    $image_name = note["image"];
                    $price = note["price"];
                    $qty = note["qty"];
                    $subtotal = note["subtotal"];
                    $rowid = note["rowid"];
                    $('#tb').append('\
                                <tr>\n\
                                    <td>'+$i+'</td>\n\
                                    <td><img height="60" width="60" class="img-responsive" src="'+_base_url+'assets/images/'+$image_name+'"/></td>\n\
                                    <td>'+$product_no+' - '+$product_name+'</td>\n\
                                    <td><button type="button" onclick="delete_row(\''+$rowid+'\')" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></button></td>\n\
                                </tr>');                    
                    $i++;
                });
            }
        });
        
        $(".btn-hide-dilution").on("click",function(e){
		if ($(this).hasClass("btn-outline-secondary")) {
			$(this).removeClass("btn-outline-secondary").addClass("btn-secondary")
			$("#dilution_mode").val("hide")
		} else {
			$(this).removeClass("btn-secondary").addClass("btn-outline-secondary")
			$("#dilution_mode").val("show")
		}
	})
    });

    function refresh()
    {
        alert(_base_url+'Page/ajax_list');
    }
    
    function generate_pdf(){
        window.location = _base_url + 'Page/save_download';
    }
    
    function add(product_id){
        var url;
        var $form = "#form_"+product_id;
        //alert($form);
        
        url = "<?php echo site_url('Page/ajax_add')?>";
        $.ajax({
        url : url,
        type: "POST",
        data: $($form).serialize(),
        dataType: "JSON",
        success: function(data)
            {

                if(data.status) //if success close modal and reload ajax table
                {
                    $('#tb').fadeOut('slow').empty();
                    var post_url_2 = _base_url + 'Page/ajax_list_selected_product';
                    $.ajax({
                        type: "POST",
                        url: post_url_2,
                        success: function(selected_products)
                        {
                            var $i = 1;
                            var data = eval('(' + selected_products + ')')
                            $.each(data,function(row,note)
                            {
                                $product_id = note["id"]
                                $product_no = note["prod_no"];
                                $product_name = note["name"];
                                $image_name = note["image"];
                                $price = note["price"];
                                $qty = note["qty"];
                                $subtotal = note["subtotal"];
                                $rowid = note["rowid"];
                                $('#tb').append('\
                                    <tr>\n\
                                        <td>'+$i+'</td>\n\
                                        <td><img height="60" width="60" class="img-responsive" src="'+_base_url+'assets/images/'+$image_name+'"/></td>\n\
                                        <td>'+$product_no+' - '+$product_name+'</td>\n\
                                        <td><button type="button" onclick="delete_row(\''+$rowid+'\')" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></button></td>\n\
                                    </tr>');                    
                                $i++;
                            });
                         }
                    });
                    $('#tb').html(data).fadeIn("slow");
                }else{
                    serr = 'alreadey selected.';
                    try {
                      serr = data.error+ ' ' + serr;
                    } catch(e) {}

                    alert(serr);
                }
            },error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error adding / update data');
            }
        });
    }
    
    function delete_row(row_id){
        var post_url = _base_url + 'Page/ajax_delete/' + row_id;
        $.ajax({
            type: "POST",
            url: post_url,
            dataType: "JSON",
            success: function(data)
            {
                if(data.status) //if success close modal and reload ajax table
                {
                    $('#tb').fadeOut('slow').empty();
                    var post_url_2 = _base_url + 'Page/ajax_list_selected_product';
                    $.ajax({
                        type: "POST",
                        url: post_url_2,
                        success: function(selected_products)
                        {
                            var $i = 1;
                            var data = eval('(' + selected_products + ')')
                            $.each(data,function(row,note)
                            {
                                $product_id = note["id"]
                                $product_no = note["prod_no"];
                                $product_name = note["name"];
                                $image_name = note["image"];
                                $price = note["price"];
                                $qty = note["qty"];
                                $subtotal = note["subtotal"];
                                $rowid = note["rowid"];
                                $('#tb').append('\
                                    <tr>\n\
                                        <td>'+$i+'</td>\n\
                                        <td><img height="60" width="60" class="img-responsive" src="'+_base_url+'assets/images/'+$image_name+'"/></td>\n\
                                        <td>'+$product_no+' - '+$product_name+'</td>\n\
                                        <td><button type="button" onclick="delete_row(\''+$rowid+'\')" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></button></td>\n\
                                    </tr>');                    
                                $i++;
                            });
                         }
                    });
                    $('#tb').html(data).fadeIn("slow");
                }else{
                    serr = 'Fail to delete selected product.';
                    alert(data);
                }
            },error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error delete selected product.');
            }
        });
        //alert("Test Delete. "+row_id);
    }

    function category(category_id)
    {
        //alert(category_id);
        var post_url = _base_url + 'Page/ajax_list_by_category/' + category_id;
        $.ajax({
            type: "POST",
            url: post_url,
            success: function(products)
            {
                $('#product_list').empty();
                var data = eval('(' + products + ')')
                $.each(data,function(row,note)
                {
                    $product_id = note["product_id"];
                    $product_no = note["product_no"];
                    $product_name = note["product_name"];
                    $image_name = note["image_name"];
                    $category= note["category"];
                    $('#product_list').append('\
                        <div class="col-lg-3 col-md-6 mb-4">\n\
                            <div class="kotak" id="product">\n\
                                <a href="#" onclick="add('+$product_id+')" title=\'Select this product\'><img class="img-thumbnail" src="'+_base_url+'assets/images/'+$image_name+'"/></a>\n\
                                <div class="card-body">\n\
                                    <h6 class="card-title"><a href="#">'+$product_no+'</a></h6>\n\
                                    <h6>'+$product_name+'</h6>\n\
                                    <p class="card-text"></p>\n\
                                </div>\n\
                                <div class="card-footer">\n\
                                    <form id="form_'+$product_id+'" method="post" action="#" method="post" accept-charset="utf-8">\n\
                                    <input type="hidden" name="id" value="'+$product_id+'" />\n\
                                    <input type="hidden" name="prod_no" value="'+$product_no+'" />\n\
                                    <input type="hidden" name="nama" value="'+$product_name+'" />\n\
                                    <input type="hidden" name="harga" value="1000" />\n\
                                    <input type="hidden" name="gambar" value="'+$image_name+'" />\n\
                                    <input type="hidden" name="qty" value="1" />\n\
                                    </form>\n\
                                    <h6><img width=20 height=20 src="'+_base_url+'images/pdf.png"/> CAT Sheet <a href="#">en</a> | <a href="#">th</a></h6>\n\
                                    <!--<a href="'+_base_url+'shopping/detail_produk/'+$product_id+'" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-search"></i> Detail</a>-->\n\
                                    <!--<button type="button" onclick="add('+$product_id+')" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-plus"></i> Add</button>-->\n\
                                </div>\n\
                            </div>\n\
                        </div>');
                });
            } 
        });
        //$('#product_list').fadeOut('slow').load('data.php').fadeIn("slow");
        $('#product_list').html(data).fadeOut('slow').fadeIn("slow");
    }
</script>
<table class="table" id="table">
    <input type="hidden" name="dilution_mode" id="dilution_mode" value="">
    <thead id="main_heading">
        <th width="2%">No</td>
        <th width="10%">Image</td>
        <th width="33%">Product</td>
        <th width="10%">Delete</td>
    </thead>
    <tbody id="tb">
    </tbody>
</table>
<table class="table">
    <tr>
        <td>
            <button type="button" onclick="delete_row('all')" class="btn btn-danger">Unselect All</button>
            <button type="submit" class="btn btn-success" onclick="generate_pdf()"><img src="<?= base_url() ?>images/pdf.png" width=20 height=20>&nbsp;Generate</button>
        </td>
    </tr>
</table>

<div class="row clearfix">
    	<div class="col-md-4 column">
    		<table class='table table-borderless table-option'>
				<tbody>
					<tr>
						<td width="20%" style="border-top: 0;"><b>Icon:</b></td>
						<td width="80%" style="border-top: 0;">
							<input type="file" class="form-horizontal" name="fileToUpload" id="fileToUpload">
							<small> (.png or .jpg only )</small>
						</td>
					</tr>
					<tr>
						<td style="border-top: 0;"><b>Dilution:</b></td>
						<td style="border-top: 0;"> <button type="button" class="btn btn-outline-secondary btn-hide-dilution">Hide Text</button> </td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-4 column">
    		<table class='table table-borderless table-option'>
				<tbody>
					<tr>
						<td width="25%" style="border-top: 0;"><b>Sales1:</b></td>
						<td width="75%" style="border-top: 0;">
							<input type="text" class="form-control" name="sales-name-1" id="sales-name-1" placeholder="Salesperson 1 Full Name">
						</td>
					</tr>
					<tr>
						<td style="border-top: 0;"><b>Phone1:</b></td>
						<td style="border-top: 0;">
							<input type="text" class="form-control" name="sales-tel-1" id="sales-tel-1" placeholder="Salesperson 2 phone no.">
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-4 column">
    		<table class='table table-borderless table-option'>
				<tbody>
					<tr>
						<td width="25%" style="border-top: 0;"><b>Sales2:</b></td>
						<td width="75%" style="border-top: 0;">
							<input type="text" class="form-control" name="sales-name-2" id="sales-name-2" placeholder="Salesperson 2 full name">
						</td>
					</tr>
					<tr>
						<td style="border-top: 0;"><b>Phone2:</b></td>
						<td style="border-top: 0;">
							<input type="text" class="form-control" name="sales-tel-2" id="sales-tel-2" placeholder="Salesperson 2 phone no.">
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</div>
<hr>
<? // print_r($cart);?>
<h2>Product List</h2>
<div id="product_list">
</div>