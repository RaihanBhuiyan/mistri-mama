<?php

namespace App\Library\Bkash;

class Bkash
{
    public function tokenFile()
    {
        if (env('MM_ENV') == 'local') {
            return $file = $_SERVER['DOCUMENT_ROOT'] . "/app/library/bkash/token.json";
        } else {
            return  $file = $_SERVER['DOCUMENT_ROOT'] . "/backend/app/library/bkash/token.json";
        }
    }

    public function getToken()
    {
        $token = $this->generateToken();
        $file = $this->tokenFile();
        $tokenFile = file_get_contents($file);
        $array = json_decode($tokenFile, true);

        $array['token'] = $token['id_token'];

        $newJsonString = json_encode($array);
        file_put_contents($file, $newJsonString);
        return $token['id_token'];
    }

    public function generateToken()
    {
        $post_token = [
            'app_key' => env('BKASH_APP_KEY', ''),
            'app_secret' => env('BKASH_APP_SECRET', '')
        ];
        $password = env('BKASH_PASSWORD', '');
        $username = env('BKASH_USERNAME', '');
        $tokenUrl = env('BKASH_TOKEN_URL', '');
        $url = curl_init($tokenUrl);
        $proxy = env('BKASH_PROXY', '');
        $posttoken = json_encode($post_token);
        $header = [
            'Content-Type:application/json',
            'password:' . $password,
            'username:' . $username
        ];

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $posttoken);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($url, CURLOPT_PROXY, $proxy);
        $resultdata = curl_exec($url);
        curl_close($url);
        return json_decode($resultdata, true);
    }


    public function createPayment($amount, $invoice)
    {
        $file = $this->tokenFile();
        $strJsonFileContents = file_get_contents($file);
        $array               = json_decode($strJsonFileContents, true);
        $intent              = "sale";
        $proxy               = env('BKASH_PROXY');
        $createpaybody       = array('amount' => $amount, 'currency' => 'BDT', 'merchantInvoiceNumber' => $invoice, 'intent' => $intent);
        $url                 = curl_init(env('BKASH_CREATE_URL'));

        $createpaybodyx = json_encode($createpaybody);

        $header = [
            'Content-Type:application/json',
            'authorization:' . $array["token"],
            'x-app-key:' . env('BKASH_APP_KEY'),
        ];

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $createpaybodyx);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($url, CURLOPT_PROXY, $proxy);

        $resultdata = curl_exec($url);
        curl_close($url);
        echo $resultdata;
    }


    public function executePayment($paymentID)
    {
        $file = $this->tokenFile();
        $strJsonFileContents = file_get_contents($file);
        $array = json_decode($strJsonFileContents, true);
        $paymentID = $_GET['paymentID'];
        $proxy = env('BKASH_PROXY');

        $url = curl_init(env('BKASH_EXECUTE_URL') . $paymentID);

        $header = [
            'Content-Type:application/json',
            'authorization:' . $array["token"],
            'x-app-key:' . env('BKASH_APP_KEY'),
        ];

        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        //curl_setopt($url, CURLOPT_PROXY, $proxy);

        $resultdatax = curl_exec($url);
        curl_close($url);
        echo $resultdatax;
    }
}
