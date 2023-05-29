
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cashier Panel: <?=$_SESSION['system_info']['name'];?></title>

  <link rel="icon" href="<?php echo validate_image($_settings->info('logo')) ?>" />

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?php echo base_url; ?>plugins/fontawesome-free/css/all.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url; ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">

  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="<?php echo base_url; ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  
  <!-- Toastr -->
  <link rel="stylesheet" href="<?php echo base_url; ?>plugins/toastr/toastr.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url; ?>dist/css/adminlte.min.css">

  <!-- select2 -->
  <link href="<?php echo base_url; ?>plugins/select2/css/select2.min.css" rel="stylesheet" />
  <link href="<?php echo base_url; ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css" rel="stylesheet" />   
 
  <!-- jQuery -->
  <script src="<?php echo base_url ?>plugins/jquery/jquery.min.js"></script>
  
  <!-- jQuery UI 1.11.4 -->
  <script src="<?php echo base_url ?>plugins/jquery-ui/jquery-ui.min.js"></script>
  
  <script>
      var _base_url_ = '<?php echo base_url ?>';
  </script>

    <script src="<?php echo base_url ?>dist/js/script.js"></script>
  <style>
    .text-plato {
      color: #007bff;
    }
    .text-kutsara {
      color: #D38D52;
    } 
    .text-pitsel {
      color: #156DAA;
    }
    .text-tinidor {
      color: #ced4da;
    }
    .text-mangkok {
      color: #21D029;
    }

    /*     
    .bg-sidebar {
        background-color: #0064fe !important;
        color: #ffffff !important;
    }
    .bg-sidebar-selected {
      background-color: #ffffff !important;
    }

    .accent-indigo .btn-link, .accent-indigo .nav-tabs .nav-link, .accent-indigo a:not(.dropdown-item):not(.btn-app):not(.nav-link):not(.brand-link):not(.page-link):not(.badge):not(.btn) {
      color: #ffffff !important;
    } */

  </style>

    <!-- datatables-checkboxes --> 
    <link href="<?php echo base_url; ?>plugins/datatables-checkboxes/css/dataTables.checkboxes.css" rel="stylesheet" />
</head>

<!-- <?php 
  echo '<pre>';
    print_r($_SESSION['system_info']);
  echo '</pre>';
?> -->