<?php
include ('mysql_con.php');
$json = file_get_contents("php://input");
$decoded = json_decode($json,true);
print_r($decoded);
foreach($decoded as $key=>$value) {
            echo "key : ".$key.",".$value."<BR>";
   if($key == "pm5Value" ||$key == "pm10Value" || $key =="pm25Value" || $key =="fromWhere"){
# if($key == "pm5Value" || $key == "pm10Value" || $key =="pm25Value" || $key =="fromWhere"){

#  if($key == "latitude" || $key == "longitude" || $key == "pm10Value" || $key =="pm25Value" || $key == "stationName" || $key =="humidity" || $key == "temperature" || $key =="fromWhere"){
                   $k .= $key.", ";
                   $v .= "'$value'".", ";
           }
}
    $k = substr($k,0,-2);
    $v = substr($v,0,-2);
    print $k."<BR>";
    print $v."<BR>";

    $sql = "INSERT INTO `tb_dust3`($k,longitude, latitude) VALUES ($v,127.050767,37.595426)";
   # $sql = "INSERT INTO 'tb_dust3'($k."longitude,latitude") VALUES ($v,504617.45446,207230.47013)";
    if(!mysqli_query($mysqli, $sql)) {
        die('Error : '.mysqli_error($mysqli));
        }
    echo "1 record added ";

        $mysqli->close();
        curl_close($ch);

?>


