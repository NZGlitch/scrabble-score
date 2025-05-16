<?php
require_once 'authcheck.php';

Auth::logout();
header('Location: /index.php');
exit;
