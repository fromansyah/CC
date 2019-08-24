<!DOCTYPE html>
<script src="<?php echo base_url('assets/jquery/jquery-3.4.0.min.js')?>"></script>

<!--<script src="<?php echo base_url('assets/bootstrap-4-4.1.1/js/bootstrap.min.js')?>"></script>-->

<script src="<?php echo base_url('assets/bootstrap/js/bootstrap-3.4.1.min.js')?>"></script>

<script src="<?php echo base_url('assets/datatables-4/datatables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables-4/dataTables.bootstrap.min.js')?>"></script>
<!--<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>-->

<script src="<?php echo base_url('assets/Bootstrap-4-4.1.1/js/bootstrap-datepicker.min.js')?>"></script>
<link href="<?php echo base_url('assets/Bootstrap-4-4.1.1/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

<link href="<?php echo base_url('assets/bootstrap-4-4.1.1/css/bootstrap.min.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/datatables-4/datatables.css')?>" rel="stylesheet">
<link href="<?php echo base_url('assets/datatables-4/datatables.min.css')?>" rel="stylesheet">

        <span width="100" style="background-color: #e0e0e0; display: block;">
            &nbsp;<font size="5" style="font-weight: bold; color: #737373">Contract Management : <input id="divName" name="divName" type="text" disabled="disabled"></font>
        </span>
        <br/>
        
        <?if($this->session->userdata("role") == 1 || $this->session->userdata("role") == 2):?>

        <button id="add_button" class="btn btn-success" onclick="add_contract()" disabled="true"><i class="glyphicon glyphicon-plus"></i> Add Contract</button>
        <!--<button class="btn btn-success" onclick="upload_contract()"><i class="glyphicon glyphicon-plus"></i> Upload Contract</button>-->
        <?endif;?>
        <button class="btn btn-default" onclick="reload_table()"> Reload</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="1050">
            <thead>
                <tr>
                    <th style="width:30px;">Action</th>
                    <th>Contract No</th>
                    <th>Other Party</th>
                    <th>Description</th>
                    <th>Expired Date</th>
                    <th>Check By</th>
                    <th>1st Req.</th>
                    <th>2nd Req.</th>
                    <th>Filename</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
                <tr>
                    <th>Action</th>
                    <th>Contract No</th>
                    <th>Other Party</th>
                    <th>Description</th>
                    <th>Expired Date</th>
                    <th>Check By</th>
                    <th>1st Req.</th>
                    <th>2nd Req.</th>
                    <th>Filename</th>
                    <th>Status</th>
                </tr>
            </tfoot>
        </table>
    </div>


<script type="text/javascript">
var _base_url = '<?= base_url() ?>';
var save_method; //for save method string
var table;

var _divId = <?=$divId?>;
var _encDiv = '<?=$enc_div?>';
var _divname = '<?=$divName?>';

var files;

    
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    
    var dataJson = { [csrfName]: csrfHash };
    

$(document).ready(function() {
    
    if(_divId != 0){
        $('#add_button').attr('disabled',false); 
        $('#divId').val(_divId);
        $('#divName').val(_divname);
    }
    
    var _url = "<?php echo site_url('index.php/Contract/ajax_list_null')?>";
    if(_divId != 0){
        $('#divId').val(_divId);
        $('#divName').val(_divname);
        $("#add_button").attr("disabled", false);
        
        _url = "<?php echo site_url('index.php/Contract/ajax_division_list')?>/"+_encDiv;
    }

    //datatables
    
    
    // $.ajax({
    //     'async': true,
    //     'type': "GET",
    //     'dataType': 'json',
    //     //first ajax url to get csrf token
    //     'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
    //     'success': function (data) {
    //         tmp = data;
    //         csrf_token = data.csrf_token;
            
    //         dataJson = { [csrfName]: csrf_token };
    //     }
    // });
    
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": _url,
            "type": "POST",
            data: dataJson
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ 0 ], //last column
            "orderable": false //set not orderable
        },
        ],
        "createdRow": function( row, data, dataIndex){
            if( data[10] <= 90 && data[10] > 60 && data[9] == 'OK' ){
                $(row).css('background-color', '#66ff33');
            }else if( data[10] <= 60 && data[10] > 30 && data[9] == 'OK' ){
                $(row).css('background-color', '#ffff66');
            }else if( data[10] <= 30 && data[10] > 3 && data[9] == 'OK' ){
                $(row).css('background-color', '#ff9900');
            }else if( data[10] <= 3 && data[9] == 'OK' ){
                $(row).css('background-color', '#ff0000');
            }
        }
    });
    
    // $.ajax({
    //     'async': true,
    //     'type': "GET",
    //     'dataType': 'json',
    //     //first ajax url to get csrf token
    //     'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
    //     'success': function (data) {
    //         tmp = data;
    //         csrf_token = data.csrf_token;
            
    //         dataJson = { [csrfName]: csrf_token };
            
    //         $('#csrf').val(csrf_token);
    //     }
    // });

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true
    });
    
    $( "#add_button" ).click(function() {
        //alert( "Handler for .click() called." );
        save_method = 'add';
        $('#form')[0].reset(); // reset form on modals
        $('.form-group').removeClass('has-error'); // clear error class
        $('.help-block').empty(); // clear error string
        
        $('#modal_form').modal('show'); // show bootstrap modal
        $('.modal-title').text('Add Contract'); // Set Title to Bootstrap modal title
        
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
        
        $('#division').val($('#divId')[0].value);
        $('#firstParty').val($('#compId')[0].value);
        $('#division_name').val($('#divName')[0].value);
    
        $('[name="status"]').attr("disabled", "disabled");
        $('[name="newContractNo"]').attr("disabled", "disabled");
        $('[name="note"]').attr("disabled", "disabled");
    });
    
//    $('#op_check').change(function(){ //any select change on the dropdown with id country trigger this code
//        
//        $('#otherParty_new').val("");
//        $('#otherParty_new').attr("disabled", "disabled");
//        $('#otherParty').removeAttr("disabled");
//    
//    }); 
});

function add_contract()
{
    //alert("ADD");
    // save_method = 'add';
    // $('#form')[0].reset(); // reset form on modals
    // $('.form-group').removeClass('has-error'); // clear error class
    // $('.help-block').empty(); // clear error string
    
    // $('#modal_form').modal('show'); // show bootstrap modal
    // $('.modal-title').text('Add Contract'); // Set Title to Bootstrap modal title
    // $('#division').val($('#divId')[0].value);
    // $('#firstParty').val($('#compId')[0].value);
    // $('#division_name').val($('#divName')[0].value);

    // $('[name="status"]').attr("disabled", "disabled");
    // $('[name="newContractNo"]').attr("disabled", "disabled");
    // $('[name="note"]').attr("disabled", "disabled");
}

function upload_contract()
{
    window.location = _base_url + 'index.php/Contract/new_upload_contract/';
}

function upload(id, enc_id)
{

    $('#form_upload')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
//    $('#modal_form').modal('show'); // show bootstrap modal
//    $('.modal-title').text('Edit Contract'); // Set Title to Bootstrap modal title

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
                    url : "<?php echo site_url('index.php/Contract/ajax_edit/')?>" + enc_id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    {
                        $('[name="upload_contractId"]').val(data.contract_id);
                        $('[name="upload_division"]').val(data.division_id);
                        $('[name="upload_contractNo"]').val(data.contract_no);
            
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

function edit_contract(id, enc_id)
{
    save_method = 'update';
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

            $.ajax({
                url : "<?php echo site_url('index.php/Contract/ajax_edit/')?>/" + enc_id,
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    $('[name="contractId"]').val(data.contract_id);
                    $('[name="division"]').val(data.division_id);
                    $('[name="contractNo"]').val(data.contract_no);
                    $('#division_name').val($('#divName')[0].value);
                    $('[name="agrDate"]').val(data.agr_date);
                    $('[name="effDate"]').val(data.eff_date);
                    $('[name="expDate"]').val(data.exp_date);
                    $('[name="firstParty"]').val(data.first_party);
                    $('[name="fpRep"]').val(data.fp_rep);
                    $('[name="otherParty_new"]').val(data.other_party);
                    $('[name="opRep"]').val(data.op_rep);
                    $('[name="opTitle"]').val(data.op_title);
                    $('[name="opAddress"]').val(data.op_address);
                    $('[name="description"]').val(data.description);
                    $('[name="prodLoc"]').val(data.prod_loc);
                    $('[name="tog"]').val(data.tog);
                    $('[name="qty"]').val(data.quantity);
                    $('[name="spec"]').val(data.specs);
                    $('[name="kpi"]').val(data.kpi);
                    $('[name="price"]').val(data.price);
                    $('[name="estValue"]').val(data.est_value);
                    $('[name="top"]').val(data.term_of_payment);
                    $('[name="delTime"]').val(data.delivery_time);
                    $('[name="termination"]').val(data.termination);
                    $('[name="penalty"]').val(data.penalty);
                    $('[name="forceMajeur"]').val(data.force_majeure);
                    $('[name="dispute"]').val(data.dispute);
                    $('[name="govLaw"]').val(data.gov_law);
                    $('[name="conf"]').val(data.conf);
                    $('[name="others"]').val(data.others);
                    $('[name="checkBy"]').val(data.check_by);
                    $('[name="ackBy"]').val(data.ack_by);
                    $('[name="requestor"]').val(data.requestor);
                    $('[name="req_2"]').val(data.req_2);
                    $('[name="req_3"]').val(data.req_3);
                    $('[name="req_4"]').val(data.req_4);
                    $('[name="req_5"]').val(data.req_5);
                    $('[name="appBy"]').val(data.approved_by);
                    
                    $('[name="status"]').removeAttr("disabled");
                    $('[name="newContractNo"]').removeAttr("disabled");
                    $('[name="note"]').removeAttr("disabled");
                    
                    $('[name="status"]').val(data.status);
                    $('[name="newContractNo"]').val(data.new_contract_no);
                    $('[name="note"]').val(data.note);
                    
                    $('[name="last_update_by"]').val(data.last_update_by);
                    $('[name="last_update_date"]').val(data.last_update_date);
                    
                    $('#otherParty').attr("disabled", "disabled");
                    $('#otherParty_new').removeAttr("disabled");
                    
                    
        //            $('[name="filed"]').removeAttr("checked");
                    
                    if(data.filed_at_legal == '1'){
                        $('[name="filed"]').attr("checked", true);
                    }
                    
        //            $('[name="divId"]').attr("readOnly", true);
                    $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                    $('.modal-title').text('Edit Contract'); // Set title to Bootstrap modal title
        
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
    //table.ajax.reload(null,false);
    //location.reload();
    //alert(_divId+" "+_encDiv);
    if(_divId != '0'){
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
                
                var _url = "<?php echo site_url('index.php/Contract/ajax_list_null')?>";
                if(_divId != 0){
                    _url = "<?php echo site_url('index.php/Contract/ajax_division_list')?>/"+_encDiv;
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
                        "data": dataJson
                    },
            
                    //Set column definition initialisation properties.
                    "columnDefs": [
                    { 
                        "targets": [ -1 ], //last column
                        "orderable": false //set not orderable
                    },
                    ],
                    "createdRow": function( row, data, dataIndex){
                        if( data[10] <= 90 && data[10] > 60 && data[8] == 'OK' ){
                            $(row).css('background-color', '#66ff33');
                        }else if( data[10] <= 60 && data[10] > 30 && data[8] == 'OK' ){
                            $(row).css('background-color', '#ffff66');
                        }else if( data[10] <= 30 && data[10] > 3 && data[8] == 'OK' ){
                            $(row).css('background-color', '#ff9900');
                        }else if( data[10] <= 3 && data[8] == 'OK' ){
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
        url = "<?php echo site_url('index.php/Contract/ajax_add')?>";
    } else {
        url = "<?php echo site_url('index.php/Contract/ajax_update')?>";
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

function delete_contract(id, no, enc_id)
{
    if(confirm('Are you sure delete this contract: ' + no + ' ?'))
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
                    url : "<?php echo site_url('index.php/Contract/ajax_delete')?>/"+ enc_id,
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
        });
        

    }
}

function on_progress(id, no, enc_id)
{
    if(confirm('Update status to On Renewal Process : ' + no + ' ?'))
    {
        // ajax delete data to database
        
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
                    url : "<?php echo site_url('index.php/Contract/ajax_on_progress')?>/"+ enc_id,
                    type: "POST",
                    dataType: "JSON",
                    data: dataJson,
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
        // ajax delete data to database
        
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
                    url : "<?php echo site_url('index.php/Contract/ajax_expire')?>/"+ enc_id,
                    type: "POST",
                    dataType: "JSON",
                    data: dataJson,
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
//    $('.modal-title').text('Edit Contract'); // Set Title to Bootstrap modal title

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
                    url : "<?php echo site_url('index.php/Contract/ajax_edit/')?>/" + enc_id,
                    type: "GET",
                    dataType: "JSON",
                    data: dataJson,
                    success: function(data)
                    {
                        $('[name="terminate_contractId"]').val(data.contract_id);
                        $('[name="terminate_division"]').val(data.division_id);
                        $('[name="terminate_contractNo"]').val(data.contract_no);
            
                        $('#modal_form_terminate').modal('show'); // show bootstrap modal when complete loaded
                        $('.modal-title').text('Terminate Contract'); // Set title to Bootstrap modal title
            
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

    url = "<?php echo site_url('index.php/Contract/ajax_terminate')?>";

    // ajax adding data to database
    
    $.ajax({
        url : url,
        type: "POST",
        data: dataJson,
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
//    $('.modal-title').text('Edit Contract'); // Set Title to Bootstrap modal title

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
                    url : "<?php echo site_url('index.php/Contract/ajax_edit/')?>/" + enc_id,
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    {
                        $('[name="renew_contractId"]').val(data.contract_id);
                        $('[name="renew_division"]').val(data.division_id);
                        $('[name="renew_contractNo"]').val(data.contract_no);
            
                        $('#modal_form_renew').modal('show'); // show bootstrap modal when complete loaded
                        $('.modal-title').text('Renew Contract'); // Set title to Bootstrap modal title
            
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

    url = "<?php echo site_url('index.php/Contract/ajax_renew')?>";

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
        $('#otherParty_new').val("");
        $('#otherParty_new').attr("disabled", "disabled");
        $('#otherParty').removeAttr("disabled");
    }else{
//        $('#otherParty_new').val("");
        $('#otherParty').attr("disabled", "disabled");
//        $('#otherParty').removeAttr("disabled");
        $('#otherParty_new').removeAttr("disabled");
    } //? document.getElementById("LevyFee").disabled = true : document.getElementById("LevyFee").disabled = false;    
}

function division_contract(division, division_name, company_id, enc_div){
    _divId = division;
    _encDiv = enc_div;
    
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
            
            $('#divId').val(division);
            $('#divName').val(division_name);
            $('#compId').val(company_id);
            $("#add_button").attr("disabled", false);
            
            var _url = "<?php echo site_url('index.php/Contract/ajax_division_list')?>/"+enc_div;
            
            table = $('#table').DataTable({ 

                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
        
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": _url,
                    "type": "POST",
                    data: dataJson
                },
        
                //Set column definition initialisation properties.
                "columnDefs": [
                { 
                    "targets": [ 0 ], //last column
                    "orderable": false //set not orderable
                },
                ],
                "createdRow": function( row, data, dataIndex){
                    if( data[10] <= 90 && data[10] > 60 && data[9] == 'OK' ){
                        $(row).css('background-color', '#66ff33');
                    }else if( data[10] <= 60 && data[10] > 30 && data[9] == 'OK' ){
                        $(row).css('background-color', '#ffff66');
                    }else if( data[10] <= 30 && data[10] > 3 && data[9] == 'OK' ){
                        $(row).css('background-color', '#ff9900');
                    }else if( data[10] <= 3 && data[9] == 'OK' ){
                        $(row).css('background-color', '#ff0000');
                    }
                }
            });
        }
    });
    
    // var csfrData = {};
    // csfrData['<?php echo $this->security->get_csrf_token_name(); ?>'] = '<?php echo$this->security->get_csrf_hash(); ?>';
    
    // dataJson = { [csrfName]: csrfHash };
    
    // table.ajax.reload(null,false);
    // var _url = "<?php echo site_url('index.php/Contract/ajax_division_list')?>/"+enc_div;
    // table.ajax.url(_url).load();
}

function view_doc(contract_id, enc_id) {
//    alert('CAT Sheet') ;
    var win = window.open(_base_url+'index.php/Contract/view_contract/'+ enc_id, '_blank');
    win.focus();
}

function summary(contract_id, enc_id) {
  window.location = _base_url + 'index.php/Contract/summary/' + enc_id;
}
</script>

<!-- Bootstrap modal -->
<input id="divId" name="divId" class="form-control" type="hidden">
<input id="compId" name="compId" class="form-control" type="hidden">

<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Contract Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#" id="form" class="form-horizontal" enctype="multipart/form-data" method="post">
                <input type="hidden" id="csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" /> 
                <div class="modal-body form">
                    <input name="contractId" class="form-control" type="hidden" readonly="true">
                    <input id="division" name="division" class="form-control" type="hidden">
                    
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Contract No</label>
                            <div class="col-md-9">
                                <input name="contractNo" placeholder="Contract No" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Division</label>
                            <div class="col-md-9">
                                <input id="division_name" name="division_name" class="form-control" type="text" readonly="true">
                                <?php // echo form_dropdown('division', $division_list, '', 'id="division" name="division" placeholder="Division" class="form-control" type="text" readonly="true" disabled="disabled"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Agreement Date</label>
                            <div class="col-md-9">
                                <input name="agrDate" placeholder="yyyy-mm-dd" class="form-control datepicker" data-date-format="mm-dd-yyyy" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Effective Date</label>
                            <div class="col-md-9">
                                <input name="effDate" placeholder="yyyy-mm-dd" class="form-control datepicker" data-date-format="mm-dd-yyyy" type="text">
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
                            <label class="control-label col-md-3">First Party</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('firstParty', $company_list, '', 'id="firstParty" name="firstParty" placeholder="First Party" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">First Party Rep.</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('fpRep', $employee_list, '', 'id="fpRep" name="fpRep" placeholder="First Party" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Other Party</label>
                            <div class="col-md-9">
                                <input id="otherParty_new" name="otherParty_new" placeholder="Other Party" class="form-control" type="text">
                                <input name="op_check" value="1" id="op_check" type ="checkbox" onchange = "checkChange(this)"> Existing other party </input>
                                <?php echo form_dropdown('otherParty', $op_list, '', 'id="otherParty" name="otherParty" placeholder="Other Party" class="form-control" type="text" disabled="disabled"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Other Party Rep.</label>
                            <div class="col-md-9">
                                <input name="opRep" placeholder="Other Party Authorized Representative" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Other Party Rep. Title</label>
                            <div class="col-md-9">
                                <input name="opTitle" placeholder="Other Party Authorized Representative Title" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Other Party Address</label>
                            <div class="col-md-9">
                                <textarea name="opAddress" placeholder="Other Party Address" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Description</label>
                            <div class="col-md-9">
                                <textarea name="description" placeholder="Agreement Description" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Location</label>
                            <div class="col-md-9">
                                <textarea name="prodLoc" placeholder="Production Location" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Type of Goods</label>
                            <div class="col-md-9">
                                <input name="tog" placeholder="Type of Goods" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Quantity</label>
                            <div class="col-md-9">
                                <input name="qty" placeholder="Quantity" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Specifications</label>
                            <div class="col-md-9">
                                <input name="spec" placeholder="Specifications" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">KPI</label>
                            <div class="col-md-9">
                                <input name="kpi" placeholder="Key Performance Indicator" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Price</label>
                            <div class="col-md-9">
                                <input name="price" placeholder="Price" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Estimated Value</label>
                            <div class="col-md-9">
                                <input name="estValue" placeholder="Estimated Value" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Term of Payment</label>
                            <div class="col-md-9">
                                <input name="top" placeholder="Term of Payment" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Delivery Time</label>
                            <div class="col-md-9">
                                <input name="delTime" placeholder="Delivery Time" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Termination</label>
                            <div class="col-md-9">
                                <input name="termination" placeholder="Termination" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Penalty</label>
                            <div class="col-md-9">
                                <input name="penalty" placeholder="Penalty" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Force Majeur</label>
                            <div class="col-md-9">
                                <input name="forceMajeur" placeholder="Force Majeur" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Dispute Resolution</label>
                            <div class="col-md-9">
                                <input name="dispute" placeholder="Dispute Resolution" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Governing Law</label>
                            <div class="col-md-9">
                                <input name="govLaw" placeholder="Governing Law" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Confidentiality</label>
                            <div class="col-md-9">
                                <input name="conf" placeholder="Confidentiality" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Others</label>
                            <div class="col-md-9">
                                <textarea name="others" placeholder="Others" class="form-control" type="text"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Checked by</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('checkBy', $employee_list, '', 'id="checkBy" name="checkBy" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Acknowledged by</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('ackBy', $employee_list, '', 'id="ackBy" name="ackBy" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">1st Requestor</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('requestor', $employee_list, '', 'id="requestor" name="requestor" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">2nd Requestor</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('req_2', $employee_list, '', 'id="req_2" name="req_2" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">3rd Requestor</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('req_3', $employee_list, '', 'id="req_3" name="req_3" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">4th Requestor</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('req_4', $employee_list, '', 'id="req_4" name="req_4" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">5th Requestor</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('req_5', $employee_list, '', 'id="req_5" name="req_5" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Approved by</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('appBy', $employee_list, '', 'id="appBy" name="appBy" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Filed at legal</label>
                            <div class="col-md-9">
                                <input name="filed" value="1" id="filed" type ="checkbox"></input>
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
                            <label class="control-label col-md-3">New Contract No.</label>
                            <div class="col-md-9">
                                <input name="newContractNo" placeholder="New Contract No." class="form-control" type="text" disabled="disbaled">
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
<!--                        <div class="form-group">
                            <label class="control-label col-md-3">File</label>
                            <div class="col-md-9">
                                <input type="file" name="berkas" id="berkas" size="20" class="input_text" accept="application/pdf"/>
                                <span class="help-block"></span>
                            </div>
                        </div>-->
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
                <h3 class="modal-title">Upload Contract Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="<?=base_url()?>index.php/Contract/upload_contract" method="post" id="form_upload" class="form-horizontal" enctype="multipart/form-data">
                <input type="hidden" id="csrf_upload" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" /> 
                <div class="modal-body form">
                    <input name="upload_contractId" class="form-control" type="hidden" readonly="true">
                    <input id="upload_division" name="upload_division" class="form-control" type="hidden">
                    
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Contract No</label>
                            <div class="col-md-9">
                                <input name="upload_contractNo" placeholder="Contract No" class="form-control" type="text" readonly="true">
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
                <h3 class="modal-title">Terminate Contract</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#" id="form_terminate" class="form-horizontal" enctype="multipart/form-data" method="post">  
                <input type="hidden" id="csrf_terminate" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" /> 
                <div class="modal-body form">
                    <input name="terminate_contractId" class="form-control" type="hidden" readonly="true">
                    <input id="terminate_division" name="terminate_division" class="form-control" type="hidden">
                    
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Contract No.</label>
                            <div class="col-md-9">
                                <input name="terminate_contractNo" placeholder="Contract No" class="form-control" type="text" readonly="true">
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
                <h3 class="modal-title">Renew Contract</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="#" id="form_renew" class="form-horizontal" enctype="multipart/form-data" method="post">  
                <input type="hidden" id="csrf_renew" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" /> 
                <div class="modal-body form">
                    <input name="renew_contractId" class="form-control" type="hidden" readonly="true">
                    <input id="renew_division" name="terminate_division" class="form-control" type="hidden">
                    
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Contract No.</label>
                            <div class="col-md-9">
                                <input name="renew_contractNo" placeholder="Contract No" class="form-control" type="text" readonly="true">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">New Contract No.</label>
                            <div class="col-md-9">
                                <input name="renew_newContractNo" placeholder="New Contract No." class="form-control" type="text">
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

<?
//    print_r($branch);
    
?>

