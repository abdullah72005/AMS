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
?>

<!-- Easter Egg: Custom CSS for modern aesthetic -->
<style>
/* Modern styling with shadows and hover effects */
.card {
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    border: none;
    overflow: hidden;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}

.card-body {
    padding: 1.5rem;
}

.card-title {
    margin-bottom: 1rem;
    font-weight: 600;
    color: #333;
}

.card-text {
    color: #555;
    line-height: 1.6;
}

.btn-primary {
    background-color: #4e73df;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 4px;
    transition: all 0.2s;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.btn-primary:hover {
    background-color: #2e59d9;
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.container-fluid {
    padding: 2rem 1.5rem;
}

h5 {
    margin-bottom: 1.5rem;
    color: #333;
    font-weight: 600;
}

.row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -15px;
}

/ Proper spacing for card layout /
.w-50 {
    width: calc(50% - 30px) !important;
    margin: 0 15px 30px;
}

/ Easter egg: Add a little surprise on hover */
.card:hover .card-title {
    background: linear-gradient(to right, #4e73df, #224abe);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>

<div class="mt-4 container-fluid">
    <?php if (!empty($newsletters)){ ?>
    <?php if(!(isset($_GET['id']) && $_GET['id'] !== '')){ ?>
    <h5>all newsletters(<?= count($newsletters) ?>):</h5>
    <div class="row">
        <?php foreach ($newsletters as $newsletter): ?>
            <div class="card mb-3 w-50 mr-4">
                <div class="card-body ml-4">
                    <h5 class="card-title"><?= htmlspecialchars($newsletter->getTitle()) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($newsletter->getBody()) ?></p>
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