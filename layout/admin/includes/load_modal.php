<?php
$modal = $_GET['modal'] ?? '';

$basePath = __DIR__ . '/../components/';

switch ($modal) {
    case 'detail':
        include $basePath . 'product/detail_modal.php';
        break;
    case 'create':
        include $basePath . 'product/create_form.php';
        break;
    default:
        echo "<div>Modal not found</div>";
        break;
}
