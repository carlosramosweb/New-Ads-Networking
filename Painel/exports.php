<?php 
require_once('../config.php');
include_once('inc/functions.php');

$User = new User();
$userLogged = $User;
$User->isLoggedUserIn();

$userLogged->id = $_SESSION['user']['id'];
$userLogged = $userLogged->getUser();
define('USER_LOGGED', (array)$userLogged);

adminPage(USER_LOGGED);
accessPermission('exports', USER_LOGGED);

if (isset($_GET['export']) && !empty($_GET['export'])) {

  $reports = new Report();

  $success = false;
  $error = false;

  $getReports = array();
  $date_end   = date('Y-m-d');
  $date_start = date('Y-m-d', strtotime('-30 days'));
  $title = '';

  $date_end   = (isset($_POST['date_end'])) ? $_POST['date_end'] : $date_end;
  $date_start = (isset($_POST['date_end'])) ? $_POST['date_start'] : $date_start;
  $title = (isset($_POST['title'])) ? $_POST['title'] : '';

  $reports->date_end = $date_end;
  $reports->date_start = $date_start;
  $reports->title = $title;
  $getReports = $reports->getReportItems();

  if(!empty($getReports)) {
    if (isset($_POST['type']) && trim($_POST['type']) == 'txt') {
      // INCLUDE_PATH_PAINEL - https://rrfluidos.tk/Painel/
      // BASE_DIR_PAINEL -  /home/storage/d/e0/7c/rrfluidos1/public_html/Painel/
      $url = INCLUDE_PATH_PAINEL . 'Export/';
      $directory = BASE_DIR_PAINEL . 'Export/';
      if(!is_dir($directory)){ 
          mkdir($directory, 0777, true);
          chmod($directory, 0777);
      }

      $get_file = date('dmY-His') . '.txt';            
      $file = fopen($directory . $get_file, "w");
      $header = "E-mails extraidos.\n";
      fwrite($file, $header);
      foreach($getReports as $row) {
          $customer = (isset($row->name)) ? $row->name : 'N/A';
          $customer_email = (isset($row->email)) ? $row->email : 'N/A';
          $content = $customer . "," . $customer_email . "\n";
          fwrite($file, $content);
          //fputs($file, $content);
      }
      fclose($file);

      $filename = $directory . $get_file;
      if (file_exists($filename)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: 0");
        header('Content-Disposition: attachment; filename="'.basename($filename).'"');
        header('Content-Length: ' . filesize($filename));
        header('Pragma: public');
        flush();
        readfile($filename);
        ?>
        <script type="text/javascript">
        window.close();
        </script>
        <?php
        die();
      }
    }

    if (isset($_POST['type']) && trim($_POST['type']) == 'xlsx') {
      // INCLUDE_PATH_PAINEL - https://rrfluidos.tk/Painel/
      // BASE_DIR_PAINEL -  /home/storage/d/e0/7c/rrfluidos1/public_html/Painel/
      $url = INCLUDE_PATH_PAINEL . 'Export/';
      $directory = BASE_DIR_PAINEL . 'Export/';
      if(!is_dir($directory)){ 
          mkdir($directory, 0777, true);
          chmod($directory, 0777);
      }

      $get_file = date('dmY-His') . '.xlsx'; 
      $filename = $directory . $get_file; 
    }

  } else {
    echo '<p>Nenhum resultado encontrado!</p>';
  }
  
}