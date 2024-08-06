<!DOCTYPE html>
<html>
<head>
    <title>Generate Model</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- <style>
        .ui-autocomplete-loading {
            background: white url('<?php echo base_url('assets/images/loading.gif'); ?>') right center no-repeat;
        }
    </style> -->
    <script>
        // $(function() {
        //     var availableTables = <?php //echo json_encode($tables); ?>;

        //     $('#table').autocomplete({
        //         source: availableTables
        //     });
        // });
    </script>
</head>
<body>
    <h2>Generate Model</h2>
    <?php if($this->session->flashdata('success')): ?>
        <p style="color: green;"><?php echo $this->session->flashdata('success'); ?></p>
    <?php endif; ?>
    <?php echo form_open('backend/ModelGenerator/model'); ?>

        <div class="form-group">
            <label for="table">Table Name:</label>
            <input type="text" class="form-control" id="table" name="table" value="<?php echo set_value('table'); ?>" required>
        </div>
        <!-- <button type="submit" class="btn btn-primary">Add Category</button> -->

        <div class="form-group">
            <label for="model_name">Model Name:</label>
            <input type="text" class="form-control" id="model_name" name="model_name" value="<?php echo set_value('model_name'); ?>" required>
        </div>

        <div class="form-group">
            <label for="model_path">Model Path:</label>
            <input type="text" class="form-control" id="model_path" name="model_path" value="<?php echo set_value('model_path', 'models/'); ?>" required>
        </div>

        <!-- <label for="namespace">Namespace:</label>
        <input type="text" id="namespace" name="namespace" value="<?php echo set_value('namespace'); ?>"><br><br> -->

        <!-- <input type="submit" class="btn btn-primary" value="Generate Model"> -->
        <button type="submit" class="btn btn-primary">Generate Model</button>
    <?php echo form_close(); ?>
</body>
</html>
