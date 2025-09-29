<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

// CSRF token
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
