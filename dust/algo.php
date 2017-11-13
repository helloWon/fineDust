<?php
include('mysql_con.php');
        $sql = "SELECT * FROM tb_dust2;";
         
	#$currentTime = date("Y-m-d H:i:s");
	$currentTime = "2017-10-01 01:30:00";
        $mytmX = 199997.934625;//사당현위치tm좌표
        $mytmY = 444638.69207;
        $pm10_zw = 0;
        $pm10_w = 0;
	$pm25_zw = 0;
        $pm25_w = 0;


        $result = mysqli_query($mysqli, $sql);
        
        while($row = $result->fetch_array()) {
		$diff =((int)strtotime($currentTime) - (int)strtotime($row['dataTime']))/3600;
                //시간차이가 1시간을 넘어가면 더이상 유효한 데이터가 존재하지 않는다
    		if($diff < 1 && $diff >0) {
                        $distance = sqrt(($mytmX-$row['tmX'])*($mytmX-$row['tmX']) + ($mytmY-$row['tmY'])*($mytmY-$row['tmY']));
                        //tmX와 tmY로 3km내에 존재하는지 조사
			print $distance."<BR>";
                        if($distance < 3000) {
				$distance_squ = $distance*$distance;
				$pm10_zw_exp = ($row['pm10Value']/$distance_squ);
				#print "pm10Value : ".$pm10_zw_exp."<BR>";
                                $pm10_zw += $pm10_zw_exp;
				#print "pm10_zw : ".$pm10_zw."<BR>";
                                $pm10_w = $pm10_w + 1/$distance_squ;
                                $pm25_zw = $pm25_zw + $row['pm25Value']/$distance_squ;
                                $pm25_w = $pm25_w + 1/$distance_squ;
		        }
                        else {
                                continue;
                        }
                }
                else {
                        continue;
                }

        }
        print "<BR>"; 
        $resultPm10 =  $pm10_zw/$pm10_w;
        print "알고리즘pm10 : ".$resultPm10;
        print "<BR>";
        $resultPm25 = $pm25_zw/$pm25_w;
        print "알고리즘pm25 : ".$resultPm25;
        print "<BR>";
        #echo "고마워 짐 전부 다.....";
 
        $mysqli->close();
        curl_close($ch);
?>
