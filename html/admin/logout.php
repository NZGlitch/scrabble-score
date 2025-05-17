<?php
require_once '../../includes/admin/authcheck.php';
SessionManager::destroySession();
header('Location: /index.php?error=logged_out');
exit;
