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
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-hover: #3a56d4;
            --light-bg: #f8f9fa;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }

        .container-fluid {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

        .donations-card {
            background-color: #fff;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: none;
            transition: var(--transition);
            margin-bottom: 1.5rem;
            padding: 1.5rem;
        }

        .donations-card:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        h5 {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #edf2f7;
            text-transform: capitalize;
        }

        .table-responsive {
            overflow-x: auto;
            background-color: #fff;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            padding: 0.75rem;
            font-weight: 600;
            text-align: left;
            color: #4a5568;
            text-transform: capitalize;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .table tbody td {
            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }

        .table-hover tbody tr {
            transition: var(--transition);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
            transform: translateY(-1px);
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .badge {
            display: inline-block;
            padding: 0.25em 0.4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .badge-primary {
            color: #fff;
            background-color: var(--primary-color);
        }

        .badge-pill {
            padding-right: 0.6em;
            padding-left: 0.6em;
            border-radius: 10rem;
        }

        @media (max-width: 768px) {
            .table thead {
                display: none;
            }
            
            .table, .table tbody, .table tr, .table td {
                display: block;
                width: 100%;
            }
            
            .table tr {
                margin-bottom: 15px;
                border: 1px solid #dee2e6;
                border-radius: var(--border-radius);
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            
            .table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
                border-bottom: 1px solid #eee;
            }
            
            .table td:before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 45%;
                padding-left: 15px;
                font-weight: 600;
                text-align: left;
                color: #4a5568;
            }
            
            .table td:last-child {
                border-bottom: 0;
            }
        }
    </style>
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