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
    echo $gps;
    curl_close($ch);
    return $gps;
}


//curl 세션 초기화
    $ch = curl_init();
#    $url = 'http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getCtprvnRltmMesureDnsty'; /*URL*/
#    $queryParams = '?' . urlencode('ServiceKey') . '='.'MFKunqZBlShaYp2Sb7FEKscwne8WWNH4a4YQmybLP1VX9ACkc8swi2vMp1Z%2Fe8MgPjyBE7YmbQ4Ni8Ev46WJuQ%3D%3D'; /*Service Key*/
   $url = 'http://openapi.airkorea.or.kr/openapi/services/rest/ArpltnInforInqireSvc/getCtprvnRltmMesureDnsty'; /*URL*/
   $queryParams = '?' . urlencode('ServiceKey') . '='.'MFKunqZBlShaYp2Sb7FEKscwne8WWNH4a4YQmybLP1VX9ACkc8swi2vMp1Z%2Fe8MgPjyBE7YmbQ4Ni8Ev46WJuQ%3D%3D'; /*Service Key*/

    $queryParams .= '&' . urlencode('sidoName') . '=' . urlencode('서울'); /*파라미>터설명*/
    $queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1');
    $queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode('40');
    $queryParams .= '&' . urlencode('ver') . '=' . urlencode('1.3');
    $queryParams .= '&' . urlencode('_returnType') . '=' . urlencode('json');


    //접속 url 설정
    curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
    //Request에 대한 결과값을 받아오는지 체크 - exec 함수를 위한 반환값을 원격지 내용을 >받는다
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    //받는 방식
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    //세션을 실행함
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    //위 CURLOPT_RETURNTRANSFER인자에 의해서 반환값 받을 수 있는듯?
    $response = curl_exec($ch);

    $xmlData = json_decode($response, true);
    print_r($xmlData);

for($i=0;$i<39;$i++){
    foreach($xmlData["list"][$i] as $key=>$value) {
            echo "key : ".$key.",".$value."<BR>";
            if($key == "dataTime" || $key == "stationName" || $key == "pm10Value" || $key == "pm10Grade1h" || $key =="pm25Value" || $key == "pm25Grade1h"){
                   $k .= $key.", ";
                   $v .= "'$value'".", ";
           }
    }
    
#    $tmx = $xmlData["list"][$i]["tmX"];
#    $tmy = $decoded["list"][$i]["tmY"];
#    $gm = tmTOgps($tmx, $tmy);
//    echo $gm;
    $k .= "x,y".", ";
    $v .= "12".",12,";
//    $v .= "$gm[0]".","."$gm[1]".", ";


    $k = substr($k,0,-2).",fromWhere";
    $v = substr($v,0,-2).",1";
    print $k."<BR>";
    print $v."<BR>" ;  
    
    $sql= "insert into `tb_dust`($k) values ($v); ";     
    $sql_ .= $sql;
    $k = "";
    $v = "";
}
    echo "$sql_, $i";

    if(!$mysqli->multi_query($sql_)) {
        die('Error : '.mysqli_error($mysqli));
    }
    echo "record added ";
	

        $mysqli->close();
        curl_close($ch);
?>

