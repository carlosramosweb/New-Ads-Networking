<?php 
require_once('../config.php');
require_once('inc/functions.php');

$domains = new Domain();

$success = false;
$error = false;

$date_now = date('Y-m-d');
$date_at = date('Y-m-d', strtotime('-1 day'));

if(isset($_GET['action']) && $_GET['action']=='register') {
    $revenue = new Revenue();
    $revenue->author_id = trim($_POST['author_id']);
    $revenue->domain = trim($_POST['domain']);
    $revenue->currency = trim($_POST['currency']);
    $revenue->date_at = trim($_POST['date_at']);
    $revenue->title = trim($_POST['title']);
    $get_revenue = str_replace('.', '', $_POST['revenue']);
    $get_revenue = str_replace(',', '.', $get_revenue);
    $revenue->revenue = trim($get_revenue);
    $revenue->views = trim($_POST['views']);
    $revenue->printing = trim($_POST['printing']);
    $revenue->admin_id = trim($_POST['admin_id']);
    if($revenue->newRevenue()) {
        header('Location: revenues.php?alert=success');
        $success = true;
    } else {
        $error = true;
    }
}

require_once('header.php');
adminPage(USER_LOGGED);
accessPermission('revenues', USER_LOGGED);

$domains->admin_id = $user_logged_id;
$getDomains = $domains->getDomains();
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Rendimentos</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Rendimentos</li>
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
                      <h3 class="card-title">Novo Rendimento</h3>
                    </div>

                    <div class="card-body">
                        <form method="post" action="?action=register" style="margin:15px 0 0 0;">

                            <div class="row">
                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                    <label class="control-label requiredField" for="domain">
                                        Domínio: <span class="asteriskField">*</span>
                                    </label>
                                    <select id="domain" name="domain" class="form-control form-select domain" required>
                                        <option value="">Selecione</option>
                                        <?php if (!empty($getDomains)) { ?>
                                            <?php foreach($getDomains as $row) { ?>
                                        <option value="<?php echo $row->id; ?>">
                                            <?php echo $row->domain; ?>
                                        </option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="row form-group col-md-6 col-lg-6 col-xl-6">
                                    <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                        <label class="control-label requiredField" for="date_at">
                                            Data:<span class="asteriskField">*</span>
                                        </label>
                                        <input class="form-control" id="date_at" name="date_at" type="date" required value="<?php echo $date_at; ?>" />
                                    </div>
                                    <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                        <label class="control-label requiredField" for="currency">
                                            Moeda: <span class="asteriskField">*</span>
                                        </label>
                                        <select name="currency" class="form-control form-select currency" required>
                                            <option value="">Selecione</option>
                                            <option value="1">Real (R$)</option>
                                            <option value="2" selected>Dolar ($)</option>
                                            <option value="3">Euro (€)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4 col-lg-4 col-xl-4">
                                    <label class="control-label requiredField" for="revenue">
                                        Valor: <span class="asteriskField">*</span>
                                    </label>
                                    <input class="form-control" id="revenue" name="revenue" type="text" required />
                                </div>
                                <div class="form-group col-md-4 col-lg-4 col-xl-4">
                                    <label class="control-label requiredField" for="views">
                                        Page Views: <span class="asteriskField">*</span>
                                    </label>
                                    <input class="form-control" id="views" name="views" type="number" required />
                                </div>
                                <div class="form-group col-md-4 col-lg-4 col-xl-4">
                                    <label class="control-label requiredField" for="printing">
                                        Impressões: <span class="asteriskField">*</span>
                                    </label>
                                    <input class="form-control" id="printing" name="printing" type="number" required />
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="admin_id" value="<?php echo $user_logged_id; ?>">
                                <input class="form-control" id="title" name="title" type="hidden" required value="Rendimento" />
                                <input type="hidden" name="author_id" value="<?php echo USER_LOGGED['id']; ?>">
                                <div>
                                    <button class="btn btn-primary submit" id="btn-submit" type="submit">
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="<?php echo INCLUDE_PATH_PAINEL; ?>js/jquery.priceformat.min.js"></script>
<script type="text/javascript">  
! function(e) {
    e(document).ready(function() {
        e("#revenue").priceFormat({
            prefix: "",
            centsSeparator: ",",
            thousandsSeparator: "."
        })
    })
}(jQuery);

$( "#domain" ).change(function() {
    getDomainRevenue();
});

$( "#date_at" ).change(function() {
    getDomainRevenue();
});

function getDomainRevenue() {  
    var domain = $( "#domain" ).val();
    var date_at = $( "#date_at" ).val();
    $.post('ajax.php', 
        {action: 'getDomainRevenue', domain: domain, date_at: date_at}, 
        function(response){ 
          var data = $.parseJSON(response);
          if (response.trim() == 0 || response.trim() == '0' || response.trim() == '') {
            $("#btn-submit").removeAttr('disabled'); 
          } else {
            $("#btn-submit").attr('disabled', 'true'); 
            alert('Este domínio já tem entrada nessa data;');
          }        
    });
}
</script>

<?php 
require_once('footer.php');
