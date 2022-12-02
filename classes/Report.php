<?php 

	class Report{

		public $id;
		public $order_id;
		public $product_id;
		public $title;
		public $category_id;
		public $variation_attributes;
		public $quantity;
		public $unit_price;
		public $full_unit_price;
		public $sale_fee;
		public $listing_type_id;
		public $created_at;

		public $customer;
		public $customer_email;
		
		public $date_end;
		public $date_start;

		public function getReportItems() {
			$date_start = $this->date_start . ' 00:00:00';
			$date_end = $this->date_end . ' 00:00:00';
			$title = '%' . $this->title . '%';
			$resposive = false;
			$query = Banco::conectar()->prepare("SELECT * FROM tb_order_items WHERE title LIKE ? ORDER BY title ASC");
			$query->execute(array($title));

			if($query->rowCount() > 0) {
				$getReports = $query->fetchAll(PDO::FETCH_OBJ);
				foreach($getReports as $row) {
					if ((strtotime($row->created_at) >= strtotime($date_start)) 
						&& (strtotime($row->created_at) <= strtotime($date_end))) {
						$resposive[] = $row;
					}
				}
			}
			return $resposive;
		}


	}//endClass

 ?>