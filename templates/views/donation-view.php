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


    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }
        
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }
        
        .form-card {
            background: linear-gradient(145deg, #ffffff, #f5f7fa);
        }
        
        .table-card {
            background-color: #ffffff;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
            border-color: #86b7fe;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
        }
        
        .btn-donate {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 6px rgba(13, 110, 253, 0.2);
            transition: all 0.3s ease;
            background-color: #0d6efd;
            border: none;
        }
        
        .btn-donate:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 10px rgba(13, 110, 253, 0.25);
            background-color: #0b5ed7;
        }
        
        .page-header {
            color: #0d6efd;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .donation-icon {
            font-size: 2rem;
            color: #0d6efd;
            margin-right: 12px;
        }
        
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }
        
        .table thead {
            background-color: #f8f9fa;
        }
        
        .table thead th {
            font-weight: 600;
            color: #495057;
            border-bottom: none;
            padding: 15px;
        }
        
        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: #f1f1f1;
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        
        .donation-amount {
            font-weight: 600;
            color: #198754;
        }
        
        .section-title {
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 20px;
            color: #343a40;
            font-weight: 600;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background-color: #0d6efd;
            border-radius: 2px;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon .form-control {
            padding-left: 45px;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
    </style>


    <div class="container py-5">
        <div class="row g-4">
            <!-- Left Column (Form) -->
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
            
            <!-- Right Column (Donations Table) -->
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
