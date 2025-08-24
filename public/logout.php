<?php
require_once __DIR__ . '/../app/auth.php';
customer_logout();
header("Location: index.php");
