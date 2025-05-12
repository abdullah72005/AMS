<?php
$user = $_SESSION['userObj'];

if ($_SESSION['role'] !== 'FacultyStaff') {
    echo "you do not have permission to create a newsletter.";
    exit;
}

if (isset($_GET['id']) && $_GET['id'] !== '') {
    try {
        $newsletter = $user->getNewsletter($_GET['id']);
        if ($newsletter instanceof Exception) {
            echo "Failed to get newsletter: " . $newsletter->getMessage();
            exit;
        }

    } catch (Exception $e) {
        echo "Failed to get newsletter: " . $e->getMessage();
        exit;
    }
}
if (isset($newsletter)  && $newsletter->getIntState() == 1) {
    echo "You cannot edit a published newsletter.";
    exit;
} 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $state = $_POST['state'];
    $id = $_POST['id'];
    switch ($state) {
        case 'draft':
            $state = new DraftState();
            break;
        case 'publish':
            $state = new PublishedState();
            break;
    }
    if ($state === 'delete') {
        newsletter::delete($id);
        exit;
    }
    $newsletter = $user->createNewsletter($title, $description, $state, $id);
    
    $newId = $newsletter->save();
    if ($state instanceof DraftState){
        header("Location: newsletterDrafts.php?");
    }
    else {
        header("Location: newsletter.php?id=$newId");
    }
    exit;
}
?>    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Newsletter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./../../static/stylesheets/createNewsletter-view.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card rounded-4">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="page-header mb-4 text-center">Create a New Newsletter</h2>

                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <input type="hidden" id="id" name="id" value="<?= isset($_GET['id']) ? $_GET['id'] : null ?>">
                            <div class="mb-4">
                                <label for="title" class="form-label">Newsletter Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?= isset($newsletter) ? $newsletter->getTitle() : '' ?>" required>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="form-label">Newsletter Content</label>
                                <textarea class="form-control" id="description" name="description" rows="6" required><?= isset($newsletter) ? htmlspecialchars($newsletter->getBody()) : '' ?></textarea>
                            </div>

                            <div class="mb-3 d-flex action-buttons flex-wrap">
                                <button type="submit" name="state" value="draft" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-save me-1"></i> Save as Draft
                                </button>
                                <button type="submit" name="state" value="publish" class="btn btn-success flex-grow-1">
                                    <i class="bi bi-send me-1"></i> Publish Newsletter
                                </button>
                                <button type="submit" name="state" value="delete" class="btn btn-danger flex-grow-1">
                                    <i class="bi bi-trash me-1"></i> Delete Newsletter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Bootstrap Icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>