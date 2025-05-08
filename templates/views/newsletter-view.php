<?php 
if (isset($_GET['id']) && $_GET['id'] !== '') {
    try {
        $newsletters = Newsletter::getNewsletter($_GET['id']);
    } catch (Exception $e) {
        echo "Failed to get newsletter: " . $e->getMessage();
        exit;
    } 
} else {
    $newsletters = Newsletter::getPublishedNewsletters();   
}
?>
<div class="mt-4 container-fluid">
    <?php if (!empty($newsletters)){ ?>
    <?php if(!(isset($_GET['id']) && $_GET['id'] !== '')){ ?>
    <h5>all donations(<?= count($newsletters) ?>):</h5>
    <div class="row">
                <?php  foreach ($newsletters as $newsletter): ?>
                    <div class="card mb-3 w-50 mr-4">
                        <div class="card-body ml-4">
                            <h5 class="card-title"><?= htmlspecialchars($newsletter->getTitle()) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($newsletter->getBody()) ?></p>
                            <a href="newsletter.php?id=<?= $newsletter->getId() ?>" class="btn btn-primary">View</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php } ?>
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
<?php } ?>