<?php
require_once 'app/config/db.php';
require_once 'app/core/router.php';

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home';
Router::route($url);
?>