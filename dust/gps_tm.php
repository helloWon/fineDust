<?php

function gpsTOtm($x, $y){
    //curl 세션 초기화
    $url = 'https://dapi.kakao.com/v2/local/geo/transcoord.json?&input_coord=WGS84&output_coord=TM'; 
    $ch = curl_init($url);
    $headers = array( 
    'Authorization: KakaoAK e084bf2d9ae6466ffe3464f1f94c4739' 
    ); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 

    $queryParams = '&' . urlencode('y') . '=' . urlencode($x); 
    $queryParams .= '&' . urlencode('x') . '=' . urlencode($y); 

     //접속 url 설정
    curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
    //Request에 대한 결과값을 받아오는지 체크 - exec 함수를 위한 반환값을 원격지 내용을 >받는다
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //받는 방식
   # curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //세션을 실행함
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    //위 CURLOPT_RETURNTRANSFER인자에 의해서 반환값 받을 수 있는듯?
    $response = curl_exec($ch);

    $xmlData = json_decode($response, true);
    print_r($xmlData);
    foreach($xmlData["documents"][0] as $key=>$value) {
          echo "key : ".$key.",".$value."<BR>";
    }
    $tm = array($xmlData["documents"][0]["x"],$xmlData["documents"][0]["y"]);
    echo $tm;
    curl_close($ch);
    return $tm;
}

var_dump(gpsTOtm(37.48562628564602,126.97231485326833));


?>

