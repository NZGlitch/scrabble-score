<?php
require_once __DIR__ . '/SessionManager.php';

if (SessionManager::isSessionExpired()) {
    SessionManager::destroySession();
    header('Location: /index.php?error=session_expired');
    exit;
}
