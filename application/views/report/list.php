<!DOCTYPE html>    
    <div class="container">
        <font size="5">Asset Report Management</font>
        <br/>
        <br/>
        <?if($this->session->userdata("role") == 1):?>
        <button class="btn btn-success" onclick="add_report()"><i class="glyphicon glyphicon-plus"></i> Add Report</button>
        <?endif;?>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="1100">
            <thead>
                <tr>
                    <th>Report Name</th>
                    <th style="width:15px;">Group</th>
                    <th style="width:30px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
                <tr>
                    <th>Report Name</th>
                    <th>Group</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>
    </div>

<!--<script src="<?php echo base_url('assets/jquery/jquery-3.4.0.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap-3.4.1.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables-4/datatables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables-4/dataTables.bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>-->



<?
    $CI =& get_instance();
    $CI->load->library('encrypt');
?>

<script type="text/javascript">
var _base_url = '<?= base_url() ?>';
var save_method; //for save method string
var table;


// var csrfName = '';
// var csrfHash = '';
// var dataJson;

    
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    
    var dataJson = { [csrfName]: csrfHash };

$(document).ready(function() {
    //alert(JSON.stringify(dataJson));
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
    //         csrfHash = data.csrf_token;
    //         csrfName = data.crsf_name;
            
    //         dataJson = { [csrfName]: csrf_token };
            
    //         // $('#csrf').val(csrf_token);
    //     }
    // });
    
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
        "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
        "pageLength": 25,

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('index.php/Report/ajax_list')?>",
            "type": "POST",
            "data": dataJson
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false //set not orderable
        },
        ]

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
            
    //         // $('#csrf').val(csrf_token);
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

});



function add_report()
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
    
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Report'); // Set Title to Bootstrap modal title
}

function edit_report(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('index.php/Report/ajax_edit/')?>" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id"]').val(data.id);
            $('[name="name"]').val(data.name);
            $('[name="url"]').val(data.url);
            $('[name="group"]').val(data.group);
            
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
            
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit Report'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    
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
            csrf_name = data.csrf_name;
            
            dataJson[csrf_name] = csrf_token;
            
            table = $('#table').DataTable({ 
        
                "processing": true, //Feature control the processing indicator.
                "serverSide": true, //Feature control DataTables' server-side processing mode.
                "order": [], //Initial no order.
                "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
                "pageLength": 25,
        
                // Load data for the table's content from an Ajax source
                "ajax": {
                    "url": "<?php echo site_url('index.php/Report/ajax_list')?>",
                    "type": "POST",
                    "data": dataJson
                },
        
                //Set column definition initialisation properties.
                "columnDefs": [
                { 
                    "targets": [ -1 ], //last column
                    "orderable": false //set not orderable
                },
                ]
        
            });
                    
            //alert(JSON.stringify(dataJson));
            
            //table.ajax.reload(null,false);
        }
    });
    //location.reload();
    //alert('test');
    // table.clear();
    //table.empty();
    
}

function save()
{
    
    
    $('#btnSave').text('saving...'); //change button text
            $('#btnSave').attr('disabled',true); //set button disable 
            var url;
        
            if(save_method == 'add') {
                url = "<?php echo site_url('index.php/Report/ajax_add')?>";
            } else {
                url = "<?php echo site_url('index.php/Report/ajax_update')?>";
            }
        
            // ajax adding data to database
            $.ajax({
                url : url,
                type: "POST",
                data: $('#form').serialize(),
                dataType: "JSON",
                //  beforeSend:function(xhr, settings){
                //  settings.data += '&crsf_name='+csrf_token;
                //  },
                success: function(data)
                {
        
                    if(data.status) //if success close modal and reload ajax table
                    {
                        $('#modal_form').modal('hide');
                        reload_table();
                        //location.reload();
                    }else{
                        serr = 'Fail to save data.';
                        try {
                          serr = serr + ' ' + data.error;
                        } catch(e) {}
                        
                        $.ajax({
                            'async': true,
                            'type': "GET",
                            'dataType': 'json',
                            'url': "<?php echo base_url('Main_menu/get_csrf'); ?>",
                            'success': function (data) {
                                tmp = data;
                                csrf_token = data.csrf_token;
                                
                                dataJson = { [csrfName]: csrf_token };
                                
                                $('#csrf').val(csrf_token);
                            }
                        });
                        
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

function delete_report(id, report)
{
    if(confirm('Are you sure delete this data: ' + report + ' ?'))
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
                csrf_name = data.csrf_name;
                
                dataJson = { [csrf_name]: csrf_token }
                
                $.ajax({
                    url : "<?php echo site_url('index.php/Report/ajax_delete')?>/"+id,
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

function contract_due_date(){
    $('#form_contract_due_date')[0].reset(); // reset form on modals
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
            
            dataJson = { [csrfName]: csrf_token }
            
            $('#csrf_con_due_date').val(csrf_token);
        }
    });
    
    $('#modal_form_contract_due_date').modal('show'); // show bootstrap modal
    $('.modal-title').text('Contract Due Date Report'); 
}

function run_contract_due_date()
{
    var status = $('#con_status_dd')[0].value;
    var day = $('#con_day_dd')[0].value;
    var cutOff = $('#con_cutOffDate_dd')[0].value;
    
    if($('#con_day_dd')[0].value == null || $('#con_day_dd')[0].value == ''){
        alert("Due date can not empty");
    }else if($('#con_cutOffDate_dd')[0].value == null || $('#con_cutOffDate_dd')[0].value == ''){
        alert("Cut Off Date To can not empty");
    }else{
        window.location = _base_url + 'index.php/Report/ajax_run_contract_due_date/' + status + '/' + day + '/' + cutOff;
    }
    
}

function contract_summary(){
    $('#form_contract_summary')[0].reset(); // reset form on modals
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
            
            dataJson = { [csrfName]: csrf_token }
            
            $('#csrf_con_summary').val(csrf_token);
        }
    });
    
    $('#modal_form_contract_summary').modal('show'); // show bootstrap modal
    $('.modal-title').text('Contract Summary Report'); 
}

function run_contract_summary()
{
    var status = $('#status')[0].value;
    
    var from = $('#expDateFrom')[0].value;
    
    var to = $('#expDateTo')[0].value;
    
    var cutOff = $('#cutOffDate')[0].value;

    if($('#expDateFrom')[0].value == null || $('#expDateFrom')[0].value == ''){
        alert("Expired Date From can not empty");
    }else if($('#expDateTo')[0].value == null || $('#expDateTo')[0].value == ''){
        alert("Expired Date To can not empty");
    }else if($('#cutOffDate')[0].value == null || $('#cutOffDate')[0].value == ''){
        alert("Cut Off Date To can not empty");
    }else{
        window.location = _base_url + 'index.php/Report/ajax_run_contract_summary/' + status + '/' + from + '/' + to + '/' + cutOff;
        // var _url = _base_url + 'index.php/Report/ajax_run_contract_summary_new';
        // param = 'status=' + status + '&from=' + from + '&to=' + to + '&cutOff=' + cutOff;
        // $.ajax({
        //   url: _url,
        //   data: param
        // });
    }
    
}

function license_due_date(){
    $('#form_license_due_date')[0].reset(); // reset form on modals
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
            
            dataJson = { [csrfName]: csrf_token }
            
            $('#csrf_lic_due_date').val(csrf_token);
        }
    });
    
    $('#modal_form_license_due_date').modal('show'); // show bootstrap modal
    $('.modal-title').text('License Due Date Report'); 
}

function run_license_due_date(){
    var status = $('#status_license_dd')[0].value;
    var day = $('#day_license_dd')[0].value;
    var cutOff = $('#cutOffDate_license_dd')[0].value;
    
    if($('#day_license_dd')[0].value == null || $('#day_license_dd')[0].value == ''){
        alert("Due date from can not empty");
    }else if($('#cutOffDate_license_dd')[0].value == null || $('#cutOffDate_license_dd')[0].value == ''){
        alert("Cut Off Date To can not empty");
    }else{
        window.location = _base_url + 'index.php/Report/ajax_run_license_due_date/' + status + '/' + day + '/' + cutOff;
    }
}

function license_summary(){
    $('#form_license_summary')[0].reset(); // reset form on modals
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
            
            dataJson = { [csrfName]: csrf_token }
            
            $('#csrf_lic_summary').val(csrf_token);
        }
    });
    
    $('#modal_form_license_summary').modal('show'); // show bootstrap modal
    $('.modal-title').text('License Summary Report'); 
}

function run_license_summary(){
    var status = $('#status_license')[0].value;
    var from = $('#expDateFrom_license')[0].value;
    var to = $('#expDateTo_license')[0].value;
    var cutOff = $('#cutOffDate_license')[0].value;
    
    if($('#expDateFrom_license')[0].value == null || $('#expDateFrom_license')[0].value == ''){
        alert("Expired Date From can not empty");
    }else if($('#expDateTo_license')[0].value == null || $('#expDateTo_license')[0].value == ''){
        alert("Expired Date To can not empty");
    }else if($('#cutOffDate_license')[0].value == null || $('#cutOffDate_license')[0].value == ''){
        alert("Cut Off Date To can not empty");
    }else{
        window.location = _base_url + 'index.php/Report/ajax_run_license_summary/' + status + '/' + from + '/' + to + '/' + cutOff;
    }
}

</script>
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Report Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" id="csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <input type="hidden" name="id"/> 
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">ID</label>
                            <div class="col-md-9">
                                <input name="id" placeholder="ID" class="form-control" type="text" readonly="true">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Name</label>
                            <div class="col-md-9">
                                <input name="name" placeholder="Report Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">URL</label>
                            <div class="col-md-9">
                                <input name="url" placeholder="URL" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Group</label>
                            <div class="col-md-9">
                                <input name="group" placeholder="Group" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_contract_summary" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Contract Summary Report</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="<?=base_url()?>index.php/Report/ajax_run_contract_summary_new" id="form_contract_summary" class="form-horizontal" method="post" class="form-horizontal" enctype="multipart/form-data" target="_blank">
                    <input type="hidden" id="csrf_con_summary" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"  />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Status</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('status', $status_list, '-1', 'id="status" name="status" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Expired Date From</label>
                            <div class="col-md-9">
                                <input id="expDateFrom" name="expDateFrom" placeholder="yyyy-mm-dd" class="form-control datepicker" data-date-format="mm-dd-yyyy" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Expired Date To</label>
                            <div class="col-md-9">
                                <input id="expDateTo" name="expDateTo" placeholder="yyyy-mm-dd" class="form-control datepicker" data-date-format="mm-dd-yyyy" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Cut Off Date</label>
                            <div class="col-md-9">
                                <input id="cutOffDate" name="cutOffDate" placeholder="yyyy-mm-dd" class="form-control datepicker" data-date-format="mm-dd-yyyy" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                
            </div>
            <div class="modal-footer">
                <button type="submit" id="btnSave_contract_summary" class="btn btn-primary" data-dismiss="modal">Run</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_contract_due_date" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Contract Due Date Report</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="<?=base_url()?>index.php/Report/ajax_run_contract_due_date_new" class="form-horizontal" method="post" class="form-horizontal" enctype="multipart/form-data" id="form_contract_due_date" target="_blank">
                    <input type="hidden" id="csrf_con_due_date" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Status</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('con_status_dd', $status_list, '-1', 'id="con_status_dd" name="con_status_dd" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Due Date</label>
                            <div class="col-md-9">
                                <input id="con_day_dd" name="con_day_dd" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Cut Off Date</label>
                            <div class="col-md-9">
                                <input id="con_cutOffDate_dd" name="con_cutOffDate_dd" placeholder="yyyy-mm-dd" class="form-control datepicker" data-date-format="mm-dd-yyyy" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btnSave_contract_due_date" class="btn btn-primary">Run</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_license_summary" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">License Summary Report</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="<?=base_url()?>index.php/Report/ajax_run_license_summary_new" id="form_license_summary" class="form-horizontal" method="post" class="form-horizontal" enctype="multipart/form-data" target="_blank">
                    <input type="hidden" id="csrf_lic_summary" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Status</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('status_license', $status_license_list, '-1', 'id="status_license" name="status_license" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Expired Date From</label>
                            <div class="col-md-9">
                                <input id="expDateFrom_license" name="expDateFrom_license" placeholder="yyyy-mm-dd" class="form-control datepicker" data-date-format="mm-dd-yyyy" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Expired Date To</label>
                            <div class="col-md-9">
                                <input id="expDateTo_license" name="expDateTo_license" placeholder="yyyy-mm-dd" class="form-control datepicker" data-date-format="mm-dd-yyyy" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Cut Off Date</label>
                            <div class="col-md-9">
                                <input id="cutOffDate_license" name="cutOffDate_license" placeholder="yyyy-mm-dd" class="form-control datepicker" data-date-format="mm-dd-yyyy" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btnSave_license_summary" class="btn btn-primary">Run</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_license_due_date" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">License Due Date Report</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="<?=base_url()?>index.php/Report/ajax_run_license_due_date_new" class="form-horizontal" method="post" class="form-horizontal" enctype="multipart/form-data" target="_blank" id="form_license_due_date">
                    <input type="hidden" id="csrf_lic_due_date" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Status</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('status_license_dd', $status_license_list, '-1', 'id="status_license_dd" name="status_license_dd" class="form-control" type="text"'); ?>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Due Date</label>
                            <div class="col-md-9">
                                <input id="day_license_dd" name="day_license_dd" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Cut Off Date</label>
                            <div class="col-md-9">
                                <input id="cutOffDate_license_dd" name="cutOffDate_license_dd" placeholder="yyyy-mm-dd" class="form-control datepicker" data-date-format="mm-dd-yyyy" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" id="btnSave_license_due_date" class="btn btn-primary">Run</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
                </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>