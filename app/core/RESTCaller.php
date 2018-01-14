<?php

namespace core;

class RESTCaller {

    public function get($url, $headers = []) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if($result === false) {
            throw new RuntimeException("RESTCaller::Get, Error: ".curl_error($ch));
        }

        curl_close($ch);

        $json = new JSON();
        return $json->decode($result, true);
    }

    public function post($url, $postData = [], $headers = []) {
        $ch = curl_init();
        $post = http_build_query($postData, '', '&');

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        if($result === false) {
            throw new RuntimeException("RESTCaller::Post, Error: ".curl_error($ch));
        }

        curl_close($ch);

        $json = new JSON();
        return $json->decode($result, true);
    }
}