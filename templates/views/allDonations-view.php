<?php 
$user = $_SESSION['userObj'];
if ($_SESSION['role'] != "FacultyStaff"){
    echo "you are not allowed to access this";
    exit;
}
$donations = $user->getAllDonations();
?>

 <?php if (!empty($donations)): ?>
                        <div class="mt-4 container-fluid">
                            <h5>all donations(<?= count($donations) ?>):</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>amount</th>
                                            <th>cause</th>
                                            <th>date</th>
                                            <th>donorId</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($donations as $donation): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($donation['amount']) ?></td>
                                                <td><?= htmlspecialchars($donation['cause']) ?></td>
                                                <td><?= htmlspecialchars($donation['date']) ?></td>
                                                <td><?= htmlspecialchars($donation['donor_id']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>                   
<?php endif; ?>