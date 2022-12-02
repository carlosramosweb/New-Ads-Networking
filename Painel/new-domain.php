<?php 
require_once('../config.php');
require_once('inc/functions.php');

$success = false;
$error = false;

$alert = (isset($_GET['alert'])) ? $_GET['alert'] : null;

$date_now = date('Y-m-d');
$date_at = date('Y-m-d', strtotime('-1 day'));

if(isset($_GET['action']) && $_GET['action']=='register') {
    $domain = new Domain();
    $domain->customer_id = trim($_POST['customer_id']);
    $domain->domain = trim($_POST['domain']);
    $domain->status = trim($_POST['status']);
    $domain->admin_id = trim($_POST['admin_id']);
    if($domain->newDomain()) {
        header('Location: domains.php?alert=success');
        $success = true;
    } else {
        $error = true;
    }
}

require_once('header.php');
adminPage(USER_LOGGED);
accessPermission('domains', USER_LOGGED);

$customer = new Customer();
$customer->admin_id = $user_logged_id;
$getCustomers = $customer->getCustomers();
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dominíos</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Dominíos</li>
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
                      <h3 class="card-title">Novo Dominío</h3>
                    </div>

                    <div class="card-body">
                        <form method="post" action="?action=register" style="margin:15px 0 0 0;">

                            <div class="row">
                                <div class="form-group col-md-12 col-lg-12 col-xl-12">
                                    <label class="control-label requiredField" for="domain">
                                        Domínio:<span class="asteriskField">*</span>
                                    </label>
                                    <input class="form-control" id="domain" name="domain" type="url" required placeholder="Ex: https://meusite.com.br" />
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="customer_id" value="0">
                                <input type="hidden" name="admin_id" value="<?php echo $user_logged_id; ?>">
                                <input type="hidden" name="status" value="1">
                                <div>
                                    <button class="btn btn-primary submit" type="submit">
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
