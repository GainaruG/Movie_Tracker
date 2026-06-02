<?php
require_once __DIR__ . '/php/functions.php';

$action = $_GET['action'] ?? 'switch';
$email  = trim($_GET['email'] ?? '');

if ($action === 'remove' && $email !== '') {
    if (function_exists('remove_saved_account')) {
        remove_saved_account($email);
    }
    // Dacă nu mai are niciun cont, redirecționează la login
    if (!is_logged_in()) {
        header('Location: login.php?lang=' . current_lang());
    } else {
        header('Location: index.php?lang=' . current_lang());
    }
    exit;
}

if ($action === 'switch' && $email !== '') {
    if (activate_account($email)) {
        header('Location: index.php?lang=' . current_lang());
        exit;
    }
}

// fallback
header('Location: login.php?lang=' . current_lang());
exit;
