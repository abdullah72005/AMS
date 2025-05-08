<?php 
require_once("../src/User.php");
require_once("../src/Alumni.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $amount = $_POST['amount'];
    $cause = $_POST['cause'];
    $user = $_SESSION['userObj'];
    $id = $user->getId();
    $username = $user->getUsername();
    echo $user->makeDonation($id, $amount , $cause);
}
else if (!isset($username) || User::getRole($username) != 'Alumni'){        
    echo "You are not allowed to make a donation.    " . $user;
    exit;} 
else {
    ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">donate</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">donation amount</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="amount" 
                        name="amount" 
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="pass" class="form-label">donation cause</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="cause" 
                        name="cause" 
                        required
                    >
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </form> 
        </div>
    </div>
</div>
<?php }?>