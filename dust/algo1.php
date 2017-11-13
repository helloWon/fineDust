<?php
include('mysql_con.php');
#        $mysqli = new mysqli('localhost', 'ialy', 'root', 'sampledb');
#        if($mysqli->connect_error) {
#                die('Connect Error:('.$mysqli->connect_errno.')'.$mysqli->connect_error);
#        }
        #print "접속을 성공했어 개자식아아아라ㅏㄴㅁㄹ만";
        $sql = "SELECT * FROM tb_dust2 union select * from tb_dust6";
        $currentTime = "2017-08-23 12:00:00";
        $mytmX = 199997.934625;
        $mytmY = 444638.69207;
        $pm10_zw = 0;
        $pm10_w = 0;
        $pm25_zw = 0;
        $pm25_w = 0;

        print "<BR>".$currentTime."<BR>";

        $result = mysqli_query($mysqli, $sql);
        
        while($row = $result->fetch_array()) {
                echo $row['dataTime'].'<BR>';
                $diff = (int)(strtotime($currentTime) - strtotime($row['dataTime']))/3600;
                //시간차이가 1시간을 넘어가면 더이상 유효한 데이터가 존재하지 않는다
                if($diff < 1) {
                        $distance = sqrt(($mytmX-$row['tmX'])*($mytmX-$row['tmX']) + ($mytmY-$row['tmY'])*($mytmY-$row['tmY']));
                        //tmX와 tmY로 3km내에 존재하는지 조사
                        if($distance < 3000) {
                                $distance_squ = $distance*$distance;
                                $pm10_zw = $pm10_zw + $row['pm10Value']/$distance_squ;
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
        
        print "pm10 : ".$pm10_zw/$pm10_w;
        print "<BR>";
        print "pm25 : ".$pm25_zw/$pm25_w;
        print "<BR>";
        #echo "고마워 짐 전부 다.....";
        $mysqli->close();
?>
