<?php 
require_once('../config.php');
require_once('inc/functions.php');

$success = false;
$error = false;
$alert = (isset($_GET['alert'])) ? $_GET['alert'] : null;

$date_now = date('Y-m-d');
$date_at = date('Y-m-d', strtotime('-1 day'));

if(isset($_GET['action'])) {
    if($_GET['action'] == 'edit') {
        if(isset($_GET['id'])) {
            $expense = new Expense();
            $expense->id = trim($_POST['id']);
            $expense->title = trim($_POST['title']);
            $expense->date_at = trim($_POST['date_at']);
            $get_expense = str_replace('.', '', $_POST['expense']);
            $get_expense = str_replace(',', '.', $get_expense);
            $expense->expense = trim($get_expense);
            $expense->customer_id = trim($_POST['customer_id']);
            $expense->admin_id = trim($_POST['admin_id']);
            if($expense->updateExpense()) {
                $success = true;
            } else {
                $error = true;
            }
        }
    }
}

require_once('header.php');
adminPage(USER_LOGGED);
accessPermission('expenses', USER_LOGGED);

$customer = new Customer();
$customer->admin_id = $user_logged_id;
$getCustomers = $customer->getCustomers();

$expenses = new Expense();
$expenses->id = trim($_GET['id']);
$expenses->admin_id = $user_logged_id;
$getExpense = $expenses->getExpense();

$date_at = date('Y-m-d', strtotime($getExpense->date_at));
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Despesas</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active">Despesas</li>
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
                      <h3 class="card-title">Editar Despesa</h3>
                    </div>

                    <div class="card-body">
                        <form method="post" action="?action=edit&id=<?php echo $getExpense->id; ?>" style="margin:15px 0 0 0;">

                            <div class="row">
                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                    <label class="control-label requiredField" for="title">
                                        Descrição:<span class="asteriskField">*</span>
                                    </label>
                                    <input class="form-control" id="title" name="title" type="text" required value="<?php echo $getExpense->title; ?>" />
                                </div>
                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                    <label class="control-label requiredField" for="customer_id">
                                        Cliente: <span class="asteriskField">*</span>
                                    </label>
                                    <select name="customer_id" class="form-control form-select customer_id" required>
                                        <option value="">Selecione</option>
                                        <?php if (!empty($getCustomers)) { ?>
                                            <?php foreach($getCustomers as $row) { ?>
                                        <option value="<?php echo $row->id; ?>" <?php if ($getExpense->customer_id==$row->id) { echo 'selected'; } ?>>
                                            <?php echo $row->name; ?>
                                        </option>
                                            <?php } ?>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                    <label class="control-label requiredField" for="date_at">
                                        Data:<span class="asteriskField">*</span>
                                    </label>
                                    <input class="form-control" id="date_at" name="date_at" type="date" required value="<?php echo $date_at; ?>" />
                                </div>
                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                    <label class="control-label requiredField" for="expense">
                                        Valor: <span class="asteriskField">*</span>
                                    </label>
                                    <input class="form-control" id="expense" name="expense" type="text" required value="<?php echo number_format($getExpense->expense, 2, ',', '.'); ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="admin_id" value="<?php echo $user_logged_id; ?>">
                                <input type="hidden" name="author_id" value="<?php echo USER_LOGGED['id']; ?>">
                                <input type="hidden" name="id" value="<?php echo $getExpense->id; ?>">
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="<?php echo INCLUDE_PATH_PAINEL; ?>js/jquery.priceformat.min.js"></script>
<script type="text/javascript">  
! function(e) {
    e(document).ready(function() {
        e("#expense").priceFormat({
            prefix: "",
            centsSeparator: ",",
            thousandsSeparator: "."
        })
    })
}(jQuery);
</script>

<?php 
require_once('footer.php');
