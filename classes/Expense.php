<?php 

	class Expense{

		public $id;
		public $title;
		public $date_at;
		public $expense;
		public $customer_id;
		public $admin_id;
		public $created_at;
		public $updated_at;

		public $date_end;
		public $date_start;

		public function newExpense() {
			$this->created_at = date('Y-m-d H:i:s');
			$expense = number_format($this->expense, 2, '.', '');
			$sql = Banco::conectar()->prepare("INSERT INTO tb_expenses (title, date_at, expense, customer_id, admin_id, created_at)VALUES(?,?,?,?,?,?)");
			$sql->execute(array($this->title, $this->date_at, $expense, $this->customer_id, $this->admin_id, $this->created_at));
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

		public function updateExpense(){
			$this->updated_at = date('Y-m-d H:i:s');
			$expense = number_format($this->expense, 2, '.', '');		
			$sql = Banco::conectar()->prepare("UPDATE tb_expenses SET title = ?, date_at = ?, expense = ?, customer_id = ?, admin_id = ?, updated_at = ?  WHERE id = ?");
			$sql->execute(array($this->title, $this->date_at, $expense, $this->customer_id, $this->admin_id, $this->updated_at, $this->id));
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

		public function getReportExpense() {
			$title = '%' . $this->title . '%';
			$query = Banco::conectar()->prepare("SELECT * FROM tb_expenses WHERE admin_id = ? AND title LIKE ? AND date_at BETWEEN ? AND ? ORDER BY date_at DESC");
			$query->execute(array($this->admin_id, $title, $this->date_start, $this->date_end));
			return $query->fetchAll(PDO::FETCH_OBJ);
		}

		public function getReportCustomerExpenses() {
			$query = Banco::conectar()->prepare("SELECT SUM(expense) AS total FROM tb_expenses WHERE admin_id = ? AND customer_id = ? AND date_at BETWEEN ? AND ? ORDER BY date_at DESC");
			$query->execute(array($this->admin_id, $this->customer_id, $this->date_start, $this->date_end));
			if($query->rowCount() > 0 ) {
				$getExpenses = $query->fetchAll(PDO::FETCH_OBJ);
				$total = 0;
				foreach ($getExpenses as $key => $row) {
					$total = $total + $row->total;
				}
				return $total;
			} else {
				return 0;
			}
		}

		public function getExpenses() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_expenses WHERE admin_id = ? ORDER BY id DESC");
			$query->execute(array($this->admin_id));
			return $query->fetchAll(PDO::FETCH_OBJ);
		}

		public function getMonthExpenses() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_expenses WHERE admin_id = ? AND date_at BETWEEN ? AND ? ORDER BY id DESC");
			$query->execute(array($this->admin_id, $this->date_start, $this->date_end));
			return $query->fetchAll(PDO::FETCH_OBJ);
		}

		public function getExpense() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_expenses WHERE id = ?");
			$query->execute(array($this->id));
			return $query->fetch(PDO::FETCH_OBJ);
		}

		public function deleteExpense() {
			$sql = Banco::conectar()->prepare("DELETE FROM tb_expenses WHERE admin_id = ? AND id = ?");
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

		public function getNewExpenseWeek() {
			$start = date('Y-m-d H:i:s');
			$end = date('Y-m-d H:i:s');
			$query = Banco::conectar()->prepare("SELECT * FROM tb_expenses WHERE admin_id = ? AND created_at BETWEEN ? AND ? ");
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