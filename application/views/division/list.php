<!DOCTYPE html>
        <span width="100" style="background-color: #e0e0e0; display: block;">
            &nbsp;<font size="5" style="font-weight: bold; color: #737373">Division Management :  <?echo $company_name?></font>
        </span>
        <br/>
        <button class="btn btn-warning" onclick="back_to_company()"><i class="glyphicon glyphicon-backward"></i> Back</button>
        <?if($this->session->userdata("role") == 1 || $this->session->userdata("role") == 2):?>
        <button class="btn btn-success" onclick="add_div()"><i class="glyphicon glyphicon-plus"></i> Add Division</button>
        <!--<button class="btn btn-success" onclick="upload_div()"><i class="glyphicon glyphicon-plus"></i> Upload Division</button>-->
        <?endif;?>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="1100">
            <thead>
                <tr>
                    <th>Division ID</th>
                    <th>Division Name</th>
                    <th>Note</th>
                    <th style="width:30px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
                <tr>
                    <th>Division ID</th>
                    <th>Division Name</th>
                    <th>Note</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>
    </div>

<!--<script src="<?php echo base_url('assets/jquery/jquery-2.1.4.min.js')?>"></script>-->
<script src="<?php echo base_url('assets/jquery/jquery-3.0.0.min.js')?>"></script>

<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<!--<script src="<?php echo base_url('bootstrap-4.3.1/js/bootstrap.min.js')?>"></script>-->

<!--<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>-->
<!--<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>-->
<script src="<?php echo base_url('assets/datatables-4/datatables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables-4/dataTables.bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>


<script type="text/javascript">
var _base_url = '<?= base_url() ?>';
var save_method; //for save method string
var table;

    
    var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
    csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
    
    var dataJson = { [csrfName]: csrfHash };
    
$(document).ready(function() {

    
    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('index.php/Division/ajax_list/'.$company_id)?>",
            "type": "POST",
            data : dataJson
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false //set not orderable
        },
        ]

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

});




function back_to_company()
{
    window.location = _base_url + 'index.php/Company/';
}

function add_div()
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
            'url': "<?php echo base_url('main/get_csrf'); ?>",
            'success': function (data) {
                tmp = data;
                csrf_token = data.csrf_token;
                
                dataJson = { [csrfName]: csrf_token };
                
                $('#csrf').val(csrf_token);
            }
        });
    
//    $('[name="divId"]').attr("readOnly", false);
    
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Division'); // Set Title to Bootstrap modal title
}

function upload_div()
{
    window.location = _base_url + 'index.php/Division/new_upload_div/';
}

function edit_div(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
//    $('#modal_form').modal('show'); // show bootstrap modal
//    $('.modal-title').text('Edit Division'); // Set Title to Bootstrap modal title

    $.ajax({
            'async': true,
            'type': "GET",
            'dataType': 'json',
            //first ajax url to get csrf token
            'url': "<?php echo base_url('main/get_csrf'); ?>",
            'success': function (data) {
                tmp = data;
                csrf_token = data.csrf_token;
                
                dataJson = { [csrfName]: csrf_token };
                
                $('#csrf').val(csrf_token);
                
                //Ajax Load data from ajax
                $.ajax({
                    url : "<?php echo site_url('index.php/Division/ajax_edit/')?>/" + id.replace('/', 'slash'),
                    type: "GET",
                    dataType: "JSON",
                    success: function(data)
                    {
            
                        $('[name="divId"]').val(data.division_id);
                        $('[name="divName"]').val(data.division_name);
                        $('[name="companyId"]').val(data.company_id);
                        $('[name="note"]').val(data.note);
            //            $('[name="divId"]').attr("readOnly", true);
                        $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                        $('.modal-title').text('Edit Division'); // Set title to Bootstrap modal title
            
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
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('index.php/Division/ajax_add')?>";
    } else {
        url = "<?php echo site_url('index.php/Division/ajax_update')?>";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form').serialize(),
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
                    'url': "<?php echo base_url('main/get_csrf'); ?>",
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
                    'url': "<?php echo base_url('main/get_csrf'); ?>",
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

function delete_div(id, emp)
{
    if(confirm('Are you sure delete this emp: ' + emp + ' ?'))
    {
        $.ajax({
            'async': true,
            'type': "GET",
            'dataType': 'json',
            //first ajax url to get csrf token
            'url': "<?php echo base_url('main/get_csrf'); ?>",
            'success': function (data) {
                tmp = data;
                csrf_token = data.csrf_token;
                
                dataJson = { [csrfName]: csrf_token }
                
                // ajax delete data to database
                $.ajax({
                    url : "<?php echo site_url('index.php/Division/ajax_delete')?>/"+ id.replace('/', 'slash'),
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

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Division Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" id="csrf" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Division ID</label>
                            <div class="col-md-9">
                                <input name="divId" placeholder="Division ID" class="form-control" type="text" readonly="true">
                                <input name="companyId" value="<?=$company_id?>" class="form-control" type="hidden" readonly="true">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Division Name</label>
                            <div class="col-md-9">
                                <input name="divName" placeholder="Division Name" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Note</label>
                            <div class="col-md-9">
                                <textarea name="note" placeholder="Note" class="form-control" type="text"></textarea>
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
