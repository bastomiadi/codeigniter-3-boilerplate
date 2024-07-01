<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small Box (Stat card) -->
        <div class="row">
            <div class="col-md-12">
                <!-- Button trigger modal -->
                    <button class="btn btn-success mb-2" onclick="add_product()">Add Product</button>
                    <div class="card">
                        <div class="card-body">
                            <table id="productTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Category</th>
                                    <th>Action</th>
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


<!-- Modal Form for Adding/Editing Products -->
<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="productForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Product Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" name="description" id="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="text" class="form-control" name="price" id="price" required>
                    </div>
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <input type="text" class="form-control" name="category_id" id="category_id" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    load_products();

    $('#category_id').select2({
        width: '100%',
        placeholder: 'Select a category',
        ajax: {
            url: '<?php echo site_url('backend/category/select2'); ?>',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
    
    $('#productForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        var url = $('#id').val() ? '<?php echo site_url('backend/product/update') ?>' : '<?php echo site_url('backend/product/store') ?>';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#productModal').modal('hide');
                $('#productForm')[0].reset();
                Swal.fire(
                    'Success',
                    'Product saved successfully!',
                    'success'
                );
                load_products();
            }
        });
    });
});

function load_products() {
    $('#productTable').DataTable({
        'destroy': true,
        'ajax': {
            'url': '<?php echo site_url('backend/product/fetch') ?>',
            'type': 'GET',
            'dataSrc': ''
        },
        'columns': [
            { 'data': 'id' },
            { 'data': 'name' },
            { 'data': 'description' },
            { 'data': 'price' },
            { 'data': 'category_name' },
            { 'data': 'id', 'render': function(data, type, row) {
                return '<button class="btn btn-warning" onclick="edit_product('+data+')">Edit</button> ' +
                       '<button class="btn btn-danger" onclick="delete_product('+data+')">Delete</button>';
            }}
        ]
    });
}

function add_product() {
    $('#productForm')[0].reset();
    $('#productModal').modal('show');
}

function edit_product(id) {
    $.ajax({
        url: '<?php echo site_url('backend/product/edit/') ?>' + id,
        type: 'GET',
        dataType: 'JSON',
        success: function(data) {
            $('#id').val(data.id);
            $('#name').val(data.name);
            $('#description').val(data.description);
            $('#price').val(data.price);
            $('#category_id').val(data.category_id);
            $('#productModal').modal('show');
        }
    });
}

function delete_product(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?php echo site_url('backend/product/delete/') ?>' + id,
                type: 'POST',
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        'Your product has been deleted.',
                        'success'
                    );
                    load_products();
                }
            });
        }
    });
}
</script>