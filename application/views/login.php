<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="<?= base_url() ?>css/backoff.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url() ?>js/utility.js" type="text/javascript"></script>


<link href="<?= base_url() ?>css/datePicker.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url() ?>js/jquery.datePicker.js" type="text/javascript"></script>

<link href="<?= base_url() ?>css/tabbed_new.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url() ?>js/jquery-1.8.0.min.js" type="text/javascript"></script>

<link href="<?= base_url() ?>css/style.css" rel="stylesheet" type="text/css" />

<link rel="icon" type="image/gif" href="<?= base_url() ?>images/logo_application.gif" />
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
</head>
<body>
    
    <?
    if ($this->session->userdata('username')):
    ?>
        <div id="alert-box">
        <table>
            <td width="100">
                &nbsp;<img border="0" src="<?= base_url() ?>images/new_logo.jpg"/>
            </td>
            <!--
            <td><input type="button" value="X" onclick="close_alert()"></td>
            -->
            <td valign="bottom">
                <b><font color="#00688B">ID. User: <?= $this->session->userdata("username") ?></font></b>
            </td>
            <td valign="bottom">
                <b><font color="#00688B">| Grup: <?= $this->session->userdata("user_group") ?> | </font></b>
            </td>
            <td valign="bottom">
                <b><a style="text-decoration:none" href="<?= base_url() ?>index.php/main/logout"><button class="button_logout">Log Out</button></a></b>
            </td>
        </table>
        </div>
    <?
    endif;
    ?>

</html>
<div id="content">
    <?= $content ?>
</div>

<br/>
<br/>
<br/>

<?
    if ($this->session->userdata('username')):
      $this->load->view('vfooter');
    endif;
?>

</body>
</html>