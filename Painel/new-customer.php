<?php 
require_once('../config.php');
require_once('inc/functions.php');

$success = false;
$error = false;

if(isset($_POST['name']) && isset($_POST['email'])) {
    $customer = new Customer();
    $customer->name = $_POST['name'];
    $customer->email = trim($_POST['email']);
    $customer->admin_id = trim($_POST['admin_id']);
    if($customer->newCustomer()) {
        header('Location: customers.php?alert=success');
        $success = true;
    } else {
        $error = true;
    }
}

$users = new User();
$getUsers = $users->getUsers();

require_once('header.php');
adminPage(USER_LOGGED);
accessPermission('customers', USER_LOGGED);
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Clientes</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Clientes</li>
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

                    <div class="card-header">
                      <h3 class="card-title">Novo Cliente</h3>
                    </div>

                    <div class="card-body">
                        <form method="post" action="?action=register" style="margin:15px 0 0 0;">

                            <div class="row">
                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                    <label class="control-label requiredField" for="name">
                                        Nome:<span class="asteriskField">*</span>
                                    </label>
                                    <input class="form-control" id="name" name="name" type="text" required />
                                </div>

                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                    <label class="control-label requiredField" for="email">
                                        Email: <span class="asteriskField">*</span>
                                    </label>
                                    <input class="form-control" id="email" name="email" type="text" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <div>
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
