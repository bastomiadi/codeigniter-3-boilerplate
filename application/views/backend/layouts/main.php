<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $title; ?></title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css'); ?>">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css'); ?>">
    <!-- JQVMap -->
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/adminlte/plugins/jqvmap/jqvmap.min.css'); ?>">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css'); ?>">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/adminlte/plugins/daterangepicker/daterangepicker.css'); ?>">
    <!-- summernote -->
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/adminlte/plugins/summernote/summernote-bs4.min.css'); ?>">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/adminlte/plugins/fontawesome-free/css/all.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/adminlte/dist/css/adminlte.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/backend/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css'); ?>">
    <!-- Add more CSS files as needed -->
    <!-- Include jQuery -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src="<?php echo base_url('assets/backend/adminlte/plugins/jquery/jquery.min.js'); ?>"></script>
    <!-- Include DataTables CSS and JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <!-- Include SweetAlert2 CSS and JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include Bootstrap CSS and JS if needed -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <!-- Select 2 Library -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<!-- Add this to your main header or a common CSS file -->
<style>
    .select2-container--default .select2-selection--single {
        height: 40px; /* Adjust this value to your desired height */
        padding: 8px 12px; /* Adjust padding for better appearance */
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px; /* Match this to the height for vertical centering */
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px; /* Slightly less than the container height */
    }
    /* .select2 {
        width: 100% !important;
    } */
</style>
<div class="wrapper">

    <!-- Navbar -->
    <?php $this->load->view('backend/layouts/navbar'); ?>

    <!-- Main Sidebar Container -->
    <?php $this->load->view('backend/layouts/sidebar'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <?php $this->load->view('backend/layouts/breadcrumb'); ?>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <?php echo $contents; ?>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Footer -->
    <?php $this->load->view('backend/layouts/footer'); ?>

</div>
<!-- ./wrapper -->

<!-- JS -->
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url('assets/backend/adminlte/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="<?php echo base_url('assets/backend/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/backend/adminlte/dist/js/adminlte.min.js'); ?>"></script>

<!-- ChartJS -->
<script src="<?php echo base_url('assets/backend/adminlte/plugins/chart.js/Chart.min.js'); ?>"></script>
<!-- Sparkline -->
<script src="<?php echo base_url('assets/backend/adminlte/plugins/sparklines/sparkline.js'); ?>"></script>
<!-- JQVMap -->
<!-- <script src="<?php // echo base_url('assets/backend/adminlte/plugins/jqvmap/jquery.vmap.min.js'); ?>"></script>
<script src="<?php // echo base_url('assets/backend/adminlte/plugins/jqvmap/maps/jquery.vmap.usa.js'); ?>"></script> -->
<!-- jQuery Knob Chart -->
<script src="<?php echo base_url('assets/backend/adminlte/plugins/jquery-knob/jquery.knob.min.js'); ?>"></script>
<!-- daterangepicker -->
<script src="<?php echo base_url('assets/backend/adminlte/plugins/moment/moment.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/backend/adminlte/plugins/daterangepicker/daterangepicker.js'); ?>"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo base_url('assets/backend/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js'); ?>"></script>
<!-- Summernote -->
<script src="<?php echo base_url('assets/backend/adminlte/plugins/summernote/summernote-bs4.min.js'); ?>"></script>
<!-- overlayScrollbars -->
<script src="<?php echo base_url('assets/backend/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/backend/adminlte/dist/js/adminlte.js'); ?>"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php //echo base_url('assets/backend/adminlte/dist/js/demo.js'); ?>"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="<?php //echo base_url('assets/backend/adminlte/dist/js/pages/dashboard.js'); ?>"></script>

<!-- Add more JS files as needed -->
<script type="text/javascript">
    // $(document).ready(function() {
    //     // $.fn.select2.defaults.set("width", "100%");
        
    //     // $('.select2').select2({
    //     //     width: '100%'
    //     // });
    // });
</script>
</body>
</html>
