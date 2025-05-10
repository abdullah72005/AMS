<?php 

$successMsg = "";
$errorMsg = "";
$manager=$_SESSION['userObj'];

//check if user is FacultyStaff role
if (!isset($_SESSION['userObj']) || !($_SESSION['userObj'] instanceof FacultyStaff)) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $date = new DateTime($_POST['date']);

        
        
        $eventId = $manager->scheduleEvent($title, $description, $date);
        header("Location: eventPage.php/?eventId=$eventId");
        exit();
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif;
        }
        
        .card {
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }
        
        .form-control {
            border-radius: 8px;
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
        
        .btn-primary {
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 6px rgba(13, 110, 253, 0.2);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(13, 110, 253, 0.25);
        }
        
        .card-title {
            color: #212529;
            font-weight: 600;
        }
        
        .alert {
            border-radius: 8px;
            padding: 1rem;
        }

        .page-header {
            color: #0d6efd;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="page-header mb-4 text-center">Create a New Event</h2>

                        <?php if (!empty($errorMsg)): ?>
                            <div class="alert alert-danger mb-4">
                                <?php echo htmlspecialchars($errorMsg); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="" novalidate>
                            <div class="mb-4">
                                <label for="title" class="form-label">Event Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label">Event Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="date" class="form-label">Event Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>

                            <div class="mt-5">
                                <button type="submit" class="btn btn-primary w-100">Create Event</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS for optional components -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>