<div class="row-2">
            <div class="col-lg-2">
                <div class="row clearfix">
                </div>
                <div class="list-group">
                    <a class="list-group-item"><strong>COUNTRY</strong></a>
                    <!--<a href="#" onclick="country_product('ALL')" class="list-group-item">All</a>-->
                    <?php foreach ($country as $row):?>
                    <a href="#" onclick="country_product('<?php echo $row['country_iso_code'] ?>','<?php echo $row['image_name'] ?>')" class="list-group-item"><?php echo $row['country_name'];?></a>
                    <?php endforeach;?>
                </div>
                <br>
            </div>

            <div class="col-lg-10">
                <span width="100" style="background-color: #e0e0e0; display: block;">
                    &nbsp;<font size="5" style="font-weight: bold; color: #737373">Product Management</font>
                    <?
                        $img_url = '';
                        if($country_flag != ''){
                            $img_url = base_url().'assets/uploads/images/'.$country_flag;
                        }
                    ?>
                    &nbsp;&nbsp;&nbsp;<img id="flag" src="<?=$img_url?>" height="30">
                </span>
                <br/>
                <button id="add_button" class="btn btn-success" onclick="add_product()" disabled="true"><i class="glyphicon glyphicon-plus"></i> Add Product</button>
                <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
                <br />
                <br />
                <table id="table" class="table table-striped table-bordered" cellspacing="0" width="1100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product No.</th>
                            <th>Product Name</th>
                            <th>Image</th>
                            <th style="width:100px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Product No.</th>
                            <th>Product Name</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
<script type="text/javascript">
    
var $pick_country = '<?=$pick_country?>';
var _base_url = '<?= base_url() ?>';
var save_method; //for save method string
var table;

var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
             
var dataJson = { [csrfName]: csrfHash };

$(document).ready(function() {
    
//    table = $('#table').DataTable();

    //datatables
    if($pick_country != 'NULL'){
        $("#add_button").attr("disabled", false);
    }
    
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('product/ajax_list_product/')?>"+$pick_country,
            "type": "POST",
            "data": dataJson
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false //set not orderable
        },
        ]

    });

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true
    });
    
    $('#category').change(function(){ //any select change on the dropdown with id country trigger this code
                $("#subCategory > option").remove();
                var ctgr_id = $('#category').val();
                var post_url = _base_url + 'Sub_category/getSubCategoryList/' + ctgr_id;
                    $.ajax({
                        type: "POST",
                        url: post_url,
                        data: dataJson,
                        success: function(site) //we're calling the response json array 'cities'
                        {
                            $('#subCategory').empty();
                            var data = eval('(' + site + ')')
                            $.each(data,function(category,note)
                            {
                                var opt = $('<option />'); // here we're creating a new select option for each group
                                opt.val(category);
                                opt.text(note);
                                $('#subCategory').append(opt);
                            });
                        } //end success
                    });
                }); 

});



function add_product()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Product'); // Set Title to Bootstrap modal title
//    $('[name="productId"]').attr("readOnly", false);
    $('[name="beverage"]').attr("checked", false);
    $('[name="brewery"]').attr("checked", false);
    $('[name="dairy"]').attr("checked", false);
    $('[name="food"]').attr("checked", false);
    $('[name="seafood"]').attr("checked", false);
    $('[name="poultry"]').attr("checked", false);
    $('[name="pharma"]').attr("checked", false);
    $('[name="mode"]').val(0);
    $('[name="country"]').val($('#country_code')[0].value);
}

function edit_product(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('Product/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $("#subCategory > option").remove();
                var ctgr_id = data.category;
                var post_url = _base_url + 'Sub_category/getSubCategoryList/' + ctgr_id;
                    $.ajax({
                        type: "POST",
                        url: post_url,
                        success: function(sub_category) //we're calling the response json array 'cities'
                        {
                            $('#subCategory').empty();
                            var data_sub = eval('(' + sub_category + ')')
                            var i = 0;
                            var selection = null;
                            $.each(data_sub,function(sub_category_id,note)
                            {
                                var opt = $('<option />'); // here we're creating a new select option for each group
                                opt.val(sub_category_id);
                                opt.text(note);
                                $('#subCategory').append(opt);
                                
                                if(data.sub_category == sub_category_id){
                                    selection = i;
                                }
                                
                                i++;
                            });
                            
//                            $('[name="test_data"]').val(data.sub_category);
                            document.getElementById('subCategory').options[selection].selected = true;
                            
                        } //end success
                    });

            $('[name="productId"]').val(data.product_id);
            $('[name="productNo"]').val(data.product_no);
            $('[name="productName"]').val(data.product_name);
            $('[name="category"]').val(data.category);
            $('[name="subCategory"]').val(data.sub_category);
            $('[name="subType"]').val(data.type);
            $('[name="kgPackage"]').val(data.kg_package);
            $('[name="application"]').val(data.application);
            $('[name="property"]').val(data.property);
            $('[name="dilution"]').val(data.dilution);
            $('[name="howToUse"]').val(data.how_to_use);
            $('[name="firstAid"]').val(data.first_aid);
            $('[name="country"]').val(data.country);
            $('[name="mode"]').val(1);
            $('[name="rating"]').val(data.rating);
            $('[name="desc"]').val(data.desc);
            
            if(data.beverage == 1){
                $('[name="beverage"]').attr("checked", true);
            }else{
                $('[name="beverage"]').attr("checked", false);
            }
            
            if(data.brewery == 1){
                $('[name="brewery"]').attr("checked", true);
            }else{
                $('[name="brewery"]').attr("checked", false);
            }
            
            if(data.dairy == 1){
                $('[name="dairy"]').attr("checked", true);
            }else{
                $('[name="dairy"]').attr("checked", false);
            }
            
            if(data.food == 1){
                $('[name="food"]').attr("checked", true);
            }else{
                $('[name="food"]').attr("checked", false);
            }
            
            if(data.seafood == 1){
                $('[name="seafood"]').attr("checked", true);
            }else{
                $('[name="seafood"]').attr("checked", false);
            }
            
            if(data.poultry == 1){
                $('[name="poultry"]').attr("checked", true);
            }else{
                $('[name="poultry"]').attr("checked", false);
            }
            
            if(data.pharma == 1){
                $('[name="pharma"]').attr("checked", true);
            }else{
                $('[name="pharma"]').attr("checked", false);
            }
            
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Product'); // Set title to Bootstrap modal title
            $('[name="productId"]').attr("readOnly", true);

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('product/ajax_add')?>";
    } else {
        url = "<?php echo site_url('product/ajax_update')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
            }else{
                serr = 'Fail to save data.';
                try {
                  serr = serr + ' ' + data.error;
                } catch(e) {}
                
                alert(serr);
            }

                $('#btnSave').text('save'); //change button text
                $('#btnSave').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function save_safety()
{
    $('#btnSave_safety').text('saving...'); //change button text
    $('#btnSave_safety').attr('disabled',true); //set button disable 
    var url;

    url = "<?php echo site_url('Product_safety/ajax_save')?>";

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_safety').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_safety').modal('hide');
                reload_table();
            }else{
                serr = 'Fail to save product safety data.';
                try {
                  serr = serr + ' ' + data.error;
                } catch(e) {}
                
                alert(serr);
            }

                $('#btnSave_safety').text('save'); //change button text
                $('#btnSave_safety').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update product safety data');
            $('#btnSave_safety').text('save'); //change button text
            $('#btnSave_safety').attr('disabled',false); //set button enable 

        }
    });
}

function save_ghs()
{
    $('#btnSave_ghs').text('saving...'); //change button text
    $('#btnSave_ghs').attr('disabled',true); //set button disable 
    var url;

    url = "<?php echo site_url('Product_ghs/ajax_save')?>";

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_ghs').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_ghs').modal('hide');
                reload_table();
            }else{
                serr = 'Fail to save product GHS data.';
                try {
                  serr = serr + ' ' + data.error;
                } catch(e) {}
                
                alert(serr);
            }

                $('#btnSave_ghs').text('save'); //change button text
                $('#btnSave_ghs').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update product safety data');
            $('#btnSave_ghs').text('save'); //change button text
            $('#btnSave_ghs').attr('disabled',false); //set button enable 

        }
    });
}

function save_lang()
{
    $('#btnSave_lang').text('saving...'); //change button text
    $('#btnSave_lang').attr('disabled',true); //set button disable 
    var url;

    url = "<?php echo site_url('Product_language/ajax_save')?>";


    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_lang').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_lang').modal('hide');
                reload_table();
            }else{
                serr = 'Fail to save product local language data.';
                try {
                  serr = serr + ' ' + data.error;
                } catch(e) {}
                
                alert(serr);
            }

                $('#btnSave_lang').text('save'); //change button text
                $('#btnSave_lang').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update local language data');
            $('#btnSave_lang').text('save'); //change button text
            $('#btnSave_lang').attr('disabled',false); //set button enable 

        }
    });
}

function delete_product(id, product)
{
    if(confirm('Are you sure delete this data: ' + product + ' ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('product/ajax_delete_product')?>/"+id,
            type: "POST",
            dataType: "JSON",
            data: dataJson,
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function country_product($country, $flag){

    table.ajax.reload(null,false); 
    var _url = "<?php echo site_url('product/ajax_list_product/')?>"+$country;
    table.ajax.url(_url).load();
    
    var src = _base_url + 'assets/uploads/images/' + $flag;
    $("#flag").attr("src", src);
    
    $("#country_code").val($country);
    
    $("#add_button").attr("disabled", false);
}

function safety(product_id, product_name) {
//    alert('Safety') ;
//    window.location = _base_url + 'Product_safety/lists/' + product_id ;

//    $.ajax({
//                url : "<?php echo site_url('Safety/ajax_get_safety')?>" ,
//                type: "GET",
//                dataType: "JSON",
//                success: function(data)
//                {
//                    for (var key in data) {
//                        if (data.hasOwnProperty(key)) {
//                            var val = data[key];
//                            var _name2 = '[name="safety_'+val['safety_id']+'"]';
//
//                            $(_name2).removeAttr("checked");
//                        }
//                      }
//                },
//                error: function (jqXHR, textStatus, errorThrown)
//                {
//                    alert('Error get data from ajax');
//                }
//            });

    $('[name="safety_1"]').removeAttr("checked");
    $('[name="safety_2"]').removeAttr("checked");
    $('[name="safety_3"]').removeAttr("checked");
    $('[name="safety_4"]').removeAttr("checked");
    $('[name="safety_5"]').removeAttr("checked");
    $('[name="safety_6"]').removeAttr("checked");
    
    $.ajax({
        url : "<?php echo site_url('Product_safety/ajax_get_product_safety/')?>" + product_id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            
            
            $('#form_safety')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form_safety').modal('show'); // show bootstrap modal
            $('.modal-title').text('Add Product Safety - '+product_name); // Set Title to Bootstrap modal title
            $('[name="mode_safety"]').val(0);
            $('[name="productId_safety"]').val(product_id);
            
//            $('[name="safety_1"]').val('1');
//            $('[name="safety_2"]').val('2');
//            $('[name="safety_3"]').val('3');
//            $('[name="safety_4"]').val('4');
            
            for (var key in data) {
                if (data.hasOwnProperty(key)) {
                  var val = data[key];
                  var _name1 = '[name="productSafetyId_'+val['safety_id']+'"]';
                  var _name2 = '[name="safety_'+val['safety_id']+'"]';
                  
                    $(_name1).val(val['id']);
                    $(_name2).val(val['safety_id']);
                    $(_name2).attr("checked", true);
                }
              }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}


function ghs(product_id, product_name) {
    $('[name="ghs_1"]').removeAttr("checked");
    $('[name="ghs_2"]').removeAttr("checked");
    $('[name="ghs_3"]').removeAttr("checked");
    $('[name="ghs_4"]').removeAttr("checked");
    $('[name="ghs_5"]').removeAttr("checked");
    $('[name="ghs_6"]').removeAttr("checked");
    $('[name="ghs_7"]').removeAttr("checked");
    $('[name="ghs_8"]').removeAttr("checked");
    $('[name="ghs_9"]').removeAttr("checked");
    
    $.ajax({
        url : "<?php echo site_url('Product_ghs/ajax_get_product_ghs/')?>" + product_id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            
            
            $('#form_ghs')[0].reset(); // reset form on modals
            $('.form-group').removeClass('has-error'); // clear error class
            $('.help-block').empty(); // clear error string
            $('#modal_form_ghs').modal('show'); // show bootstrap modal
            $('.modal-title').text('Add Product GHS - '+product_name); // Set Title to Bootstrap modal title
            $('[name="mode_ghs"]').val(0);
            $('[name="productId_ghs"]').val(product_id);
            
//            $('[name="safety_1"]').val('1');
//            $('[name="safety_2"]').val('2');
//            $('[name="safety_3"]').val('3');
//            $('[name="safety_4"]').val('4');
            
            for (var key in data) {
                if (data.hasOwnProperty(key)) {
                  var val = data[key];
                  var _name1 = '[name="productGhsId_'+val['ghs_id']+'"]';
                  var _name2 = '[name="ghs_'+val['ghs_id']+'"]';
                  
                    $(_name1).val(val['id']);
                    $(_name2).val(val['ghs_id']);
                    $(_name2).attr("checked", true);
                }
              }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function cat_sheet(product_id) {
//    alert('CAT Sheet') ;
    window.location = _base_url + 'Cat_sheet/lists/' + product_id ;
}

function label(product_id) {
//    alert('CAT Sheet') ;
    window.location = _base_url + 'Product_label/lists/' + product_id ;
}

function language(product_id, product_no, product_name) {
//    alert('LANGUAGE') ;
    $('#form_lang')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('Product_language/ajax_get_product_language/')?>" + product_id + '/' + $('#country_code')[0].value,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="productId_lang"]').val(product_id);
            $('[name="productNo_lang"]').val(product_no);
            $('[name="productName_lang"]').val(product_name);
            $('[name="country_lang"]').val($('#country_code')[0].value);
            $('[name="mode_lang"]').val(0);
            
            if(data!=null){
                $('[name="productLangId"]').val(data.id);
                $('[name="type_lang"]').val(data.type);
                $('[name="desc_lang"]').val(data.desc);
                $('[name="application_lang"]').val(data.application);
                $('[name="property_lang"]').val(data.property);
                $('[name="dilution_lang"]').val(data.dilution);
                $('[name="howToUse_lang"]').val(data.how_to_use);
                $('[name="firstAid_lang"]').val(data.first_aid);
            }else{
                $('[name="productLangId"]').val(null);
                $('[name="type_lang"]').val(null);
                $('[name="desc_lang"]').val(null);
                $('[name="application_lang"]').val(null);
                $('[name="property_lang"]').val(null);
                $('[name="dilution_lang"]').val(null);
                $('[name="howToUse_lang"]').val(null);
                $('[name="firstAid_lang"]').val(null);
            }
            
//            $('[name="country_lang"]').val(data.country);
            $('#modal_form_lang').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Product Local Language'); // Set title to Bootstrap modal title
            $('[name="productId"]').attr("readOnly", true);

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}
</script>

<?
//    print_r($country);
?>
<input name="country_code" id="country_code" class="form-control" type="hidden" value="<?=$pick_country?>">
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Product Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="<?=base_url()?>Product/save_product" method="post" id="form" class="form-horizontal" enctype="multipart/form-data">
            <input type="hidden" id="csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
            <input name="mode" class="form-control" type="hidden">
            <input name="productId" class="form-control" type="hidden">
            <input name="country" id="country" class="form-control" type="hidden">
            <!--<input name="test_data" id="test_data" class="form-control" type="text">-->
            <div class="modal-body form">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Product No.</label>
                            <div class="col-md-9">
                                <input name="productNo" placeholder="Product No." class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Product Name</label>
                            <div class="col-md-9">
                                <input name="productName" placeholder="Product Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Description</label>
                            <div class="col-md-9">
                                <textarea name="desc" placeholder="Description" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Application</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('category', $category_list, '', 'id="category" name="category" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Product Type</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('subCategory', $sub_category_list, '', 'id="subCategory" name="subCategory" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Sub Product Type</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('subType', $sub_type_list, '', 'id="subType" name="subType" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Package</label>
                            <div class="col-md-9">
                                <input name="kgPackage" placeholder="" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Beverage</label>
                            <div class="col-md-1">
                                <input name = "beverage" value="1" id="beverage" type = "checkbox" class="form-control">
                                <span class="help-block"></span>
                            </div>
                            <label class="control-label col-md-3">Brewery</label>
                            <div class="col-md-1">
                                <input name = "brewery" value="1" id="brewery" type = "checkbox" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Dairy</label>
                            <div class="col-md-1">
                                <input name = "dairy" value="1" id="dairy" type = "checkbox" class="form-control">
                                <span class="help-block"></span>
                            </div>
                            <label class="control-label col-md-3">Food</label>
                            <div class="col-md-1">
                                <input name = "food" value="1" id="food" type = "checkbox" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Seafood</label>
                            <div class="col-md-1">
                                <input name = "seafood" value="1" id="seafood" type = "checkbox" class="form-control">
                                <span class="help-block"></span>
                            </div>
                            <label class="control-label col-md-3">Protein</label>
                            <div class="col-md-1">
                                <input name = "poultry" value="1" id="poultry" type = "checkbox" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Healthcare & Pharma</label> 
                            <div class="col-md-1">
                                <input name = "pharma" value="1" id="pharma" type = "checkbox" class="form-control">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Rating</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('rating', $rating_list, '', 'id="rating" name="rating" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
<!--                        <div class="form-group">
                            <label class="control-label col-md-3">Type</label>
                            <div class="col-md-9">
                                <input name="type" placeholder="Ex: Liquid, etc." class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>-->
                        <input name="application" placeholder="" class="form-control" type="hidden">
<!--                        <div class="form-group">
                            <label class="control-label col-md-3">Application</label>
                            <div class="col-md-9">
                                <textarea name="application" placeholder="Application" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <label class="control-label col-md-3">Property</label>
                            <div class="col-md-9">
                                <textarea name="property" placeholder="Property" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Dilution</label>
                            <div class="col-md-9">
                                <textarea name="dilution" placeholder="Dilution" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">How To Use</label>
                            <div class="col-md-9">
                                <textarea name="howToUse" placeholder="How To Use" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">First Aid</label>
                            <div class="col-md-9">
                                <textarea name="firstAid" placeholder="First Aid" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Image</label>
                            <div class="col-md-9">
                                <input type="file" name="berkas" id="berkas" size="20" class="input_text" accept="image/x-png,image/gif,image/jpeg"/>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btnSave" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_safety" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Product Safety Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <!--<form action="<?=base_url()?>Product/save_product" method="post" id="form_safety" class="form-horizontal" enctype="multipart/form-data">-->
            <div class="modal-body form">
                <form action="#" id="form_safety" class="form-horizontal">
                    <input type="hidden" id="csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <input name="mode_safety" class="form-control" type="hidden">
                    <input name="productId_safety" class="form-control" type="hidden">
                    <div class="form-body">
                        <p>
                            <? 
                                $i = 1;
                                foreach($safety_list as $safety_row):
                            ?>
                            <input name="productSafetyId_<?=$safety_row['safety_id']?>" class="form-control" type="hidden">
                            <input name = "safety_<?=$safety_row['safety_id']?>" value="<?=$safety_row['safety_id']?>" id="safety_<?=$safety_row['safety_id']?>" type = "checkbox" value="<?=$safety_row['safety_id']?>"> <img height="30" src="<?= base_url() ?>assets/uploads/images/<?=$safety_row['image_name']?>"> <?=$safety_row['desc'];?></input><br/><br/>
                            <? 
                                $i++;
                                endforeach; 
                            ?>
                        </p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave_safety" onclick="save_safety()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_ghs" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Product GHS Form</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <!--<form action="<?=base_url()?>Product/save_product" method="post" id="form_safety" class="form-horizontal" enctype="multipart/form-data">-->
            <div class="modal-body form">
                <form action="#" id="form_ghs" class="form-horizontal">
                    <input type="hidden" id="csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <input name="mode_ghs" class="form-control" type="hidden">
                    <input name="productId_ghs" class="form-control" type="hidden">
                    <div class="form-body">
                        <p>
                            <? 
                                $i = 1;
                                foreach($ghs_list as $ghs_row):
                            ?>
                            <input name="productGhsId_<?=$ghs_row['ghs_id']?>" class="form-control" type="hidden">
                            <input name = "ghs_<?=$ghs_row['ghs_id']?>" value="<?=$ghs_row['ghs_id']?>" id="ghs_<?=$ghs_row['ghs_id']?>" type = "checkbox" value="<?=$ghs_row['ghs_id']?>"> <img height="30" src="<?= base_url() ?>assets/uploads/images/<?=$ghs_row['image_name']?>"> <?=$ghs_row['desc'];?></input><br/><br/>
                            <? 
                                $i++;
                                endforeach; 
                            ?>
                        </p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave_ghs" onclick="save_ghs()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_lang" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Product Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <!--<form action="<?=base_url()?>Product/save_product" method="post" id="form_lang" class="form-horizontal" enctype="multipart/form-data">-->
            <div class="modal-body form">
                <form action="#" id="form_lang" class="form-horizontal">
                    <input type="hidden" id="csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <input name="mode_lang" class="form-control" type="hidden">
                    <input name="productId_lang" class="form-control" type="hidden">
                    <input name="country_lang" class="form-control" type="hidden">
                    <input name="productLangId" class="form-control" type="hidden">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Product No.</label>
                            <div class="col-md-9">
                                <input name="productNo_lang" placeholder="Product No." class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Product Name</label>
                            <div class="col-md-9">
                                <input name="productName_lang" placeholder="Product Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Description</label>
                            <div class="col-md-9">
                                <textarea name="desc_lang" placeholder="Description" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Type</label>
                            <div class="col-md-9">
                                <input name="type_lang" placeholder="Ex: Liquid, etc." class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Application</label>
                            <div class="col-md-9">
                                <textarea name="application_lang" placeholder="Application" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Property</label>
                            <div class="col-md-9">
                                <textarea name="property_lang" placeholder="Property" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Dilution</label>
                            <div class="col-md-9">
                                <textarea name="dilution_lang" placeholder="Dilution" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">How To Use</label>
                            <div class="col-md-9">
                                <textarea name="howToUse_lang" placeholder="How To Use" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">First Aid</label>
                            <div class="col-md-9">
                                <textarea name="firstAid_lang" placeholder="First Aid" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave_lang" onclick="save_lang()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            <!--</form>-->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
<?
//    print_r($safety_list);
?>
</body>
</html>