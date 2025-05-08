<?php 

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $amount = $_POST['amount'];
    $cause = $_POST['cause'];
    $user = $_SESSION['userObj'];
    $id = $user->getId();
    $username = $user->getUsername();
    $user->makeDonation($id, $amount , $cause);
}
else if (!isset($_SESSION['loggedin']) || User::getRole($_SESSION['username']) != 'Alumni'){        
    echo "You are not allowed to make a donation.    ";
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