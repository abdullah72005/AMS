<?php 
    if ($_SESSION['role'] !== 'FacultyStaff') {
        echo "<div class='container mt-5'><div class='alert alert-danger'>You do not have permission to create a newsletter.</div></div>";
        exit;
    }
    $newsletters = Newsletter::getDraftedNewsletters(); 
?>

<!-- Custom CSS for enhanced design -->
<style>
    .newsletter-section {
        padding: 2rem 0;
    }
    
    .section-title {
        color: #333;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        position: relative;
        border-bottom: 2px solid #f0f0f0;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, #007bff, #6610f2);
        bottom: -2px;
        left: 0;
    }
    
    .drafts-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
    }
    
    .draft-card {
        flex: 0 0 calc(50% - 1.5rem);
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        position: relative;
        background: #fff;
    }
    
    @media (max-width: 992px) {
        .draft-card {
            flex: 0 0 100%;
        }
    }
    
    .draft-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
    }
    
    .draft-header {
        padding: 1.25rem 1.5rem;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        border-bottom: 1px solid #f0f0f0;
    }
    
    .draft-title {
        margin: 0;
        font-weight: 600;
        color: #333;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .draft-body {
        padding: 1.5rem;
        position: relative;
    }
    
    .draft-content {
        color: #6c757d;
        margin-bottom: 1.5rem;
        max-height: 120px;
        overflow: hidden;
        position: relative;
    }
    
    .draft-content:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 40px;
        background: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, 1));
    }
    
    .draft-footer {
        padding: .75rem 1.5rem;
        background-color: #f8f9fa;
        border-top: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .draft-status {
        display: inline-flex;
        align-items: center;
        background-color: #e9f5ff;
        color: #0d6efd;
        border-radius: 50px;
        padding: 0.25rem 0.75rem;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .draft-status i {
        margin-right: 0.3rem;
    }
    
    .btn-view {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        border: none;
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
    }
    
    .btn-view:hover {
        transform: translateY(-2px);
        background: linear-gradient(135deg, #0069d9, #004494);
        box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3);
        color: white;
    }
    
    .empty-state {
        width: 100%;
        text-align: center;
        padding: 3rem;
        background-color: #f8f9fa;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .empty-icon {
        font-size: 3rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    
    .empty-text {
        color: #6c757d;
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    
    .btn-create {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.2);
        transition: all 0.3s ease;
    }
    
    .btn-create:hover {
        background: linear-gradient(135deg, #218838, #1e9b7f);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(40, 167, 69, 0.3);
        color: white;
    }
</style>

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