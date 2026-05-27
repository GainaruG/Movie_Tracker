<?php
require_once __DIR__ . '/php/functions.php';
$_SESSION = [];
session_destroy();
header('Location: index.php');
exit;
