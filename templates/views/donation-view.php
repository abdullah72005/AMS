<?php 

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != 'Alumni'){        
    echo "You are not allowed to make a donation.  ";
    exit;
} 
$user = $_SESSION['userObj'];


$donations = $user->getDonations();
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $amount = $_POST['amount'];
    $cause = $_POST['cause'];
    $id = $user->getId();
    $user->makeDonation($amount , $cause);
    header("Location: ".$_SERVER['PHP_SELF']);
}




else {
    ?>
<div class="container-fluid mt-5">
    <div class="row"> <!-- Main row for two columns -->
        <!-- Left Column (Form) -->
        <div class="col-md-6">
            <h2 class="mb-4 text-center">Donate</h2>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="mb-3">
                    <label for="amount" class="form-label">Donation Amount</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="amount" 
                        name="amount" 
                        required
                    >
                </div>
                <div class="mb-3">
                    <label for="cause" class="form-label">Donation Cause</label>
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
        <?php } ?>
        <!-- Right Column (Donations Table) -->
        <div class="col-md-6">
            <?php if (!empty($donations)): ?>
                <h5>Your Donations (<?= count($donations) ?>):</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Cause</th>
                                <th>Date</th>
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
            <?php endif; ?>
        </div>
    </div>
</div