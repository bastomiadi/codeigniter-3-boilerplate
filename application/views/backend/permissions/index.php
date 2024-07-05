<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small Box (Stat card) -->
        <div class="row">
            <div class="col-md-12">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#addPermissionModal">
                    Create Permission
                </button>
                <!-- DataTables Card -->
                <div class="card">
                    <div class="card-body">
                        <table id="permission-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Populate table rows dynamically using AJAX -->
                        </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- Add Permission Modal -->
<div class="modal fade" id="addPermissionModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Permission</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form to add Permission -->
                <form id="addPermissionForm">
                    <div class="form-group">
                        <label for="permissionName">Permission Name</label>
                        <input type="text" class="form-control" id="permissionName" name="permissionName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Permission</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Permission Modal -->
<div class="modal fade" id="editPermissionModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Permission</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form to edit Permission -->
                <form id="editPermissionForm">
                    <input type="hidden" id="editPermissionId" name="editPermissionId">
                    <div class="form-group">
                        <label for="editpermissionName">Permission Name</label>
                        <input type="text" class="form-control" id="editpermissionName" name="editpermissionName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Permission Modal -->
<div class="modal fade" id="deletePermissionModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Permission</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this Permission?</p>
                <input type="hidden" id="deletePermissionId" name="deletePermissionId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="confirmDeletePermission">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- AJAX and DataTable initialization -->
<script>
$(document).ready(function() {
    // DataTable initialization
    $('#permission-table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
        "dom": 'Bfrtip', // This ensures that the buttons are placed correctly
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "<?php echo base_url('backend/permissions/get_permissions'); ?>",
            type: "POST"
        },
        "columns": [
            { "data": "permission_id" },
            { "data": "permission_name" },
            { "data": "description" },
            { "data": "actions" }
        ]
    });

    // Add Permission Modal
    $('#addPermissionModal').on('shown.bs.modal', function () {
        $('#permissionName').focus();
    });

    // Edit Permission Modal
    $('#editPermissionModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var PermissionId = button.data('id'); // Extract Permission ID from data-id attribute
        var permissionName = button.data('name'); // Extract Permission name from data-name attribute
        $('#editPermissionId').val(PermissionId);
        $('#editpermissionName').val(permissionName);
    });

    // Delete Permission Modal
    $('#deletePermissionModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var PermissionId = button.data('id'); // Extract Permission ID from data-id attribute
        $('#deletePermissionId').val(PermissionId);
    });

    // Add Permission Form Submission via AJAX
    $('#addPermissionForm').submit(function(e) {
        e.preventDefault();
        var permissionName = $('#permissionName').val();
        console.log(permissionName);

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("backend/permissions/add_permission"); ?>',
            data: { permissionName: permissionName },
            success: function(response) {
                $('#addPermissionModal').modal('hide');
                $('#permission-table').DataTable().ajax.reload();
                Swal.fire(
                    'Added!',
                    'The Permission has been added.',
                    'success'
                );
            }
        });
    });

    // Edit Permission Form Submission via AJAX
    $('#editPermissionForm').submit(function(e) {
        e.preventDefault();
        var PermissionId = $('#editPermissionId').val();
        var permissionName = $('#editpermissionName').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("backend/permissions/edit_permission"); ?>',
            data: { id: PermissionId, permissionName: permissionName },
            success: function(response) {
                $('#editPermissionModal').modal('hide');
                $('#permission-table').DataTable().ajax.reload();
                Swal.fire(
                    'Updated!',
                    'The Permission has been updated.',
                    'success'
                );
            }
        });
    });

    // Delete Permission via AJAX
    $('#confirmDeletePermission').click(function() {
        var PermissionId = $('#deletePermissionId').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("backend/permissions/delete_permission"); ?>',
            data: { id: PermissionId },
            success: function(response) {
                $('#deletePermissionModal').modal('hide');
                $('#permission-table').DataTable().ajax.reload();
                Swal.fire(
                    'Deleted!',
                    'The Permission has been deleted.',
                    'success'
                );
            }
        });
    });
});
</script>
