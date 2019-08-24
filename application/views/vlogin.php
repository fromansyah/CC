<script type="text/javascript">
$(document).ready(function(){
    var base_url = '<?= base_url() ?>';

    $("#id_user").focus();

    $('#id_user').keypress(function(e){
        if (e.which == 13) {
            $('#password').focus();
        }
    });

    $('#password').keypress(function(e){
        if (e.which == 13) {
            $('#btn_ok').click();
        }
    });

    $('#btn_ok').click(function(){
        var id_user = trim($('#id_user')[0].value);
        var password = trim($('#password')[0].value);

        if (id_user == '') {
            alert('ID. User harus diisi.');
            $('#id_user').focus();
            return;
        }
        if (password == '') {
            alert('Password harus diisi.');
            $('#password').focus();
            return;
        }
        
//        window.location = base_url + 'Welcome/login/'+id_user+'/'+password;

        $.post(base_url + 'Welcome/check_login', 
            {id_user: id_user, password: password},
            function(data) {
                if (data.result == 'true') {
                    window.location = base_url+'Main_menu';
                } else {
                    alert('ID User atau Password yang Anda masukkan salah. ');
                    $('#id_user').select();
                    $('#id_user').focus();
                }
            },
            'json'
        );
        /*$.ajax({
            type: "POST",
            url: base_url + 'Welcome/check_login',
            data: {
                id_user: id_user,
                password: password
            },
            success: function(result)
            {
                alert(result);
                if (result == 'true') {
                    window.location.replace(base_url+'Menu_utama');
                }
                else {
                    alert('ID User atau Password yang Anda masukkan salah. '+base_url);
                    $('#id_user').select();
                    $('#id_user').focus();
                }
            }
        });*/

    });

});
</script>

<fieldset align="center" style="width: 1250px; height: 500px">
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <div align="center">
    <!--<img src="<?= base_url() ?>images/fix_asset_logo.jpg"/>-->
    </div>
    <br/>
    <? // print_r($test);?>
    <br/>
    <?//= base_url() ?>
    <table width="263px" height="225" border="0" align="center" background="<?= base_url() ?>images/login_image_new.jpg">
        <tr>
            <td width="10">

            </td>
            <td>
                <div id="login-box" style="margin-left:10px"><br />
                    <p>
                        <b><font face="verdana" color="grey">Username</font></b> <br />
                        <input type="text" name="id_user" id="id_user" class="input_text"/>
                    </p>
                    <p>
                        <b><font face="verdana" color="grey">Password</font></b> <br />
                        <input type="password" name="password" id="password" class="input_text"/>
                    </p>
                    <p>
                        <input type="button" value="LogIn" id="btn_ok" align="middle" class="button_login"/>
                    </p>
            </div>
            </td>
        </tr>
    </table>

    <br/>

    <?
    if ($this->session->flashdata('err_message')):
    ?>
    <table align="center" id="error-box">
        <tr>
            <td>
            <center>
            <?= $this->session->flashdata('err_message') ?>
            </center>
            </td>
        </tr>
    </table>
    <?
    endif;
    ?>
</fieldset>

<?
//for ($i = 0; $i < 5; $i++) {
//    echo "<br/>";
//}
?>

<!--<div align="center">
    <img src="<?= base_url() ?>images/judul.jpg"/>
</div>
<br/>

<table width="266px" height="225" border="0" align="center" background="<?= base_url() ?>images/bg_login.jpg">
  <tr>
    <td>
      <div id="login-box" style="margin-left:10px">
        <br />
        
        <p>
              Username<br />
              <input type="text" name="id_user" id="id_user"/>
        </p>
        <p>
              Password<br />
              <input type="password" name="password" id="password"/>
	</p>
        <p>
            <input type="button" value="OK" id="btn_ok" align="middle"/>
        </p>
      
      </div>
    </td>
  </tr>
</table>

<br/>-->

<?
//if ($this->session->flashdata('err_message')):
?>
<!--<table align="center" id="error-box">
    <tr>
        <td>
        <center>
        <?= $this->session->flashdata('err_message') ?>
        </center>
        </td>
    </tr>
</table>-->
<?
//endif;
?>