
<?php 
if(isset($_GET['logout'])){
  Painel::logout();
}
require_once('../config.php');

$month = date('m');
$year = date("Y"); // Ano atual
$last_day = date("t", mktime(0,0,0, $month, '01', $year));

$revenues = new Revenue();
$revenues->date_start = $date_start = date('Y-m') . '-01';
$revenues->date_end = $date_end = date('Y-m') . '-' . $last_day;
$getMonthRevenues = $revenues->getMonthRevenues();

$payments = new Payment();
$user = new User();
$customers = new Customer();
$customers->user_id = $_SESSION['user']['id'];
$getUserCustomer = $customers->getUserCustomer();

$customer_id = 0;
if(isset($getUserCustomer->id) && !empty($getUserCustomer->id)) {
  $customer_id = $getUserCustomer->id;
}

$domain = new Domain();
$domain->admin_id = $_SESSION['user']['id'];
$getCustomer = $domain->getUserDomains();

$get_domain = false;
$get_domain_id = false;
$get_domain_id = (isset($getCustomer->id)) ? $getCustomer->id : 0;

$user_logged_id = $_SESSION['user']['id'];
$user->id = $_SESSION['user']['id'];
$user = $user->getUser();

$users = new User();
$getUsers = $users->getUsers();

$customers->admin_id = $user_logged_id;
$getCustomers = $customers->getCustomers();

$domains = new Domain();
$domains->admin_id = $user_logged_id;
$getDomains = $domains->getDomains();

$goals = new Goals();
$goals->date_start = $date_start;
$goals->date_end = $date_end;
$goals->admin_id = $user_logged_id;
$getGoals = $goals->getMonthGoals();

require_once('header.php');
adminPage(USER_LOGGED);

$getDate = date("Y-m");
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-4">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-2">
          </div><!-- /.col -->
          <div class="form-group col-md-2 col-lg-2 col-xl-2">
              <label class="control-label requiredField" for="date_start">
                  Data Ínicio:<span class="asteriskField">*</span>
              </label>
              <input class="form-control" id="date_start" name="date_start" type="date" value="<?php echo $date_start; ?>" required />
          </div>
          <div class="form-group col-md-2 col-lg-2 col-xl-2">
              <label class="control-label requiredField" for="date_end">
                  Data Final:<span class="asteriskField">*</span>
              </label>
              <input class="form-control" id="date_end" name="date_end" type="date" value="<?php echo $date_end; ?>" required />
          </div>
          <div class="form-group col-md-2 col-lg-2 col-xl-2">
            <label class="control-label requiredField" for="btn-search">
              Ação
            </label>
            <button class="btn btn-info btn-sm" id="btn-search" style="width: 100%; padding: 7px;">
              Pesquisar
            </button>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 class="total_revenue">$ 0,00</h3>
                <p>Receita Total</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 class="net_revenue">R$ 0,00</h3>
                <p>Valor a Receber</p>
              </div>
              <div class="icon">
                <i class="ion ion-cash"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 class="expense">R$ 0,00</h3>
                <p>Total de Despesas</p>
              </div>
              <div class="icon">
                <i class="fas fa-shopping-cart"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 class="invalids">$ 0,00</h3>
                <p>Trafégo Inválido</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->

  <section class="content">
    <div class="container-fluid">

      <div class="card card-secondary">
        <div class="card-header">
          <h3 class="card-title">
            Metas Geradas do Mês - 
            <?php echo getMonthBR(date("m")); ?>/<?php echo date('Y'); ?>  
          </h3>
        </div>
          <div class="card-body">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th style="width: 150px">Metas</th>
                  <th style="width: 150px">Meta já Atinginda</th>
                  <th>Progresso dessa Meta</th>
                  <th style="width: 80px">Porcento</th>
                </tr>
              </thead>
              <tbody id="loadingGoals">
                  <?php 
                  if(!empty($getGoals)) {
                  $i = 1; 
                  $progress = 0;
                  $total = 0;
                  foreach($getGoals as $row) { 
                    $getDate = getMonthBR(date("m", strtotime($row->date_at))) . '/'. date("Y", strtotime($row->date_at));
                  $revenues = new Revenue();
                  $revenues->date_start = $date_start;
                  $revenues->date_end = $date_end;
                  $revenues->admin_id = $user_logged_id;
                  $getReportRevenue = $revenues->getReportRevenue();
                  if(!empty($getReportRevenue)) {
                    foreach ($getReportRevenue as $key => $row_) {
                      $total = $total + $row_->revenue;
                    }
                  }
                  if(isset($total) && $total > 0) {
                    $progress = ($total / $row->goals) * 100;
                    if(isset($progress) && $progress > 100) {
                      $progress = '100';
                    }
                  }

                  $bg_progress = 'bg-danger';
                  $bar_progress = 'bg-danger';
                  if(isset($progress) && $progress >= 25 && $progress <= 50) {
                    $bg_progress = 'bg-warning';
                    $bar_progress = 'bg-warning';
                  }
                  if(isset($progress) && $progress >= 50 && $progress <= 75) {
                    $bg_progress = 'bg-primary';
                    $bar_progress = 'bg-primary';
                  }
                  if(isset($progress) && $progress >= 75) {
                    $bg_progress = 'bg-success';
                    $bar_progress = 'bg-success';
                  }
                  ?>
                <tr>
                  <td><?php echo $i; ?>.</td>
                  <td>$ <?php echo number_format($row->goals, 2, ',', '.'); ?></td>
                  <td>$ <?php echo number_format($total, 2, ',', '.'); ?></td>
                  <td>
                    <div class="progress progress-xs">
                      <span class="text-progress-bar"><?php echo number_format($progress, 2, ',', ''); ?>% Completo</span>
                      <div class="progress-bar <?php echo $bar_progress; ?>" style="width: <?php echo number_format($progress, 2, '.', '');; ?>%">
                      </div>
                    </div>
                  </td>
                  <td style="text-align: center;">
                    <span class="badge <?php echo $bg_progress; ?>" style="font-size: 100%;">
                      <?php echo number_format($progress, 2, ',', ''); ?>%
                    </span>
                  </td>
                </tr>
                  <?php $i++; 
                    }
                  } else {?>
                  <tr id="no-result-goals" class="no-result-goals">
                  <td colspan="4">
                  Nenhum resultado foi encontrado!
                  </td>
                  </tr>
                  <?php } ?>
              </tbody>
            </table>
          </div>
      </div>  

      <div class="row">
        <div class="col-lg-12 col-12">

          <div class="card">
            <div class="card-header border-0">
              <h3 class="card-title">Gerados nesse mês</h3>
              <div class="card-tools">
                <a href="#" class="btn btn-tool btn-sm">
                  <i class="fas fa-bars"></i>
                </a>
              </div>
            </div>
            <div class="card-body table-responsive p-0">
              <table class="table table-striped table-valign-middle">
                <thead>
                <tr>
                  <!---<th>Data</th>--->
                  <th>Website</th>
                  <!---<th>Porcentagem</th>--->
                  <th>Valor</th>
                </tr>
                </thead>
                <tbody id="loadingResults">

                  <tr class="no-result hide">
                    <td colspan="2">
                      Nenhum resultado foi encontrado!
                    </td>
                  </tr>

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
                $reports->admin_id = trim($user_logged_id);
                $getExports = $reports->getDomainRevenues(); 
                foreach($getExports as $row) {
                  $get_domain = (isset($drow->domain)) ? $drow->domain : 'N/A';
                  $revenue = $revenue + $row->total;
                  $views = $views + $row->views;
                  $currency = $row->currency;
                  $revenue_old = 0;
                  $percent = 0;

                  $revenues->date_start = date('Y-m', strtotime('-1 month')) . '-01';
                  $revenues->date_end = date('Y-m', strtotime('-1 month')) . '-31';
                  $revenues->domain = $row->domain;
                  $revenues->admin_id = trim($user_logged_id);
                  $getReportRevenue = $revenues->getReportRevenue();
                  foreach ($getReportRevenue as $key => $value) {
                    $revenue_old = $revenue_old + $value->revenue;
                  }

                  if ($revenue_old > 0 && $revenue > 0) {
                    $percent = (($revenue / $revenue_old) * 100);
                  }

                  $arrow = 'fa-arrow-up';
                  $text = 'text-success';
                  if ($percent < 0) {
                    $arrow = 'fa-arrow-down';
                    $text = 'text-danger';
                  }
                  if ($percent == 0) {
                    $arrow = 'fa-arrow-down';
                    $text = 'text-warning';
                  }
                  $getDate = getMonthBR(date("m")) . '/'. date("Y");
                  ?>
                  <tr>
                    <!---<td><?php echo $getDate;?></td>--->
                    <td>
                      <?php echo $get_domain;?>
                    </td>
                    <!---<td>
                      <small class="<?php echo $text;?> mr-1">
                        <i class="fas <?php echo $arrow;?>"></i>
                        <?php echo number_format($percent, 2, ',', '.');?>%
                      </small>
                    </td>--->
                    <td>
                      R$ <?php echo number_format($row->total, 2, ',', '.'); ?>
                    </td>
                  </tr>
                  <?php $i++; } ?> 
                  <?php } ?>

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!---
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">
                <i class="far fa-chart-bar"></i>
                Pagamentos Geradas por Mês
              </h3>
            </div>
            <div class="card-body">
              <div id="bar-chart-sales-month" style="height: 300px;"></div>
            </div>
          </div>
        </div>
      </div>
      --->


        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>

<script src="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/jquery/jquery.min.js"></script>
<script src="<?php echo INCLUDE_PATH_PAINEL; ?>plugins/flot/jquery.flot.js"></script>
<script src="<?php echo INCLUDE_PATH_PAINEasdasL; ?>plugins/flot/plugins/jquery.flot.pie.js"></script>


<script type="text/javascript">
$( "#btn-search" ).click(function() {
    getMainReport();
    getReportRevenues();
    getMainGoals();
});

function getMainReport() {  
  var admin_id = <?php echo $user_logged_id; ?>; 
  var date_start = $( "#date_start" ).val();
  var date_end = $( "#date_end" ).val();
  $.post('ajax.php', 
      {action: 'getMonthReport', date_start: date_start, date_end: date_end, admin_id: admin_id}, 
      function(response){ 
        var data = $.parseJSON(response);
        $("h3.total_revenue").html( '$ ' + data.totalRevenues);
        $("h3.expense").html( 'R$ ' + data.totalExpenses);
        $("h3.invalids").html( '$ ' + data.totalInvalids);
        $("h3.net_revenue").html( 'R$ ' + data.totalReceivables);          
  });
}
getMainReport();

function getReportRevenues() { 
    var admin_id = <?php echo $user_logged_id; ?>; 
    var date_start = $( "#date_start" ).val();
    var date_end = $( "#date_end" ).val();
    $("#loadingResults").addClass('active'); 
    $.post('ajax.php', 
        {action: 'getReportRevenues', date_start: date_start, date_end: date_end, admin_id: admin_id}, 
        function(response){ 
          $("#loadingResults").html(response);  
          $("#loadingResults").removeClass('active');
          if (response == '') {
            $("#no-result").addClass('show');
          } else {
            $("#no-result").removeClass('show');
          }       
    });
}

function getMainGoals() { 
    var admin_id = <?php echo $user_logged_id; ?>; 
    var date_start = $( "#date_start" ).val();
    var date_end = $( "#date_end" ).val();
    $.post('ajax.php', 
        {action: 'getReportGoals', date_start: date_start, date_end: date_end, admin_id: admin_id}, 
        function(response){ 
          $("#loadingGoals").html(response);  
          if (response == '') {
            $("#no-result-goals").addClass('show');
          } else {
            $("#no-result-goals").removeClass('show');
          }       
    });
}

function getReportRevenues() { 
    var admin_id = <?php echo $user_logged_id; ?>; 
    var date_start = $( "#date_start" ).val();
    var date_end = $( "#date_end" ).val();
    $("#loadingResults").addClass('active'); 
    $.post('ajax.php', 
        {action: 'getReportRevenues', date_start: date_start, date_end: date_end, admin_id: admin_id}, 
        function(response){ 
          $("#loadingResults").html(response);  
          $("#loadingResults").removeClass('active');
          if (response == '') {
            $("#no-result").addClass('show');
          } else {
            $("#no-result").removeClass('show');
          }       
    });
}

<?php $getSalesMonth = $payments->getSalesMonth(); ?>
var bar_data = {
  data : [
  <?php if($getSalesMonth) {?>
    <?php $i = 1; foreach ($getSalesMonth as $key => $sales) { 
      if (empty($sales)) {
        $sales = 0;
      }
      ?>
  [<?php echo $i;?>,<?php echo $sales;?>],
    <?php $i++; } ?>
  <?php } ?>
  ],
  bars: { show: true }
}
$.plot('#bar-chart-sales-month', [bar_data], {
  grid  : {
    borderWidth: 1,
    borderColor: '#f3f3f3',
    tickColor  : '#f3f3f3'
  },
  series: {
     bars: {
      show: true, barWidth: 0.4, align: 'center',
    },
  },
  colors: ['#3c8dbc'],
  xaxis : {
    ticks: [[1,'Janeiro'], [2,'Fevereiro'], [3,'Março'], [4,'Abril'], [5,'Maio'], [6,'Junho'], [7,'Julho'], [8,'Agosto'], [9,'Setembro'], [10,'Outubro'], [11,'Novembro'], [12,'Dezembro']]
  }
});
</script>

<style type="text/css">
.no-result {
  display: none;
  opacity: 0;
}
.show {
  display: block;
  opacity: 1;
}
.table tr>td .progress {
  padding: 0;
  border-radius: 5px;
  height: 28px;
}
.table tr>td .progress-bar {
  height: auto;
}
.text-progress-bar {
  position: absolute;
  color: #000;
  margin-top: 14px;
  margin-left: 20px;
  text-shadow: 0 0 black;
}
#loadingResults.active {
  background-image: url('<?php echo INCLUDE_PATH_PAINEL; ?>img/loading-small.gif');
  background-position: center center;
  background-repeat: no-repeat;
}
#loadingResults.active tr,
#loadingResults.active tr td{
  opacity: 0.3;
}
</style>

<?php 
require_once('footer.php');