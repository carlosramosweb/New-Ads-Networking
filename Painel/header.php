
<?php 
require_once('../config.php');
include_once('inc/functions.php');

$User = new User();
$userLogged = $User;
$User->isLoggedUserIn();

$user_logged_id = $_SESSION['user']['id'];
$userLogged->id = $user_logged_id;
$userLogged = $userLogged->getUser();
define('USER_LOGGED', (array)$userLogged);

?>
<!doctype html>
<html lang="pt-br" dir="ltr">
<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sistema de Orçamentos">
    <meta name="author" content="Criação Criativa - Soluções para Internet">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo INCLUDE_PATH_PAINEL; ?>img/favicon.ico" />

    <!-- TITLE -->
    <title>Painel de Controle</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/summernote/summernote-bs4.min.css">
    </head>
<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="<?php echo INCLUDE_PATH_PAINEL; ?>img/AdminLTELogo.png" alt="RR Fluidos" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button">
            <i class="fas fa-bars"></i>
        </a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index.php" class="nav-link">Home</a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

<?php include_once('sidebar.php') ?>
