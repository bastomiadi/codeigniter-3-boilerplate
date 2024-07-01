<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <!-- Include AdminLTE CSS -->
</head>
<body>
<div class="wrapper">
    <!-- Include header and sidebar here -->
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Profile</h1>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Your Profile</h3>
                            </div>
                            <div class="card-body">
                                <p><strong>Username:</strong> <?= $user->username ?></p>
                                <p><strong>Email:</strong> <?= $user->email ?></p>
                                <!-- Add more profile fields here if needed -->
                            </div>
                            <div class="card-footer">
                                <a href="#" class="btn btn-primary">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Include footer here -->
</div>
<!-- Include AdminLTE JS and dependencies -->
</body>
</html>
