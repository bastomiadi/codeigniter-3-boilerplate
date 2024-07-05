<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small Box (Stat card) -->
        <div class="row">
            <div class="col-md-12">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#addCategoryModal">
                    Create Category
                </button>
                <!-- DataTables Card -->
                <div class="card">
                    <div class="card-body">
                        <table id="category-table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
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

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form to add category -->
                <form id="addCategoryForm">
                    <div class="form-group">
                        <label for="categoryName">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form to edit category -->
                <form id="editCategoryForm">
                    <input type="hidden" id="editCategoryId" name="editCategoryId">
                    <div class="form-group">
                        <label for="editCategoryName">Category Name</label>
                        <input type="text" class="form-control" id="editCategoryName" name="editCategoryName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Category Modal -->
<div class="modal fade" id="deleteCategoryModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this category?</p>
                <input type="hidden" id="deleteCategoryId" name="deleteCategoryId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCategory">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- AJAX and DataTable initialization -->
<script>
$(document).ready(function() {
    // DataTable initialization
    $('#category-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "<?php echo base_url('backend/category/get_categories'); ?>",
            type: "POST"
        },
        "columns": [
            { "data": "id" },
            { "data": "name" },
            { "data": "actions" }
        ]
    });

    // Delete category via AJAX
    // $('body').on('click', '.delete-category', function(e) {
    //     e.preventDefault();
    //     var categoryId = $(this).data('id');

    //     Swal.fire({
    //         title: 'Are you sure?',
    //         text: "You won't be able to revert this!",
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonColor: '#3085d6',
    //         cancelButtonColor: '#d33',
    //         confirmButtonText: 'Yes, delete it!'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 type: 'POST',
    //                 url: '<?php echo base_url("backend/category/delete_category"); ?>',
    //                 data: { id: categoryId },
    //                 success: function(response) {
    //                     // Reload DataTable after deletion
    //                     $('#category-table').DataTable().ajax.reload();
    //                     Swal.fire(
    //                         'Deleted!',
    //                         'The category has been deleted.',
    //                         'success'
    //                     );
    //                 }
    //             });
    //         }
    //     });
    // });

    // Add Category Modal
    $('#addCategoryModal').on('shown.bs.modal', function () {
        //$('#categoryName').focus();
    });

    // Edit Category Modal
    $('#editCategoryModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var categoryId = button.data('id'); // Extract category ID from data-id attribute
        var categoryName = button.data('name'); // Extract category name from data-name attribute
        $('#editCategoryId').val(categoryId);
        $('#editCategoryName').val(categoryName);
    });

    // Delete Category Modal
    $('#deleteCategoryModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var categoryId = button.data('id'); // Extract category ID from data-id attribute
        $('#deleteCategoryId').val(categoryId);
    });

    // Add Category Form Submission via AJAX
    $('#addCategoryForm').submit(function(e) {
        e.preventDefault();
        var categoryName = $('#categoryName').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("backend/category/add_category"); ?>',
            data: { categoryName: categoryName },
            success: function(response) {
                $('#addCategoryModal').modal('hide');
                $('#category-table').DataTable().ajax.reload();
                Swal.fire(
                    'Added!',
                    'The category has been added.',
                    'success'
                );
            }
        });
    });

    // Edit Category Form Submission via AJAX
    $('#editCategoryForm').submit(function(e) {
        e.preventDefault();
        var categoryId = $('#editCategoryId').val();
        var categoryName = $('#editCategoryName').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("backend/category/edit_category"); ?>',
            data: { id: categoryId, categoryName: categoryName },
            success: function(response) {
                $('#editCategoryModal').modal('hide');
                $('#category-table').DataTable().ajax.reload();
                Swal.fire(
                    'Updated!',
                    'The category has been updated.',
                    'success'
                );
            }
        });
    });

    // Delete Category via AJAX
    $('#confirmDeleteCategory').click(function() {
        var categoryId = $('#deleteCategoryId').val();

        $.ajax({
            type: 'POST',
            url: '<?php echo base_url("backend/category/delete_category"); ?>',
            data: { id: categoryId },
            success: function(response) {
                $('#deleteCategoryModal').modal('hide');
                $('#category-table').DataTable().ajax.reload();
                Swal.fire(
                    'Deleted!',
                    'The category has been deleted.',
                    'success'
                );
            }
        });
    });
});
</script>
