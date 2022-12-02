<?php 
require_once('../config.php');
require_once('inc/functions.php');

$success = false;
$error = false;
$alert = (isset($_GET['alert'])) ? $_GET['alert'] : null;

if(isset($_GET['action'])) {
    if($_GET['action'] == 'edit') {
        if(isset($_GET['id'])) {
            $user = new User();
            $user->id = trim($_POST['id']);
            $user->name = trim($_POST['name']);
            $user->email = trim($_POST['email']);
            $user->role = trim($_POST['role']);
            $user->password = trim($_POST['new-password']);
            $user->admin_id = trim($_POST['admin_id']);
            if($user->updateUser()) {
                $success = true;
            } else {
                $error = true;
            } 
        } 
    }
}

require_once('header.php');
adminPage(USER_LOGGED);
accessPermission('users', USER_LOGGED);

$users = new User();
$users->id = $_GET['id'];
$user = $users->getUser();
?>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Usuários</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Usuários</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                <?php if($success) { ?>
                <div class="alert alert-success" role="alert">
                    Alterações realizadas com sucesso.
                </div>
                <?php } ?>
                <?php if($error) { ?>
                <div class="alert alert-danger" role="alert">
                    Erro ao realizar as alterações.
                </div>
                <?php } ?>
                </div>
            </div>
        </div>
    </section>
    
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
		<div class="row">
			<div class="col-12">
				<div class="card card-secondary">

                    <div class="card-body">
                        <div class="card-header">
                          <h3 class="card-title">Editar Usuário</h3>
                        </div>

                        <form method="post" action="?action=edit&id=<?php echo $user->id; ?>" autocomplete="off" style="margin:15px 0 0 0;">
                            <input type="hidden" name="id" value="<?php echo $user->id; ?>">

                            <?php if( isset($user->user) && isset($user->email)) { ?>
                            <div class="form-group ">
                                <label class="control-label requiredField" for="name">
                                    Usuário:<span class="asteriskField">*</span>
                                </label><br/>
                                <strong><?php echo $user->user ?></strong><br/>
                                <span>O campo usuário não pode ser alterado.</span>
                                <input class="form-control" id="user" name="user" type="hidden" required  value="<?php echo $user->user ?>"/>
                            </div>
                            <?php } ?>

                            <div class="form-group ">
                                <label class="control-label requiredField" for="name">
                                    Nome:<span class="asteriskField">*</span>
                                </label>
                                <input class="form-control" id="name" name="name" type="text" required  value="<?php echo $user->name ?>"/>
                            </div>

                            <div class="form-group ">
                                <label class="control-label requiredField" for="email">
                                    Email: <span class="asteriskField">*</span>
                                </label>
                                <input class="form-control" id="email" name="email" type="text" required value="<?php echo $user->email ?>"/>
                            </div>

                            <?php if(USER_LOGGED['role'] <= 2) { ?>
                            <hr/>
                            <div class="form-group ">
                                <h4>Alteração de Senha</h4>
                                <label class="control-label requiredField" for="new-password">
                                    Nova Senha: <span class="asteriskField"></span>
                                </label>
                                <input class="form-control" id="new-password" name="new-password" type="password" autocomplete="new-password"/>
                                <span>Obs: <i>Preencha caso queira alterar a senha.</i></span>
                            </div>
                            <?php } ?>

                            <div class="form-group">
                                <div>
                                    <?php if (isset($user->role) && $user->role==1) { ?>
                                    <input type="hidden" name="role" value="1">
                                    <?php } else { ?>
                                    <input type="hidden" name="role" value="2">
                                    <?php } ?>
                                    <input type="hidden" name="admin_id" value="<?php echo $user_logged_id; ?>">
                                    <button class="btn btn-primary submit" name="submit" type="submit">
                                        Salvar Alterações
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
				</div>
			</div>
		</div>

    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>

<?php 
require_once('footer.php');
