<?php 
require_once('../config.php');
require_once('inc/functions.php');

$success = false;
$error = false;

$users = new User();
$users->id = $_SESSION['user']['id'];
$user = $users->getUser();

$alert = (isset($_GET['alert'])) ? $_GET['alert']  : null;

if(isset($_GET['action'])) {
    if($_GET['action'] == 'edit') {
        $users->name = $_POST['name'];
        $users->email = $_POST['email'];
        $users->role = $_POST['role'];
        $users->password = $_POST['new-password'];
        if($users->updateProfile()) {
            $success = true;
        } else {
            $error = true;
        } 
        
    }
}
require_once('header.php');
adminPage(USER_LOGGED);
accessPermission('profile', USER_LOGGED);
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
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
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

                  <?php if(isset($alert) && $alert == 'success') { ?>
                  <div class="alert alert-success" role="alert">
                      Sucesso ao realizar a ação.
                  </div>
                  <?php } else if(isset($alert) && $alert == 'error') { ?>
                  <div class="alert alert-danger" role="alert">
                      Erro ao realizar a ação.
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

                    <div class="card-header">
                        <h3 class="card-title">Editar Perfil</h3>
                    </div>

                    <div class="card-body">
                        <form method="post" action="?action=edit&id=<?php echo $user->id ?>" autocomplete="off" style="margin:15px 0 0 0;">
                            <input type="hidden" name="id" value="<?php echo $user->id ?>">

                            <div class="form-group ">
                                <label class="control-label requiredField" for="name">
                                    Nome:<span class="asteriskField">*</span>
                                </label>
                                <input class="form-control" id="name" name="name" type="text" required  value="<?php echo $user->name ?>" autocomplete="off"/>
                            </div>

                            <div class="form-group ">
                                <label class="control-label requiredField" for="email">
                                    Email: <span class="asteriskField">*</span>
                                </label>
                                <input class="form-control" id="email" name="email" type="text" required value="<?php echo $user->email ?>" autocomplete="off"/>
                            </div>
                            <div class="form-group ">
                                <label class="control-label requiredField" for="new-password">
                                    Senha: <span class="asteriskField"></span>
                                </label>
                                <input class="form-control" id="new-password" name="new-password" type="password" autocomplete="new-password"/>
                                <span>Preencha caso queira alterar a senha.</span>
                            </div>

                            <div class="form-group">
                              <input name="id" type="hidden" value="<?php echo $user->id ?>" required />
                              <input name="role" type="hidden" value="<?php echo $user->role ?>" required />
                                <div>
                                    <button class="btn btn-primary submit" name="submit" type="submit">
                                        Salvar
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
