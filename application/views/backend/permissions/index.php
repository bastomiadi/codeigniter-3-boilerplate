<div class="container">
    <h1>Permissions</h1>
    <button class="btn btn-success" onclick="add_permission()">Add Permission</button>
    <br><br>
    <table id="permissionsTable" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Permission Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form" class="form-horizontal">
                <div class="modal-header">
                    <h3 class="modal-title">Add Permission</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="permission_id"/>
                    <div class="form-group">
                        <label>Permission Name</label>
                        <input name="permission_name" class="form-control" type="text">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
var table;

$(document).ready(function() {
    table = $('#permissionsTable').DataTable({
        "ajax": {
            "url": "<?= site_url('backend/permissions/fetch_permissions') ?>",
            "type": "GET"
        }
    });
});

function add_permission() {
    $('#form')[0].reset(); // reset form on modals
    $('#modal_form').modal('show'); // show bootstrap modal
}

function save() {
    var url;
    if($('[name="permission_id"]').val() == '') {
        url = "<?= site_url('backend/permissions/save') ?>";
    } else {
        url = "<?= site_url('backend/permissions/save') ?>";
    }

    // ajax adding data to database
    $.ajax({
        url: url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function(data) {
            if(data.status) {
                $('#modal_form').modal('hide');
                table.ajax.reload();
                Swal.fire('Success!', 'Data has been saved.', 'success');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            Swal.fire('Error!', 'Error adding / updating data', 'error');
        }
    });
}

function delete_permission(permission_id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "<?= site_url('backend/permissions/delete/') ?>" + permission_id,
                type: "POST",
                dataType: "JSON",
                success: function(data) {
                    if(data.status) {
                        table.ajax.reload();
                        Swal.fire('Deleted!', 'Your file has been deleted.', 'success');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire('Error!', 'Error deleting data', 'error');
                }
            });
        }
    });
}
</script>