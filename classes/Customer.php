<?php 

	class Customer{

		public $id;
		public $name;
		public $email;
		public $cpf;
		public $cnpj;
		public $phone;
		public $cellphone;
		public $birthdate;
		public $sex;
		public $admin_id;
		public $created_at;
		public $updated_at;

		public function newCustomer() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_customers WHERE email = ?");
			$query->execute(array($this->email));
			if($query->rowCount() <= 0) {
				$this->created_at = date('Y-m-d H:i:s');
				$sql = Banco::conectar()->prepare("INSERT INTO tb_customers (name, email, admin_id, created_at) VALUES(?,?,?,?)");
				if($sql->execute(array($this->name, $this->email, $this->admin_id, $this->created_at))){
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		public function updateCustomer(){
			$this->updated_at = date('Y-m-d H:i:s');
			$sql = Banco::conectar()->prepare("UPDATE tb_customers SET name = ?, email = ?, admin_id = ?, updated_at = ?  WHERE id = ?");
			$sql->execute(array($this->name, $this->email, $this->admin_id, $this->updated_at, $this->id));
			if($sql) {
				return true;
			} else {
				return false;
			}
		}

		public function getCustomers() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_customers WHERE admin_id = ? ORDER BY id DESC");
			$query->execute(array($this->admin_id));
			return $query->fetchAll(PDO::FETCH_OBJ);
		}

		public function getCustomer() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_customers WHERE id = ?");
			$query->execute(array($this->id));
			return $query->fetch(PDO::FETCH_OBJ);
		}

		public function getUserCustomer() {
			$super_id = 1;
			$query = Banco::conectar()->prepare("SELECT * FROM tb_customers WHERE admin_id = ?");
			$query->execute(array($this->admin_id));
			return $query->fetch(PDO::FETCH_OBJ);
		}

		public function deleteCustomer() {
			$sql = Banco::conectar()->prepare("DELETE FROM tb_customers WHERE id = ?");
			if($sql->execute(array($this->id))){
				return true;
			}else{
				return false;
			}
		}

	}//endClass

 ?>