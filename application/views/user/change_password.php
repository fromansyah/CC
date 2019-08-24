<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>

<script type="text/javascript">

$_base_url = '<?= base_url() ?>';

var $old_password;
var $password;
var $confirm_password;

$(document).ready(function(){

  $old_password = $('#old_password')[0];
  $password = $('#password')[0];
  $confirm_password = $('#confirm_password')[0];

  $old_password.focus();

  $('#btn_simpan').click(function(){
    if ($.trim($old_password.value) == '') {
      alert('Old password can not empty.');
      $old_password.focus();
      return false;
    }
    
    if ($.trim($password.value) == '') {
      alert('New password can not empty.');
      $password.focus();
      return false;
    }
    
    if ($.trim($password.value).toUpperCase() == 'PASSWORD' || $.trim($password.value) == 'passw0rd' || $.trim($password.value) == 'Passw0rd') {
      alert('New password is to easy.');
      $('#password').val('');
       $('#confirm_password').val('');
      $password.focus();
      return false;
    }
    
    if ($.trim($password.value).length < 6 ) {
      alert('New password is to short.');
      $('#password').val('');
       $('#confirm_password').val('');
      $password.focus();
      return false;
    }
    var regex = /\S*(\S*([a-zA-Z]\S*[0-9])|([0-9]\S*[a-zA-Z]))\S*/i;
    if(!regex.test($.trim($password.value))){
      alert('New password must containt letter and number.');
      $('#password').val('');
      $('#confirm_password').val('');
      $password.focus();
      return false;
    }
    
    if ($.trim($confirm_password.value) == '') {
      alert('Confirm new password!');
      $confirm_password.focus();
      return false;
    }
    
    if($.trim($password.value) != $.trim($confirm_password.value)){
       alert('Your password and confirmation password do not match.');
       $('#password').val('');
       $('#confirm_password').val('');
       $password.focus();
       return false; 
    }


      $.post(
        $_base_url + 'index.php/users/change_password_ajax',
        {
         old_password: $old_password.value,
         password: $password.value
        },
        function(data) {
          if (data.result == 'false') {
            serr = 'Fail to change password.';
            try {
              serr = serr + ' ' + data.keterangan;
            } catch(e) {}
            alert(serr);
          } else {
            alert('Success to change password.');
            window.location = $_base_url + 'index.php/menu_utama';
          }
        },
        'json'
      );
    
  });

  $('#btn_batal').click(function(){
    window.location = $_base_url ;
  });
});

</script>

<script type="text/javascript">
function cancel(){
    window.location = $_base_url ;
}

function save(){
    var url;
    
    url = "<?php echo site_url('User/ajax_change_password')?>";
    
    var $oldPassword = document.getElementById("oldPassword").value;
    var $newPassword = document.getElementById("newPassword").value;
    var $confirmPassword = document.getElementById("confirmPassword").value;
    
    if ($.trim($oldPassword) == '') {
      alert('Old password can not empty.');
      return false;
    }
    
    if ($.trim($newPassword) == '') {
      alert('New password can not empty.');
      return false;
    }
    
    if ($.trim($newPassword).toUpperCase() == 'PASSWORD' || $.trim($newPassword) == 'passw0rd' || $.trim($newPassword) == 'Passw0rd') {
      alert('New password is to easy.');
      $('#password').val('');
       $('#confirm_password').val('');
      return false;
    }
    
    if ($.trim($newPassword).length < 6 ) {
      alert('New password is to short.');
      return false;
    }
    var regex = /\S*(\S*([a-zA-Z]\S*[0-9])|([0-9]\S*[a-zA-Z]))\S*/i;
    if(!regex.test($.trim($newPassword))){
      alert('New password must containt letter and number.');
      return false;
    }
    
    if ($.trim($confirmPassword) == '') {
      alert('Confirm new password!');
      return false;
    }
    
    if($.trim($newPassword) != $.trim($confirmPassword)){
       alert('Your password and confirmation password do not match.');
       return false; 
    }
    
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                alert('Your password has been changed.');
                window.location = $_base_url;
            }else{
                serr = 'Fail to save data.';
                try {
                  serr = serr + ' ' + data.error;
                } catch(e) {}
                
                alert(serr);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
        }
    });

//
//
//      $.post(
//        $_base_url + 'index.php/users/change_password_ajax',
//        {
//         old_password: $old_password.value,
//         password: $password.value
//        },
//        function(data) {
//          if (data.result == 'false') {
//            serr = 'Fail to change password.';
//            try {
//              serr = serr + ' ' + data.keterangan;
//            } catch(e) {}
//            alert(serr);
//          } else {
//            alert('Success to change password.');
//            window.location = $_base_url + 'index.php/menu_utama';
//          }
//        },
//        'json'
//      );
}
</script>
<!--<form action="#" id="form" class="form-horizontal">
    <font size="5">Change Password</font>
    <p/>
    <p>
    Old Password <br/>
    <input type="password" name="old_password" id="old_password" height="4" size="50" class="input_text"/>
    </p>

    <p>
    New Password <br/>
    <input type="password" name="password" id="password" height="4" size="50" class="input_text"/>
    </p>

    <p>
    Confirm New Password <br/>
    <input type="password" name="confirm_password" id="confirm_password" height="4" size="50" class="input_text"/>
    </p>

    <p>
        <font color="red">Note: New password must be at least 6 characters and contain a number.
        </font>
    </p>
    <br/>
</form>-->
<font size="5">Change Password</font>
<p/>
<form action="#" id="form" class="form-horizontal">
    <div class="form-body">
        <div class="form-group">
            <label class="control-label col-md-3">Old Password</label>
            <div class="col-md-9">
                <input id="oldPassword" name="oldPassword" placeholder="Old Password" class="form-control" type="password">
                <span class="help-block"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">New Password</label>
            <div class="col-md-9">
                <input id="newPassword" name="newPassword" placeholder="New Password" class="form-control" type="password">
                <span class="help-block"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Confirm Password</label>
            <div class="col-md-9">
                <input id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" class="form-control" type="password">
                <span class="help-block"></span>
            </div>
        </div>
    </div>
</form>


            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" onclick="cancel()" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
<!--    <p>
        <input type="button" value="Change Password" onclick="save()" id="btn_simpan" class="button_save"/>
        <input type="button" value="Cancel" onclick="cancel()" id="btn_batal" class="button_cancel"/>
    </p>-->

<table height="520" width="1300"></table>