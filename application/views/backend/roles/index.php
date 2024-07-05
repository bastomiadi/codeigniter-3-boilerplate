<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small Box (Stat card) -->
        <div class="row">
            <div class="col-md-12">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#addRoleModal">
                    Create Role
                </button>
                <!-- DataTables Card -->
                <div class="card">
                    <div class="card-body">
                        <table id="Role-table" class="table table-bordered table-hover">
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

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Role</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form to add Role -->
                <form id="addRoleForm">
                    <div class="form-group">
                        <label for="roleName">Role Name</label>
                        <input type="text" class="form-control" id="roleName" name="roleName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Role</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Role</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form to edit Role -->
                <form id="editRoleForm">
                    <input type="hidden" id="editRoleId" name="editRoleId">
                    <div class="form-group">
                        <label for="editroleName">Role Name</label>
                        <input type="text" class="form-control" id="editroleName" name="editroleName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Role Modal -->
<div class="modal fade" id="deleteRoleModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Role</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this Role?</p>
                <input type="hidden" id="deleteRoleId" name="deleteRoleId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteRole">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- AJAX and DataTable initialization -->
<script>
$(document).ready(function() {
    // DataTable initialization
    $('#Role-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "<?php echo base_url('backend/roles/get_roles'); ?>",
            type: "POST"
        },
        "columns": [
            { "data": "role_id" },
            { "data": "role_name" },
            { "data": "description" },
            { "data": "actions" }
        ]
    });

    // Add Role Modal
    $('#addRoleModal').on('shown.bs.modal', function () {
        //$('#roleName').focus();
    });

    // Edit Role Modal
    $('#editRoleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var RoleId = button.data('id'); // Extract Role ID from data-id attribute
        var roleName = button.data('name'); // Extract Role name from data-name attribute
        $('#editRoleId').val(RoleId);
        $('#editroleName').val(roleName);
    });

    // Delete Role Modal
    $('#deleteRoleModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var RoleId = button.data('id'); // Extract Role ID from data-id attribute
        $('#deleteRoleId').val(RoleId);
    });

    // Add Role Form Submission via AJAX
    $('#addRoleForm').submit(function(e) {
        e.preventDefault();
        var roleName = $('#roleName').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("backend/roles/add_role"); ?>',
            data: { roleName: roleName },
            success: function(response) {
                $('#addRoleModal').modal('hide');
                $('#Role-table').DataTable().ajax.reload();
                Swal.fire(
                    'Added!',
                    'The Role has been added.',
                    'success'
                );
            }
        });
    });

    // Edit Role Form Submission via AJAX
    $('#editRoleForm').submit(function(e) {
        e.preventDefault();
        var RoleId = $('#editRoleId').val();
        var roleName = $('#editroleName').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("backend/roles/edit_role"); ?>',
            data: { id: RoleId, roleName: roleName },
            success: function(response) {
                $('#editRoleModal').modal('hide');
                $('#Role-table').DataTable().ajax.reload();
                Swal.fire(
                    'Updated!',
                    'The Role has been updated.',
                    'success'
                );
            }
        });
    });

    // Delete Role via AJAX
    $('#confirmDeleteRole').click(function() {
        var RoleId = $('#deleteRoleId').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("backend/roles/delete_role"); ?>',
            data: { id: RoleId },
            success: function(response) {
                $('#deleteRoleModal').modal('hide');
                $('#Role-table').DataTable().ajax.reload();
                Swal.fire(
                    'Deleted!',
                    'The Role has been deleted.',
                    'success'
                );
            }
        });
    });
});
</script>
