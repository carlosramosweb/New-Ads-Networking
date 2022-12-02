<?php 

	class Domain{

		public $id;
		public $customer_id;
		public $domain;
		public $status;
		public $admin_id;
		public $created_at;
		public $updated_at;

		public function newDomain() {
			$this->created_at = date('Y-m-d H:i:s');
			$sql = Banco::conectar()->prepare("INSERT INTO tb_domains (customer_id, domain, status, admin_id, created_at)VALUES(?,?,?,?,?)");
			$sql->execute(array($this->customer_id, $this->domain, $this->status, $this->admin_id, $this->created_at));
			if($sql){
				return true;
			} else {
				return false;
			}
		}

		public function updateDomain(){
			$this->updated_at = date('Y-m-d H:i:s');
			$sql = Banco::conectar()->prepare("UPDATE tb_domains SET customer_id = ?, domain = ?, status = ?, admin_id = ?, updated_at = ?  WHERE id = ?");
			$sql->execute(array($this->customer_id, $this->domain, $this->status, $this->admin_id, $this->updated_at, $this->id));
			if($sql) {
				return true;
			} else {
				return false;
			}
		}

		public function getDomains() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_domains WHERE admin_id = ? ORDER BY id DESC");
			$query->execute(array($this->admin_id));
			return $query->fetchAll(PDO::FETCH_OBJ);
		}

		public function getDomain() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_domains WHERE id = ?");
			$query->execute(array($this->id));
			return $query->fetch(PDO::FETCH_OBJ);
		}

		public function getUserDomains() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_domains WHERE admin_id = ?");
			$query->execute(array($this->admin_id));
			return $query->fetchAll(PDO::FETCH_OBJ);
		}

		public function deleteDomain() {
			$sql = Banco::conectar()->prepare("DELETE FROM tb_domains WHERE id = ?");
			if($sql->execute(array($this->id))){
				return true;
			}else{
				return false;
			}
		}

	}//endClass

 ?>