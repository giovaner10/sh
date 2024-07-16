<?php

function get_os($token,$status){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://gestor.showtecnologia.com/rest/index.php/os/list?status={$status}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Authorization: ".$token."",
            "Cache-Control: no-cache",
            "Postman-Token: 2c189355-0256-2d21-0170-6498de3e7adb"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
  } else {
      return $response;
  }


}

function login_instalador($email, $password) {

    $key = 'dgdtyfvvmth674gjrddjghdktudn64fdhd';

    $curl = curl_init();

    $postdata = http_build_query(
        array(
            'email' => $email,
            'password' => $password
        )
    );
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://gestor.showtecnologia.com/rest/index.php/auth/login_instalador",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_POSTFIELDS => $postdata,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Content-Type: application/x-www-form-urlencoded",
            "Postman-Token: 6e45456c-d77d-caf4-aa34-45a5e522f003",
            "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        return "cURL Error #:" . $err;
    } else {
        return $response;
    }
}


function get_equipamento($serial){

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://gestor.showtecnologia.com/rest/index.php/api/last_track?ID={$serial}",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "SHOW-API-KEY: go00840cooswgwk8o4c0kwgws000okkk848kscko",
            "Cache-Control: no-cache",
            "Postman-Token: 2c189355-0256-2d21-0170-6498de3e7adb"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
  } else {
      return $response;
  }


}


