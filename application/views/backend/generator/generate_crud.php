<?php if($this->session->flashdata('success')): ?>
    <p style="color: green;"><?php echo $this->session->flashdata('success'); ?></p>
<?php endif; ?>
<?php echo form_open('backend/CrudGenerator/crud'); ?>
    <div class="form-group">
        <label for="model_path">Model Path:</label>
        <input type="text" class="form-control" id="model_path" name="model_path" value="<?php echo set_value('model_path', 'models/'); ?>" required>
    </div>
    <div class="form-group">
        <label for="controller_path">Controller Path:</label>
        <input type="text" class="form-control" id="controller_path" name="controller_path" value="<?php echo set_value('controller_path', 'controllers/'); ?>" required>
    </div>
    <div class="form-group">
        <label for="view_path">View Path:</label>
        <input type="text" class="form-control" id="view_path" name="view_path" value="<?php echo set_value('view_path', 'views/'); ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Generate CRUD</button>
<?php echo form_close(); ?>