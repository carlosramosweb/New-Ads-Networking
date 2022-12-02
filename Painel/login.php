<!doctype html>
<html lang="en" dir="ltr">

<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Painel - RR Fluidos">
    <meta name="author" content="Criação Criativa -  Soluções para Internet">
    <meta name="keywords" content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/brand/favicon.ico" />

    <!-- TITLE -->
    <title>Painel de Controle</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1" style="font-size: 1.5rem;">
        <b>Painel de Controle</b>
      </a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Faça login para iniciar sua sessão</p>

      <?php if(isset($_GET['alert']) && $_GET['alert'] == 'login') { ?>
      	<?php if(isset($_GET['logged']) && $_GET['logged'] == 'false') { ?>
          <div class="alert alert-danger" role="alert" style="text-align: center;padding: 10px;">
              Sua sessão foi encerrada!
          </div>
      	<?php } ?>
      <?php } ?>

      <form action="<?php echo INCLUDE_PATH_PAINEL; ?>" method="post">
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="E-mail" required="">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Senha" required="">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Lembre de mim
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="acao" class="btn btn-primary btn-block">Entrar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

  <?php 
  if(isset($_POST['acao'])){
    $password = '';
    $email = '';
    $count = 0;
    if(isset($_POST['password']) && !empty($_POST['password'])){
      $password = MD5($_POST['password']);
    }
    if(isset($_POST['email']) && is_email($_POST['email'])){
      $email = trim($_POST['email']);
      $sql = Banco::conectar()->prepare("SELECT * FROM tb_users WHERE email = ? AND password = ?");
      $sql->execute(array($email,$password));
    } else {
      $user = trim($_POST['email']);
      $sql = Banco::conectar()->prepare("SELECT * FROM tb_users WHERE user = ? AND password = ?");
      $sql->execute(array($user,$password));
    }
    $count = $sql->rowCount();

    if($count>0){
      $info = $sql->fetch();
      //Logamos Com Sucesso!
      $_SESSION['login'] = true;
      $_SESSION['user'] = $info;
      $_SESSION['name'] = $info['name'];
      $_SESSION['email'] = $info['email'];
      $_SESSION['role'] = $info['role'];

      $id = $info['id'];
      $last_activity = date('Y-m-d H:i:s');
      $update_sql = Banco::conectar()->prepare("UPDATE tb_users SET last_activity = ? WHERE id = ?");
      $update_sql->execute(array($last_activity, $id));                         
      exit( header("Location: ".INCLUDE_PATH_PAINEL."index.php") );
    } else {
      echo '<div class="alert alert-message alert-danger alert-dismissible " role="alert">
          <i class="far fa-fw fa-bell"></i>
          <strong>Atenção!</strong> Usuario e/ou senha inválido.
        </div>
      </div>';
    }
  }
  ?>

      <p class="mb-1">
        <a href="forgot-password.php">Esqueci a minha senha</a>
      </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo INCLUDE_PATH_PAINEL; ?>js/adminlte.min.js"></script>
</body>
</html>
