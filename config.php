
<?php 
	session_start();
	ini_set('display_errors',1);
	error_reporting(E_ALL);
	date_default_timezone_set('America/Sao_Paulo');
	header('Content-Type: text/html; charset=utf-8');

	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://"; 
	$path_url = $protocol . $_SERVER['HTTP_HOST'];
	$panel_name = '/Painel/';
	$path_root = __DIR__;

	//define('ERROR_MSG', '');
	define('WEBSITE_NAME', 'New Ads Networking');
	define('INCLUDE_PATH', $path_url);
	define('INCLUDE_PATH_PAINEL', $path_url . $panel_name);
	define('INCLUDE_PATH_ROOT_PAINEL', $path_root);	
	define('BASE_DIR_PAINEL', $path_root . $panel_name);

	define('HOST', 'localhost');
	define('USER', 'user');
	define('PASSWORD', 'passowrd');
	define('DATABASE', 'db');

	define('EMAIL_ADMIN', 'contact@exemplo.com');
	define('SMTP_HOST', 'smtp.exemplo.com');
	define('SMTP_AUTH', true);
	define('SMTP_SECURE', 'ssl');
	define('SMTP_USER', 'no-reply@exemplo.com');
	define('SMTP_PASS', 'passowrd');
	define('SMTP_PORT', 465);

	// require_once('PHPMailer-master/src/Exception.php');
	// require_once('PHPMailer-master/src/OAuth.php');
	// require_once('PHPMailer-master/src/PHPMailer.php');
	// require_once('PHPMailer-master/src/SMTP.php');

	$autoload = function($class){
		if (file_exists( INCLUDE_PATH_ROOT_PAINEL . '/classes/' . $class . '.php')) {
			require_once('classes/' . $class . '.php');
		}
	};

	spl_autoload_register($autoload);

	function getRole($role){
		$arr = [
			'1' => 'Desenvolvedor',
			'2' => 'Administrador',
			'3' => 'Collaborator',
		];

		return $arr[$role];
	}

	function adminPage($user) {
		if(!is_numeric($user['role'])) {
			echo '<script>location.href="index.php";</script>';
		}
		if(!isset($user['role']) || $user['role'] == '' || $user['role'] == '0') {
			echo '<script>location.href="index.php";</script>';
		}
	}

	function accessPermission($access, $user) {
		if(!isset($user['id'])) {
			echo '<script>location.href="index.php";</script>';
		}
		if($user['role']<=1) {
			return true;
		}
		if($user['role']==2) {
			$page_access = 
			array(
				'settings',
				'domains', 
				'profile',
				'customers',
				'revenues',
				'expenses',
				'invalids',
				'goals',
				'payments',
				'reports',
				'incomes'
			);
			if(in_array($access, $page_access, true)) {
				return true;
			} else {
				echo '<script>location.href="index.php";</script>';
			}
		}
		if($user['role']>=3) {
			$page_access = 
			array(
			  'dashboard',
			  'profile'
			);
			if(in_array($access, $page_access, true)) {
				return true;
			} else {
				echo '<script>location.href="index.php";</script>';
			}
		}
	}

?>