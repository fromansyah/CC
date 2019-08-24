<html>
    <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ecolab Contract App.</title>
    
    <!--<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>-->
    <!--<script src="<?php echo base_url('assets/jquery/jquery-3.0.0.min.js')?>"></script>
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap-3.4.1.min.js')?>"></script>-->
    <!--<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>-->
    <!--<script src="<?php echo base_url('bootstrap-4.3.1/js/bootstrap.min.js')?>">-->
    <!--<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>-->
    <!--<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>-->
    <!--<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>-->
    <!--<script src="<?php echo base_url('js/bootstrap.min.js')?>"></script>-->
    
    <script src="<?php echo base_url('assets/jquery/jquery-3.4.0.min.js')?>"></script>
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap-3.4.1.min.js')?>"></script>
    <!--<script src="<?= base_url() ?>js/utility.js" type="text/javascript"></script>-->
    
    <!--<script src="<?= base_url() ?>bootstrap-4.3.1/js/bootstrap.min.js" type="text/javascript"></script>
    <link href="<?php echo base_url()?>bootstrap-4.3.1/css/bootstrap.min.css" rel="stylesheet">-->
    
    <script src="<?= base_url() ?>assets/bootstrap-4-4.1.1/js/bootstrap.min.js" type="text/javascript"></script>
    <link href="<?php echo base_url()?>assets/bootstrap-4-4.1.1/css/bootstrap.min.css" rel="stylesheet">

    <!--<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">-->
    <!--<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">-->
    <!--<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">-->
    
    <!--<link href="<?php echo base_url()?>bootstrap-4.3.1/css/bootstrap.min.css" rel="stylesheet">-->
    <!--<link href="<?php echo base_url()?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
    <link rel="icon" type="image/gif" href="<?= base_url() ?>images/icon_ecolab.jpg" />
    
    <!--<link href="<?=$this->config->item('base_url');?>css/jquery.alert.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/table.css" />-->
</head>

<body>
<script>
var btn_counter = 0

    $(document).ready(function(e) {
        $(".btn-lang").on("click",function(e){
                        if ($(this).hasClass("btn-outline-secondary")) {
                                btn_counter++
                                if (btn_counter <= 2)
                                        $(this).removeClass("btn-outline-secondary").addClass("btn-secondary")
                                else {
                                        $(".btn-lang").removeClass("btn-secondary").addClass("btn-outline-secondary")
                                        $(this).removeClass("btn-outline-secondary").addClass("btn-secondary")
                                        btn_counter = 1
                                }
                        } else {
                                $(this).removeClass("btn-secondary").addClass("btn-outline-secondary")
                                btn_counter--
                        }
//                        $(".btn-search").trigger("click");
                })
    });
</script>
</body>
    
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #2a87fe;">
    <div class="navbar-header">
        <a href="<?= base_url() ?>"><img src="<?= base_url() ?>images/ecolab_logo.jpg"></a> 
    </div>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto" style="margin-top: 15px;">
        <? echo $this->custom_menu->build_menu_2();?>
    </ul>
    <ul class="nav navbar-nav navbar-right">
        <li>
            <a href="<?= base_url() ?>" class="nav-link active">
                <font color="#264d73">Home</font>
            </a>
        </li>
        <li>
            <a href="<?= base_url() ?>Main_menu/logout"><font color="#264d73">Log Out</font></a>
        </li>
    </ul>
  </div>
</nav>

<!--<div class="container">
    <div id="content">-->
<?= $content ?>
<!--    </div>
</div>-->