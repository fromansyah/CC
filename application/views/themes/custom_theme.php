<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Ecolab Product Catalog</title>

    <script src="<?php echo base_url('assets/jquery/jquery-3.0.0.min.js')?>"></script>
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
    
    <script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>

    <link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
    
    <link rel="icon" type="image/gif" href="<?= base_url() ?>images/icon_ecolab.jpg" />
<title>
    <?
    $title_ = "";
    if (isset($title)) {
        $title_ = " - " . $title;
    }
    if ($this->config->item('nama_aplikasi')):
        echo $this->config->item('nama_aplikasi').$title_;
    endif;
    ?>
</title>
<script>  
    var q = ""
    var s = ""
    var lang = ""
    var btn_counter = 0
    var selected_counter = 0
    var selected_products = []
    var selected_products_detailed = []
    var _base_url = '<?= base_url() ?>';
    var cart = <?=$this->cart->contents()?>;
    var existing_country = '<?=$this->session->userdata("country")?>';
    var flag = '<?=$this->session->userdata("flag")?>';
    
    $(document).ready(function(){
          
        var post_url_2 = _base_url + 'Product/ajax_list_selected_product';
        $.ajax({
            type: "POST",
            url: post_url_2,
            success: function(selected_products)
            {
                $('#tb').empty();
                var $i = 1;
                var $row_count = 0;
                var data = eval('(' + selected_products + ')')
                $.each(data,function(row,note)
                {
                    $row_count++;               
                    $i++;
                });
                
                if($i > 1){
                    $("#buttonGenerated").removeAttr("disabled");
                }
                //alert($row_count);
                if($row_count > 0){
                    //$("#cart_icon").title = $row_count;
                    //document.getElementById('cart_icon').setAttribute('title', $row_count);
                    //$('[data-toggle="tooltip"]').tooltip({placement: 'top',trigger: 'manual'}).tooltip('show');
                    document.getElementById('cart_icon').src= _base_url + 'images/list_icon_2.png';
                }
            }
        });
    });

    function logout(){
        //alert("inside onclick");
        window.location = _base_url+"Main_menu/logout";
    }
</script>
</head>
<body>
        <div id="alert-box" style="background-color: #2a87fe; height: 95px; padding: 5px;">
            
       <table width="100%" border="0">
            <tr height="40">
                <td width="100" valign="bottom" rowspan="2">
                <a href="<?= base_url() ?>"><img height="60" src="<?= base_url() ?>images/ecolab_logo_prod_selection.jpg"></a>
                </td>
                <td valign="top" align="right">
                    <font color="white">
                        Hi <?= $this->session->userdata("fullname") ?> | 
                    <?$img_url = base_url().'assets/uploads/images/'.$this->session->userdata("flag");?> 
                    <? if($this->session->userdata("country") != 'null'):?>
                    <img id="flag" src="<?=$img_url?>" height="20"/>&nbsp;&nbsp;
                    <? endif;?>
                    </font>
                    <button type="button" class="btn btn-default btn-sm" style="height:30px;" onclick="logout()">
                        <span class="glyphicon glyphicon-log-out"></span> <font color="grey">Log out</font>
                    </button>
                    <!--<b><a href="<?= base_url() ?>index.php/main/logout"><button class="button_logout">Log Out</button></a></b>-->
                </td>
            </tr>
           <tr>
               <td height="40" valign="top">
                   <font color="white">
                        <? echo $this->custom_menu->build_menu_2();?> 
                    </font>
                   <div style="text-align: right;">
                       <? // echo $this->session->userdata("flag");?> 
                       
                       <a href="<?= base_url() ?>Product/wallchart"><img id="cart_icon" data-toggle="tooltip" title="" height="25" src="<?= base_url() ?>images/list_icon.png"></a>&nbsp;&nbsp;&nbsp;
                   </div>
               </td>
           </tr>
        </table>
        </div>
    <br/>
<div>
<?= $content ?>
</div>
</body>
</html>