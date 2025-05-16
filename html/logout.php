<?php
session_start();
require_once __DIR__ . '/../includes/Auth.php';

Auth::logout();
header('Location: index.php');
exit;
