<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small Box (Stat card) -->
        <div class="row">
            <div class="col-md-12">
                <!-- Button trigger modal -->
                    <button class="btn btn-success mb-2" id="addRolePermissionBtn">Roles and Permissions</button>
                    <div class="card">
                        <div class="card-body">
                            <table id="rolePermissionTable" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Role</th>
                                <th>Permission</th>
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

<!-- Modal -->
<div class="modal fade" id="rolePermissionModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rolePermissionForm" class="form-horizontal">
                <div class="modal-header">
                    <h3 class="modal-title">Add Role/Permission</h3>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="role_id">Role:</label>
                        <select name="role_id" id="role_id" class="form-control"></select>
                    </div>
                    <div class="form-group">
                        <label for="permission_id">Permission:</label>
                        <input name="permission_id" id="permission_id" class="form-control" type="text">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnSave" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <div id="rolePermissionModal" class="modal">
    <form id="rolePermissionForm">
        <label for="role_id">Role:</label>
        <select id="role_id" name="role_id"></select>
        
        <select id="permission_id" name="permission_id"></select>
        <button type="submit">Save</button>
    </form>
</div> -->

<script>
    $(document).ready(function() {
    var table = $('#rolePermissionTable').DataTable({
        ajax: '<?= site_url('backend/Roles_Permission/get_role_permissions') ?>',
        columns: [
            { data: 'role_name' },
            { data: 'permission_name' },
            {
                data: null,
                render: function(data, type, row) {
                    return `<button class="editBtn" data-role_id="${data.role_id}" data-permission_id="${data.permission_id}">Edit</button>
                            <button class="deleteBtn" data-role_id="${data.role_id}" data-permission_id="${data.permission_id}">Delete</button>`;
                }
            }
        ]
    });

    $('#addRolePermissionBtn').on('click', function() {
        $('#rolePermissionForm')[0].reset();
        $('#rolePermissionForm').data('action', 'add');
        loadRolesAndPermissions();
        $('#rolePermissionModal').show();
    });

    $('#rolePermissionForm').on('submit', function(e) {
    e.preventDefault();
    var role_id = $('#role_id').val();
    var permission_id = $('#permission_id').val();
    var action = $('#rolePermissionForm').data('action');

    if (!role_id || !permission_id) {
        Swal.fire('Error', 'Role and Permission cannot be empty.', 'error');
        return;
    }

    var url = action === 'add' ? '<?= site_url('backend/Roles_Permission/add_role_permission') ?>' : '<?= site_url('backend/Roles_Permission/update_role_permission') ?>';
    $.ajax({
        url: url,
        type: 'POST',
        data: $('#rolePermissionForm').serialize(),
        success: function(response) {
            var res = JSON.parse(response);
            if (res.status) {
                $('#rolePermissionModal').hide();
                table.ajax.reload();
                Swal.fire('Success', 'Role Permission saved successfully', 'success');
            } else {
                Swal.fire('Error', res.message + ' ' + (res.error ? JSON.stringify(res.error) : ''), 'error');
            }
        },
        error: function(xhr, status, error) {
            console.log('AJAX Error: ' + error);
            Swal.fire('Error', 'Failed to update role permission. Please try again.', 'error');
        }
    });
});

    $('#rolePermissionTable').on('click', '.editBtn', function() {
        var role_id = $(this).data('role_id');
        var permission_id = $(this).data('permission_id');
        loadRolesAndPermissions(role_id, permission_id);
        $('#rolePermissionForm').data('action', 'edit');
        $('#rolePermissionModal').show();
    });

    $('#rolePermissionTable').on('click', '.deleteBtn', function() {
        var role_id = $(this).data('role_id');
        var permission_id = $(this).data('permission_id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= site_url('backend/Roles_Permission/delete_role_permission') ?>',
                    type: 'POST',
                    data: { role_id: role_id, permission_id: permission_id },
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status) {
                            table.ajax.reload();
                            Swal.fire('Deleted!', 'Role Permission has been deleted.', 'success');
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    }
                });
            }
        });
    });

    function loadRolesAndPermissions(role_id = null, permission_id = null) {
        $('#role_id').empty();
        $('#permission_id').empty();
        $.ajax({
            url: '<?= site_url('backend/Roles_Permission/get_roles') ?>',
            success: function(data) {
                var roles = JSON.parse(data);
                roles.forEach(function(role) {
                    $('#role_id').append(new Option(role.role_name, role.role_id, role.role_id == role_id));
                });
            }
        });
        $.ajax({
            url: '<?= site_url('backend/Roles_Permission/get_permissions') ?>',
            success: function(data) {
                var permissions = JSON.parse(data);
                permissions.forEach(function(permission) {
                    $('#permission_id').append(new Option(permission.permission_name, permission.permission_id, permission.permission_id == permission_id));
                });
            }
        });
    }
});

</script>