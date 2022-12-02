<?php 
require_once('../config.php');

$customers = new Customer();

$success = false;
$error = false;

$alert = (isset($_GET['alert'])) ? $_GET['alert'] : null;

if(isset($_GET['action'])) {
    if($_GET['action'] == 'delete') {
        if(isset($_GET['id'])) {
          $customers->id = trim($_GET['id']);
          if($customers->deleteCustomer()) {
            if($_GET['bond'] && isset($_GET['user_id']) && $_GET['user_id'] > 0) {
              $users = new User();
              $users->id = trim($_GET['user_id']);
              $customers->deleteCustomer();
            }
            $success = true;
          } else {
            $error = true;
          } 
        } 
    }
}

require_once('header.php');
adminPage(USER_LOGGED);
accessPermission('customers', USER_LOGGED);

$customers->admin_id = $user_logged_id;
$getCustomers = $customers->getCustomers();
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
            <h3 class="card-title">Lista de Clientes</h3>

            <a class="btn btn-info btn-sm" href="new-customer.php" style="float: right;">
              <i class="fa fa-plus"></i>
              Adicionar Cliente
            </a>
          </div>

          <div class="card-body">                    
						<table class="table table-striped customers" style="width:100%">
							<thead>
								<tr>
									<th style="width: 60px;">ID</th>
									<th>Nome</th>
									<th>E-mail</th>
									<th style="width: 160px;">Criado em</th>
									<th style="width: 120px; text-align:center;">Ações</th>
								</tr>
							</thead>
							<tbody id="loadingResults">
								<tr>
                  <?php if(!empty($getCustomers)) {
                    $bond = 0;
                    $user_id = 0;
                    foreach($getCustomers as $row) {
                    $document = '';
                    if (isset($row->cpf) && !empty($row->cpf)) {
                      $document = 'CPF: ' . $row->cpf;
                    }
                    if (isset($row->cnpj) && !empty($row->cnpj)) {
                      $document = 'CNPJ: ' . $row->cnpj;
                    }
                    ?>
                    <tr>
                        <td><?php echo $row->id; ?></td>
                        <td><?php echo $row->name; ?></td>
                        <td><?php echo $row->email; ?></td>
                        <td><?php echo getFormatDate($row->created_at, 1); ?></td>
                        <td style="text-align:center;">
                            <a class="btn btn-info btn-sm" href="edit-customer.php?id=<?php echo $row->id; ?>">
                              <i class="fas fa-pencil-alt"></i>
                            </a>
                            <?php if(isset($row->user_id) && $row->user_id > 0) { ?>
                            <a class="btn btn-success bg-success btn-sm" href="edit-user.php?id=<?php echo $row->user_id ?>">
                              <i class="fas fa-pencil-alt"></i>
                              Perfil
                            </a>
                            <?php } ?>
                            <a class="btn btn-danger btn-sm" href="?action=delete&id=<?php echo $row->id ?>&bond=<?php echo $bond; ?>]" onclick="return confirm('Tem certeza que deseja apagar esse item?')">
                              <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
								</tr>
							</tbody>
						</table>
            <?php if(empty($getCustomers)) { ?>
              <div class="callout callout-warning">
                <p>Nenhuma item foi encontrado!</p>
              </div> 
            <?php } ?>
					</div>
				</div>
			</div>
		</div>

    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>

<?php 
require_once('footer.php');
