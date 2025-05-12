<?php 

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != 'Alumni'){        
    echo '<div style="padding: 20px; background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; border-radius: 5px; margin: 20px;">
             You are not allowed to make a donation.
          </div>';
    exit;
} 
$user = $_SESSION['userObj'];

if (!$user->isVerfied()) {
    echo '<div style="padding: 20px; background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; border-radius: 5px; margin: 20px;">
             Your account is not verified. Please contact the administrator for verification.
          </div>';
    exit;
}


$donations = $user->getDonations() ?? null;
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $amount = $_POST['amount'];
    $cause = $_POST['cause'];
    $id = $user->getId();
    $user->makeDonation($amount , $cause);
    header("Location: ".$_SERVER['PHP_SELF']);
}

else {
    ?>


    <link rel="stylesheet" href="./../../static/stylesheets/donation-view.css">

    <div class="container py-5">
        <div class="row g-4">
            
            <div class="col-md-6">
                <div class="card form-card p-4 p-md-5">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-heart-fill donation-icon"></i>
                        <h2 class="page-header m-0">Make a Donation</h2>
                    </div>
                    
                    <p class="text-muted mb-4">Your contribution helps support our alumni community and university initiatives.</p>
                    
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <div class="mb-4">
                            <label for="amount" class="form-label">Donation Amount</label>
                            <div class="input-with-icon">
                                <i class="bi bi-currency-dollar input-icon"></i>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="amount" 
                                    name="amount" 
                                    placeholder="Enter amount"
                                    required
                                >
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="cause" class="form-label">Donation Cause</label>
                            <div class="input-with-icon">
                                <i class="bi bi-tag input-icon"></i>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="cause" 
                                    name="cause"
                                    placeholder="Specify cause (e.g., Scholarship Fund)"
                                    required
                                >
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-donate">
                                <i class="bi bi-heart me-2"></i> Make Donation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <?php } ?>
            
            
            <div class="col-md-6">
                <div class="card table-card p-4">
                    <h3 class="section-title">
                        <i class="bi bi-clock-history me-2"></i>
                        Your Donation History
                    </h3>
                    
                    <?php if (!empty($donations)): ?>
                        <p class="text-muted mb-3">Thank you for your support!</p>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-currency-dollar me-1"></i>Amount</th>
                                        <th><i class="bi bi-tag me-1"></i>Cause</th>
                                        <th><i class="bi bi-calendar-date me-1"></i>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($donations as $donation): ?>
                                        <tr>
                                            <td class="donation-amount">$<?= htmlspecialchars($donation['amount']) ?></td>
                                            <td><?= htmlspecialchars($donation['cause']) ?></td>
                                            <td><?= htmlspecialchars($donation['date']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-cash-stack" style="font-size: 3rem; color: #dee2e6;"></i>
                            <p class="mt-3 text-muted">You haven't made any donations yet.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
