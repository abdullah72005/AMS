<?php 
$user = $_SESSION['userObj'];
if ($_SESSION['role'] != "FacultyStaff"){
    echo "you are not allowed to access this";
    exit;
}
$donations = $user->getAllDonations();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./../../static/stylesheets/allDonations-view.css">
</head>
<body>

<?php if (!empty($donations)): ?>
    <div class="mt-4 container-fluid">
        <div class="donations-card">
            <h5>
                All Donations 
                <span class="badge badge-primary badge-pill"><?= count($donations) ?></span>
            </h5>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Cause</th>
                            <th>Date</th>
                            <th>Donor ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($donations as $donation): ?>
                            <tr>
                                <td data-label="Amount"><?= htmlspecialchars($donation['amount']) ?></td>
                                <td data-label="Cause"><?= htmlspecialchars($donation['cause']) ?></td>
                                <td data-label="Date"><?= htmlspecialchars($donation['date']) ?></td>
                                <td data-label="Donor ID"><?= htmlspecialchars($donation['donor_id']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>                   
<?php endif; ?>

</body>
</html>