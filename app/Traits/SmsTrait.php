<?php

namespace App\Traits;

trait SmsTrait
{
    public function sendSms($to, $text)
    {
        $url = 'https://console.melipayamak.com/api/send/simple/9f20a2ff432f4300897763e8dd19253e';
        $data = array('from' => '50004001401210', 'to' => $to, 'text' => $text);
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        // Next line makes the request absolute insecure
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Use it when you have trouble installing local issuer certificate
        // See https://stackoverflow.com/a/31830614/1743997

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );
        $result = curl_exec($ch);
        curl_close($ch);
        // to debug
        // if(curl_errno($ch)){
        //     echo 'Curl error: ' . curl_error($ch);
        // }
    }
}
