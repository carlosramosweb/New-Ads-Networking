<?php
$pages_dashboards = 
array(
  "dashboard",
  "index"
);
$pages_settings = 
array(
  "settings",
  "domains", 
  "new-domain", 
  "edit-domain",
  "profile",
  "users", 
  "new-user", 
  "edit-user",
  "customers", 
  "new-customer", 
  "edit-customer", 
  "goals",
  "new-goals",
  "edit-goals"
);

$pages_profiles = 
array(
  "profile"
);

$pages_payments = 
array(
  "payments",
  "new-payment", 
  "edit-payment",
);

$pages_releases = 
array(
  "revenues", 
  "new-revenue", 
  "edit-revenue",
  "view-revenue",
  "expenses",
  "edit-expense",
  "new-expense",
  "invalids",
  "edit-invalid",
  "new-invalid",
);

$pages_reports = 
array(
  "reports", 
);

$pages_incomes = 
array(
  "incomes", 
);
$disabled = true;
$hidden = false;
?> 

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="<?php echo INCLUDE_PATH_PAINEL; ?>img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Painel de Controle</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo INCLUDE_PATH_PAINEL; ?>img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="profile.php" class="d-block"><?php echo $userLogged->name; ?></a>
          <span style="color: #6e7881;"><?php echo getRole($userLogged->role); ?></span>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <li class="nav-item <?php if(get_sidebar_item($pages_dashboards)) { echo 'menu-is-opening menu-open';} ?>">
            <a href="index.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>

          <?php if (isset(USER_LOGGED['role']) && USER_LOGGED['role'] <= 2 ) { ?>
          <li class="nav-item <?php if(get_sidebar_item($pages_releases)) { echo 'menu-is-opening menu-open';} ?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Lançamentos
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" <?php if(get_sidebar_item($pages_releases)) { echo 'style="display: block;"';} ?>>
              <li class="nav-item">
                <a href="revenues.php" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Rendimentos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="expenses.php" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Despesas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="invalids.php" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Inválidos</p>
                </a>
              </li>
            </ul>
          </li>  
          <?php } ?>

          <?php if (isset(USER_LOGGED['role']) && USER_LOGGED['role'] <= 2 ) { ?>
          <li class="nav-item <?php if(get_sidebar_item($pages_payments)) { echo 'menu-is-opening menu-open';} ?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-credit-card"></i>
              <p>
                Pagamentos
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" <?php if(get_sidebar_item($pages_payments)) { echo 'style="display: block;"';} ?>>
              <li class="nav-item">
                <a href="payments.php" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Histórico</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="new-payment.php" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Gerar Pagamento</p>
                </a>
              </li>
            </ul>
          </li>
          <?php } ?>

          <?php if (isset(USER_LOGGED['role']) && USER_LOGGED['role'] <= 2 ) { ?>
          <li class="nav-item <?php if(get_sidebar_item($pages_reports)) { echo 'menu-is-opening menu-open';} ?>">
            <a href="reports.php" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>
                Relatórios
              </p>
            </a>
          </li>
          <?php } ?>

          <?php if (isset(USER_LOGGED['role']) && USER_LOGGED['role'] <= 2 ) { ?>
          <li class="nav-item <?php if(get_sidebar_item($pages_incomes)) { echo 'menu-is-opening menu-open';} ?>">
            <a href="incomes.php" class="nav-link">
              <i class="nav-icon fas fa-table"></i>
              <p>
                Receitas
              </p>
            </a>
          </li>
          <?php } ?>

          <?php if (isset(USER_LOGGED['role']) && USER_LOGGED['role'] <= 2 ) { ?>
          <li class="nav-item <?php if(get_sidebar_item($pages_settings)) { echo 'menu-is-opening menu-open';} ?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>
                Configurações
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" <?php if(get_sidebar_item($pages_settings)) { echo 'style="display: block;"';} ?>>
              <li class="nav-item">
                <a href="goals.php" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Metas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="domains.php" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Domínios</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="customers.php" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Clientes</p>
                </a>
              </li>
              <?php if (isset(USER_LOGGED['role']) && USER_LOGGED['role'] == 1 ) { ?>
              <li class="nav-item">
                <a href="users.php" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Usuários</p>
                </a>
              </li>
              <?php } ?> 
            </ul>
          </li>  
          <?php } ?>   

          <?php if ($hidden) { ?>
          <li class="nav-item <?php if(get_sidebar_item($pages_profiles)) { echo 'menu-is-opening menu-open';} ?>">
            <a href="profile.php" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Perfil
              </p>
            </a>
          </li>
          <?php } ?>   

          <li class="nav-item">
            <a href="index.php?logout" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Desconectar
              </p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

<style type="text/css">
.nav-treeview {
  background: #292b2e !important;
}
</style>
