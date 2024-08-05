
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Your Profile</h3>
                    </div>
                    <div class="card-body">
                        <?php if($this->session->flashdata('success')): ?>
                            <p style="color: green;"><?php echo $this->session->flashdata('success'); ?></p>
                        <?php endif; ?>

                        <!-- Form with action URL -->
                        <?php echo form_open('backend/profile'); ?>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" id="username" required value="<?= $user->username ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" name="email" id="email" required value="<?= $user->email ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" required value="<?= $profile->first_name ?>">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" required value="<?= $profile->last_name ?>">
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" name="address" id="address"><?= $profile->address ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input type="text" class="form-control" name="phone" id="phone" required value="<?= $profile->phone ?>">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</section>