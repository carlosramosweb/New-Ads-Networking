<?php 

	class Revenue{

		public $id;
		public $author_id;
		public $currency;
		public $date_at;
		public $title;
		public $revenue;
		public $views;
		public $printing;
		public $reading_at;
		public $domain;
		public $admin_id;
		public $created_at;
		public $updated_at;

		public $date_end;
		public $date_start;

		public function newRevenue() {
			$this->created_at = date('Y-m-d H:i:s');
			$reading_at = ($this->printing / $this->views);
			if (is_float($reading_at)) {
				$reading_at = number_format($reading_at, 2, '.', '');
			}	
			$sql = Banco::conectar()->prepare("INSERT INTO tb_revenues (author_id, currency, date_at, title, revenue, views, printing, reading_at, domain, admin_id, created_at)VALUES(?,?,?,?,?,?,?,?,?,?,?)");
			$sql->execute(array($this->author_id, $this->currency, $this->date_at, $this->title, $this->revenue, $this->views, $this->printing, $reading_at, $this->domain, $this->admin_id, $this->created_at));
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

		public function updateRevenue(){
			$this->updated_at = date('Y-m-d H:i:s');
			$reading_at = ($this->printing / $this->views);
			if (is_float($reading_at)) {
				$reading_at = number_format($reading_at, 2, '.', '');
			}			
			$sql = Banco::conectar()->prepare("UPDATE tb_revenues SET author_id = ?, currency = ?, date_at = ?, title = ?, revenue = ?, views = ?, printing = ?, reading_at = ?, domain = ?, admin_id = ?, updated_at = ?  WHERE id = ?");
			$sql->execute(array($this->author_id, $this->currency, $this->date_at, $this->title, $this->revenue, $this->views, $this->printing, $reading_at, $this->domain, $this->admin_id, $this->updated_at, $this->id));
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

		public function getReportRevenue() {
			if (empty($this->domain) || $this->domain == 0) {
				$query = Banco::conectar()->prepare("SELECT * FROM tb_revenues WHERE admin_id = ? AND date_at BETWEEN ? AND ? ORDER BY date_at DESC");
				$query->execute(array($this->admin_id, $this->date_start, $this->date_end));
			} else {
				$query = Banco::conectar()->prepare("SELECT * FROM tb_revenues WHERE admin_id = ? AND domain = ? AND date_at BETWEEN ? AND ? ORDER BY date_at DESC");
				$query->execute(array($this->admin_id, $this->domain, $this->date_start, $this->date_end));
			}
			if($query) {
				return $query->fetchAll(PDO::FETCH_OBJ);
			} else {
				return false;
			}
		}

		public function getDomainRevenues() {
			$query = Banco::conectar()->prepare("SELECT SUM(revenue) AS total, domain, currency, views FROM tb_revenues WHERE admin_id = ? AND domain = ? AND date_at BETWEEN ? AND ? ORDER BY title ASC");
			$query->execute(array($this->admin_id, $this->domain, $this->date_start, $this->date_end));
			if($query->rowCount() > 0 ) {
				return $query->fetchAll(PDO::FETCH_OBJ);
			} else {
				return 0;
			}
		}

		public function getDomainRevenue() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_revenues WHERE admin_id = ? AND domain = ? AND date_at = ? ORDER BY date_at DESC");
			$query->execute(array($this->admin_id, $this->domain, $this->date_at));
			if($query->rowCount() > 0 ) {
				return $query->fetch(PDO::FETCH_OBJ);
			} else {
				return 0;
			}
		}

		public function getRevenues() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_revenues WHERE admin_id = ? ORDER BY date_at DESC");
			$query->execute(array($this->admin_id));
			if($query->rowCount() > 0 ) {
				return $query->fetchAll(PDO::FETCH_OBJ);
			} else {
				return false;
			}
		}

		public function getMonthRevenues() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_revenues WHERE admin_id = ? AND date_at BETWEEN ? AND ? ORDER BY id DESC");
			$query->execute(array($this->admin_id, $this->date_start, $this->date_end));
			if($query->rowCount() > 0 ) {
				return $query->fetchAll(PDO::FETCH_OBJ);
			} else {
				return false;
			}
		}

		public function getRevenue() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_revenues WHERE id = ?");
			$query->execute(array($this->id));
			return $query->fetch(PDO::FETCH_OBJ);
			if($query->rowCount() > 0 ) {
				return $query->fetch(PDO::FETCH_OBJ);
			} else {
				return false;
			}
		}

		public function deleteRevenue() {
			$query = Banco::conectar()->prepare("DELETE FROM tb_revenues WHERE admin_id = ? AND id = ?");
			$query->execute(array($this->admin_id, $this->id));
			if($query->rowCount() > 0 ) {
				$payment = new Payment();
				$payment->admin_id = $this->admin_id;
				$payment->date_at = $this->date_at;
				$payment->getSyncPayment();
				return true;
			} else {
				return false;
			}
		}

		public function getNewRevenueWeek() {
			$start = date('Y-m-d H:i:s');
			$end = date('Y-m-d H:i:s');
			$query = Banco::conectar()->prepare("SELECT * FROM tb_revenues WHERE admin_id = ? AND created_at BETWEEN ? AND ? ");
			$query->execute(array($this->admin_id, date( 'Y-m-d H:i:s', strtotime("$start -7 days")), $end));
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