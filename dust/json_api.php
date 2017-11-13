<?php
include ('mysql_con.php');

function tmTOgps($x, $y){
    //curl 세션 초기화
    $url = 'https://dapi.kakao.com/v2/local/geo/transcoord.json?&input_coord=TM&output_coord=WGS84';
    $ch = curl_init($url);
    $headers = array(
    'Authorization: KakaoAK e084bf2d9ae6466ffe3464f1f94c4739'
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $queryParams = '&' . urlencode('x') . '=' . urlencode($x);
    $queryParams .= '&' . urlencode('y') . '=' . urlencode($y);

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
#    print_r($xmlData);
    foreach($xmlData["documents"][0] as $key=>$value) {
#          echo "key : ".$key.",".$value."<BR>";
    }
    $tm = array($xmlData["documents"][0]["x"],$xmlData["documents"][0]["y"]);
#    echo $gps;
    curl_close($ch);
    return $tm;
}

function url_conn($i){
    $ch = curl_init();
    $url = 'http://openapi.airkorea.or.kr/openapi/services/rest/MsrstnInfoInqireSvc/getTMStdrCrdnt'; /*URL*/
    $queryParams = '?' . urlencode('ServiceKey') . '='.'MFKunqZBlShaYp2Sb7FEKscwne8WWNH4a4YQmybLP1VX9ACkc8swi2vMp1Z%2Fe8MgPjyBE7YmbQ4Ni8Ev46WJuQ%3D%3D'; /*Service Key*/
    $queryParams .= '&' . urlencode('umdName') . '=' . urlencode($i); /*파라미터설명*/
    $queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1');
    $queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode('39');
    $queryParams .= '&' . urlencode('ver') . '=' . urlencode('1.3');
    $queryParams .= '&' . urlencode('_returnType') . '=' . urlencode('json');
    //접속 url 설정
    curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
    //Request에 대한 결과값을 받아오는지 체크 - exec 함수를 위한 반환값을 원격지 내용을 받는다
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //받는 방식
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //세션을 실행함
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    //위 CURLOPT_RETURNTRANSFER인자에 의해서 반환값 받을 수 있는듯?
    $response = curl_exec($ch);

    $xmlData = json_decode($response, true);
    print_r($xmlData);

    foreach($xmlData["list"][0] as $key=>$value) {
            echo "key : ".$key.",".$value."<BR>";
            if($key == "tmX"){
                $X = $value;
            }
            elseif($key == "tmY"){
                $Y = $value;
            }
            elseif($key == "sggName"){
                $sggName = $value;
            }
    }
    $gm = tmTOgps($X, $Y);
    echo "$gm[0]"."$gm[1]";
    $sql = "update tb_dust set tmX = $X, tmY = $Y, x =$gm[1], y = $gm[0] where stationName like '$sggName';";
    return $sql;
}
#$arrDong = array('반포동','사당동');
    $arrDong = array('서소문동','동자동','효제동','한남동','구의동','성수동','면목동','제기동','청량리동','길음동','정릉동','쌍문동','불광동','연희동','신수>동','노고산동','화곡동','마곡동','구로동','당산동','영등포동','사당동','신림동','삼성동','반포동','논현동','서초동','방이동','천호동','독산동','우이동','신정
동','공릉동');
    for($i=0;$i<33;$i++){
    $sql = url_conn($arrDong[$i]);
    $sql_ .=$sql;
    }
    echo "$sql_";
    if(!$mysqli->multi_query($sql_)) {
       die('Error : '.mysqli_error($mysqli));
    }
    echo "record added ";
    $mysqli->close();
    curl_close($ch);
?>


