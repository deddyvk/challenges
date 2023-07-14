<?php
class BankAccount2 {
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
	
	public function loginCheck($username,$password) {		
		$result = $this->db->query("SELECT id_user, username,password FROM user where username='".$username."' and password='".$password."'");
		$jumlah = mysqli_num_rows($result);
		if ($jumlah>0) {
			$row = mysqli_fetch_assoc($result);
			$_SESSION["id_user"]=$row["id_user"];
			$_SESSION["username"]=$row["username"];
		}
		else
			echo "Username atau password salah <br><a href='index.php'>Kembali</a>";
    }
	
	public function selectOptionUser() {		
		$result = $this->db->query("SELECT id_user, username FROM user where id_user <> ".$_SESSION["id_user"]."");
		$jumlah = mysqli_num_rows($result);
		if ($jumlah>0) {
			
			$content = "<select name='select_user' id='select_user'>";
			while ($row = mysqli_fetch_array($result)) {
				$content.= "<option value='".$row['id_user']."'>".$row['username']."</option>". PHP_EOL;
			}
			$content.= "</select>";
		}
		else
			$content = "<select name='select_user' id='select_user'><option value=''></option></select>";
		
		
		echo $content;
    }
	
	public function deposit($amount) {		
		if (!$this->db->query("INSERT INTO transactions2 (amount,type,status,id_user) VALUES (".$amount.",'DEPOSIT','DEBIT',".$_SESSION["id_user"].")")) {
			echo("Error description: " . $this->db->error);
		}
    }
	
	public function transfer($amount,$recipient) {	
		$recipient_name = '';
		$result = $this->db->query("SELECT username FROM user where id_user='".$recipient."'");
		$jumlah = mysqli_num_rows($result);
		if ($jumlah>0) {
			$row = mysqli_fetch_assoc($result);
			$recipient_name = $row["username"];
		}
		
		if (!$this->db->query("INSERT INTO transactions2 (amount,type,status,id_user,id_recipient,description) VALUES (".$amount.",'TRANSFER','CREDIT',".$_SESSION["id_user"].",".$recipient.",'Transfer to ".$recipient_name."')")) {
			echo("Error description: " . $this->db->error);
		}
		if (!$this->db->query("INSERT INTO transactions2 (amount,type,status,id_user,id_sender,description) VALUES (".$amount.",'TRANSFER','DEBIT',".$recipient.",".$_SESSION["id_user"].",'Transfer from ".$_SESSION["username"]."')")) {
			echo("Error description: " . $this->db->error);
		}
    }
	
	public function withdraw($amount) {
		$result = $this->db->query("SELECT SUM(CASE WHEN status = 'DEBIT' THEN amount ELSE 0 END) - SUM(CASE WHEN status = 'CREDIT' THEN amount ELSE 0 END) as amount FROM transactions2 where id_user = ".$_SESSION["id_user"]."");
		while ($row = mysqli_fetch_object($result)) {
			if($row->amount >= $amount){
				if (!$this->db->query("INSERT INTO transactions2 (amount,type,status,id_user) VALUES (".$amount.",'WITHDRAW','CREDIT',".$_SESSION["id_user"].")")) {
					echo("Error description: " . $this->db->error);
				}
			}
			else{
				echo "Your balance is insufficient" . PHP_EOL;
			}
		}
    }
	
	public function checkBalance() {
		if ($result = $this->db->query("SELECT (SUM(CASE WHEN type = 'DEPOSIT' THEN amount ELSE 0 END) + SUM(CASE WHEN type = 'TRANSFER' THEN amount ELSE 0 END)) - SUM(CASE WHEN type = 'WITHDRAW' THEN amount ELSE 0 END) as amount FROM transactions2 where id_user = ".$_SESSION["id_user"]."")) {
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
				a.description,
				case when a.status = 'DEBIT' then a.amount else 0 end debit,
				case when a.status = 'CREDIT' then a.amount else 0 end credit,
				coalesce(
					(
						(select sum(case when b.status = 'DEBIT' then b.amount else 0 end) from transactions2 b where b.id_transactions <= a.id_transactions and b.id_user = a.id_user)
						- 
						(select sum(case when b.status = 'CREDIT' then b.amount else 0 end) from transactions2 b where b.id_transactions <= a.id_transactions and b.id_user = a.id_user))
				, 0) as balance 
			from transactions2 a
			where a.id_user = ".$_SESSION["id_user"]."
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
			$content.= "<th>Description</th>". PHP_EOL;
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
				$content.= "<td >".$row['description']."</td>". PHP_EOL;
				$content.= "</tr>". PHP_EOL;
			}
			$content.= "<tbody>". PHP_EOL;
			$content.= "</table>". PHP_EOL; 
			echo $content;
		} 
    }
}



?>