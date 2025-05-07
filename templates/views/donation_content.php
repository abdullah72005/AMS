<?php require_once("../src/Alumni.php");
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $amount = $_POST['amount'];
    $cause = $_POST['cause'];
    $user = new alumni(1); // Assuming user ID is 1 for this example
    echo $user->makeDonation($amount, $cause);
}else{ ?>
<form method="post" action="<?php $_SERVER['PHP_SELF'];?>">
    <label for="amount">Amount:</label>
    <input type="number" id="amount" name="amount" required>
    
    <label for="cause">Cause:</label>
    <input type="text" id="cause" name="cause" required>
    
    <button type="submit">Donate</button>
</form>
<?php }?>