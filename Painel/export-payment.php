<?php 
require_once('../config.php');
require_once('inc/functions.php');

$user_logged_id = $_SESSION['user']['id'];

$success = false;
$error = false;

$getExports = array();

$month = date('m');
$year = date("Y");
$last_day = date("t", mktime(0,0,0, $month, '01', $year));

$date_start = date('Y-m-') . '01';
$date_end = date('Y-m-') . $last_day;

if(isset($_GET['action']) && isset($_GET['id'])) {
  if($_GET['action'] == 'export' && !empty($_GET['id'])) {

    $payments = new Payment();
    $payments->id = trim($_GET['id']);
    $getPayment = $payments->getPayment();

    $month = date('m', strtotime($getPayment->date_at));
    $year = date("Y", strtotime($getPayment->date_at));
    $last_day = date("t", mktime(0,0,0, $month, '01', $year));

    $date_start = date('Y-m-', strtotime($getPayment->date_at)) . '01';
    $date_end = date('Y-m-', strtotime($getPayment->date_at)) . $last_day;

    $domains = new Domain();
    $domains->admin_id = $user_logged_id;
    $getDomains = $domains->getDomains();

    $expenses = new Expense();
    $expenses->date_start = $date_start;
    $expenses->date_end = $date_end;
    $expenses->admin_id = $user_logged_id;
    $getExpenses = $expenses->getMonthExpenses();

  }
}

$month_ref = getMonthBR(date("m", strtotime($getPayment->date_at))) . '/'. date("Y", strtotime($getPayment->date_at));
$created_at = date("d/m/Y H:i:s", strtotime($getPayment->created_at));
?>
<!doctype html>
<html lang="pt-br" dir="ltr">
<head>
    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Sistema de Orçamentos">
    <meta name="author" content="Criação Criativa - Soluções para Internet">

    <!-- FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo INCLUDE_PATH_PAINEL; ?>img/favicon.ico" />

    <!-- TITLE -->
    <title>Exports-<?php echo date('Y-m-d'); ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/summernote/summernote-bs4.min.css">
    </head>
<body class="hold-transition sidebar-mini layout-fixed">


  <div class="content">

    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">
              <img src="<?php echo INCLUDE_PATH_PAINEL; ?>img/AdminLTELogo.png" alt="Logo New Ads Networking" title="Logo New Ads Networking" style="width: 80px; height: auto;">
              Receitas
            </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                Mês Ref: 
                <strong style="display: block;">
                  <?php echo $month_ref; ?>
                </strong>
                Criado em: 
                <strong style="display: block;">
                  <?php echo $created_at; ?>
                </strong>
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>

      <section class="content">
        <div class="container-fluid">

          <div class="row">
            <div class="col-12">
              <div class="card card-secondary">

              <div class="card-header">
                <h3 class="card-title">Receitas Geradas</h3>
              </div>

              <?php if(!empty($getDomains)) { ?>

              <div class="card-body">
                <table class="table table-striped reports" style="width:100%">
                  <thead>
                    <tr>
                      <th>Domínio</th>
                      <th style="width: 180px;">Rendimento</th>
                      <th style="width: 180px;">Impressões</th>
                    </tr>
                  </thead>
                  <tbody id="loadingResults">
                      <?php
                      $i = 0;
                      $views = 0;
                      $items = 0; 
                      $revenue = 0; 
                      $currency = 0;
                      foreach($getDomains as $drow) {
                      $reports = new Revenue();
                      $reports->date_start = $date_start;
                      $reports->date_end = $date_end;
                      $reports->domain = trim($drow->id);
                      $reports->admin_id = $user_logged_id;
                      $getExports = $reports->getDomainRevenues(); 
                      foreach($getExports as $row) {
                        $domain = new Domain();
                        $domain->id =  $row->domain;
                        $getDomain = $domain->getDomain();
                        $revenue = $revenue + $row->total;
                        $views = $views + $row->views;
                        $currency = $row->currency;
                        ?>
                        <tr style="font-weight: normal;">
                            <td><?php echo $getDomain->domain; ?></td>
                            <td><?php echo getCurrency($row->currency); ?> <?php echo number_format($row->total, 2, ',', '.'); ?></td>
                            <td><?php echo $row->views; ?> </td>
                        </tr>
                        <?php $i++;} ?> 
                        <?php } ?>
                        <?php if (empty($getExports)) { ?>
                        <tr>
                            <td><?php echo $drow->domain; ?></td>
                            <td>R$ 0,00</td>
                            <td>0</td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td>Domínios: <strong><?php echo $i; ?></strong></td>
                            <td>Total: <strong><?php echo getCurrency($currency); ?> <?php echo number_format($revenue, 2, ',', '.'); ?></strong></td>
                            <td>Impressões: <strong><?php echo $views; ?></strong></td>
                        </tr>      
                  </tbody>
                </table>
              </div>
              <?php } ?>

            </div>
          </div>
        </div>
      </section>

      <section class="content">
        <div class="container-fluid">

          <div class="row">
            <div class="col-12">
              <div class="card card-secondary">

              <div class="card-header">
                <h3 class="card-title">Despesas Geradas</h3>
              </div>

              <div class="card-body">
          <div class="card-body">
            <table id="datatables-reponsive" class="table table-striped table-hover" style="width:100%">
              <thead>
                <tr>
                  <th style="width: 100px;">Data</th>
                  <th style="width: 120px;">Descrição</th>
                  <th style="width: 120px;">Usuário</th>
                  <th style="width: 100px;">Valor</th>
                </tr>
              </thead>
              <tbody id="loadingResults">
                  <?php if(!empty($getExpenses)) {
                    $customers = array();
                    $total = 0;
                    foreach($getExpenses as $row) { 
                    $customer = new Customer();
                    $customer->id = $row->customer_id;
                    $getCustomer = $customer->getCustomer();
                    $customers[] = $row->customer_id;
                    $total = $total + $row->expense;
                    ?>
                    <tr style="font-weight: normal;">
                        <td><?php echo getFormatDate($row->date_at, 0); ?></td>
                        <td><?php echo $row->title; ?></td>
                        <td><?php echo $getCustomer->name; ?></td>                        
                        <td>R$ <?php echo number_format($row->expense, 2, ',', '.'); ?></td>        
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>
                          Total Geral: <strong>R$ <?php echo number_format($total, 2, ',', '.'); ?></strong>
                        </td>
                    </tr>
                    <?php } ?>

                  <?php 
                  if(!empty($customers)) { 
                    $customers = array_unique($customers);
                    foreach($customers as $row) { 
                    $expense = new Expense();
                    $expense->date_start = $date_start;
                    $expense->date_end = $date_end;
                    $expense->customer_id = $row;
                    $expense->admin_id = $user_logged_id;
                    $getCustomerExpenses = $expense->getReportCustomerExpenses(); 
                    if(!empty($getCustomerExpenses)) { ?>
                    <tr>
                        <td>&nbsp</td>
                        <td>&nbsp</td>
                        <td>
                          <?php 
                          $customers = new Customer();
                          $customers->id = $row;
                          $getCustomer = $customers->getCustomer();
                          echo $getCustomer->name; 
                          ?>
                        </td>
                        <td>
                          Total: <strong>R$ <?php echo number_format($getCustomerExpenses, 2, ',', '.'); ?></strong>
                        </td>
                    </tr> 
                  <?php } ?>
                  <?php } ?>
                  <?php } ?>

              </tbody>
            </table>
            <?php if(empty($getExpenses)) { ?>
              <div class="callout callout-warning">
                <p>Nenhuma item foi encontrado!</p>
              </div> 
            <?php } ?>
          </div>

            </div>
          </div>
        </div>
      </section>

      <section class="content">
        <div class="container-fluid">

          <div class="row">
            <div class="col-12">
              <div class="card card-secondary">

              <div class="card-header">
                <h3 class="card-title">Resumo Final - Cotação do Dolar: $ <?php echo number_format($getPayment->quotation, 2, ',', '.'); ?></h3>
              </div>

              <div class="card-body">

                <table id="datatables-reponsive" class="table table-striped table-hover" style="width:100%">
                <thead>
                  <tr>
                    <th colspan="5"></th>
                    <th style="width: 300px; text-align: center;">Total a Receber</th>
                  </tr>
                </thead>
                <tbody>
                  <tr style="font-weight: normal;">
                    <td style="width: 150px;">$ <?php echo number_format($getPayment->total_revenue, 2, ',', '.'); ?></td>
                    <td>Receita Gerada</td>
                    <td rowspan="4" style="width: 100px;"></td>
                    <?php $total_receivable = ($getPayment->net_revenue * $getPayment->quotation)?>
                    <td style="width: 150px;">R$ <?php echo number_format($total_receivable, 2, ',', '.'); ?></td>
                    <td>A Receber</td>
                    <td rowspan="4" style="padding: 20px; text-align: center;">
                      <strong style="font-size: 2rem;">R$ <?php echo number_format($getPayment->total_receivable, 2, ',', '.'); ?></strong>
                    </td>
                  </tr>
                  <tr style="font-weight: normal;">
                    <td style="border-bottom: 3px solid #ccc;">
                      -$ <?php echo number_format($getPayment->invalids, 2, ',', '.'); ?>
                    </td>
                    <td>Inválidos</td>
                    <td style="border-bottom: 3px solid #ccc;">-R$ <?php echo number_format($getPayment->exchange_rate, 2, ',', '.'); ?></td>
                    <td>Taxa de Câmbio</td>
                  </tr>
                  <?php
                  $total_revenue = $getPayment->total_revenue;
                  if ($getPayment->total_revenue <= 0) {
                    $total_revenue = 0;
                  }
                  $invalids = $getPayment->invalids;
                  if ($getPayment->invalids <= 0) {
                    $invalids = 0;
                  }
                  $total = ($total_revenue - $invalids);
                  $total_exchange = ($total_receivable - $getPayment->exchange_rate);
                  ?>
                  <tr style="font-weight: normal;">
                    <td><strong>$ <?php echo number_format($total, 2, ',', '.'); ?></strong></td>
                    <td></td>
                    <td>
                      <strong>R$ <?php echo number_format($total_exchange, 2, ',', '.'); ?></strong>
                    </td>
                    <td></td>
                  </tr>
                  <tr style="font-weight: normal;">
                    <td>$ <?php echo number_format($getPayment->net_revenue, 2, ',', '.'); ?></td>
                    <td>Receita Líquida</td>
                    <td>-R$ <?php echo number_format($getPayment->expense, 2, ',', '.'); ?></td>
                    <td>Despesas</td>
                    <td></td>
                  </tr>
                </tbody>
                </table>

              </div>

            </div>
          </div>
        </div>
      </section>

  </div>

<div class="no-print">
  <center>
    <button onclick="window.print();" style="color: #FFF; background: #6c757d;">Imprimir</button>
    <button onclick="window.close();">Fechar Janela</button>
  </center>
  <br/>
</div>

<script type="text/javascript">
  window.print();
</script>

<style type="text/css">
@media print {    
    .no-print, .no-print * {
      display: none !important;
    }
    .main-footer {
      display: none !important;
    }
    .content-header {
      display: block !important;
    }
}
button {
  padding: 5px 20px;
  border-radius: 5px;
}
</style>

<?php
require_once('footer.php');

