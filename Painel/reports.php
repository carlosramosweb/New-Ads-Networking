<?php 
require_once('../config.php');

$reports = new Revenue();

$success = false;
$error = false;

$alert = (isset($_GET['alert'])) ? $_GET['alert'] : null;

$getReports = array();
$date_end   = date('Y-m-d');
$date_start = date('Y-m-d', strtotime('-30 days'));
$domain = '';

if(isset($_GET['action'])) {
    if($_GET['action'] == 'delete') {
        if(isset($_GET['id'])) {
          $revenues = new Revenue();
          $revenues->id = trim($_GET['id']);
          $revenues->admin_id = $_SESSION['user']['id'];
          if($revenues->deleteRevenue()) {
            header('Location: revenues.php?alert=success');
            $success = true;
          } else {
            $error = true;
          } 
        } 
    }
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
  $date_end   = (isset($_POST['date_end'])) ? $_POST['date_end'] : $date_end;
  $date_start = (isset($_POST['date_end'])) ? $_POST['date_start'] : $date_start;
  $domain = (isset($_POST['domain'])) ? $_POST['domain'] : '';

  $reports->date_end = $date_end;
  $reports->date_start = $date_start;
  $reports->domain = $domain;
  $reports->admin_id = $_SESSION['user']['id'];
  $getReports = $reports->getReportRevenue();
}

require_once('header.php');
adminPage(USER_LOGGED);
accessPermission('reports', USER_LOGGED);

$domains = new Domain();
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
            <h1 class="m-0">Relatório</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Relatório</li>
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
          <h3 class="card-title">Gerar Relatórios</h3>
        </div>
                    
					<div class="card-body">
            <?php if($success) { ?>
            <div class="alert alert-success" role="alert">
                Relatório deletado com sucesso.
            </div>
            <?php } if($error) { ?>
            <div class="alert alert-danger" role="alert">
                Erro ao deletar o Relatório.
            </div>
            <?php } ?>

            <form action="?search=true" method="post">
            <div class="row">

            <div class="form-group col-12 col-sm-3">
              <label>Data Inicial:</label>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="date" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" name="date_start" inputmode="numeric" value="<?php echo $date_start; ?>" required="">
              </div>
              <!-- /.input group -->
            </div>

            <div class="form-group col-12 col-sm-3">
              <label>Data Final:</label>

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="date" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" data-mask="" name="date_end" inputmode="numeric" value="<?php echo $date_end; ?>" required="">
              </div>
              <!-- /.input group -->
            </div>

            <div class="form-group col-12 col-sm-4">
              <label class="control-label requiredField" for="domain">
                  Domínio: <span class="asteriskField">*</span>
              </label>
              <select name="domain" class="form-control form-select domain" required>
                  <option value="">Selecione</option>
                  <?php if (!empty($getDomains)) { ?>
                      <?php foreach($getDomains as $row) { ?>
                  <option value="<?php echo $row->id; ?>" <?php if($row->id == $domain) { echo 'selected'; } ?>>
                      <?php echo $row->domain; ?>
                  </option>
                      <?php } ?>
                  <?php } ?>
                  <option value="0" <?php if($domain == '0') { echo 'selected'; } ?>>Todos os websites</option>
              </select>
            </div>

            <div class="form-group col-12 col-sm-2">

              <label>&nbsp</label>

              <div class="input-group">
                <button class="btn btn-primary submit" type="submit" style="width: 100%;">
                    Pesquisar
                </button>
              </div>
              <!-- /.input group -->
            </div>

            </div>
          </form>

						<table class="table table-striped reports" style="width:100%">
							<thead>
								<tr>
                  <th style="width: 100px;">Data</th>
                  <th style="width: 120px;">Domínio</th>
                  <th style="width: 180px;">Receita</th>
                  <th style="width: 120px;">Page Views</th>                  
                  <th style="width: 100px;">Impressões</th>
                  <th style="width: 100px;">Leitura</th>
                  <th style="width: 100px;">Criado em</th>
                  <th style="width: 120px; text-align:center;">Ações</th>
								</tr>
							</thead>
							<tbody id="loadingResults">
                  <?php if(!empty($getReports)) { ?>
									<?php 
                  $i = 0;
                  $views = 0;
                  $items = 0; 
                  $revenue = 0; 
                  $currency = 0;
                  foreach($getReports as $row) {
                    $domain = new Domain();
                    $domain->id =  $row->domain;
                    $getDomain = $domain->getDomain();
                    $paid_out = 1;
                    $revenue = $revenue + $row->revenue;
                    $views = $views + $row->views;
                    $currency = $row->currency;
                    ?>
                    <tr>
                        <td><?php echo getFormatDate($row->date_at, 0); ?></td>
                        <td><?php echo $getDomain->domain; ?></td>
                        <td><?php echo getCurrency($row->currency); ?> <?php echo number_format($row->revenue, 2, ',', '.'); ?></td>
                        <td><?php echo $row->views; ?></td>
                        <td><?php echo $row->printing; ?></td>
                        <td><?php echo $row->reading_at; ?>x</td>                        
                        <td><?php echo getFormatDate($row->created_at, 1); ?></td>
                        <td style="text-align:center;">
                            <a class="btn btn-info btn-sm" href="edit-revenue.php?id=<?php echo $row->id ?>">
                              <i class="fas fa-pencil-alt"></i>
                            </a>
                            &nbsp;&nbsp;
                            <a class="btn btn-danger btn-sm" href="?action=delete&id=<?php echo $row->id ?>&admin_id=<?php echo $user_logged_id; ?>"  onclick="return confirm('Tem certeza que deseja apagar esse item?')">
                              <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php $i++;} ?>  
                    <tr>
                        <td>&nbsp</td>
                        <td>Domínios: <strong><?php echo $i; ?></strong></td>
                        <td colspan="2">Total: <strong><?php echo getCurrency($currency); ?> <?php echo number_format($revenue, 2, ',', '.'); ?></strong></td>
                        <td colspan="2">Impressões: <strong><?php echo $views; ?></strong></td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                    </tr>      
                    <?php } ?>
							</tbody>
						</table>
            <?php if(empty($getReports)) { ?>
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
