<?php 
require_once('../config.php');
require_once('inc/functions.php');

$success = false;
$error = false;
$step = 0;

if(isset($_GET['action'])){

    if($_GET['action'] == 'step1') {
        $sql = Banco::conectar()->prepare("SELECT * FROM tb_users WHERE user = ?");
        $sql->execute(array($_POST['user']));
        if($sql->rowCount()>0){
            $user = $sql->fetch(PDO::FETCH_OBJ);
            $token = substr(uniqid(rand(), true),0,5);
            $sql2 = Banco::conectar()->prepare("DELETE FROM tb_tokens_forgot_password WHERE user_id = ?");
            $sql2->execute(array($user->id));
            $sql2 = Banco::conectar()->prepare("INSERT INTO tb_tokens_forgot_password (token, user_id)VALUES(?,?)");
            if($sql2->execute(array($token,$user->id))) {
                $email = new Email();
                $email->toEmail = $user->user;
                $email->toName = $user->name;
                $email->subject = 'Recuperação de Senha';
                $email->body = '
                <h2>Recuperação de Senha</h2>
                <p>Você solicitou a recuperação de senha em nosso sitema.</p>
                <p>O seu código de verificação para recuperação de senha é: <strong>'.$token.'</strong></p>
                <p>Caso você não tenha solicitado, favor desconsiderar.</p>
                ';
                $email->from = 'naoresponder@omnesweb.com.br';
                if($email->send()) {
                    $success = true;
                    $step = 1;
                    define('SUCCESS_MSG','Enviamos um e-mail com código de verificação para recuperação de senha.');
                } else {
                    $error = true;
                    $step = 0;
                    define('ERROR_MSG',$email->ErrorInfo);
                }
            } else {
                $error = true;
                $step = 0;
                define('ERROR_MSG','Ocorreu um erro, tente novamente. Se o erro persistir, contate o administrador do sistema.');
            }
        } else {
            $error = true;
            $step = 0;
            define('ERROR_MSG','Não existe usuário registrado em nosso sistema com este e-mail.');
        }
    } else if($_GET['action'] == 'step2') {
        $user = new User();
        $user->id = $_POST['userID'];
        $user->password = $_POST['password'];
        $sql = Banco::conectar()->prepare("SELECT * FROM tb_tokens_forgot_password WHERE user_id = ? AND token = ? AND active = ?");
        if($sql->execute(array($_POST['userID'], $_POST['token'], '1'))) {
            if($sql->rowCount() > 0) {
                if($user->updatePassword()) {
                    $success = true;
                    $step = 2;
                    define('SUCCESS_MSG','Senha alterada com sucesso.');
                } else {
                    $error = true;
                    $step = 1;
                    define('ERROR_MSG','Ocorreu um erro, tente novamente. Se o erro persistir, contate o administrador do sistema.');
                }
            } else {
                $error = true;
                $step = 1;
                define('ERROR_MSG','Código de verificação inválido.');
            }
        } else {
            $error = true;
            $step = 1;
            define('ERROR_MSG','Ocorreu um erro, tente novamente. Se o erro persistir, contate o administrador do sistema.');
        }

    }


}
?>
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
      <p class="lead">
        Recuperação de senha
      </p>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Recuperação de senha</p>

                    <?php if($step <= 0) { ?>
                    <form method="POST" method="POST" action="<?php echo INCLUDE_PATH_PAINEL; ?>forgot-password.php?action=step1">
                      <div class="col-12 input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="E-mail" required="">
                        <div class="input-group-append">
                          <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                          </div>
                        </div>
                      </div>
                      <div class="col-12">
                        <button type="submit" name="acao" class="btn btn-primary btn-block">Recuperar</button>
                      </div>
                    </form>
                    <?php } else if($step == '1') { ?>
                    <form method="POST" method="POST" action="<?php echo INCLUDE_PATH_PAINEL; ?>forgot-password.php?action=step2">
                      <input type="hidden" name="userID" value="<?php echo $user->id ?>">
                      <div class="col-12 input-group mb-3">
                        <label class="form-label">Código de verificação:</label>
                        <input class="form-control form-control-lg" type="text" required name="token" placeholder="Informe o código de verificação recebido em seu e-mail..." />
                      </div>
                      <div class="col-12 input-group mb-3">
                        <label class="form-label">Nova senha:</label>
                        <input class="form-control form-control-lg" type="password" required name="password" placeholder="Informe sua nova senha..." />
                      </div>
                      <div class="text-center col-12 input-group mb-3">
                        <button type="submit" name="acao" class="btn btn-login btn-lg btn-primary">Salvar</button>
                      </div>
                    </form>
                      <?php } else if($step == '2') { ?>
                      <div class="text-center col-12 input-group mb-3">
                        <a href="index.php" class="btn btn-login btn-lg btn-primary">Login</a>
                      </div>
                    <?php } ?>


      <p class="text-center mb-3">
        <a href="index.php">Login</a>
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
