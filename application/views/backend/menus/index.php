<?php 
function generate_menu_options($menus, $level = 0) {
    $output = '';
    foreach ($menus as $menu) {
        $indent = str_repeat('--', $level);
        $output .= '<option value="' . $menu->menu_id . '">' . $indent . ' ' . $menu->menu_name . '</option>';
        if (!empty($menu->children)) {
            $output .= generate_menu_options($menu->children, $level + 1);
        }
    }
    return $output;
}
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small Box (Stat card) -->
        <div class="row">
            <div class="col-md-12">
                <!-- Button trigger modal -->
                <button class="btn btn-success mb-2" onclick="add_menu()">Add Menu</button>
                <!-- DataTables Card -->
                <div class="card">
                    <div class="card-body">
                        <table id="menusTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>URL</th>
                                    <th>Icon</th>
                                    <th>Parent</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
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

<!-- Modal for Add/Edit Menu -->
<div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="menuModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuModalLabel">Add Menu</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="menuForm">
                <div class="form-group">
                    <label for="menu_name">Menu Name</label>
                    <input type="text" name="menu_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="menu_url">Menu URL</label>
                    <input type="text" name="menu_url" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="menu_icon">Menu Icon</label>
                    <input type="text" name="menu_icon" class="form-control">
                </div>
                <!-- <div class="form-group">
                    <label for="parent_id">Parent Menu</label>
                    <select name="parent_id" class="form-control select2">
                        <option value="">None</option>
                        <?php foreach ($menus as $menu): ?>
                            <option value="<?php echo $menu->menu_id; ?>"><?php echo $menu->menu_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div> -->
                <div class="form-group">
                    <label for="parent_id">Parent Menu</label>
                    <select name="parent_id" class="form-control select2">
                        <option value="">None</option>
                        <?php echo generate_menu_options($menus); ?>
                    </select>
                </div>
                <input type="hidden" name="menu_id">
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="save_menu()">Save</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var table;

    $(document).ready(function() {
         // Initialize DataTable
         table = $('#menusTable').DataTable({
            "ajax": {
                "url": "<?php echo site_url('backend/menus/get_menus'); ?>",
                "type": "GET",
                "dataSrc": function(json) {
                    // Log the response to the console
                    console.log(json);

                    // Check for JSON errors
                    if (typeof json !== 'object' || json === null) {
                        console.error('Invalid JSON response', json);
                        return [];
                    }

                    return json.data;
                }
            },
            "columns": [
                { "data": "menu_id" },
                { "data": "menu_name" },
                { "data": "menu_url" },
                { "data": "menu_icon" },
                { "data": "parent_id" },
                {
                    "data": null,
                    "defaultContent": '<button class="btn btn-sm btn-primary" onclick="edit_menu(this)">Edit</button> <button class="btn btn-sm btn-danger" onclick="delete_menu(this)">Delete</button>'
                }
            ]
        });

        // Initialize Select2
        $('.select2').select2();

    });

    function add_menu() {
        $('#menuForm')[0].reset();
        $('#menuModal').modal('show');
        $('#menuModalLabel').text('Add Menu');
    }

    function edit_menu(element) {
        var data = table.row($(element).parents('tr')).data();
        $('[name="menu_id"]').val(data.menu_id);
        $('[name="menu_name"]').val(data.menu_name);
        $('[name="menu_url"]').val(data.menu_url);
        $('[name="menu_icon"]').val(data.menu_icon);
        $('[name="parent_id"]').val(data.parent_id).trigger('change');
        $('#menuModal').modal('show');
        $('#menuModalLabel').text('Edit Menu');
    }

    function save_menu() {
        $.ajax({
            url: "<?php echo site_url('backend/menus/save'); ?>",
            type: "POST",
            data: $('#menuForm').serialize(),
            success: function(response) {
                $('#menuModal').modal('hide');
                table.ajax.reload();
                Swal.fire('Success', 'Menu saved successfully', 'success');
            },
            error: function() {
                Swal.fire('Error', 'Failed to save menu', 'error');
            }
        });
    }

    function delete_menu(element) {
        var data = table.row($(element).parents('tr')).data();
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
                    url: "<?php echo site_url('backend/menus/delete/'); ?>" + data.menu_id,
                    type: "POST",
                    success: function() {
                        table.ajax.reload();
                        Swal.fire('Deleted!', 'Menu has been deleted.', 'success');
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to delete menu', 'error');
                    }
                });
            }
        });
    }
</script>