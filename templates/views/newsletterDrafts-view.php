<?php 
    if ($_SESSION['role'] !== 'FacultyStaff') {
        echo "<div class='container mt-5'><div class='alert alert-danger'>You do not have permission to create a newsletter.</div></div>";
        exit;
    }
    $newsletters = Newsletter::getDraftedNewsletters(); 
?>

<!-- Link to external CSS for enhanced design -->
<link rel="stylesheet" href="./../../static/stylesheets/newsletterDrafts-view.css">

<div class="container newsletter-section">
    <h2 class="section-title">Newsletter Drafts</h2>
    
    <?php if (!empty($newsletters)): ?>
        <div class="drafts-container">
            <?php foreach ($newsletters as $newsletter): ?>
                <div class="draft-card">
                    <div class="draft-header">
                        <h5 class="draft-title">
                            <i class="bi bi-file-earmark-text"></i>
                            <?= htmlspecialchars($newsletter->getTitle()) ?>
                        </h5>
                    </div>
                    <div class="draft-body">
                        <div class="draft-content">
                            <?= htmlspecialchars($newsletter->getBody()) ?>
                        </div>
                    </div>
                    <div class="draft-footer">
                        <span class="draft-status">
                            <i class="bi bi-pencil-square"></i> Draft
                        </span>
                        <a href="createnewsletter.php?id=<?= $newsletter->getId() ?>" class="btn btn-view">
                            <i class="bi bi-eye"></i> View & Edit
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <i class="bi bi-journal-x"></i>
            </div>
            <p class="empty-text">You don't have any newsletter drafts yet.</p>
            <a href="createnewsletter.php" class="btn btn-create">
                <i class="bi bi-plus-circle"></i> Create New Newsletter
            </a>
        </div>
    <?php endif; ?>
</div>