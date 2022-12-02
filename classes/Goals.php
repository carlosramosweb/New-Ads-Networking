<?php 

	class Goals{

		public $id;
		public $title;
		public $date_at;
		public $goals;
		public $customer_id;
		public $admin_id;
		public $created_at;
		public $updated_at;

		public $date_end;
		public $date_start;

		public function newGoals() {
			$this->created_at = date('Y-m-d H:i:s');
			$goals = number_format($this->goals, 2, '.', '');
			$sql = Banco::conectar()->prepare("INSERT INTO tb_goals (title, date_at, goals, customer_id, admin_id, created_at)VALUES(?,?,?,?,?,?)");
			$sql->execute(array($this->title, $this->date_at, $goals, $this->customer_id, $this->admin_id, $this->created_at));
			if($sql){
				return true;
			} else {
				return false;
			}
		}

		public function updateGoals(){
			$this->updated_at = date('Y-m-d H:i:s');
			$goals = number_format($this->goals, 2, '.', '');		
			$query = Banco::conectar()->prepare("UPDATE tb_goals SET title = ?, date_at = ?, goals = ?, customer_id = ?, admin_id = ?, updated_at = ?  WHERE id = ?");
			$query->execute(array($this->title, $this->date_at, $goals, $this->customer_id, $this->admin_id, $this->updated_at, $this->id));
			if($query) {
				return true;
			} else {
				return false;
			}
		}

		public function getReportGoals() {
			$date_start = $this->date_start;
			$date_end = $this->date_end;
			$title = '%' . $this->title . '%';
			$resposive = false;
			$query = Banco::conectar()->prepare("SELECT * FROM tb_goals WHERE admin_id = ? title LIKE ? ORDER BY title ASC");
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

		public function getAllGoals() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_goals WHERE admin_id = ? ORDER BY YEAR('date_at'), MONTH('date_at') DESC");
			$query->execute(array($this->admin_id));
			if($query->rowCount() > 0 ) {
				return $query->fetchAll(PDO::FETCH_OBJ);
			} else {
				return false;
			}
		}

		public function getMonthGoals() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_goals WHERE admin_id = ? AND date_at BETWEEN ? AND ? ORDER BY date_at DESC");
			$query->execute(array($this->admin_id, $this->date_start, $this->date_end));
			if($query->rowCount() > 0 ) {
				return $query->fetchAll(PDO::FETCH_OBJ);
			} else {
				return false;
			}
		}

		public function getGoals() {
			$query = Banco::conectar()->prepare("SELECT * FROM tb_goals WHERE id = ?");
			$query->execute(array($this->id));			
			if($query->rowCount() > 0 ) {
				return $query->fetch(PDO::FETCH_OBJ);
			} else {
				return false;
			}
		}

		public function deleteGoals() {
			$query = Banco::conectar()->prepare("DELETE FROM tb_goals WHERE admin_id = ? AND id = ?");
			$query->execute(array($this->admin_id, $this->id));
			if($query) {
				return true;
			} else {
				return false;
			}
		}


	}//endClass

 ?>