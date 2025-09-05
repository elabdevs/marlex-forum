<?php
session_start();
if (@$_SERVER['HTTP_REFERER'] == "") {
    http_response_code(404);
    exit;
} else {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $length = 6;
    $captcha = '';
    for ($i = 0; $i < $length; $i++) {
        $captcha .= $chars[rand(0, strlen($chars) - 1)];
    }
    
    $_SESSION['captcha'] = $captcha;
    
    $image = imagecreatetruecolor(120, 40);
    $bgColor = imagecolorallocate($image, 110, 31, 106);
    $textColor = imagecolorallocate($image, 0, 0, 0);
    imagefilledrectangle($image, 0, 0, 120, 40, $bgColor);
    imagestring($image, 5, 10, 10, $captcha, $textColor);
    
    header('Content-type: image/png');
    imagepng($image);
    imagedestroy($image);
}