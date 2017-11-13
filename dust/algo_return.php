<?php
    header("Content-Type:application/json");
    
    include('mysql_con.php');
    
    $x = $_GET['x'];
    $y = $_GET['y'];
    
    $sql = "SELECT * FROM tb_dust2;";
         
	#$currentTime = date("Y-m-d H:i:s");
    $currentTime = "2017-10-10 01:30:00";
    $pm10_zw = 0;
    $pm10_w = 0;
	$pm25_zw = 0;
    $pm25_w = 0;

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
        
        foreach($xmlData["documents"][0] as $key=>$value) {
        }
        $tm = array($xmlData["documents"][0]["x"],$xmlData["documents"][0]["y"]);

        return $tm;
    }
    
    
    $gm = gpsTOtm($x, $y);
    $mytmX = $gm[0];
    $mytmY = $gm[1];

    
    $result = mysqli_query($mysqli, $sql);
        
    while($row = $result->fetch_array()) {
        $diff =((int)strtotime($currentTime) - (int)strtotime($row['dataTime']))/3600;
        //시간차이가 1시간을 넘어가면 더이상 유효한 데이터가 존재하지 않는다
        if($diff < 1 && $diff >0) {
            $distance = sqrt(($mytmX-$row['tmX'])*($mytmX-$row['tmX']) + ($mytmY-$row['tmY'])*($mytmY-$row['tmY']));
            //tmX와 tmY로 3km내에 존재하는지 조사
            if($distance < 3000) {
                $distance_squ = $distance*$distance;
                $pm10_zw_exp = ($row['pm10Value']/$distance_squ);
                
                #print "pm10Value : ".$pm10_zw_exp."<BR>";
                $pm10_zw += $pm10_zw_exp;
                #print "pm10_zw : ".$pm10_zw."<BR>";
                $pm10_w = $pm10_w + 1/$distance_squ;
                $pm25_zw = $pm25_zw + $row['pm25Value']/$distance_squ;
                $pm25_w = $pm25_w + 1/$distance_squ;
            } else {
                continue;
            }
        } else {
            continue;
        }
    }
    
    $resultPm10 = 0;
    $resultPm25 = 0;
    
    if($pm10_zw == 0 && $pm10_w == 0){
        $resultPm10 =  0;
        
    }else{
        $resultPm10 =  round($pm10_zw/$pm10_w,3);
    }
    
    if($pm25_zw == 0 && $pm25_w == 0){
        $resultPm25 =  0;
    }else{
        $resultPm25 = round($pm25_zw/$pm25_w,3);
    }
  #  round($testnun, 3);   


    echo json_encode(array( x => $resultPm10,y => $resultPm25));
    
    $mysqli->close();
    curl_close($ch);
?>
