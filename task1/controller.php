<?php
class BankAccount {
    private $db;
	// Connect to the database
	private $dbHost = 'localhost';
	private $dbName = 'testing';
	private $dbUser = 'root';
	private $dbPass = '';

	

    public function __construct() {
		try {
			$this->db = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName);
		} catch (Exception $e) {
			echo "Database connection failed: " . $e->getMessage();
		exit;
		}
    }
	
	public function deposit($amount) {		
		if (!$this->db->query("INSERT INTO transactions (amount,type,status) VALUES (".$amount.",'DEPOSIT','DEBIT')")) {
			echo("Error description: " . $this->db->error);
		}
    }
	
	public function withdraw($amount) {
		$result = $this->db->query("SELECT SUM(CASE WHEN status = 'DEBIT' THEN amount ELSE 0 END) - SUM(CASE WHEN status = 'CREDIT' THEN amount ELSE 0 END) as amount FROM transactions");
		while ($row = mysqli_fetch_object($result)) {
			if($row->amount >= $amount){
				if (!$this->db->query("INSERT INTO transactions (amount,type,status) VALUES (".$amount.",'WITHDRAW','CREDIT')")) {
					echo("Error description: " . $this->db->error);
				}
			}
			else{
				echo "Your balance is insufficient" . PHP_EOL;
			}
		}
    }
	
	public function checkBalance() {
		if ($result = $this->db->query("SELECT SUM(CASE WHEN status = 'DEBIT' THEN amount ELSE 0 END) - SUM(CASE WHEN status = 'CREDIT' THEN amount ELSE 0 END) as amount FROM transactions")) {
			while ($row = mysqli_fetch_object($result)) {
				echo "Current amount: $row->amount" . PHP_EOL;
			}
		} 
    }
	
	public function history() {
		if ($result = $this->db->query("
			select
				a.id_transactions,
				a.times,
				a.type,
				case when a.status = 'DEBIT' then a.amount else 0 end debit,
				case when a.status = 'CREDIT' then a.amount else 0 end credit,
				coalesce(
					(
						(select sum(case when b.status = 'DEBIT' then b.amount else 0 end) from transactions b where b.id_transactions <= a.id_transactions)
						- 
						(select sum(case when b.status = 'CREDIT' then b.amount else 0 end) from transactions b where b.id_transactions <= a.id_transactions))
				, 0) as balance 
			from transactions a
            order by 1
		")) {
			$content = "";
			$content.= "<table>". PHP_EOL;
			$content.= "<thead>". PHP_EOL;
			$content.= "<tr>". PHP_EOL;
			$content.= "<th>Time</th>". PHP_EOL;
			$content.= "<th>Type</th>". PHP_EOL;
			$content.= "<th>Debit</th>". PHP_EOL;
			$content.= "<th>Credit</th>". PHP_EOL;
			$content.= "<th>Balance</th>". PHP_EOL;
			$content.= "</tr>". PHP_EOL;
			$content.= "</thead>". PHP_EOL;
			$content.= "<tbody>". PHP_EOL;

			while ($row = mysqli_fetch_array($result)) {
				$content.= "<tr>". PHP_EOL;
				$content.= "<td>".$row['times']."</td>". PHP_EOL;
				$content.= "<td >".$row['type']."</td>". PHP_EOL;
				$content.= "<td >".$row['debit']."</td>". PHP_EOL;
				$content.= "<td >".$row['credit']."</td>". PHP_EOL;
				$content.= "<td >".$row['balance']."</td>". PHP_EOL;
				$content.= "</tr>". PHP_EOL;
			}
			$content.= "<tbody>". PHP_EOL;
			$content.= "</table>". PHP_EOL; 
			echo $content;
		} 
    }
}



?>