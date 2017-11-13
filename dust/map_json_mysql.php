<?php
// set location
$address = "Brooklyn+NY+USA";

//set map api url
$url = "http://maps.google.com/maps/api/geocode/json?address=$address";

//call api
$json = file_get_contents($url);
$json = json_decode($json);
$lat = $json->results[0]->geometry->location->lat;
$lng = $json->results[0]->geometry->location->lng;
echo "Latitude: " . $lat . ", Longitude: " . $lng;

// output
// Latitude: 40.6781784, Longitude: -73.9441579
  
 //connect to mysql db

//$con = new PDO('mysql:host=localhost;dbname=sample', 'root', '');
//$stmt = $con->prepare('SELECT * FROM sample_api');
//$stmt->execute();

/$con = mysqli_connect("mysql","root","","sample") or die('Could not connect: ' . mysql_error());
// $con = mysqli_connect("52.78.96.195","root","","sample");
// Check connection
 //connect to the employee database
  mysql_select_db("sample", $con);

    //read the json file contents
    $jsondata = file_get_contents($url);
    
    //convert json object to php associative array
    $data = json_decode($jsondata, true);
    
    //get the employee details
    $lat = $data['results[0]']['geometry']['location']['lat'];

    $lng = $data['results[0]']['geometry']['location']['lng'];
    //insert into mysql table
    $sql = "INSERT INTO 'sample_api'(latitude,longitude)
    VALUES( '$lat', '$lng')";
    if(!mysql_query($sql,$con))
    {
        die('Error : ' . mysql_error());
    }

?>
