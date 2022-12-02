<?php 

	class Payment{

		public $id;
		public $date_at;
		public $total_revenue;
		public $invalids;
		public $type;
		public $rate;
		public $net_revenue;
		public $quotation;
		public $expense;
		public $exchange_rate;
		public $status;
		public $total_receivable;
		public $admin_id;
		public $created_at;
		public $updated_at;

		public $date_start;
		public $date_end;

		public function newPayment() {
			$this->created_at = date('Y-m-d H:i:s');
			if ($this->type=='1') {
				$this->net_revenue = ($this->total_revenue - $this->invalids) - $this->rate;
			} else {
				$net_revenue = ($this->total_revenue - $this->invalids);
				$this->net_revenue = $net_revenue - (($net_revenue / 100) * $this->rate);
			}
			$this->total_receivable = (($this->net_revenue * $this->quotation) -$this->expense) -$this->exchange_rate;
			$sql = Banco::conectar()->prepare("INSERT INTO tb_payments (date_at, total_revenue, invalids, type, rate, net_revenue, quotation, expense, exchange_rate, status, total_receivable, admin_id, created_at)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)");
			$sql->execute(array($this->date_at, $this->total_revenue, $this->invalids, $this->type, $this->rate, $this->net_revenue, $this->quotation, $this->expense, $this->exchange_rate, $this->status, $this->total_receivable, $this->admin_id, $this->created_at));
			if($sql){
				return true;
			} else {
				return false;
			}
		}

		public function updatePayment(){
			$this->updated_at = date('Y-m-d H:i:s');
			if ($this->type=='1') {
				$this->net_revenue = ($this->total_revenue - $this->invalids) - $this->rate;
			} else {
				$net_revenue = ($this->total_revenue - $this->invalids);
				$this->net_revenue = $net_revenue - (($net_revenue / 100) * $this->rate);
			}
			$this->total_receivable = (($this->net_revenue * $this->quotation) -$this->expense) -$this->exchange_rate;
			$sql = Banco::conectar()->prepare("UPDATE tb_payments SET date_at = ?, total_revenue = ?, invalids = ?, type = ?, rate = ?, net_revenue = ?, quotation = ?, expense = ?, exchange_rate = ?, status = ?, total_receivable = ?, admin_id = ?, updated_at = ? WHERE id = ?");
			$sql->execute(array($this->date_at, $this->total_revenue, $this->invalids, $this->type, $this->rate, $this->net_revenue, $this->quotation, $this->expense, $this->exchange_rate, $this->status, $this->total_receivable, $this->admin_id, $this->updated_at, $this->id));
			if($sql) {
				return true;
			} else {
				return false;
			}
		}

		public function getSyncPayment(){
			$expenses = 0;
			$expense = new Expense();
			$expense->admin_id = $admin_id = $this->admin_id;
			$expense->date_start = $date_start = trim(date("Y-m", strtotime($this->date_at)) . '-01');
			$expense->date_end = $date_end = trim(date("Y-m", strtotime($this->date_at)) . '-31');
			$getExpenses = $expense->getMonthExpenses();
			if (!empty($getExpenses)) {
				foreach($getExpenses as $row) {
					if (isset($row->expense)) {
						$expenses = $expenses + $row->expense;
					}
				}
			}

			$invalids = 0;
			$invalid = new Invalid();
			$invalid->admin_id = $admin_id;
			$invalid->date_start = $date_start;
			$invalid->date_end = $date_end;
			$getInvalids = $invalid->getMonthInvalids();
			if (!empty($getInvalids)) {
				foreach($getInvalids as $row) {
					if (isset($row->invalid)) {
						$invalids = $invalids + $row->invalid;
					}
				}
			}

			$revenues = 0;
			$revenue = new Revenue();
			$revenue->admin_id = $admin_id;
			$revenue->date_start = $date_start;
			$revenue->date_end = $date_end;
			$getRevenues = $revenue->getMonthRevenues();
			if (!empty($getRevenues)) {
				foreach($getRevenues as $row) {
					if (isset($row->revenue)) { 
						$revenues = $revenues + $row->revenue;
					}
				}
			}

			$payments = 0;
			$this->date_start = $date_start;
			$this->date_end = $date_end;
			$getPayment = $this->getMonthPayment();
			if (!empty($getPayment)) {
				$this->id = $getPayment->id;
		        $this->date_at = $getPayment->date_at;
		        $this->total_revenue = $revenues;
		        $this->invalids = $invalids;
		        $this->type = $getPayment->type;
		        $this->rate = $getPayment->rate; 
		        $this->net_revenue = ($revenues - $invalids);  
		        $this->quotation = $getPayment->quotation;
		        $this->expense = $expenses;
		        $this->exchange_rate = $getPayment->exchange_rate;
		        $this->status = $getPayment->status;
		        $this->admin_id = $getPayment->admin_id;
				if ($this->updatePayment()) {
					return true;
				} else {
					return false;
				}
			}
			return;
		}

		public function getReportPayment() {
			$date_start = $this->date_start;
			$date_end = $this->date_end;
			$resposive = false;
			$query = Banco::conectar()->prepare("SELECT * FROM tb_payments WHERE admin_id = ? ORDER BY title ASC");
			$query->execute(array($this->admin_id));
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

		public function getPayments() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_payments WHERE admin_id = ? ORDER BY id DESC");
			$query->execute(array($this->admin_id));
			return $query->fetchAll(PDO::FETCH_OBJ);
		}

		public function getPayment() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_payments WHERE id = ?");
			$query->execute(array($this->id));
			return $query->fetch(PDO::FETCH_OBJ);
		}

		public function deletePayment() {
			$sql = Banco::conectar()->prepare("DELETE FROM tb_payments WHERE admin_id = ? AND id = ?");
			if($sql->execute(array($this->admin_id, $this->id))){
				return true;
			}else{
				return false;
			}
		}

		public function getSalesMonth() {
			$sales = array();
			$year = date('Y');
			$j = 0;
			for ($i=1; $i <= 12; $i++) { 
				$month = $i;
				if (strlen($i) <= 1) {
					$month = '0' . $i;
				}
				$this->date_start = $year . '-' . $month . '-01';
				$this->date_end = $year . '-' . $month . '-31';
				$getMonthPayments = $this->getMonthPayments();
				$sales[] = $getMonthPayments->total;
				$j++;
			}
			return $sales;
		}

		public function getMonthPayments() {
			$query = Banco::conectar()->prepare("SELECT SUM(total_revenue) AS total FROM tb_payments WHERE admin_id = ? AND date_at BETWEEN ? AND ? ");
			$query->execute(array($this->admin_id, $this->date_start, $this->date_end));
			if($query->rowCount() > 0 ) {
				return $query->fetch(PDO::FETCH_OBJ);
			} else {
				return 0;
			}
		}

		public function getMonthPayment() {
			$query = Banco::conectar()->prepare("SELECT SUM(total_receivable) AS total, id, date_at, total_revenue, invalids, type, rate, net_revenue, quotation, expense, exchange_rate, status, admin_id, created_at, updated_at FROM tb_payments WHERE admin_id = ? AND date_at BETWEEN ? AND ? ");
			$query->execute(array($this->admin_id, $this->date_start, $this->date_end));
			if($query->rowCount() > 0 ) {
				return $query->fetch(PDO::FETCH_OBJ);
			} else {
				return 0;
			}
		}

	}//endClass

 ?>