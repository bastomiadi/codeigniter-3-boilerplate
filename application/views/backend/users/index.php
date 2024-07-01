<div class="container">
    <h1>Manage Users</h1>
    <button id="btnAddUser">Add User</button>
    <table id="usersTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>

<!-- User Modal -->
<div id="userModal" class="modal">
    <form id="userForm">
        <input type="hidden" name="id" id="user_id">
        <label for="username">Username</label>
        <input type="text" name="username" id="username">
        <label for="email">Email</label>
        <input type="email" name="email" id="email">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <label for="role_id">Role</label>
        <select name="role_id" id="role_id" class="select2">
            <option value="1">Superadmin</option>
            <option value="2">Admin</option>
            <option value="3">Member</option>
        </select>
        <button type="submit">Save</button>
    </form>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTables
    var table = $('#usersTable').DataTable({
        ajax: {
            url: '<?= site_url('backend/users/fetch_users') ?>',
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            { data: 'username' },
            { data: 'email' },
            { data: 'role_name' },
            { data: 'id', render: function(data, type, row) {
                return `
                    <button class="btnEdit" data-id="${data}">Edit</button>
                    <button class="btnDelete" data-id="${data}">Delete</button>
                `;
            }}
        ]
    });

    // Initialize Select2
    $('.select2').select2();

    // Add User Button Click
    $('#btnAddUser').on('click', function() {
        $('#userForm')[0].reset();
        $('#userModal').modal('show');
    });

    // Edit User Button Click
    $('#usersTable').on('click', '.btnEdit', function() {
        var id = $(this).data('id');
        $.ajax({
            url: '<?= site_url('backend/users/edit') ?>/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#user_id').val(data.id);
                $('#username').val(data.username);
                $('#email').val(data.email);
                $('#role_id').val(data.role_id).trigger('change');
                $('#userModal').modal('show');
            }
        });
    });

    // Save User (Add/Edit)
    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#user_id').val();
        var url = id ? '<?= site_url('backend/users/update') ?>' : '<?= site_url('backend/users/store') ?>';
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                $('#userModal').modal('hide');
                Swal.fire({
                    icon: response.status ? 'success' : 'error',
                    title: response.status ? 'Success' : 'Error',
                    text: response.status ? 'User saved successfully' : 'An error occurred'
                });
                table.ajax.reload();
            }
        });
    });

    // Delete User Button Click
    $('#usersTable').on('click', '.btnDelete', function() {
        var id = $(this).data('id');
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
                    url: '<?= site_url('backend/users/delete') ?>/' + id,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        Swal.fire({
                            icon: response.status ? 'success' : 'error',
                            title: response.status ? 'Deleted!' : 'Error',
                            text: response.status ? 'User has been deleted.' : 'An error occurred'
                        });
                        table.ajax.reload();
                    }
                });
            }
        });
    });
});
</script>
