<div class="card">
    <div class="card-header">
        <h3 class="card-title">Generate Model</h3>
    </div>
    <div class="card-body">
        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <?php echo form_open('backend/ModelGenerator/model'); ?>
            <div class="form-group">
                <label for="table">Table Name</label>
                <select class="form-control" id="table" name="table" required>
                    <option value="">-- Select Table --</option>
                    <?php foreach ($tables as $table): ?>
                        <option value="<?php echo $table; ?>" <?php echo set_select('table', $table); ?>><?php echo $table; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="model_name">Model Name</label>
                <input type="text" class="form-control" id="model_name" name="model_name" value="<?php echo set_value('model_name'); ?>" placeholder="Auto filled from table name (e.g. users -> Users_model)">
                <small class="form-text text-muted">Leave empty to generate automatically as <strong>&lt;TableName&gt;_model</strong>.</small>
            </div>

            <div class="form-group">
                <label for="model_path">Model Path</label>
                <input type="text" class="form-control" id="model_path" name="model_path" value="<?php echo set_value('model_path', 'models/'); ?>" required>
                <small class="form-text text-muted">Path relative to <code>application/</code>, e.g. <code>models/</code> or <code>models/module1/</code>.</small>
            </div>

            <button type="submit" class="btn btn-primary">Generate Model</button>
        <?php echo form_close(); ?>
    </div>
</div>

<script>
    $('#table').on('change', function() {
        $('#model_name').val($(this).val());
    });
</script>
