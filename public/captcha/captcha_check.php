<?php
session_start();

$captchaCode = $_POST['captcha'] ?? '';
$storedCaptcha = $_SESSION['captcha'] ?? '';

if ($captchaCode === $storedCaptcha) {
    echo 'success';
} else {
    echo 'failure';
}
?>
