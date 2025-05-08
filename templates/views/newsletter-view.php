<?php 
if (isset($_GET['id']) && $_GET['id'] !== '') {
    try {
        $newsletter = new Newsletter($_GET['id']);
    } catch (Exception $e) {
        echo "Failed to get newsletter: " . $e->getMessage();
        exit;
    }
} 
?>