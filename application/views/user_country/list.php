<div class="container">
        <span width="100" style="background-color: #e0e0e0; display: block;">
            &nbsp;<font size="5" style="font-weight: bold; color: #737373">User Country Management : <?echo $user_name?></font>
        </span>
        <br/>
        <button class="btn btn-warning" onclick="back_to_user()"><i class="glyphicon glyphicon-backward"></i> Back</button>
        <button class="btn btn-success" onclick="add_sub_user()"><i class="glyphicon glyphicon-plus"></i> Add</button>
        <button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>
        <br />
        <br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="1100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Country</th>
                    <th style="width:30px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Country</th>
                    <th>Action</th>
                </tr>
            </tfoot>
        </table>
</div>
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
            "url": "<?php echo site_url('User_country/ajax_list/'.$user_id)?>",
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

function back_to_user()
{
    window.location = _base_url + 'User/';
}

function add_sub_user()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add User Country'); // Set Title to Bootstrap modal title
}

function edit_user_country(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('User_country/ajax_edit/')?>" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="userId"]').val(data.user_id);
            $('[name="country"]').val(data.country);
            $('[name="oldCountry"]').val(data.country);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit User Country'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
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
        url = "<?php echo site_url('User_country/ajax_add')?>";
    } else {
        url = "<?php echo site_url('User_country/ajax_update')?>";
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
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_user_country(id, country)
{
    if(confirm('Are you sure delete this data: ' + country + ' ?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('User_country/ajax_delete')?>/"+ id,
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
}

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" user="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">User Country Form</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />
                    <div class="form-body">
                        <input name="id"  class="form-control" type="hidden" readonly="true">
                        <input name="oldCountry"  class="form-control" type="hidden" readonly="true">
                        <div class="form-group">
                            <label class="control-label col-md-3">User</label>
                            <div class="col-md-9">
                                <input value="<?=$user_id?>" name="userId" placeholder="User Id" class="form-control" type="text" readonly="true">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Country</label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('country', $country_list, '', 'name="country" placeholder="Country" class="form-control" type="text"'); ?>
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
</body>
</html>