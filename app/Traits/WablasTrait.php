<?php

namespace App\Traits;

trait WablasTrait
{
    public static function sendMessage($data = [])
    {
      if(substr($data['phone_number'], 0) == 0){
        $data['phone_number'] = str_replace('08', '628', $data['phone_number']);
      }

        $curl = curl_init();
        $token = env('SECURITY_TOKEN_WABLAS');
        $payload = [
          'phone' => $data['phone_number'],
          'message' => $data['message']
        ];
        $headers = [
          'Authorization: '.$token,
      ];
      
        curl_setopt($curl, CURLOPT_URL,  env('DOMAIN_SERVER_WABLAS') ."/api/send-message");
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
