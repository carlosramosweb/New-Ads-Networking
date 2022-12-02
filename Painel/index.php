<?php 
date_default_timezone_set('America/Sao_Paulo');
include_once('../config.php');
include_once('inc/functions.php');

if(Painel::logado() == false){
	include_once('login.php');
}else{
	include_once('main.php');
}
	