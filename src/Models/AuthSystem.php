<?php

namespace App\Models;

use Exception;
use App\Models\DB;
use App\Models\Crypto;
use App\Controllers\SiteController;
use App\Controllers\SettingsController;
use DateTime;

class AuthSystem {
    private $recaptchaSecret;

    public function __construct() {
        $this->recaptchaSecret = '6LettK8rAAAAAMH_zNm_ofPBUkS7GLUlfqnp7gKm';
    }

    public function checkRecaptcha($recaptchaResponse) {
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $recaptchaUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'secret' => $this->recaptchaSecret,
            'response' => $recaptchaResponse
        ]));
        $response = curl_exec($ch);
        curl_close($ch);
        
        $responseData = json_decode($response, true);
        
        if ($responseData['success']) {
            return true;
        } else {
            return false;
        }
    }

    public function createToken($recaptchaResponse) {
        session_start();
        $captchaCheck = $this->checkRecaptcha($recaptchaResponse);
        $_SESSION['recaptchaResponse'] = $recaptchaResponse;
        
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '-1';
    
        if ($captchaCheck == true) {
            $timestamp = date("Y-m-d H:i:s");
            $token = Crypto::encrypt($recaptchaResponse . "@" . $userId . "@" . $timestamp);
            self::setCookieToken($token);
        } else {
            header('Location: /');
            exit;
        }
    }

    public function setCookieToken($token){
        setcookie('captchaToken', $token, time() + (90 * 30), "/"); 
        header('Location: /');
        exit;
    }

    public function checkToken($token){
        session_start();
        $token = DB::filter(@$_COOKIE['captchaToken']);
        if($token){
            $decryptedToken = Crypto::decrypt($token);
            if($decryptedToken){
                $decryptedTokenParts = explode("@", $decryptedToken);
                if(@$_SESSION['recaptchaResponse']){
                    if($decryptedTokenParts[0] === @$_SESSION['recaptchaResponse']){
                        $tokenDate = $decryptedTokenParts[2];
                        $date = new DateTime();
                        $givenDateTime = new DateTime($tokenDate);
                        $interval = $date->diff($givenDateTime);
                        $minutesPassed = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
                        $settingsController = new SettingsController(new \App\Models\Setting());
                        $setMinute = $settingsController->getSetting("captchaExpiration");
                        if($minutesPassed >= $setMinute){
                            unset($_SESSION['recaptchaResponse']);
                            setcookie('captchaToken', "", time() - 3600, "/"); 
                            self::includeVerifyCaptchaPage();
                        }

                        return true;
                    } else {
                        return false;
                    }
                } else {
                    self::includeVerifyCaptchaPage();
                    // die(print_r($_SESSION));
                }
            } else {
                unset($_SESSION['recaptchaResponse']);
                self::includeVerifyCaptchaPage();
            }
        } else {
            self::includeVerifyCaptchaPage();
        }
        
    }

    public static function includeVerifyCaptchaPage(){
        $siteTitle = SiteController::getSiteInfo()['siteTitle'];
        include("./src/Views/Pages/verifyCaptcha.php");
        exit;
    }
}
?>
