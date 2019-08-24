<html>
    <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ecolab Contract App.</title>
    
    <!--<script src="<?= base_url() ?>js/utility.js" type="text/javascript"></script>-->
    <script src="<?php echo base_url('assets/jquery/jquery-3.4.0.min.js')?>"></script>
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap-3.4.1.min.js')?>"></script>
    <script src="<?php echo base_url('assets/datatables-4/datatables.min.js')?>"></script>
    <script src="<?php echo base_url('assets/datatables-4/dataTables.bootstrap.min.js')?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
    
    <link href="<?php echo base_url('assets/datatables-4/datatables.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/bootstrap-4-4.1.1/css/bootstrap.min.css')?>" rel="stylesheet">

    <link rel="icon" type="image/gif" href="<?= base_url() ?>images/icon_ecolab.jpg" />
</head>

<body>
    
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #2a87fe;">
    <div class="navbar-header">
        <a href="<?= base_url() ?>"><img src="<?= base_url() ?>images/ecolab_logo.jpg"></a> 
    </div>
    <!--<a class="navbar-brand" href="<?= base_url() ?>"> Wall Chart Generator</a>-->

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto"  style="margin-top: 15px;">
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

<div class="container">
    <div id="content">
        <?= $content ?>
    </div>
</div>
</body>
</html>