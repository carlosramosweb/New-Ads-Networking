<?php 
ini_set('display_errors',1);
error_reporting(10);
require_once('../config.php');
require_once('inc/functions.php');

class Ajax {

    public $status;
    public $User;

    public $id;
    public $domain;

    public $month_year;
    public $date_start;
    public $date_end;

    public function __construct(){
        $this->User = new User();
        $this->status = null;
    }

    public function getReportRevenues() {

        $i = 0;
        $views = 0;
        $items = 0; 
        $revenue = 0; 
        $currency = 0;
        $html = '';
        $domains = new Domain();
        $admin_id = $this->admin_id;
        $domains->admin_id = $admin_id;
        $getDomains = $domains->getDomains();

        foreach($getDomains as $drow) {
            $reports = new Revenue();
            $reports->date_start = $this->date_start;
            $reports->date_end = $this->date_end;
            $reports->domain = trim($drow->id);
            $reports->admin_id = $admin_id;
            $getExports = $reports->getDomainRevenues(); 
            foreach($getExports as $row) {
                $get_domain = (isset($drow->domain)) ? $drow->domain : 'N/A';
                $revenue = $revenue + $row->total;
                $views = $views + $row->views;
                $currency = $row->currency;
                $revenue_old = 0;
                $percent = 0;
                $revenues = new Revenue();
                $revenues->date_start = $this->date_start;
                $revenues->date_end = $this->date_end;
                $revenues->domain = $row->domain;
                $revenues->admin_id = $admin_id;
                $getReportRevenue = $revenues->getReportRevenue();
                foreach ($getReportRevenue as $key => $value) {
                    $revenue_old = $revenue_old + $value->revenue;
                }
                if ($revenue_old > 0 && $revenue > 0) {
                    $percent = (($revenue / $revenue_old) * 100);
                }
                $arrow = 'fa-arrow-up';
                $text = 'text-success';
                if ($percent < 0) {
                    $arrow = 'fa-arrow-down';
                    $text = 'text-danger';
                }
                if ($percent == 0) {
                    $arrow = 'fa-arrow-down';
                    $text = 'text-warning';
                }
                $getDate = getMonthBR(date("m", strtotime($this->date_start))) . '/'. date("Y", strtotime($this->date_start));
                $html .= '<tr>';
                $html .= '<!---<td>' . $getDate . '</td>--->';
                $html .= '<td>' . $get_domain . '</td>';
                $html .= '<!---<td><small class="' .$text . ' mr-1"><i class="fas ' . $arrow . '"></i>';
                $html .= number_format($percent, 2, ',', '.') . '%</small></td>--->';
                $html .= '<td>R$ ' . number_format($row->total, 2, ',', '.') . '</td>';
                $html .= '</tr>';
                $i++; 
            } 
        }
        return $html;
    }

    public function getMonthReport() {

        $totalRevenues = 0;
        $totalExpenses = 0;
        $totalInvalids = 0;
        $totalNetRevenues = 0;
        $admin_id = $this->admin_id;

        $revenues = new Revenue();
        $revenues->date_start = $this->date_start;
        $revenues->date_end = $this->date_end;
        $revenues->admin_id = $admin_id;
        $getMonthRevenues = $revenues->getMonthRevenues();
        if (!empty($getMonthRevenues)) {
            foreach($getMonthRevenues as $row) { 
                $totalRevenues = $totalRevenues + $row->revenue;
            }
        }

        $expenses = new Expense();
        $expenses->date_start = $this->date_start;
        $expenses->date_end = $this->date_end;
        $expenses->admin_id = $admin_id;
        $getMonthExpenses = $expenses->getMonthExpenses();
        if (!empty($getMonthExpenses)) {
            foreach($getMonthExpenses as $row) { 
                $totalExpenses = $totalExpenses + $row->expense;
            }
        }

        $invalids = new Invalid();
        $invalids->date_start = $this->date_start;
        $invalids->date_end = $this->date_end;
        $invalids->admin_id = $admin_id;
        $getMonthInvalids = $invalids->getMonthInvalids();
        if (!empty($getMonthInvalids)) {
            foreach($getMonthInvalids as $row) { 
                $totalInvalids = $totalInvalids + $row->invalid;
            }
        }

        $payments = new Payment();
        $payments->date_start = $this->date_start;
        $payments->date_end = $this->date_end;
        $payments->admin_id = $admin_id;
        $getMonthPayment = $payments->getMonthPayment();
        $totalReceivables = 0;
        if (!empty($getMonthPayment)) {
            $totalReceivables = $getMonthPayment->total;
        }
        $totalNetRevenues = ($totalRevenues - $totalInvalids);

        return array(
            "totalRevenues" => number_format($totalRevenues, 2, ',', '.'),
            "totalExpenses" => number_format($totalExpenses, 2, ',', '.'),
            "totalInvalids" => number_format($totalInvalids, 2, ',', '.'),
            "totalNetRevenues" => number_format($totalNetRevenues, 2, ',', '.'), 
            "totalReceivables" => number_format($totalReceivables, 2, ',', '.'),           
        );
        return;
    }

    public function getDomainRevenue() {
        $revenues = new Revenue();
        $revenues->domain = $this->domain;
        $revenues->date_at = $this->date_at;
        if (isset($this->id) && !empty($this->id)) {
            $getDomainRevenue = $revenues->getDomainRevenue();
            if (isset($getDomainRevenue->id) && $getDomainRevenue->id == $this->id) {
               return 0;
            }
        } else {
            return $revenues->getDomainRevenue();
        }
    }

    public function getReportGoals() {

        $html = '';
        $goals = new Goals();
        $goals->date_start = $this->date_start;
        $goals->date_end = $this->date_end;
        $goals->admin_id = $this->admin_id;
        $getGoals = $goals->getMonthGoals();

        if(!empty($getGoals)) {
            $i = 1; 
            $progress = 0;
            $total = 0;
            foreach($getGoals as $row) { 
                $getDate = getMonthBR(date("m", strtotime($row->date_at))) . '/'. date("Y", strtotime($row->date_at));
                $revenues = new Revenue();
                $revenues->date_start = $this->date_start;
                $revenues->date_end = $this->date_end;
                $revenues->admin_id = $this->admin_id;
                $getReportRevenue = $revenues->getReportRevenue();
                if(!empty($getReportRevenue)) {
                    foreach ($getReportRevenue as $key => $row_) {
                      $total = $total + $row_->revenue;
                    }
                }
                if(isset($total) && $total > 0) {
                    $progress = ($total / $row->goals) * 100;
                    $progress = number_format($progress, 2, '.', '');
                    if(isset($progress) && $progress > 100) {
                      $progress = '100';
                    }
                }

                $bg_progress = 'bg-danger';
                $bar_progress = 'bg-danger';
                if(isset($progress) && $progress >= 25 && $progress <= 50) {
                    $bg_progress = 'bg-warning';
                    $bar_progress = 'bg-warning';
                }
                if(isset($progress) && $progress >= 50 && $progress <= 75) {
                    $bg_progress = 'bg-primary';
                    $bar_progress = 'bg-primary';
                }
                if(isset($progress) && $progress >= 75) {
                    $bg_progress = 'bg-success';
                    $bar_progress = 'bg-success';
                }

                $html = '<tr>';
                $html .= '<td>' . $i . '.</td>';
                $html .= '<td>$ ' . number_format($row->goals, 2, ',', '.') . '</td>';
                $html .= '<td>$ ' . number_format($total, 2, ',', '.') . '</td>';
                $html .= '<td>';
                $html .= '<div class="progress progress-xs">';
                $html .= '<span class="text-progress-bar">' . $progress . '% Completo</span>';
                $html .= '<div class="progress-bar ' . $bar_progress . '" style="width: ' . $progress . '%">';
                $html .= '</div>';
                $html .= '</div>';
                $html .= '</td>';
                $html .= '<td style="text-align: center;">';
                $html .= '<span class="badge ' . $bg_progress . '" style="font-size: 100%;">';
                $html .= $progress .'%';
                $html .= '</span>';
                $html .= '</td>';
                $html .= '</tr>';
                $i++; 
            }
        } else {
            $html = '<tr id="no-result-goals" class="no-result-goals">';
            $html .= '<td colspan="4">';
            $html .= 'Nenhum resultado foi encontrado!';
            $html .= '</td>';
            $html .= '</tr>';
        }
        return $html;
    }
}

$action = isset($_POST['action']) ? $_POST['action'] : 'dashboard';
$ajax = new ajax();
switch($action) {    
    case 'getPaymentMonthReport':
        if(isset($_POST['month_year'])) {
            $ajax->admin_id = trim($_POST['admin_id']);
            $ajax->date_start = trim($_POST['month_year'] . '-01');
            $ajax->date_end = trim($_POST['month_year'] . '-31');
        }
        echo trim(json_encode($ajax->getMonthReport()));
    break;
    case 'getMonthReport':
        if(isset($_POST['date_start']) && isset($_POST['date_end'])) {
            $ajax->admin_id = trim($_POST['admin_id']);
            $ajax->date_start = trim($_POST['date_start']);
            $ajax->date_end = trim($_POST['date_end']);
        }
        echo trim(json_encode($ajax->getMonthReport()));
    break;
    case 'getReportRevenues':
        if(isset($_POST['date_start']) && isset($_POST['date_end'])) {
            $ajax->admin_id = trim($_POST['admin_id']);
            $ajax->date_start = trim($_POST['date_start']);
            $ajax->date_end = trim($_POST['date_end']);
        }
        echo trim($ajax->getReportRevenues());
    break;
    case 'getReportGoals':
        if(isset($_POST['date_start']) && isset($_POST['date_end'])) {
            $ajax->admin_id = trim($_POST['admin_id']);
            $ajax->date_start = trim($_POST['date_start']);
            $ajax->date_end = trim($_POST['date_end']);
        }
        echo trim($ajax->getReportGoals());
    break;
    case 'getDomainRevenue':
        if(isset($_POST['id'])) {
            $ajax->id = trim($_POST['id']);
        }
        if(isset($_POST['domain']) && isset($_POST['date_at'])) {
            $ajax->admin_id = trim($_POST['admin_id']);
            $ajax->domain = trim($_POST['domain']);
            $ajax->date_at = trim($_POST['date_at']);
        }
        echo trim(json_encode($ajax->getDomainRevenue()));
    break;  
}
exit;
?>