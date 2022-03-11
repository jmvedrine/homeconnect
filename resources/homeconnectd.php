#!/usr/
<?php
require_once dirname(__FILE__) . '/../core/class/homeconnect.class.php';

    homeconnect::verifyToken(60);

    try {
        $ch = curl_init(homeconnect::baseUrl() . homeconnect::API_EVENTS_URL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, 'homeconnect::getEvents');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        $requestHeaders = [
            "Accept: text/event-stream",
            "Accept-Encoding: gzip, deflate",
            "Cache-Control: no-cache",
            "Connection: keep-alive",
            "Host: api.home-connect.com",
            "Accept-Language: " . config::byKey('language', 'core', 'fr_FR'),
            "Authorization: Bearer " . config::byKey('access_token','homeconnect'),
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);
        $result = curl_exec($ch);
        $req_data = curl_getinfo($ch);
        curl_close($ch);
    } catch (Exception $e) {
        homeconnect::deamon_stop();
    }
 
?>