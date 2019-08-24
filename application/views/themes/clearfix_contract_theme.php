<html>
    <head> 
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ecolab Contract App.</title>
    
    <!--<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
    <script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>-->
    <!--<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
    <script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
    <script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>-->
    <!--<script src="<?php echo base_url('js/bootstrap.min.js')?>"></script>-->
    
    <!--<script src="<?= base_url() ?>js/utility.js" type="text/javascript"></script>-->
    <!--<link href="<?php echo base_url()?>bootstrap-4.3.1/css/bootstrap.min.css" rel="stylesheet">-->
    

    <!--<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">-->
    <!--<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">-->
    <!--<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">-->
    <!--<link href="<?php echo base_url()?>bootstrap-4.3.1/css/bootstrap.min.css" rel="stylesheet">-->
    <!--<link href="<?php echo base_url('assets/Bootstrap-4-4.1.1/css/bootstrap.min.css')?>" rel="stylesheet">-->
    <!--<link href="<?php echo base_url('assets/DataTabales-1.10.18/css/dataTables.bootstrap.min.css')?>" rel="stylesheet">-->
    
    <link rel="icon" type="image/gif" href="<?= base_url() ?>images/icon_ecolab.jpg" />
    
    <script>
        $(document).ready(function () {
                $('label.tree-toggler').click(function () {
                        $(this).parent().children('ul.tree').toggle(300);
                });
        });
    </script>
</head>
<?
    $CI =& get_instance();
    $CI->load->library('encrypt');
?>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #2a87fe;">
        <div class="navbar-header">
            <a href="<?= base_url() ?>index.php/Welcome"><img src="<?= base_url() ?>images/ecolab_logo.jpg"></a>
        </div>
        <!--<a class="navbar-brand" href="<?= base_url() ?>"> Wall Chart Generator</a>-->

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto" style="margin-top: 15px;">
                <? echo $this->custom_menu->build_menu_2();?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="<?= base_url() ?>index.php/Welcome" class="nav-link active">
                        <font color="#264d73">Home</font>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url() ?>index.php/Welcome/logout"><font color="#264d73">Log Out</font></a>
                </li>
            </ul>
      </div>
    </nav>
   
    <div class="container-fluid" style="margin-left: 10px;">
        <div class="row">
            <div class="col-lg-2" style="">
                <div class="row clearfix" style="width:200px; padding: 8px 0;">
                    <div class="list-group" style="overflow-y: scroll; overflow-x: hidden; height: 1100px;">
                        <ul class="nav nav-list list-group">
                            <?php foreach ($company as $row):?>
                                <li><label class="tree-toggler nav-header"><strong><?= $row['company_name']; ?></strong></label>
                                    <ul class="nav nav-list tree list-group">
                                        <?php foreach($branch[$row['company_id']] as $branch_row): ?>
                                        <li><a href="#" onclick="division_contract('<?php echo $branch_row['division_id'] ?>','<?php echo $branch_row['division_name'] ?>', '<?php echo $row['company_id']?>', '<?=str_replace(array('=','+','/'), array('-','_','~'), $CI->encrypt->encode($branch_row['division_id']))?>')" class="list-group-item"><?=$branch_row['division_name']?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                                <li class="divider"></li>
                            <?php endforeach;?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-10" style="background-color: ;">
                <?= $content ?>
            </div>
        </div>
    </div>
</body>
</html>
        