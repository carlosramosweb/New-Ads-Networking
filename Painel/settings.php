<?php 
require_once('../config.php');
require_once('inc/functions.php');

$success = false;
$error = false;
$alert = (isset($_GET['alert'])) ? $_GET['alert'] : null;

$settings = new Setting();
//$settings->id = $_SESSION['setting']['id'];
//$setting = $settings->getSetting();



if(isset($_POST['action'])) {
    if($_POST['action'] == 'update') {
        $settings->id = '1';
        $settings->app_id = (isset($_POST['app_id'])) ? $_POST['app_id'] : '';
        $settings->seller_id = (isset($_POST['seller_id'])) ? $_POST['seller_id'] : '';
        $settings->secred_id = (isset($_POST['secred_id'])) ? $_POST['secred_id'] : '';
        $settings->public_Key = (isset($_POST['public_Key'])) ? $_POST['public_Key'] : '';
        $settings->access_token = (isset($_POST['access_token'])) ? $_POST['access_token'] : '';
        if($settings->updateSetting()) {
            $success = true;
        } else {
            $error = true;
        }         
    }
}
require_once('header.php');

$setting = new Setting();
$setting->id = '1';
$getSetting = $setting->getSetting();
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Configurações</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Configurações</li>
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
                        <h3 class="card-title">Configurações</h3>
                    </div>
                    <div class="card-body">
                        <p>Configurações do sistema.</p>
    				</div>
                </div>
			</div>
		</div>

    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>

<?php 
require_once('footer.php');
