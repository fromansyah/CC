
<script>
    var _base_url = '<?= base_url() ?>';
    var country = '<?=$country?>';
    var save_method; //for save method string
    var table;

    $(document).ready(function() {
        /*var post_url = _base_url + 'Product/ajax_list/'+country;
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
        });*/
        
        var post_url_2 = _base_url + 'Product/ajax_list_selected_product';
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
                                    <td>'+$product_no/*+' - '+$product_name*/+'</td>\n\
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
    
    function search(){
        var ctgr = $('#category')[0].value;
        var sub_ctgr = $('#sub_category')[0].value;
        var keyword = $('#keyword')[0].value;
        
        
        var beverage = 0;//$('#beverage')[0].value;
        if($('#beverage')[0].checked){
            beverage = $('#beverage')[0].value;
        }

        var brewery = 0;//$('#brewery')[0].value;
        if($('#brewery')[0].checked){
            brewery = $('#brewery')[0].value;
        }
        var dairy = 0;//$('#dairy')[0].value;
        if($('#dairy')[0].checked){
            dairy = $('#dairy')[0].value;
        }
        var food = 0;//$('#food')[0].value;
        if($('#food')[0].checked){
            food = $('#food')[0].value;
        }
        var seafood = 0;//$('#seafood')[0].value;
        if($('#seafood')[0].checked){
            seafood = $('#seafood')[0].value;
        }
        var poultry = 0;//$('#poultry')[0].value;
        if($('#poultry')[0].checked){
            poultry = $('#poultry')[0].value;
        }
        var pharma = 0;//$('#pharma')[0].value;
        if($('#pharma')[0].checked){
            pharma = $('#pharma')[0].value;
        }
//        alert(country);
//        alert(ctgr);
//        alert(sub_ctgr);
//        alert(keyword);
        var post_url = _base_url + 'Product/ajax_list_search/' + country + '/' + ctgr + '/' + sub_ctgr + '/' + beverage + '/' + brewery + '/' + dairy + '/' + food + '/' + seafood + '/' + poultry + '/' + pharma + '/' + keyword ;
        $.ajax({
            type: "POST",
            url: post_url,
            success: function(products)
            {
//                alert(products);
                var result_count = 0;
                $('#product_list').empty();
                var data = eval('(' + products + ')')
                $.each(data,function(row,note)
                {
                    result_count++;
                    $product_id = note["product_id"];
                    $product_no = note["product_no"];
                    $product_name = note["product_name"];
                    $image_name = note["image_name"];
                    $country = note["country"];
                    $category= note["category"];
                    $star = '<img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/no_star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/no_star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/no_star.gif"/>';
                    if(note["rating"] == 1){
                        $star = '<img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>';
                    }else if(note["rating"] == 2){
                        $star = '<img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/no_star.gif"/>';
                    }else if(note["rating"] == 3){
                        $star = '<img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/no_star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/no_star.gif"/>';
                    }
                    
                    $('#product_list').append('\
                        <div class="col-lg-3 col-md-6 mb-4">\n\
                            <div class="kotak" id="product">\n\
                                <a href="#" onclick="add('+$product_id+')" title=\'Select this product\'><img class="img-thumbnail" src="'+_base_url+'assets/images/'+$image_name+'"/></a>\n\
                                <div class="card-body">\n\
                                    <h6>'+$product_no+'</h6>\n\
                                    <!--<h6 class="card-title">'+$product_name+'</h6>-->\n\
                                    '+$star+'\n\
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
                                    <h6><img width=20 height=20 src="'+_base_url+'images/pdf.png"/> Label &nbsp;&nbsp;<a href="#" onclick="label('+$product_id+',\'ENG\')">ENG</a> | <a href="#" onclick="cat_sheet('+$product_id+',\''+$country+'\')">'+$country+'</a></h6>\n\
                                    <h6><img width=20 height=20 src="'+_base_url+'images/pdf.png"/> CAT Sheet &nbsp;&nbsp;<a href="#" onclick="cat_sheet('+$product_id+',\'ENG\')">ENG</a> | <a href="#" onclick="cat_sheet('+$product_id+',\''+$country+'\')">'+$country+'</a></h6>\n\
                                    <!--<a href="'+_base_url+'shopping/detail_produk/'+$product_id+'" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-search"></i> Detail</a>-->\n\
                                    <!--<button type="button" onclick="add('+$product_id+')" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-plus"></i> Add</button>-->\n\
                                </div>\n\
                            </div>\n\
                        </div>');
                });
                
                $('#search_result').empty();
                $('#search_result').append('<p><b>Search Keyword : </b>\''+keyword+'\' <b>| Found : </b>'+result_count+' product(s)</p>');
            } 
        });
        //$('#product_list').fadeOut('slow').load('data.php').fadeIn("slow");
        $('#product_list').html(data).fadeOut('slow').fadeIn("slow");
    }
    
    function clear_search(){
        $('#sub_category').val(0);
        $('#keyword').val('');
        $('#beverage').prop("checked", false);
        $('#brewery').prop("checked", false);
        $('#dairy').prop("checked", false);
        $('#food').prop("checked", false);
        $('#seafood').prop("checked", false);
        $('#poultry').prop("checked", false);
        $('#pharma').prop("checked", false);
        
        $('#product_list').empty();
        $('#search_result').empty();
    }

    function refresh()
    {
        alert(_base_url+'Product/ajax_list'+country);
    }
    
    function generate_pdf(){
        window.location = _base_url + 'Product/save_download';
    }
    
    function cat_sheet($id, $lang){
        //alert($lang);
        var win = window.open(_base_url+'Product/cat_sheet/'+ $id + '/' + $lang, '_blank');
        win.focus();
    }
    
    function label($id, $lang){
        //alert($lang);
        var win = window.open(_base_url+'Product/label/'+ $id + '/' + $lang, '_blank');
        win.focus();
    }
    
    function selected_product(){
        window.location = _base_url + 'Product/selected_product';
    }
    
    function add(product_id){
        var url;
        var $form = "#form_"+product_id;
        //alert($form);
        
        url = "<?php echo site_url('Product/ajax_add')?>";
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
                    var post_url_2 = _base_url + 'Product/ajax_list_selected_product';
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
                                        <td>'+$product_no/*+' - '+$product_name*/+'</td>\n\
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
        var post_url = _base_url + 'Product/ajax_delete/' + row_id;
        $.ajax({
            type: "POST",
            url: post_url,
            dataType: "JSON",
            success: function(data)
            {
                if(data.status) //if success close modal and reload ajax table
                {
                    $('#tb').fadeOut('slow').empty();
                    var post_url_2 = _base_url + 'Product/ajax_list_selected_product';
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
                                        <td>'+$product_no/*+' - '+$product_name*/+'</td>\n\
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
        $("#buttonSearch").removeAttr("disabled");
        
        $("#category").val(category_id)
        
        $("#sub_category > option").remove();
        var post_url_sub = _base_url + 'Product/ajax_get_sub_category/' + category_id;
        $.ajax({
            type: "POST",
            url: post_url_sub,
            success: function(subcategory) //we're calling the response json array 'cities'
            {
                $('#sub_category').empty();
                var data = eval('(' + subcategory + ')')
                $.each(data,function(subcategory_id,note)
                {
                    var opt = $('<option />'); // here we're creating a new select option for each group
                    opt.val(subcategory_id);
                    opt.text(note);
                    $('#sub_category').append(opt);
                });
//                alert(subcategory);
            } //end success
        });
        
        //alert(category_id);
        var post_url = _base_url + 'Product/ajax_list_by_category/' + country + '/' + category_id;
        $.ajax({
            type: "POST",
            url: post_url,
            success: function(products)
            {
                var result_count = 0;
                $('#product_list').empty();
                var data = eval('(' + products + ')')
                $.each(data,function(row,note)
                {
                    result_count++;
                    $product_id = note["product_id"];
                    $product_no = note["product_no"];
                    $product_name = note["product_name"];
                    $image_name = note["image_name"];
                    $country = note["country"];
                    $category= note["category"];
                    
                    $star = '<img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/no_star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/no_star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/no_star.gif"/>';
                    if(note["rating"] == 1){
                        $star = '<img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>';
                    }else if(note["rating"] == 2){
                        $star = '<img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/no_star.gif"/>';
                    }else if(note["rating"] == 3){
                        $star = '<img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/no_star.gif"/>\n\
                                <img class="img-thumbnail" src="'+_base_url+'images/no_star.gif"/>';
                    }
                    
                    $('#product_list').append('\
                        <div class="col-lg-3 col-md-6 mb-4">\n\
                            <div class="kotak" id="product">\n\
                                <div class="card-body" style="height: 250px;">\n\
                                    <a href="#" onclick="add('+$product_id+')" title=\'Select this product\'><img style="height:70%;" class="img-thumbnail" src="'+_base_url+'assets/images/'+$image_name+'"/></a>\n\
                                    <h6>'+$product_no+'</h6>\n\
                                    <!--<h6 class="card-title">'+$product_name+'</h6>-->\n\
                                    '+$star+'\n\
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
                                    <h6><img width=20 height=20 src="'+_base_url+'images/pdf.png"/> Label &nbsp;&nbsp;<a href="#" onclick="label('+$product_id+',\'ENG\')">ENG</a> | <a href="#" onclick="cat_sheet('+$product_id+',\''+$country+'\')">'+$country+'</a></h6>\n\
                                    <h6><img width=20 height=20 src="'+_base_url+'images/pdf.png"/> CAT Sheet &nbsp;&nbsp;<a href="#" onclick="cat_sheet('+$product_id+',\'ENG\')">ENG</a> | <a href="#" onclick="cat_sheet('+$product_id+',\''+$country+'\')">'+$country+'</a></h6>\n\
                                    <!--<a href="'+_base_url+'shopping/detail_produk/'+$product_id+'" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-search"></i> Detail</a>-->\n\
                                    <!--<button type="button" onclick="add('+$product_id+')" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-plus"></i> Add</button>-->\n\
                                </div>\n\
                            </div>\n\
                        </div>');
                });
                
                $('#search_result').empty();
                $('#search_result').append('<p><b>Search Keyword : </b>\'\' <b>| Found : </b>'+result_count+' product(s)</p>');
            } 
        });
        //$('#product_list').fadeOut('slow').load('data.php').fadeIn("slow");
        $('#product_list').html(data).fadeOut('slow').fadeIn("slow");
    }
</script>
<form action="<?=base_url()?>Product/save_download" method="post" enctype="multipart/form-data">
<table class="table" id="table">
    <input type="hidden" name="dilution_mode" id="dilution_mode" value="show">
    <input type="hidden" name="category" id="category" value="0">
    <input type="hidden" name="country" id="category" value="<?=$country?>">
    <thead id="main_heading">
        <th width="2%">No</td>
        <th width="10%">Image</td>
        <th width="33%">Product</td>
        <th width="10%">Delete</td>
    </thead>
    <tbody id="tb">
    </tbody>
</table>
<div class="row clearfix">
    <div class="col-md-4 column">
        <?php echo form_dropdown('language', $language_list, '', 'id="language" name="language" placeholder="Language" class="form-control" type="text"'); ?>
    </div>
    <div class="col-md-4 column">
        <button type="button" onclick="delete_row('all')" class="btn btn-danger">Unselect All</button>
        <button type="submit" class="btn btn-success"><img src="<?= base_url() ?>images/pdf.png" width=22 height=22>&nbsp;Generate</button>
    </div>
<!--    <div class="col-md-4 column">
        <button type="submit" class="btn btn-success">&nbsp;Show Product</button>
    </div>-->
</div>
<hr/>
<div class="row clearfix">
    <div class="col-md-4 column">
        <?php echo form_dropdown('sub_category', $sub_category_list, '', 'id="sub_category" name="sub_category" placeholder="sub_category" class="form-control" type="text"'); ?>
    </div>
    <div class="col-md-4 column">
        <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Product name keyword">
    </div>
    <div class="col-md-4 column">
        <button type="button" id="buttonSearch" onclick="search()" class="btn btn-primary btn-search" disabled="disabled"><img src="<?= base_url() ?>images/search-icon.png" width=20 height=20>&nbsp;Search</button>&nbsp;
    </div>
    
    <div class="col-md-4 column">
        &nbsp;
    </div>
    <div class="col-md-4 column">
    </div>
    <div class="col-md-4 column">
    </div>
    <div class="col-md-4 column">
        <table class='table table-borderless table-option'>
            <tbody>
                <tr>
                    <td width="50%" style="border-top: 0;"><b>Beverage</b> <input name = "beverage" value="1" id="beverage" type = "checkbox"></td>
                    <td width="50%" style="border-top: 0;"><b>Brewery</b> <input name = "brewery" value="1" id="brewery" type = "checkbox"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-4 column">
        <table class='table table-borderless table-option'>
            <tbody>
                <tr>
                    <td width="50%" style="border-top: 0;"><b>Dairy</b> <input name = "dairy" value="1" id="dairy" type = "checkbox"></td>
                    <td width="50%" style="border-top: 0;"><b>Food</b> <input name = "food" value="1" id="food" type = "checkbox"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-4 column">
        <button type="button"  onclick="clear_search()" class="btn btn-primary btn-danger">&nbsp;Clear Search</button>&nbsp;
    </div>
    <div class="col-md-4 column">
        <table class='table table-borderless table-option'>
            <tbody>
                <tr>
                    <td width="50%" style="border-top: 0;"><b>Seafood</b> <input name = "seafood" value="1" id="seafood" type = "checkbox"></td>
                    <td width="50%" style="border-top: 0;"><b>Poultry</b> <input name = "poultry" value="1" id="poultry" type = "checkbox"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-4 column">
        <table class='table table-borderless table-option'>
            <tbody>
                <tr>
                    <td width="50%" style="border-top: 0;"><b>Pharma</b> <input name = "pharma" value="1" id="pharma" type = "checkbox"></td>
                    <td width="50%" style="border-top: 0;"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<hr/>
<div class="row clearfix">
    <div class="col-md-12 column selected-product-area" id="search_result"></div>
</div>
<hr/>
<div class="row clearfix">
    	<div class="col-md-4 column">
    		<table class='table table-borderless table-option'>
				<tbody>
					<tr>
						<td width="10%" style="border-top: 0;"><b>Customer:</b></td>
						<td width="90%" style="border-top: 0;">
                                                    <div class="col-md-9 column">
							<?php echo form_dropdown('customer', $customer, '', 'id="customer" name="customer" placeholder="customer" class="form-control" type="text"'); ?>
                                                    </div>
						</td>
					</tr>
					<tr>
                                                <td width="10%" style="border-top: 0;"><b>Icon:</b></td>
						<td width="90%" style="border-top: 0;">
							<input type="file" class="form-horizontal" name="berkas" id="berkas" accept="image/x-png,image/gif,image/jpeg">
							<small> (.png or .jpg only )</small>
						</td>
						<!--<td style="border-top: 0;"><b>Dilution:</b></td>-->
						<!--<td style="border-top: 0;"> <button type="button" class="btn btn-outline-secondary btn-hide-dilution">Hide Text</button> </td>-->
					</tr>
				</tbody>
			</table>
		</div>
<!--                <div class="col-md-4 column"></div>
                <div class="col-md-4 column"></div>-->
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
							<input type="text" class="form-control" name="sales-tel-1" id="sales-tel-1" placeholder="Salesperson 1 phone no.">
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
</form>
<? // print_r($cart);?>
<h2>Product List</h2>
<div id="product_list"/>
<? // print_r($test);?>
<? // print_r($keyword);?>