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
    
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow rounded-4">
                    <div class="card-body p-4">
                        <h2 class="mb-4 text-center">Create a New newsletter</h2>

                        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                            <input type="hidden" id="id" name="id" value="<?= isset($_GET['id']) ? $_GET['id'] : null ?>">
                            <div class="mb-3">
                                <label for="title" class="form-label">newsletter Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?= isset($newsletter) ? $newsletter->getTitle() : '' ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">newsletter Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" required><?= isset($newsletter) ? htmlspecialchars($newsletter->getBody()) : '' ?></textarea>
                            </div>

                            <div class="mb-3 d-flex gap-2">
                                <button type="submit" name="state" value="draft" class="btn btn-primary flex-grow-1">Draft Newsletter</button>
                                <button type="submit" name="state" value="publish" class="btn btn-secondary flex-grow-1">Publish Newsletter</button>
                                <button  type="submit" name="state" value="delete" class="btn btn-danger flex-grow-1">Delete Newsletter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>