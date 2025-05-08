<?php
$user = $_SESSION['userObj'];
if ($_SESSION['role'] !== 'FacultyStaff') {
    echo "you do not have permission to create a newsletter.";
    exit;
}
if (isset($_GET['id']) && $_GET['id'] !== '') {
    try {
        $newsletter = $user->getNewsletter($_GET['id']);
    } catch (Exception $e) {
        echo "Failed to get newsletter: " . $e->getMessage();
        exit;
    }

}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $state = $_POST['state'];
    if (isset($newsletter)) {
        $newsletter->editTitle($title);
        $newsletter->editBody($description);
        $newsletter->setState($state);
    } else {
        $user->createNewsletter($title, $description, $state);
    }
    header('Location: /newsletter');
    exit;
}
?>    
    
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow rounded-4">
                    <div class="card-body p-4">
                        <h2 class="mb-4 text-center">Create a New newsletter</h2>

                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <div class="mb-3">
                                <label for="title" class="form-label">newsletter Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?= isset($newsletter) ? $newsletter->getTitle() : '' ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">newsletter Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" value="<?= $newsletter ? $newsletter->getBody() : '' ?>" required></textarea>
                            </div>

                            <div class="mb-3 d-flex gap-2">
                                <button type="submit" name="state" value="draft" class="btn btn-primary flex-grow-1">Draft Newsletter</button>
                                <button type="submit" name="state" value="publish" class="btn btn-secondary flex-grow-1">Publish Newsletter</button>
                                <buton type="submit" name="state" value="delete" class="btn btn-danger flex-grow-1">Delete Newsletter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>