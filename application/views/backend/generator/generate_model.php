<!DOCTYPE html>
<html>
<head>
    <title>Generate Model</title>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <style>
        .ui-autocomplete-loading {
            background: white url('<?php echo base_url('assets/images/loading.gif'); ?>') right center no-repeat;
        }
    </style>
    <script>
        $(function() {
            var availableTables = <?php echo json_encode($tables); ?>;

            $('#table').autocomplete({
                source: availableTables
            });
        });
    </script>
</head>
<body>
    <h2>Generate Model</h2>
    <?php if (isset($error)) { ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php } ?>
    <?php if (isset($message)) { ?>
        <div style="color: green;"><?php echo $message; ?></div>
    <?php } ?>
    <?php echo validation_errors(); ?>
    <?php echo form_open('backend/ModelGenerator/model'); ?>
        <label for="table">Table Name:</label>
        <input type="text" id="table" name="table" value="<?php echo set_value('table'); ?>"><br><br>

        <label for="model_name">Model Name:</label>
        <input type="text" id="model_name" name="model_name" value="<?php echo set_value('model_name'); ?>"><br><br>

        <label for="model_path">Model Path:</label>
        <input type="text" id="model_path" name="model_path" value="<?php echo set_value('model_path', 'models/'); ?>"><br><br>

        <label for="namespace">Namespace:</label>
        <input type="text" id="namespace" name="namespace" value="<?php echo set_value('namespace'); ?>"><br><br>

        <input type="submit" value="Generate Model">
    <?php echo form_close(); ?>
</body>
</html>
