<?php
include('controller.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account = new BankAccount();
	
    if (isset($_POST['deposit'])) {
        $depositAmount = $_POST['deposit'];
        $account->deposit($depositAmount);
    }

    if (isset($_POST['withdraw'])) {
        $withdrawAmount = $_POST['withdraw'];
        $account->withdraw($withdrawAmount);
    }
}
else{
	$account = new BankAccount();
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
    <h1>Financial Statement</h1>

    <h2>Current Balance</h2>
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
	<p>&nbsp;</p>
	<?php
    if (isset($account)) {
        $account->history();
    }
    ?>
</body>
</html>
