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
          echo "key : ".$key.",".$value."<BR>";
    }
    $gps = array($xmlData["documents"][0]["x"],$xmlData["documents"][0]["y"]);
    echo $gps;
    curl_close($ch);
    return $gps;
}


$json = file_get_contents("php://input");
$decoded = json_decode($json,true);
print_r($decoded);
foreach($decoded as $key=>$value) {
            echo "key : ".$key.",".$value."<BR>";
   if($key == "dataTime" ||$key == "tmX" || $key == "tmY" || $key == "pm5Value" || $key == "pm10Value" || $key =="pm25Value" || $key =="fromWhere"){         
#  if($key == "latitude" || $key == "longitude" || $key == "pm10Value" || $key =="pm25Value" || $key == "stationName" || $key =="humidity" || $key == "temperature" || $key =="fromWhere"){
                   $k .= $key.", ";
                   $v .= "'$value'".", ";
    }       
}    
    $tmx = $decoded["tmX"];
    $tmy = $decoded["tmY"];
    $gm = tmTOgps($tmx, $tmy);
    echo $gm;
    $k .= "x,y".", ";
    $v .= $gm[1].",".$gm[0].", ";
    

    $k = substr($k,0,-2);
    $v = substr($v,0,-2);
    print $k."<BR>";
    print $v."<BR>";

    $sql = "INSERT INTO tb_dust2($k) VALUES ($v)";
    if(!mysqli_query($mysqli, $sql)) {
        die('Error : '.mysqli_error($mysqli));
        }
    echo "1 record added ";

        $mysqli->close();
        curl_close($ch);

?>

