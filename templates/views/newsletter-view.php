<?php

if (isset($_GET['id']) && $_GET['id'] !== '') {
    try {
        $newsletters = Newsletter::getNewsletter($_GET['id']);
        if ($newsletters->getIntState() == 0) {
            echo "this does not exist";
            exit;
        }
    } catch (Exception $e) {
        echo "Failed to get newsletter: " . $e->getMessage();
        exit;
    }
} else {
    $newsletters = Newsletter::getPublishedNewsletters();
} 

if (isset($_SESSION['role']) && $_SESSION['role'] === 'Alumni') {
    $user = $_SESSION['userObj'];
    if (!$user->isVerfied()) {
        echo '<div style="padding: 20px; background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; border-radius: 5px; margin: 20px;">
                 Your account is not verified. Please contact the administrator for verification.
              </div>';
        exit;
    }
}
?>

<link rel="stylesheet" href="./../../static/stylesheets/newsletter-view.css">

<div class="mt-4 container-fluid">
    <?php if (!empty($newsletters)){ ?>
    <?php if(!(isset($_GET['id']) && $_GET['id'] !== '')){ ?>
    <h5>all newsletters(<?= count($newsletters) ?>):</h5>
    <div class="row">
        <?php foreach ($newsletters as $newsletter): ?>
            <div class="card mb-3 w-50 mr-4">
                <div class="card-body ml-4">
                    <h5 class="card-title"><?= htmlspecialchars($newsletter->getTitle()) ?></h5>
                    <a href="newsletter.php?id=<?= $newsletter->getId() ?>" class="btn btn-primary">View</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php } else { ?>
        <div class="card h-50">
            <div class="card-body">
                <h5 class="card-title">
                    <?= htmlspecialchars($newsletters->getTitle()) ?>
                </h5>
                <p class="card-text"><?= htmlspecialchars($newsletters->getBody()) ?></p>
            </div>
        </div>
    </div>
</div>
<?php }} ?>