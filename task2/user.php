<?php
session_start();

if (!isset($_SESSION["username"])) {
	echo "Anda harus login dulu <br><a href='login.php'>Klik disini</a>";
	exit;
}

$username=$_SESSION["username"];
include('controller2.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account = new BankAccount2();
	
    if (isset($_POST['deposit'])) {
        $depositAmount = $_POST['deposit'];
        $account->deposit($depositAmount);
    }

    if (isset($_POST['withdraw'])) {
        $withdrawAmount = $_POST['withdraw'];
        $account->withdraw($withdrawAmount);
    }
	
	if (isset($_POST['transfer'])) {
        $transferAmount = $_POST['transfer'];
        $select_user = $_POST['select_user'];
        $account->transfer($transferAmount,$select_user);
    }
}
else{
	$account = new BankAccount2();
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Financial Statement</title>
	<style>
		table {
			border:1px solid #b3adad;
			border-collapse:collapse;
			padding:5px;
		}
		table th {
			border:1px solid #b3adad;
			padding:5px;
			background: #c0c0c0;
			color: #313030;
		}
		table td {
			border:1px solid #b3adad;
			text-align:center;
			padding:5px;
			background: #fbfbfb;
			color: #313030;
		}
	</style>
</head>
<body>

	<h1>Finacial Statement</h1>
	<h2>Welcome back <?php echo $username; ?></h2>
	
	<a href="logout.php" class="btn btn-warning" role="button">Logout</a>
	<h3>Current Balance</h3>
	<br/>
	
	<?php
    if (isset($account)) {
        $account->checkBalance();
    }
    ?>

    <h2>Deposit</h2>
    <form method="post" action="">
        <label for="deposit-amount">Amount: </label>
        <input type="number" id="deposit-amount" name="deposit" required>
        <button type="submit">Deposit</button>
    </form>

    <h2>Withdraw</h2>
    <form method="post" action="">
        <label for="withdraw-amount">Amount: </label>
        <input type="number" id="withdraw-amount" name="withdraw" required>
        <button type="submit">Withdraw</button>
    </form>
	
	<h2>Transfer</h2>
    <form method="post" action="">
        <label for="transfer-amount">Amount: </label>
        <input type="number" id="transfer-amount" name="transfer" required>
		<p>&nbsp;</p>
		<label for="withdraw-amount">Recipient: </label>
        <?php
		$account->selectOptionUser();
		?>
        <button type="submit">Transfer</button>
    </form>
	<p>&nbsp;</p>
	<?php
    if (isset($account)) {
        $account->history();
    }
    ?>


</body>
</html> 