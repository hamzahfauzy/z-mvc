<?php
use App\Models\SmsLog;

class ZSms
{

    private $userkey;
    private $passkey;
    private $url;

    function __construct()
    {
        $env = require '../environment.php';

        $this->userkey = $env['zenziva_user'];
        $this->passkey = $env['zenziva_pass'];
        $this->url     = $env['zenziva_url'];
    }

    public function send($phone_number, $message)
    {
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $this->url);
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, 'userkey='.$this->userkey.'&passkey='.$this->passkey.'&nohp='.$phone_number.'&pesan='.urlencode($message));
        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        $results = curl_exec($curlHandle);
        curl_close($curlHandle);
        $XMLdata = new SimpleXMLElement($results);
        $status = $XMLdata->message[0]->text;
        $smslog = new SmsLog;
        $smslog->save([
            'user_id' => session()->get('id'),
            'no_hp' => $phone_number,
            'pesan' => $message,
            'status'=> $status,
            'date'  => date('Y-m-d H:i:s')
        ]);
        return $status;
        // ['status'=>$status,'url'=>$this->url,'userkey'=>$this->userkey,'passkey'=>$this->passkey];
    }

}