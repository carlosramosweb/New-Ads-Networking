<?php 
require_once('../config.php');
require_once('inc/functions.php');

$domains = new Domain();

$success = false;
$error = false;


if(isset($_GET['action'])) {
    if($_GET['action'] == 'delete') {
        if(isset($_GET['id'])) {
            $domains->id = $_GET['id'];
            if($domains->deleteDomain()) {
              header('Location: domains.php?alert=success');
              $success = true;
            } else {
              $error = true;
            } 
        } 
    }
}

require_once('header.php');
adminPage(USER_LOGGED);
accessPermission('domains', USER_LOGGED);

$domains->admin_id = $user_logged_id;
$getDomains = $domains->getDomains();
?>


<style type="text/css">
.lds-ring {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-ring div {
  box-sizing: border-box;
  display: block;
  position: absolute;
  width: 64px;
  height: 64px;
  margin: 8px;
  border: 8px solid #292f42;
  border-radius: 50%;
  animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  border-color: #292f42 transparent transparent transparent;
}
.lds-ring div:nth-child(1) {
  animation-delay: -0.45s;
}
.lds-ring div:nth-child(2) {
  animation-delay: -0.3s;
}
.lds-ring div:nth-child(3) {
  animation-delay: -0.15s;
}
@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

</style>

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

    <?php if($success) { ?>
    <div class="alert alert-success" role="alert">
       Pedido deletado com sucesso.
    </div>
    <?php } if($error) { ?>
    <div class="alert alert-danger" role="alert">
        Erro ao deletar o pedido.
    </div>
    <?php } ?>

    
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->

		<div class="row">
			<div class="col-12">
				<div class="card card-secondary">

        <div class="card-header">
          <h3 class="card-title">Lista de Dominíos</h3>

          <a class="btn btn-info btn-sm" href="new-domain.php" style="float: right;">
            <i class="fa fa-plus"></i>
            Adicionar Site
          </a>
        </div>
                    
					<div class="card-body">
						<table id="datatables-reponsive" class="table table-striped table-hover" style="width:100%">
							<thead>
								<tr>
                  <th style="width: 80px; text-align: center;">ID</th>
                  <th>Domínio</th>
                  <!---<th>Cliente</th>--->
                  <th style="width: 100px;">Status</th>
                  <th style="width: 150px;">Criado em</th>
									<th style="width: 120px; text-align:center;">Ações</th>
								</tr>
							</thead>
							<tbody id="loadingResults">
                  <?php if(!empty($getDomains)) { ?>
									<?php foreach($getDomains as $row) { ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $row->id; ?></td>
                        <td><?php echo $row->domain; ?></td>
                        <!---<td>
                          <?php 
                          $customer = new Customer();
                          $customer->id = $row->customer_id;
                          $getCustomer = $customer->getCustomer();
                          $CustomerName = '<span class="badge bg-danger">Sem Vínculo</span>';
                          if (isset($getCustomer->name)) {
                            $CustomerName = '<strong class="badge bg-primary">' . $getCustomer->name . ' (' . $getCustomer->email . ') </strong>';
                          }
                          echo $CustomerName; 
                          ?>
                        </td>--->
                        <td><?php echo getStatus($row->status); ?></td>
                        <td><?php echo getFormatDate($row->created_at, 1); ?></td>
                        <td style="text-align:center;">
                            <a class="btn btn-info btn-sm" href="edit-domain.php?id=<?php echo $row->id ?>">
                              <i class="fas fa-pencil-alt"></i>
                            </a>
                            &nbsp;&nbsp;
                            <a class="btn btn-danger btn-sm" href="?action=delete&id=<?php echo $row->id ?>"  onclick="return confirm('Tem certeza que deseja apagar esse item?')">
                              <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php } ?>
							</tbody>
						</table>
            <?php if(empty($getDomains)) { ?>
              <div class="callout callout-warning">
                <p>Nenhuma item foi encontrado!</p>
              </div> 
            <?php } ?>
					</div>
				</div>
			</div>

    <!-- /.row (main row) -->
  </div><!-- /.container-fluid -->
</section>

<?php 
require_once('footer.php');
