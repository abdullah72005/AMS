<?php 
$user = $_SESSION['userObj'];
$donations = $user->getDonations();
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $amount = $_POST['amount'];
    $cause = $_POST['cause'];
    $id = $user->getId();
    $user->makeDonation($amount , $cause);
    header("Location: ".$_SERVER['PHP_SELF']);
}


else if (!isset($_SESSION['loggedin']) || User::getRole($_SESSION['username']) != 'Alumni'){        
    echo "You are not allowed to make a donation.  ";
    exit;
} 

else {
    ?>
<div class="container-fluid mt-5 col">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="mb-4 text-center">donate</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="mb-3">
                    <label for="amount" class="form-label">donation amount</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="amount" 
                        name="amount" 
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="cause" class="form-label">donation cause</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="cause" 
                        name="cause" 
                        required
                    >
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Donate</button>
                </div>
            </form> 
        </div>
    </div>
    <?php } ?>
    <?php if (!empty($donations)): ?>
                        <div class="mt-4 container-fluid">
                            <h5>your donations(<?= count($donations) ?>):</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>amount</th>
                                            <th>cause</th>
                                            <th>date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($donations as $donation): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($donation['amount']) ?></td>
                                                <td><?= htmlspecialchars($donation['cause']) ?></td>
                                                <td><?= htmlspecialchars($donation['date']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    
                    <?php endif; ?>
                </div>