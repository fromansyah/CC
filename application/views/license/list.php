<!DOCTYPE html>

<script src="<?php echo base_url('assets/jquery/jquery-3.4.0.min.js')?>"></script>

<!--<script src="<?php echo base_url('assets/bootstrap-4-4.1.1/js/bootstrap.min.js')?>"></script>-->

<script src="<?php echo base_url('assets/bootstrap/js/bootstrap-3.4.1.min.js')?>"></script>

<script src="<?php echo base_url('assets/datatables-4/datatables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables-4/dataTables.bootstrap.min.js')?>"></script>
<!--<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>-->

<!--<link href="<?php echo base_url('assets/datatables-4/datatables.css')?>" rel="stylesheet">-->

<script src="<?php echo base_url('assets/Bootstrap-4-4.1.1/js/bootstrap-datepicker.min.js')?>"></script>
<link href="<?php echo base_url('assets/Bootstrap-4-4.1.1/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
        
<link href="<?php echo base_url('assets/datatables-4/datatables.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/bootstrap-4-4.1.1/css/bootstrap.min.css')?>" rel="stylesheet">


        <span width="100" style="background-color: #e0e0e0; display: block;">
            &nbsp;<font size="5" style="font-weight: bold; color: #737373">License Management : <input id="branchName" name="branchName" type="text" disabled="disabled"></font>
        </span>
        <br/>
        <?if($this->session->userdata("role") == 1 || $this->session->userdata("role") == 2):?>
        <button id="add_button" class="btn btn-success" onclick="add_license()" disabled="true"><i class="glyphicon glyphicon-plus"></i> Add License</button>
        <!--<button class="btn btn-success" onclick="upload_license()"><i class="glyphicon glyphicon-plus"></i> Upload License</button>-->
        <?endif;?>
        <button class="btn btn-default" onclick="reload_table()"> Reload</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="1050">
            <thead>
                <tr>
                    <th style="width:30px;">Action</th>
                    <th>License No</th>
                    <th>License Name</th>
                    <th>Description</th>
                    <th>issued By</th>
                    <th>Expired Date</th>
                    <th style="ord-wrap: break-word; width: 30px;">Filename</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
                <tr>
                    <th>Action</th>
                    <th>License No</th>
                    <th>License Name</th>
                    <th>Description</th>
                    <th>issued By</th>
                    <th>Expired Date</th>
                    <th>Filename</th>
                    <th>Status</th>
                </tr>
            </tfoot>
        </table>
    </div>

<!--<script src="<?php echo base_url('assets/jquery/jquery-3.4.0.min.js')?>"></script>

<script src="<?php echo base_url('assets/bootstrap/js/bootstrap-3.4.1.min.js')?>"></script>

<script src="<?php echo base_url('assets/datatables-4/datatables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables-4/dataTables.bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>-->


<script type="text/javascript">
var _base_url = '<?= base_url() ?>';
var save_method; //for save method string
var table;

var _branchId = <?=$branchId?>;
var _encBranch = '<?=$enc_branch?>';
var _divname = '<?=$branchName?>';
var _companyId = <?=$companyId?>;
var _companyname = '<?=$companyName?>';

var files;

    
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    
    var dataJson = { [csrfName]: csrfHash };
    
$(document).ready(function() {
    
    if(_branchId != 0){
        $('#add_button').attr('disabled',false); 
        $('#branchId').val(_branchId);
        $('#branchName').val(_divname);
        $('#companyId').val(_companyId);
        $('#companyName').val(_companyname);
    }
    
    var _url = "<?php echo site_url('index.php/License/ajax_list_null')?>";
    if(_branchId != 0){
        $('#branchId').val(_branchId);
        $('#branchName').val(_divname);
        $("#add_button").attr("disabled", false);
        
        _url = "<?php echo site_url('index.php/License/ajax_branch_list')?>/"+_encBranch;
    }
    

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": _url,
            "type": "POST",
            data : dataJson
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //last column
            "orderable": false //set not orderable
        },
        ],
        "createdRow": function( row, data, dataIndex){
            if( data[8] <= 90 && data[8] > 60 && data[6] == 'OK' ){
                $(row).css('background-color', '#66ff33');
            }else if( data[8] <= 60 && data[8] > 30 && data[6] == 'OK' ){
                $(row).css('background-color', '#ffff66');
            }else if( data[8] <= 30 && data[8] > 3 && data[6] == 'OK' ){
                $(row).css('background-color', '#ff9900');
            }else if( data[8] <= 3 && data[6] == 'OK' ){
                $(row).css('background-color', '#ff0000');
            }
        }

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
    
   // $('#ib_check').change(function(){ //any select change on the dropdown with id country trigger this code
       
    //    $('#issuedBy_new').val("");
    //    $('#issuedBy_new').attr("disabled", "disabled");
    //    $('#issuedBy').removeAttr("disabled");
   
   // }); 
});

function add_license()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    
    
    
    $.ajax({
            'async': true,
            'type': "GET",
            'dataType': 'json',
            //first ajax url to get csrf token
            'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
            'success': function (data) {
                tmp = data;
                csrf_token = data.csrf_token;
                
                dataJson = { [csrfName]: csrf_token };
                
                $('#csrf').val(csrf_token);
            }
        });
        
//    $('[name="branchId"]').attr("readOnly", false);
    
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add License'); // Set Title to Bootstrap modal title
    $('#branch').val($('#branchId')[0].value);
    $('#branch_name').val($('#branchName')[0].value);
    $('#company').val($('#companyId')[0].value);
    $('#company_name').val($('#companyName')[0].value);

    $('[name="status"]').attr("disabled", "disabled");
    $('[name="newLicenseNo"]').attr("disabled", "disabled");
    $('[name="note"]').attr("disabled", "disabled");
}

function upload_license()
{
    window.location = _base_url + 'index.php/License/new_upload_license/';
}

function upload(id, enc_id)
{

    $('#form_upload')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
//    $('#modal_form').modal('show'); // show bootstrap modal
//    $('.modal-title').text('Edit License'); // Set Title to Bootstrap modal title
    
    $.ajax({
        'async': true,
        'type': "GET",
        'dataType': 'json',
        //first ajax url to get csrf token
        'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
        'success': function (data) {
            tmp = data;
            csrf_token = data.csrf_token;
            
            dataJson = { [csrfName]: csrf_token };
            
            $('#csrf_upload').val(csrf_token);

            //Ajax Load data from ajax
            $.ajax({
                url : "<?php echo site_url('index.php/License/ajax_edit/')?>/" + enc_id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    $('[name="upload_licenseId"]').val(data.license_id);
                    $('[name="upload_branch"]').val(data.branch_id);
                    $('[name="upload_licenseNo"]').val(data.license_no);
        
                    $('#modal_form_upload').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Upload PDF'); // Set title to Bootstrap modal title
        
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        }
    });
    
}

function edit_license(id, enc_id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
//    $('#modal_form').modal('show'); // show bootstrap modal
//    $('.modal-title').text('Edit License'); // Set Title to Bootstrap modal title
    
    $.ajax({
        'async': true,
        'type': "GET",
        'dataType': 'json',
        //first ajax url to get csrf token
        'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
        'success': function (data) {
            tmp = data;
            csrf_token = data.csrf_token;
            
            dataJson = { [csrfName]: csrf_token };
            
            $('#csrf').val(csrf_token);

            //Ajax Load data from ajax
            $.ajax({
                url : "<?php echo site_url('index.php/License/ajax_edit/')?>/" + enc_id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    $('[name="licenseId"]').val(data.license_id);
                    $('[name="branch"]').val(data.branch_id);
                    $('[name="company"]').val(data.company_id);
                    $('#branch_name').val($('#branchName')[0].value);
                    $('#company_name').val($('#companyName')[0].value);
                    $('[name="licenseNo"]').val(data.license_no);
                    $('[name="licenseName"]').val(data.license_name);
                    $('[name="description"]').val(data.description);
                    $('[name="issuedBy_new"]').val(data.issued_by);
                    $('[name="issuedDate"]').val(data.issued_date);
                    $('[name="expDate"]').val(data.exp_date);
                    $('[name="remark"]').val(data.remarks);
                    
                    $('[name="status"]').removeAttr("disabled");
                    $('[name="newLicenseNo"]').removeAttr("disabled");
                    $('[name="note"]').removeAttr("disabled");
                    
                    $('[name="status"]').val(data.status);
                    $('[name="newLicenseNo"]').val(data.new_license_no);
                    $('[name="note"]').val(data.note);
                    
                    $('[name="last_update_by"]').val(data.last_update_by);
                    $('[name="last_update_date"]').val(data.last_update_date);
                    
                   // $('[name="filed"]').removeAttr("checked");
                   $('#issuedBy').attr("disabled", "disabled");
                   $('#issuedBy_new').removeAttr("disabled");
                    
                    if(data.filed_at_legal == '1'){
                        $('[name="filed"]').attr("checked", true);
                    }
                    
        //            $('[name="branchId"]').attr("readOnly", true);
                    $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Edit License'); // Set title to Bootstrap modal title
        
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        }
    });
}

function reload_table()
{
    //table.ajax.reload(null,false); //reload datatable ajax 
    
    if(_branchId != '0'){
        table.destroy();
        $.ajax({
            'async': true,
            'type': "GET",
            'dataType': 'json',
            //first ajax url to get csrf token
            'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
            'success': function (data) {
                tmp = data;
                csrf_token = data.csrf_token;
                
                dataJson = { [csrfName]: csrf_token };
                
                $('#csrf').val(csrf_token);
                
                var _url = "<?php echo site_url('index.php/License/ajax_list_null')?>";
                if(_branchId != 0){
                    _url = "<?php echo site_url('index.php/License/ajax_branch_list')?>/"+_encBranch;
                }
            
                //datatables
                table = $('#table').DataTable({ 

                    "processing": true, //Feature control the processing indicator.
                    "serverSide": true, //Feature control DataTables' server-side processing mode.
                    "order": [], //Initial no order.
            
                    // Load data for the table's content from an Ajax source
                    "ajax": {
                        "url": _url,
                        "type": "POST",
                        data : dataJson
                    },
            
                    //Set column definition initialisation properties.
                    "columnDefs": [
                    { 
                        "targets": [ -1 ], //last column
                        "orderable": false //set not orderable
                    },
                    ],
                    "createdRow": function( row, data, dataIndex){
                        if( data[8] <= 90 && data[8] > 60 && data[7] == 'OK' ){
                            $(row).css('background-color', '#66ff33');
                        }else if( data[8] <= 60 && data[8] > 30 && data[7] == 'OK' ){
                            $(row).css('background-color', '#ffff66');
                        }else if( data[8] <= 30 && data[8] > 3 && data[7] == 'OK' ){
                            $(row).css('background-color', '#ff9900');
                        }else if( data[8] <= 3 && data[7] == 'OK' ){
                            $(row).css('background-color', '#ff0000');
                        }
                    }
            
                });
            }
        });
    }
}

function save()
{
//    alert($('#berkas')[0].files[0]);

    var pdf_file = $('#berkas')[0].files;
    
//    var form = $('#form')[0]; // You need to use standard javascript object here
//    var formData = new FormData(form);
//    formData.append('pdf_file', 'aaa'); 
    
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('index.php/License/ajax_add')?>";
    } else {
        url = "<?php echo site_url('index.php/License/ajax_update')?>";
    }

    // ajax adding data to database
    
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize() + "&pdf_file=" + pdf_file,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
            }else{
                $.ajax({
                    'async': true,
                    'type': "GET",
                    'dataType': 'json',
                    //first ajax url to get csrf token
                    'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
                    'success': function (data) {
                        tmp = data;
                        csrf_token = data.csrf_token;
                        
                        dataJson = { [csrfName]: csrf_token };
                        
                        $('#csrf').val(csrf_token);
                    }
                });
                
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
            $.ajax({
                    'async': true,
                    'type': "GET",
                    'dataType': 'json',
                    //first ajax url to get csrf token
                    'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
                    'success': function (data) {
                        tmp = data;
                        csrf_token = data.csrf_token;
                        
                        dataJson = { [csrfName]: csrf_token };
                        
                        $('#csrf').val(csrf_token);
                    }
                });
                
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_license(id, emp, enc_id)
{
    if(confirm('Are you sure delete this license: ' + emp + ' ?'))
    {
        $.ajax({
            'async': true,
            'type': "GET",
            'dataType': 'json',
            //first ajax url to get csrf token
            'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
            'success': function (data) {
                tmp = data;
                csrf_token = data.csrf_token;
                
                dataJson = { [csrfName]: csrf_token }
                
                // ajax delete data to database
                $.ajax({
                    url : "<?php echo site_url('index.php/License/ajax_delete')?>/"+ enc_id,
                    type: "POST",
                    dataType: "JSON",
                    data : dataJson,
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
        });

    }
}


function on_progress(id, no, enc_id)
{
    if(confirm('Update status to On Renewal Process : ' + no + ' ?'))
    {
        $.ajax({
            'async': true,
            'type': "GET",
            'dataType': 'json',
            //first ajax url to get csrf token
            'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
            'success': function (data) {
                tmp = data;
                csrf_token = data.csrf_token;
                
                dataJson = { [csrfName]: csrf_token };
                
                $('#csrf').val(csrf_token);
                
                // ajax delete data to database
                $.ajax({
                    url : "<?php echo site_url('index.php/License/ajax_on_progress')?>/"+ enc_id,
                    type: "POST",
                    dataType: "JSON",
                    data : dataJson,
                    success: function(data)
                    {
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error updating data');
                    }
                });
            }
        });
    }
}

function expired(id, no, enc_id)
{
    if(confirm('Update status to Expired : ' + no + ' ?'))
    {
        $.ajax({
            'async': true,
            'type': "GET",
            'dataType': 'json',
            //first ajax url to get csrf token
            'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
            'success': function (data) {
                tmp = data;
                csrf_token = data.csrf_token;
                
                dataJson = { [csrfName]: csrf_token };
                
                $('#csrf').val(csrf_token);
                
                $.ajax({
                    url : "<?php echo site_url('index.php/License/ajax_expire')?>/"+ enc_id,
                    type: "POST",
                    dataType: "JSON",
                    data : dataJson,
                    success: function(data)
                    {
                        reload_table();
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        alert('Error updating data');
                    }
                });
            }
        });

    }
}

function terminated(id, enc_id)
{

    $('#form_terminate')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
//    $('#modal_form').modal('show'); // show bootstrap modal
//    $('.modal-title').text('Edit License'); // Set Title to Bootstrap modal title
    
    $.ajax({
        'async': true,
        'type': "GET",
        'dataType': 'json',
        //first ajax url to get csrf token
        'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
        'success': function (data) {
            tmp = data;
            csrf_token = data.csrf_token;
            
            dataJson = { [csrfName]: csrf_token };
            
            $('#csrf_terminate').val(csrf_token);
    
            //Ajax Load data from ajax
            $.ajax({
                url : "<?php echo site_url('index.php/License/ajax_edit/')?>/" + enc_id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    $('[name="terminate_licenseId"]').val(data.license_id);
                    $('[name="terminate_branch"]').val(data.branch_id);
                    $('[name="terminate_licenseNo"]').val(data.license_no);
        
                    $('#modal_form_terminate').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Terminate License'); // Set title to Bootstrap modal title
        
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        }
    });
}

function save_terminate()
{  
    $('#btnSaveTerminate').text('saving...'); //change button text
    $('#btnSaveTerminate').attr('disabled',true); //set button disable 
    var url;

    url = "<?php echo site_url('index.php/License/ajax_terminate')?>";

    // ajax adding data to database
    
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_terminate').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_terminate').modal('hide');
                reload_table();
            }else{
                serr = 'Fail to save data.';
                try {
                  serr = serr + ' ' + data.error;
                } catch(e) {}
                
                alert(serr);
            }

                $('#btnSaveTerminate').text('save'); //change button text
                $('#btnSaveTerminate').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSaveTerminate').text('save'); //change button text
            $('#btnSaveTerminate').attr('disabled',false); //set button enable 

        }
    });
}


function renewed(id, enc_id)
{

    $('#form_renew')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
//    $('#modal_form').modal('show'); // show bootstrap modal
//    $('.modal-title').text('Edit License'); // Set Title to Bootstrap modal title
    $.ajax({
        'async': true,
        'type': "GET",
        'dataType': 'json',
        //first ajax url to get csrf token
        'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
        'success': function (data) {
            tmp = data;
            csrf_token = data.csrf_token;
            
            dataJson = { [csrfName]: csrf_token };
            
            $('#csrf_renew').val(csrf_token);
    
            //Ajax Load data from ajax
            $.ajax({
                url : "<?php echo site_url('index.php/License/ajax_edit/')?>/" + enc_id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    $('[name="renew_licenseId"]').val(data.license_id);
                    $('[name="renew_branch"]').val(data.branch_id);
                    $('[name="renew_licenseNo"]').val(data.license_no);
        
                    $('#modal_form_renew').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Renew License'); // Set title to Bootstrap modal title
        
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
        }
    });
    
}


function save_renew()
{  
    $('#btnSaveRenew').text('saving...'); //change button text
    $('#btnSaveRenew').attr('disabled',true); //set button disable 
    var url;

    url = "<?php echo site_url('index.php/License/ajax_renew')?>";

    // ajax adding data to database
    
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_renew').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_renew').modal('hide');
                reload_table();
            }else{
                serr = 'Fail to save data.';
                try {
                  serr = serr + ' ' + data.error;
                } catch(e) {}
                
                alert(serr);
            }

                $('#btnSaveRenew').text('save'); //change button text
                $('#btnSaveRenew').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSaveRenew').text('save'); //change button text
            $('#btnSaveRenew').attr('disabled',false); //set button enable 

        }
    });
}

function view_branch(com_id) {
  window.location = _base_url + 'index.php/Branch/lists/' + com_id;
}

function checkChange(element){
    if(element.checked){
        $('#issuedBy_new').val("");
        $('#issuedBy_new').attr("disabled", "disabled");
        $('#issuedBy').removeAttr("disabled");
    }else{
//        $('#otherParty_new').val("");
        $('#issuedBy').attr("disabled", "disabled");
//        $('#otherParty').removeAttr("disabled");
        $('#issuedBy_new').removeAttr("disabled");
    } //? document.getElementById("LevyFee").disabled = true : document.getElementById("LevyFee").disabled = false;    
}

function branch_license(company, company_name, branch, branch_name, enc_branch){
    _branchId = branch;
    _encBranch = enc_branch;
    
    table.destroy();
    
    $.ajax({
        'async': true,
        'type': "GET",
        'dataType': 'json',
        //first ajax url to get csrf token
        'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
        'success': function (data) {
            tmp = data;
            csrf_token = data.csrf_token;
            
            dataJson = { [csrfName]: csrf_token };
            
            $('#branchId').val(branch);
            $('#branchName').val(branch_name);
            $('#companyId').val(company);
            $('#companyName').val(company_name);
            $("#add_button").attr("disabled", false);
            
            var _url = "<?php echo site_url('index.php/License/ajax_branch_list')?>/"+_encBranch;
            
            //datatables
            table = $('#table').DataTable({ 
        
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
        
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": _url,
                    "type": "POST",
                    data : dataJson
                },
        
                //Set column definition initialisation properties.
                "columnDefs": [
                { 
                    "targets": [ 0 ], //last column
                    "orderable": false //set not orderable
                },
                ],
                "createdRow": function( row, data, dataIndex){
                    if( data[8] <= 90 && data[8] > 60 && data[7] == 'OK' ){
                        $(row).css('background-color', '#66ff33');
                    }else if( data[8] <= 60 && data[8] > 30 && data[7] == 'OK' ){
                        $(row).css('background-color', '#ffff66');
                    }else if( data[8] <= 30 && data[8] > 3 && data[7] == 'OK' ){
                        $(row).css('background-color', '#ff9900');
                    }else if( data[8] <= 3 && data[7] == 'OK' ){
                        $(row).css('background-color', '#ff0000');
                    }
                }
        
            });
        }
    });
    
    // table.ajax.reload(null,false);
    // var _url = "<?php echo site_url('index.php/License/ajax_branch_list')?>/"+enc_branch;
    // table.ajax.url(_url).load();
}

function view_doc(license_id, enc_id) {
//    alert('CAT Sheet') ;
    var win = window.open(_base_url+'index.php/License/view_license/'+ enc_id, '_blank');
    win.focus();
}
</script>

<!-- Bootstrap modal -->
<input id="branchId" name="branchId" class="form-control" type="hidden">
<input id="companyId" name="companyId" class="form-control" type="hidden">
<input id="companyName" name="companyName" class="form-control" type="hidden">

<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">License Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#" id="form" class="form-horizontal" enctype="multipart/form-data" method="post">  
                <input type="hidden" id="csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />  
                <div class="modal-body form">
                    <input name="licenseId" class="form-control" type="hidden" readonly="true">
                    <input id="company" name="company" class="form-control" type="hidden">
                    <input id="branch" name="branch" class="form-control" type="hidden">
                    
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Company</label>
                            <div class="col-md-9">
                                <input id="company_name" name="company_name" class="form-control" type="text" readonly="true">
                                <?php // echo form_dropdown('branch', $branch_list, '', 'id="branch" name="branch" placeholder="Division" class="form-control" type="text" readonly="true" disabled="disabled"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Branch</label>
                            <div class="col-md-9">
                                <input id="branch_name" name="branch_name" class="form-control" type="text" readonly="true">
                                <?php // echo form_dropdown('branch', $branch_list, '', 'id="branch" name="branch" placeholder="Division" class="form-control" type="text" readonly="true" disabled="disabled"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">License No</label>
                            <div class="col-md-9">
                                <input name="licenseNo" placeholder="License No" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">License Name</label>
                            <div class="col-md-9">
                                <input name="licenseName" placeholder="License Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Description</label>
                            <div class="col-md-9">
                                <textarea name="description" placeholder="Description" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Issued By</label>
                            <div class="col-md-9">
                                <input id="issuedBy_new" name="issuedBy_new" placeholder="Issued By" class="form-control" type="text">
                                <input name="ib_check" value="1" id="ib_check" type ="checkbox" onchange = "checkChange(this)"> Existing data </input>
                                <?php echo form_dropdown('issuedBy', $ib_list, '', 'id="issuedBy" name="issuedBy" class="form-control" type="text" disabled="disabled"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Issued Date</label>
                            <div class="col-md-9">
                                <input name="issuedDate" placeholder="yyyy-mm-dd" class="form-control datepicker" data-date-format="mm-dd-yyyy" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Expired Date</label>
                            <div class="col-md-9">
                                <input name="expDate" placeholder="yyyy-mm-dd" class="form-control datepicker" data-date-format="mm-dd-yyyy" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Remarks</label>
                            <div class="col-md-9">
                                <textarea name="remark" placeholder="Remarks" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Status</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('status', $status_list, '0', 'id="status" name="status" class="form-control" type="text"  disabled="disbaled"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">New License No.</label>
                            <div class="col-md-9">
                                <input name="newLicenseNo" placeholder="New License No." class="form-control" type="text" disabled="disbaled">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Note</label>
                            <div class="col-md-9">
                                <textarea name="note" placeholder="Note" class="form-control" type="text" disabled="disbaled"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Last Update By</label>
                            <div class="col-md-9">
                                <input name="last_update_by" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Last Update date</label>
                            <div class="col-md-9">
                                <input name="last_update_date" class="form-control" type="text" readonly>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
            <!--</form>-->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->


<div class="modal fade" id="modal_form_upload" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Upload License Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="<?=base_url()?>index.php/License/upload_license" method="post" id="form_upload" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" id="csrf_upload" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" /> 
                <div class="modal-body form">
                    <input name="upload_licenseId" class="form-control" type="hidden" readonly="true">
                    <input id="upload_branch" name="upload_branch" class="form-control" type="hidden">
                    
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">License No</label>
                            <div class="col-md-9">
                                <input name="upload_licenseNo" placeholder="License No" class="form-control" type="text" readonly="true">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">File</label>
                            <div class="col-md-9">
                                <input type="file" name="berkas" id="berkas" size="20" class="input_text" accept="application/pdf"/>
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
            <!--</form>-->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<div class="modal fade" id="modal_form_terminate" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Terminate License</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#" id="form_terminate" class="form-horizontal" enctype="multipart/form-data" method="post">  
                <input type="hidden" id="csrf_terminate" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" /> 
                <div class="modal-body form">
                    <input name="terminate_licenseId" class="form-control" type="hidden" readonly="true">
                    <input id="terminate_branch" name="terminate_branch" class="form-control" type="hidden">
                    
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">License No.</label>
                            <div class="col-md-9">
                                <input name="terminate_licenseNo" placeholder="License No" class="form-control" type="text" readonly="true">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Note</label>
                            <div class="col-md-9">
                                <textarea name="terminate_note" placeholder="Note" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSaveTerminate" onclick="save_terminate()" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
            <!--</form>-->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<div class="modal fade" id="modal_form_renew" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Renew License</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#" id="form_renew" class="form-horizontal" enctype="multipart/form-data" method="post">  
                <input type="hidden" id="csrf_renew" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" /> 
                <div class="modal-body form">
                    <input name="renew_licenseId" class="form-control" type="hidden" readonly="true">
                    <input id="renew_branch" name="terminate_branch" class="form-control" type="hidden">
                    
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">License No.</label>
                            <div class="col-md-9">
                                <input name="renew_licenseNo" placeholder="License No" class="form-control" type="text" readonly="true">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">New License No.</label>
                            <div class="col-md-9">
                                <input name="renew_newLicenseNo" placeholder="New License No." class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSaveRenew" onclick="save_renew()" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
            <!--</form>-->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

