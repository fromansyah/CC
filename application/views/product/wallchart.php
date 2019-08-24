<html>
    <head>
<!--        <script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
        <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
        <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
        <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
        <script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8/dist/sweetalert2.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@8/dist/sweetalert2.min.css" id="theme-styles">
        
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>-->
        <!--<style>
          .label {
            color: white;
            padding: 2px;
          }

          .success {background-color: #4CAF50;} /* Green */
          .info {background-color: #2196F3;} /* Blue */
          .warning {background-color: #ff9800;} /* Orange */
          .danger {background-color: #f44336;} /* Red */
          .other {background-color: #e7e7e7; color: black;} /* Gray */
        </style>-->
        <style>
            table.roundedCorners { 
                border: 1px solid #b3cce6;
                border-radius: 13px; 
                border-spacing: 0;
                }
              table.roundedCorners td, 
              table.roundedCorners th { 
                border-bottom: 0px solid #b3cce6;
                padding: 10px; 
                }
              table.roundedCorners tr:last-child > td {
                border-bottom: none;
              }

            put[type=checkbox] + label {
                display: block;
                margin: 0.2em;
                cursor: pointer;
                padding: 0.2em;
              }

              input[type=checkbox] {
                display: none;
              }

              input[type=checkbox] + label:before {
                content: "\2714";
                border: 0.1em solid #000;
                border-radius: 0.2em;
                display: inline-block;
                width: 1.3em;
                height: 1.3em;
                padding-left: 0.2em;
                padding-bottom: 0.2em;
                margin-right: 0.2em;
                vertical-align: bottom;
                color: transparent;
                transition: .2s;
              }

              input[type=checkbox] + label:active:before {
                transform: scale(0);
              }

              input[type=checkbox]:checked + label:before {
                background-color: #268afa;
                border-color: #268afa;
                color: #fff;
              }

              input[type=checkbox]:disabled + label:before {
                transform: scale(1);
                border-color: #aaa;
              }

              input[type=checkbox]:checked:disabled + label:before {
                transform: scale(1);
                background-color: #bfb;
                border-color: #bfb;
              }
            
            .checkboxes label {
                display: inline-block;
                padding-right: 10px;
                white-space: nowrap;
              }
              .checkboxes input {
                vertical-align: middle;
              }
              .checkboxes label span {
                vertical-align: middle;
              }
            
            .navigation {
                padding: 0;
                margin: 0;
                border: 0;
                line-height: 0.5;
              }

              .navigation ul,
              .navigation ul li,
              .navigation ul ul {
                list-style: none;
                margin: 0;
                padding: 0;
              }

              .navigation ul {
                position: relative;
                z-index: 500;
                float: left;
              }

              .navigation ul li {
                float: left;
                min-height: 0.05em;
                line-height: 1em;
                vertical-align: middle;
                position: relative;
              }

              .navigation ul li.hover,
              .navigation ul li:hover {
                position: relative;
                z-index: 510;
                cursor: default;
              }

              .navigation ul ul {
                visibility: hidden;
                position: absolute;
                top: 100%;
                left: 0px;
                z-index: 520;
                width: 100%;
              }

              .navigation ul ul li { float: none; }

              .navigation ul ul ul {
                top: 0;
                right: 0;
              }

              .navigation ul li:hover > ul { visibility: visible; }

              .navigation ul ul {
                top: 0;
                left: 99%;
              }

              .navigation ul li { float: none; }

              .navigation ul ul { margin-top: 0.05em; }

              .navigation {
                width: 14em;
                background: #f2f2f2;
                /*font-family: 'roboto', Tahoma, Arial, sans-serif;*/
                zoom: 1;
              }

              .navigation:before {
                content: '';
                display: block;
              }

              .navigation:after {
                content: '';
                display: table;
                clear: both;
              }

              .navigation a {
                display: block;
                padding: 1em 1.3em;
                color: #808080;
                text-decoration: none;
              }

              .navigation > ul { width: 14em; }

              .navigation ul ul { width: 14em; }

              .navigation > ul > li > a {
                border-right: 0.3em solid #f9f9f9;
                color: #808080;
              }

              .navigation > ul > li > a:hover { color: #808080; }

              .navigation > ul > li a:hover,
              .navigation > ul > li:hover a { background: #f9f9f9; }

              .navigation li { position: relative; }

              .navigation ul li.has-sub > a:after {
                content: '»';
                position: absolute;
                right: 1em;
              }

              .navigation ul ul li.first {
                -webkit-border-radius: 0 3px 0 0;
                -moz-border-radius: 0 3px 0 0;
                border-radius: 0 3px 0 0;
              }

              .navigation ul ul li.last {
                -webkit-border-radius: 0 0 3px 0;
                -moz-border-radius: 0 0 3px 0;
                border-radius: 0 0 3px 0;
                border-bottom: 0;
              }

              .navigation ul ul {
                -webkit-border-radius: 0 3px 3px 0;
                -moz-border-radius: 0 3px 3px 0;
                border-radius: 0 3px 3px 0;
              }

              .navigation ul ul { border: 1px solid #ffffff; }

              .navigation ul ul a { color: #808080; }

              .navigation ul ul a:hover { color: #808080; }

              .navigation ul ul li { border-bottom: 1px solid #ffffff; }

              .navigation ul ul li:hover > a {
                background: #ffffff;
                color: #808080;
              }

              .navigation.align-right > ul > li > a {
                border-left: 0.3em solid #f9f9f9;
                border-right: none;
              }

              .navigation.align-right { float: right; }

              .navigation.align-right li { text-align: right; }

              .navigation.align-right ul li.has-sub > a:before {
                content: '+';
                position: absolute;
                top: 50%;
                left: 15px;
                margin-top: -6px;
              }

              .navigation.align-right ul li.has-sub > a:after { content: none; }

              .navigation.align-right ul ul {
                visibility: hidden;
                position: absolute;
                top: 0;
                left: -100%;
                z-index: 598;
                width: 100%;
              }

              .navigation.align-right ul ul li.first {
                -webkit-border-radius: 3px 0 0 0;
                -moz-border-radius: 3px 0 0 0;
                border-radius: 3px 0 0 0;
              }

              .navigation.align-right ul ul li.last {
                -webkit-border-radius: 0 0 0 3px;
                -moz-border-radius: 0 0 0 3px;
                border-radius: 0 0 0 3px;
              }

              .navigation.align-right ul ul {
                -webkit-border-radius: 3px 0 0 3px;
                -moz-border-radius: 3px 0 0 3px;
                border-radius: 3px 0 0 3px;
              }
              
              .label {
                color: white;
                padding: 3px;
              }

              .success {background-color: #4CAF50;} /* Green */
              .info {background-color: #2196F3;} /* Blue */
              .warning {background-color: #ff9800;} /* Orange */
              .danger {background-color: #f44336;} /* Red */
              .other {background-color: #e7e7e7; color: #8c8c8c;} /* Gray */
        </style>
        
        <?
            $CI =& get_instance();
            $CI->load->library('encrypt');
        ?>
        
        <script>
            var _base_url = '<?= base_url() ?>';
            var country = '<?=$this->session->userdata("country")?>';
            var save_method; //for save method string
            var table;
            var sales_row = 1;
            
            var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
                csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
             
            var dataJson = { [csrfName]: csrfHash };
            
            
            $(document).ready(function() {
                var post_url = _base_url + 'Product/ajax_list_selected_product';
                $.ajax({
                    type: "POST",
                    url: post_url,
                    data: dataJson,
                    success: function(selected_products)
                    {
                        /*$('#tb').empty();
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
                                            <td>'+$product_no+'</td>\n\
                                            <td><input type="text" class="form-control" name="dilution_'+$product_id+'" id="dilution_'+$product_id+'" placeholder="Product Dilution"></td>\n\
                                            <td><button type="button" onclick="delete_row(\''+$rowid+'\')" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></button></td>\n\
                                        </tr>');                    
                            $i++;
                        });*/
                    }
                });
            });
            
            function add_sales(){
                //alert('Add Sales');
                sales_row++;
                $('#sales_tb').append('\
                                        <tr height="40">\n\
                                            <td></td>\n\
                                            <td><input type="text" class="form-control" name="sales-name-'+sales_row+'" id="sales-name-'+sales_row+'" placeholder="Salesperson '+sales_row+' Full Name"></td>\n\
                                            <td style="padding-left: 10px;"><input type="text" class="form-control" name="sales-tel-'+sales_row+'" id="sales-tel-'+sales_row+'" placeholder="Salesperson '+sales_row+' phone no."></td>\n\
                                            <td></td>\n\
                                        </tr>');   
                                                    
               $('#sales_count').val(sales_row);
            }
            
            function product_list(){
                window.location = _base_url+"Product/new_product_lists/"+country+"/0";
            }
            
            function delete_row(row_id){
//                if(row_id == 'all'){
//                    $("#buttonGenerated").attr("disabled", true);
//                }
                var post_url = _base_url + 'Product/ajax_delete/' + row_id;
                $.ajax({
                    type: "POST",
                    url: post_url,
                    dataType: "JSON",
                    data: dataJson,
                    success: function(data)
                    {
                        if(data.status) //if success close modal and reload ajax table
                        {
                            //window.location = _base_url+"Product/wallchart";
                            $('#table_body').fadeOut('slow').empty();
                            var post_url_2 = _base_url + 'Product/ajax_list_selected_product_wallchart';
                            
                            $.ajax({
                                type: "POST",
                                url: post_url_2,
                                dataType: "JSON",
                                data: dataJson,
                                success: function(selected_products)
                                {
                                    //alert(JSON.stringify(selected_products.row_id, null, 4));
                                    //console.log(JSON.stringify(selected_products.products, null, 4));
                                    //console.log(JSON.stringify(selected_products.row_id, null, 4));
                                    var $i = 1;
                                    
                                    for (var key in selected_products.products) {
                                        //console.log(key, selected_products.products[key]);
                                        //alert(selected_products.row_id[selected_products.products[key]['product_id']]);
                                        var safety_string = '';
                                        var safety = selected_products.products[key]['safety_icon'];
                                        
                                        if(safety != null && safety != ''){
                                            var _safety_icon = safety.split(",");
                                            safety_string = '<img height="20" src="'+_base_url+'assets/uploads/images/'+_safety_icon[0]+'"><br/>';
                                            for (var a = 1; a < _safety_icon.length; a++) {
                                                safety_string += '<img height="20" src="'+_base_url+'assets/uploads/images/'+_safety_icon[a]+'"><br/>';
                                            }
                                        }
                                        
                                        var ghs_string = '';
                                        var ghs = selected_products.products[key]['ghs_icon'];
                                        
                                        if(ghs != null && ghs != ''){
                                            var _ghs_icon = ghs.split(",");
                                        
                                            ghs_string = '<img height="20" src="'+_base_url+'assets/uploads/images/'+_ghs_icon[0]+'"><br/>';
                                            for (var b = 1; b < _ghs_icon.length; b++) {
                                                ghs_string += '<img height="20" src="'+_base_url+'assets/uploads/images/'+_ghs_icon[b]+'"><br/>';
                                            }
                                        }
                                        
                                        $('#table_body').append('\
                                            <tr>\n\
                                                <td>'+$i+'</td>\n\
                                                <td><img height="60" width="60" class="img-responsive" src="'+_base_url+'assets/images/'+selected_products.products[key]['image_name']+'"/></td>\n\
                                                <td>'+selected_products.products[key]['product_name']+' - '+selected_products.products[key]['kg_package']+'</td>\n\
                                                <td>'+selected_products.products[key]['category_name']+'</td>\n\
                                                <td>'+selected_products.products[key]['sub_category_name']+'</td>\n\
                                                <td><input style="width: 200px;" type="text" class="form-control" name="dilution_'+selected_products.products[key]['product_id']+'" id="dilution_'+selected_products.products[key]['product_id']+'" placeholder="Product Dilution"></td>\n\
                                                <td align="center">\n\
                                                    '+ghs_string+'\n\
                                                </td>\n\
                                                <td align="center">\n\
                                                    '+safety_string+'\n\
                                                </td>\n\
                                                <td>Refer to Safety Data Sheet Section 4</td>\n\
                                                <td><button type="button" onclick="delete_row(\''+selected_products.row_id[selected_products.products[key]['product_id']]+'\')" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></button></td>\n\
                                            </tr>');   
                                                                                
                                        $i++;
                                    }
                                },
                                error: function (jqXHR, textStatus, errorThrown){
                                    location.reload(); 
                                }
                            });
                            $('#table_body').html(data).fadeIn("slow");
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

        </script>
    </head>
    <body>

        <div class="row-1">
            <div class="col-lg-12">
                <? //echo $this->session->userdata("country");?>
                <div id="site_map" style="padding-bottom: 10px;"><small><b><font color="#c2c2a3"><a href="<?= base_url() ?>Product/new_product_lists/
<?=str_replace(array('=','+','/'), array('-','_','~'), $CI->encrypt->encode($this->session->userdata('country')))?>/<?= str_replace(array('=','+','/'), array('-','_','~'), $CI->encrypt->encode('0'))?>">Product Selection</a> <img src="<?= base_url() ?>/images/cal_forward.gif"> <a href="<?= base_url() ?>Product/wallchart">My Wall Chart </a></font></b></small></div>
                <form action="<?=base_url()?>Product/save_download" method="post" enctype="multipart/form-data" target="_blank">
                <input type="hidden" id="csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                <table width="100%">
                    <tr>
                        <td>
                            <?$img_url = base_url().'assets/uploads/images/'.$flag;?>
                            <!--<img id="flag" src="<?=$img_url?>" height="30" width="40">-->
                            <font style="font-size: large;">My Wall Chart</font>
                            <hr/>
                            <table width="100%">
                                    <tr height="50">
                                        <td width="10%">
                                            Customer Name : 
                                        </td>
                                        <td width="20%">
                                            <?php echo form_dropdown('customer', $customer, '', 'id="customer" name="customer" placeholder="customer" class="form-control" type="text"'); ?>
                                        </td>
                                        <td width="30%">
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    <tr height="50">
                                        <td width="10%">
                                            Language : 
                                        </td>
                                        <td width="20%">
                                            <?php echo form_dropdown('language', $language_list, '', 'id="language" name="language" placeholder="Language" class="form-control" type="text"'); ?>
                                        </td>
                                        <td width="30%">
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                <tbody id="sales_tb">
                                    <tr height="40">
                                        <td>
                                            <input type="hidden" class="form-control" name="sales_count" id="sales_count" value="1">
                                            Contact Name : 
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="sales-name-1" id="sales-name-1" placeholder="Salesperson 1 Full Name">
                                        </td>
                                        <td style="padding-left: 10px;">
                                            <input type="text" class="form-control" name="sales-tel-1" id="sales-tel-1" placeholder="Salesperson 1 phone no.">
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr height="50">
                                        <td colspan="4"><button type="button" onclick="add_sales()" class="btn btn-sm btn-success"><i class="glyphicon glyphicon-plus"></i> Add Contact</button></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>
                </table>
                <br/>
                <table class="table" id="table_product">
                    <thead>
                        <tr>
                            <td style="background-color: #2a87fe; color: white;"><b>No</b></td>
                            <td style="background-color: #2a87fe; color: white;"><b>Label Image</b></td>
                            <td style="background-color: #2a87fe; color: white;"><b>Name</b></td>
                            <td style="background-color: #2a87fe; color: white;"><b>Application</b></td>
                            <td style="background-color: #2a87fe; color: white;"><b>Type</b></td>
                            <td style="background-color: #2a87fe; color: white;"><b>Dilution(% v/v or w/v)</b></td>
                            <td style="background-color: #2a87fe; color: white;"><b>GHS</b></td>
                            <td style="background-color: #2a87fe; color: white;"><b>PPE Required</b></td>
                            <td style="background-color: #2a87fe; color: white;"><b>First Aid</b></td>
                            <td style="background-color: #2a87fe; color: white;"><b></b></td>
                        </tr>
                    </thead>
                    <tbody name="table_body" id="table_body">
                        <? $i=1; foreach ($product_result as $row):?>
                        <tr>
                            <td><?=$i?></td>
                            <td><img height="60" width="60" class="img-responsive" src="<?= base_url()?>assets/images/<?=$row->image_name?>"/></td>
                            <td><?=$row->product_name?> - <?=$row->kg_package?></td>
                            <td><?=$row->category_name?></td>
                            <td><?=$row->sub_category_name?></td>
                            <td><input style="width: 200px;" type="text" class="form-control" name="dilution_<?=$row->product_id?>" id="dilution_<?=$row->product_id?>" placeholder="Product Dilution"></td>
                            <td align="center">
                                <?
                                    if($row->ghs_icon != null):
                                        $k=1;
                                    $ghs_icon_ex = explode(',', $row->ghs_icon);
                                    foreach($ghs_icon_ex as $row_icon):
                                ?>
                                <img height="20" src="<?= base_url() ?>assets/uploads/images/<?=$row_icon?>"><br/>
                                <? if($k%2 == 0):?>
                                <!--<br/>-->
                                <? endif; $k++; endforeach; endif;?>
                            </td>
                            <td align="center">
                                <?
                                    if($row->safety_icon != null):
                                        $j=1;
                                    $icon_ex = explode(',', $row->safety_icon);
                                    foreach($icon_ex as $icon):
                                ?>
                                
                                <img height="20" src="<?= base_url() ?>assets/uploads/images/<?=$icon?>"><br/>
                                <? if($j%2 == 0):?>
                                <!--<br/>-->
                                <?  endif;
                                    $j++; 
                                    endforeach; 
                                    endif;?>
                            </td>
                            <td>Refer to Safety Data Sheet Section 4</td>
                            <td><button type="button" onclick="delete_row('<?=$row_id[$row->product_id]?>')" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></button></td>
                        </tr>
                        <? $i++; endforeach;?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="10" align="right">
                                <button type="button" onclick="product_list()" class="btn btn-success"><i class="glyphicon glyphicon-plus"></i> Add Product</button> &nbsp;&nbsp;
                                <button type="button" onclick="delete_row('all')" class="btn btn-danger"><i class="glyphicon glyphicon-plus"></i> Delete All</button> &nbsp;&nbsp;
                                <button type="submit" id="buttonGenerated" class="btn btn-primary"><img src="<?= base_url() ?>images/pdf.png" width=22 height=22>&nbsp;Generate</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                </form>
                <? //print_r($product_result); echo '<br/><br/>';?>
                <? //print_r($cart);?>
            </div>
        </div>
    </body>
</html>
