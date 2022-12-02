<?php 

	class Invalid{

		public $id;
		public $title;
		public $date_at;
		public $invalid;
		public $customer_id;
		public $admin_id;
		public $created_at;
		public $updated_at;

		public $date_end;
		public $date_start;

		public function newInvalid() {
			$this->created_at = date('Y-m-d H:i:s');
			$invalid = number_format($this->invalid, 2, '.', '');
			$sql = Banco::conectar()->prepare("INSERT INTO tb_invalids (title, date_at, invalid, customer_id, admin_id, created_at)VALUES(?,?,?,?,?,?)");
			$sql->execute(array($this->title, $this->date_at, $invalid, $this->customer_id, $this->admin_id, $this->created_at));
			if($sql){
				$payment = new Payment();
				$payment->admin_id = $this->admin_id;
				$payment->date_at = $this->date_at;
				$payment->getSyncPayment();
				return true;
			} else {
				return false;
			}
		}

		public function updateInvalid(){
			$this->updated_at = date('Y-m-d H:i:s');
			$invalid = number_format($this->invalid, 2, '.', '');		
			$sql = Banco::conectar()->prepare("UPDATE tb_invalids SET title = ?, date_at = ?, invalid = ?, customer_id = ?, admin_id = ?, updated_at = ?  WHERE id = ?");
			$sql->execute(array($this->title, $this->date_at, $invalid, $this->customer_id, $this->admin_id, $this->updated_at, $this->id));
			if($sql) {
				$payment = new Payment();
				$payment->admin_id = $this->admin_id;
				$payment->date_at = $this->date_at;
				$payment->getSyncPayment();
				return true;
			} else {
				return false;
			}
		}

		public function getReportInvalid() {
			$date_start = $this->date_start;
			$date_end = $this->date_end;
			$title = '%' . $this->title . '%';
			$resposive = false;
			$query = Banco::conectar()->prepare("SELECT * FROM tb_invalids WHERE admin_id = ? AND title LIKE ? ORDER BY title ASC");
			$query->execute(array($this->admin_id, $title));

			if($query->rowCount() > 0) {
				$getReports = $query->fetchAll(PDO::FETCH_OBJ);
				foreach($getReports as $row) {
					if ((strtotime($row->date_at) >= strtotime($date_start)) 
						&& (strtotime($row->date_at) <= strtotime($date_end))) {
						$resposive[] = $row;
					}
				}
			}
			return $resposive;
		}

		public function getInvalids() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_invalids WHERE admin_id = ? ORDER BY id DESC");
			$query->execute(array($this->admin_id));
			return $query->fetchAll(PDO::FETCH_OBJ);
		}

		public function getMonthInvalids() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_invalids WHERE admin_id = ? AND date_at BETWEEN ? AND ? ORDER BY id DESC");
			$query->execute(array($this->admin_id, $this->date_start, $this->date_end));
			return $query->fetchAll(PDO::FETCH_OBJ);
		}

		public function getInvalid() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_invalids WHERE id = ?");
			$query->execute(array($this->id));
			return $query->fetch(PDO::FETCH_OBJ);
		}

		public function deleteInvalid() {
			$sql = Banco::conectar()->prepare("DELETE FROM tb_invalids WHERE admin_id = ? AND id = ?");
			if($sql->execute(array($this->admin_id, $this->id))){
				$payment = new Payment();
				$payment->admin_id = $this->admin_id;
				$payment->date_at = $this->date_at;
				$payment->getSyncPayment();
				return true;
			}else{
				return false;
			}
		}

		public function getNewInvalidWeek() {
			$start = date('Y-m-d H:i:s');
			$end = date('Y-m-d H:i:s');
			$query = Banco::conectar()->prepare("SELECT * FROM tb_invalids WHERE created_at BETWEEN ? AND ? ");
			$query->execute(array(date( 'Y-m-d H:i:s', strtotime("$start -7 days")), $end));
			if($query->rowCount() > 0 ) {
				return $query->rowCount();
			} else {
				return 0;
			}
		}

		public function getBounceRate() {
			return 53;
		}

		public function getSalesMonth() {
			$sales = array( 120, 105, 172, 421, 365, 251, 516, 421, 46, 630, 125, 691 );
			return $sales;
		}

	}//endClass

 ?>