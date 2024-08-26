<?php
// This class has all the necessary code for making API calls thru curl library

class CurlHelper {
    public function callAPI($method, $url, $data, $token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ".$token
          ),
         ));

        $response = curl_exec($curl);
        $data = json_decode($response, true);
        var_dump(sizeof($data['Result']));
        return $data;
    }
    public  function get_http_token(){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);  
        curl_setopt($curl, CURLOPT_URL, 'https://amr-production-api.azurewebsites.net/token');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
         'username' => '306098',
         'password' => 'r4TVgSVSDVHO',
         'grant_type' => 'password'
        )));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return json_decode($result);
    }
}