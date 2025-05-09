<?php 
    if ($_SESSION['role'] !== 'FacultyStaff') {
    echo "you do not have permission to create a newsletter.";
    exit;
}
$newsletters = Newsletter::getDraftedNewsletters(); 
?>                
<div class="row">              
<?php if (!empty($newsletters)): ?>
    <?php  foreach ($newsletters as $newsletter): ?>
        <div class="card mb-3 w-50 mr-4">
            <div class="card-body ml-4">
                <h5 class="card-title"><?= htmlspecialchars($newsletter->getTitle()) ?></h5>
                <p class="card-text"><?= htmlspecialchars($newsletter->getBody()) ?></p>
                <a href="createnewsletter.php?id=<?= $newsletter->getId() ?>" class="btn btn-primary">View</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
</div> 