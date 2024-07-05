<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small Box (Stat card) -->
        <div class="row">
            <div class="col-md-12">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#addUserModal">
                    Create User
                </button>
                <!-- DataTables Card -->
                <div class="card">
                    <div class="card-body">
                        <table id="user-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
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

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form to add User -->
                <form id="addUserForm">
                    <div class="form-group">
                        <label for="UserName">User Name</label>
                        <input type="text" class="form-control" id="UserName" name="UserName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form to edit User -->
                <form id="editUserForm">
                    <input type="hidden" id="editUserId" name="editUserId">
                    <div class="form-group">
                        <label for="editUserName">User Name</label>
                        <input type="text" class="form-control" id="editUserName" name="editUserName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete User Modal -->
<div class="modal fade" id="deleteUserModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this User?</p>
                <input type="hidden" id="deleteUserId" name="deleteUserId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteUser">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- AJAX and DataTable initialization -->
<script>
$(document).ready(function() {
    // DataTable initialization
    $('#user-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "<?php echo base_url('backend/users/get_users'); ?>",
            type: "POST"
        },
        "columns": [
            { "data": "id" },
            { "data": "username" },
            { "data": "email" },
            { "data": "actions" }
        ]
    });

    // Add User Modal
    $('#addUserModal').on('shown.bs.modal', function () {
        //$('#userName').focus();
    });

    // Edit User Modal
    $('#editUserModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var UserId = button.data('id'); // Extract User ID from data-id attribute
        var UserName = button.data('name'); // Extract User name from data-name attribute
        $('#editUserId').val(UserId);
        $('#editUserName').val(UserName);
    });

    // Delete User Modal
    $('#deleteUserModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var UserId = button.data('id'); // Extract User ID from data-id attribute
        $('#deleteUserId').val(UserId);
    });

    // Add User Form Submission via AJAX
    $('#addUserForm').submit(function(e) {
        e.preventDefault();
        var UserName = $('#userName').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("backend/users/add_user"); ?>',
            data: { UserName: UserName },
            success: function(response) {
                $('#addUserModal').modal('hide');
                $('#user-table').DataTable().ajax.reload();
                Swal.fire(
                    'Added!',
                    'The User has been added.',
                    'success'
                );
            }
        });
    });

    // Edit User Form Submission via AJAX
    $('#editUserForm').submit(function(e) {
        e.preventDefault();
        var UserId = $('#editUserId').val();
        var UserName = $('#editUserName').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("backend/users/edit_user"); ?>',
            data: { id: UserId, UserName: UserName },
            success: function(response) {
                $('#editUserModal').modal('hide');
                $('#user-table').DataTable().ajax.reload();
                Swal.fire(
                    'Updated!',
                    'The User has been updated.',
                    'success'
                );
            }
        });
    });

    // Delete User via AJAX
    $('#confirmDeleteUser').click(function() {
        var UserId = $('#deleteUserId').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("backend/users/delete_user"); ?>',
            data: { id: UserId },
            success: function(response) {
                $('#deleteUserModal').modal('hide');
                $('#user-table').DataTable().ajax.reload();
                Swal.fire(
                    'Deleted!',
                    'The User has been deleted.',
                    'success'
                );
            }
        });
    });
});
</script>
