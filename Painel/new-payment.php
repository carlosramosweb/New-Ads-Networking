<?php 
require_once('../config.php');
require_once('inc/functions.php');

$success = false;
$error = false;

if(isset($_GET['action'])) {
    if($_GET['action']=='register') {
        $payment = new Payment();
        $payment->date_at = trim($_POST['date_at'] . '-01');
        $total_revenue = str_replace('.', '', $_POST['total_revenue']);
        $total_revenue = str_replace(',', '.', $total_revenue);
        $payment->total_revenue = trim($total_revenue);

        $invalids = str_replace('.', '', $_POST['invalids']);
        $invalids = str_replace(',', '.', $invalids);
        $payment->invalids = trim($invalids);

        $payment->type = trim($_POST['type']);
        $rate = str_replace('.', '', $_POST['rate']);
        $rate = str_replace(',', '.', $rate);
        $payment->rate = trim($rate); 

        $net_revenue = str_replace('.', '', $_POST['net_revenue']);
        $net_revenue = str_replace(',', '.', $net_revenue);
        $payment->net_revenue = trim($net_revenue);  

        $quotation = str_replace('.', '', $_POST['quotation']);
        $quotation = str_replace(',', '.', $quotation);
        $payment->quotation = trim($quotation);

        $expense = str_replace('.', '', $_POST['expense']);
        $expense = str_replace(',', '.', $expense);
        $payment->expense = trim($expense);

        $exchange_rate = str_replace('.', '', $_POST['exchange_rate']);
        $exchange_rate = str_replace(',', '.', $exchange_rate);
        $payment->exchange_rate = trim($exchange_rate);

        $payment->status = trim($_POST['status']);
        $payment->admin_id = trim($_POST['admin_id']);
        if($payment->newPayment()) {
            header('Location: payments.php?alert=success');
            $success = true;
        } else {
            $error = true;
        }
    }
}

require_once('header.php');
adminPage(USER_LOGGED);
accessPermission('payments', USER_LOGGED);
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Pagamentos</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Pagamentos</li>
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
                      <h3 class="card-title">Novo Pagamento</h3>
                    </div>

                    <div class="card-body">
                        <form method="post" action="?action=register" style="margin:15px 0 0 0;">

                            <div class="row">
                                <div class="form-group col-md-2 col-lg-2 col-xl-2">
                                    <label class="control-label requiredField" for="date_at">
                                        Data Referência:<span class="asteriskField">*</span>
                                    </label>
                                    <input class="form-control" id="date_at" name="date_at" type="month" required />
                                </div>
                                <div class="form-group col-md-3 col-lg-3 col-xl-3">
                                    <label class="control-label requiredField" for="total_revenue">
                                        Receita Total:
                                    </label>
                                    <input class="form-control" id="total_revenue" name="total_revenue" type="text" required readonly="" />
                                </div>
                                <div class="form-group col-md-3 col-lg-3 col-xl-3">
                                    <label class="control-label requiredField" for="invalids">
                                       Inválidos:
                                    </label>
                                    <input class="form-control" id="invalids" name="invalids" type="text" required readonly="" />
                                </div>
                                <div class="form-group col-md-2 col-lg-2 col-xl-2">
                                    <label class="control-label requiredField" for="type">
                                        % ou R$: <span class="asteriskField">*</span>
                                    </label>
                                    <select name="type" class="form-control form-select type" required>
                                        <option value="1" selected="">
                                            Real (R$)
                                        </option>
                                        <option value="2">
                                            Porcentagem (%)
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 col-lg-2 col-xl-2">
                                    <label class="control-label requiredField" for="rate">
                                       Taxa de Rede ADX: <span class="asteriskField">*</span>
                                    </label>
                                    <input class="form-control" id="rate" name="rate" type="text" required d value="0.00"/>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-2 col-lg-2 col-xl-2">
                                    <label class="control-label requiredField" for="net_revenue">
                                        Receita Líquida:
                                    </label>
                                    <input class="form-control" id="net_revenue" name="net_revenue" type="text" required readonly=""/>
                                </div>
                                <div class="form-group col-md-2 col-lg-2 col-xl-2">
                                    <label class="control-label requiredField" for="quotation">
                                        Cotação do Dolar: <span class="asteriskField">*</span>
                                    </label>
                                    <input class="form-control" id="quotation" name="quotation" type="text" required value="0.00" />
                                </div>
                                <div class="form-group col-md-2 col-lg-2 col-xl-2">
                                    <label class="control-label requiredField" for="expense">
                                       Despesas:
                                    </label>
                                    <input class="form-control" id="expense" name="expense" type="text" required readonly="" />
                                </div>
                                <div class="form-group col-md-2 col-lg-2 col-xl-2">
                                    <label class="control-label requiredField" for="exchange_rate">
                                       Taxa de Câmbio: <span class="asteriskField">*</span>
                                    </label>
                                    <input class="form-control" id="exchange_rate" name="exchange_rate" type="text" value="0.00" required />
                                </div>
                                <div class="form-group col-md-2 col-lg-2 col-xl-2">
                                    <label class="control-label requiredField" for="status">
                                        Status: <span class="asteriskField">*</span>
                                    </label>
                                    <select name="status" class="form-control form-select status" required>
                                        <option value="">Selecione</option>
                                        <option value="1">
                                            Pago
                                        </option>
                                        <option value="0" selected="">
                                            Pendente
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 col-lg-2 col-xl-2">
                                    <label class="control-label requiredField" for="total_receivable">
                                       Total a Receber:
                                    </label>
                                    <input class="form-control" id="total_receivable" name="total_receivable" type="text" readonly="" />
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="admin_id" value="<?php echo $user_logged_id; ?>">
                                <input type="hidden" name="author_id" value="<?php echo USER_LOGGED['id']; ?>">
                                <div>
                                    <button class="btn btn-primary submit" type="submit">
                                        Gerar Pagamento
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

<?php require_once('footer.php'); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="<?php echo INCLUDE_PATH_PAINEL; ?>js/jquery.priceformat.min.js"></script>
<script type="text/javascript">  
! function(e) {
    e(document).ready(function() {
        e("#rate").priceFormat({
            prefix: "",
            centsSeparator: ",",
            thousandsSeparator: "."
        });
        e("#quotation").priceFormat({
            prefix: "",
            centsSeparator: ",",
            thousandsSeparator: "."
        });
        e("#exchange_rate").priceFormat({
            prefix: "",
            centsSeparator: ",",
            thousandsSeparator: "."
        });
    })
}(jQuery);

$( "#date_at" ).change(function() {
    var admin_id = <?php echo $user_logged_id; ?>; 
    var date_at = $( "#date_at" ).val();
    $.post('ajax.php', 
        {action: 'getPaymentMonthReport', month_year: date_at, admin_id: admin_id}, 
        function(response){ 
            var data = $.parseJSON(response);
            $("#total_revenue").val(data.totalRevenues);
            $("#expense").val(data.totalExpenses);
            $("#invalids").val(data.totalInvalids);
            $("#net_revenue").val(data.totalNetRevenues);            
    });
});

$( "#rate" ).change(function() {
    getGeneralCalculation();
});

function getGeneralCalculation() {
    var total = 0.00;
    var revenue = $( "#total_revenue" ).val();
    var expense = $( "#expense" ).val();
    var invalids = $( "#invalids" ).val();
    var net_revenue = $( "#net_revenue" ).val();
    var type = $( "#type" ).val();
    var rate = $( "#rate" ).val();
    var quotation = $( "#quotation" ).val();
    var exchange_rate = $( "#exchange_rate" ).val();

    //net_revenue = (revenue - invalids) - rate;
    //total = (( net_revenue * quotation ) - expense) - exchange_rate;

    //$("#net_revenue").val(total);
    //$("#total_receivable").val(total);
}
</script>
