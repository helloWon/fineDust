<?php
include ('mysql_con.php');

// 관측소
$op_sql = "SELECT * FROM tb_dust;";
// 측정기
$mi_sql = "SELECT * FROM tb_dust2;";

$op_result = mysqli_query($mysqli, $op_sql);
$mi_result = mysqli_query($mysqli, $mi_sql);

$mi_x = "";                         // 측정기 gps x좌표
$mi_y = "";                         // 측정기 gps y좌표
$op_x = "";                         // 관측소 gps x좌표
$op_y = "";                         // 관측소 gps x좌표
$mi_pm10value = "";                 // 측정기 미세먼지 값
$mi_pm25value = "";                 // 측정기 초미세먼지 값
$op_pm10value = "";                 // 관측소 미세먼지 값
$op_pm25value = "";                 // 관측소 초미세먼지 값
$op_stationName = "";
$count1 = 0;
$count2 = 0;

while($op_row = $op_result->fetch_array()) {
    if($count1 > 0){
        $op_x = $op_x."|".$op_row['x'];
        $op_y = $op_y."|".$op_row['y'];
        $op_pm10value = $op_pm10value."|".$op_row['pm10Value'];
        $op_pm25value = $op_pm25value."|".$op_row['pm25Value'];
        $op_stationName = $op_stationName."|".$op_row['stationName'];
    }else{
        $op_x = $op_row['x'];
        $op_y = $op_row['y'];
        $op_pm10value = $op_row['pm10Value'];
        $op_pm25value = $op_row['pm25Value'];
        $op_stationName = $op_row['stationName'];
    }
    $count1++;
}

while($mi_row = $mi_result->fetch_array()) {
    if($count2 > 0){
        $mi_x = $mi_x."|".$mi_row['x'];
        $mi_y = $mi_y."|".$mi_row['y'];
        $mi_pm10value = $mi_pm10value."|".$mi_row['pm10Value'];
        $mi_pm25value = $mi_pm25value."|".$mi_row['pm25Value'];
    }else{
        $mi_x = $mi_row['x'];
        $mi_y = $mi_row['y'];
        $mi_pm10value = $mi_row['pm10Value'];
        $mi_pm25value = $mi_row['pm25Value'];
    }
    $count2++;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, user-scalable=no,initial-scale=0.3, maximum-scale=3.0"/>
<title>K-ICT NET CHALLENGE 숭실대학교 콜록홈즈</title>
<link href='//spoqa.github.io/spoqa-han-sans/css/SpoqaHanSans-kr.css' rel='stylesheet' type='text/css'>
<link href="./style.css" rel="stylesheet">
</head>

<body>
	<form name="searchFrm">
	<div>
		 <div id="header">
			<img id="logo" src="kol.png"> <input type="text" id="search-input" name="juso" placeholder="주소를 검색해주세요.">
			<button type="button" id="search-button" onclick="jusoSearch();">검색</button>
			<img id="right" src="logo.png">
		</div>
		<div id="map-wrap">
			<div id="map-side">
				<div id="position"></div>
				<div id="status"><div style="width: 100px;height: 60px;display: block;margin: 0 auto;background-color: #40a6d5;border-radius: 50px;margin-bottom: 40px;text-align: center;color: #fff;font-weight: bold;padding-top: 40px;"></div></div>
				<!-- 매우나쁨 id="progile-2", 나쁨 id="progile-1", 보통 id="progile0", 좋음 id="progile1", 매우좋음 id="progile2" -->
				<div id="table">
					<table class="state" cellpadding="0" cellspacing="1">
						<tr>
							<th colspan="2">상태</th>
						</tr>
						<tr>
							<td width="100px" class="td_01">PM2.5</td>
							<td width="200px" class="td_02"><div id="25value">0 ㎍/㎥</div></td>
						<tr>
							<td class="td_01">PM10</td>
							<td class="td_02"><div id="10value">0 ㎍/㎥</div></td>
						</tr>
					</table>
				</div>
				<div id="weather_img"><div style="width: 100px;height: 60px;display: block;margin: 0 auto;background-color: #ffffff;border-radius: 50px;margin-bottom: 10px;text-align: center;font-weight: bold;padding-top: 40px;"><img src='./1.png' alt='눈/비' ></div></div>
				<div id="weather_text"><div style="text-align: center;"><br><font color='blue'>0</font>/<font color='orange'>0</font></div></div>
			</div>
			<div id="map"></div>
		</div>
	</div>
	</form>
	<script src="jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=e084bf2d9ae6466ffe3464f1f94c4739&libraries=services"></script>
	<script type="text/javascript">
    
    
    var mi_x = new Array();
    var mi_y = new Array();
    var mi_pm10value = new Array();
    var mi_pm25value = new Array();
    var mi_x_text = "";
    var mi_y_text = "";
    var mi_pm10value_text = "";
    var mi_pm25value_text = "";
    var main_pm10value = "";
    var main_pm25value = "";
    var op_x = new Array();
    var op_y = new Array();
    var op_pm10value = new Array();
    var op_pm25value = new Array();
    var op_stationName = new Array();
    var op_x_text = "";
    var op_y_text = "";
	var op_stationName_text = "";
    var op_pm10value_text = "";
    var op_pm25value_text = "";
    var local_x = "";
    var local_y = "";
    var main_pm10 = "";
    var main_pm25 = "";
    
    
    mi_x_text = "<?= $mi_x ?>";
    mi_y_text = "<?= $mi_y ?>";
    mi_pm10value_text = "<?= $mi_pm10value ?>";
    mi_pm25value_text = "<?= $mi_pm25value ?>";
    op_x_text = "<?= $op_x ?>";
    op_y_text = "<?= $op_y ?>";
    op_pm10value_text = "<?= $op_pm10value ?>";
    op_pm25value_text = "<?= $op_pm25value ?>";
    op_stationName_text = "<?= $op_stationName ?>";

    mi_x = mi_x_text.split("|");
    mi_y = mi_y_text.split("|");
    mi_pm10value = mi_pm10value_text.split("|");
    mi_pm25value = mi_pm25value_text.split("|");
    op_x = op_x_text.split("|");
    op_y = op_y_text.split("|");
    op_pm10value = op_pm10value_text.split("|");
    op_pm25value = op_pm25value_text.split("|");
    op_stationName = op_stationName_text.split("|");

    var marker_main;
    var mapContainer;
    var map;

    var geocoder = new daum.maps.services.Geocoder();
    
    //HTML5의 geolocation으로 사용할 수 있는지 확인합니다
    if (navigator.geolocation) {
    	// GeoLocation을 이용해서 접속 위치를 얻어옵니다
   		navigator.geolocation.getCurrentPosition(function(position) {
        	var lat = position.coords.latitude, // 위도
            	lon = position.coords.longitude;
        	local_x = lat;
        	local_y = lon;
    			
        	var locPosition = new daum.maps.LatLng(lat, lon), // 마커가 표시될 위치를 geolocation으로 얻어온 좌표로 생성합니다
            	message = "위도:"+lat+"경도:"+lon;   // infowindow에 표시됨 이 내용이..


            mapContainer = document.getElementById('map'),
             	mapOption = {
                 	center: new daum.maps.LatLng(lat, lon),
                 	level: 8 // 지도의 확대 레벨
            };

            map = new daum.maps.Map(mapContainer, mapOption); 

			$.ajax({
				url: 'algo_return.php?x='+local_x+'&y='+local_y,
				type: 'GET',
				cache: false,
				success: function(data) {
					main_pm10value = data.x;
					main_pm25value = data.y;
				},
				error:function(request,status,error){
					alert("다시 시도해주세요.\n" + "code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
				}
			});
        	// 마커와 인포윈도우를 표시합니다
        	displayMarker(locPosition, message);
      	});
    
    } else { // HTML5의 GeoLocation을 사용할 수 없을때 마커 표시 위치와 인포윈도우 내용을 설정합니다
    	var locPosition = new daum.maps.LatLng(33.450701, 126.570667),
        message = 'geolocation을 사용할수 없어요.. 끵끵';
    
    	displayMarker(locPosition, message);
    }
    
    function panTo() {
    	map.panTo(locPosition);
    }

    
    function displayMarker(locPosition, message) {
    
        var imageSrc = 'https://www.seongwon.microdust.kr/main_marker.png',
            imageSrc2 = 'https://www.seongwon.microdust.kr/sub_marker1.png',
            imageSrc3 = 'https://www.seongwon.microdust.kr/sub_marker2.png',
            imageSize = new daum.maps.Size(65, 65),
            imageSize2= new daum.maps.Size(40,42), // 마커이미지의 크기입니다
            imageSize3= new daum.maps.Size(40,42), // 마커이미지의 크기입니다
            imageOprion = locPosition;

        var markerImage = new daum.maps.MarkerImage(imageSrc, imageSize, imageOprion);
        var markerImage2 = new daum.maps.MarkerImage(imageSrc2, imageSize2, imageOprion);   //<- 여기 건드려 볼 거
        var markerImage3 = new daum.maps.MarkerImage(imageSrc3, imageSize3, imageOprion);   //<- 여기 건드려 볼 거

        marker_main = new daum.maps.Marker({
            map: map,
            position: locPosition,
            image: markerImage,
            title : '현재 위치' // 마커이미지 설정
        });

        var value10 = "";
    	var value25 = "";
    	var stationName = "";
    	var x = "";
    	var y = "";
    	var content = "";

    	content = "<div style='text-align: center;'><h>위도 : "+local_x+ "</br> 경도 : "+local_y+"</h></div>";
    	
    	addEvent(marker_main,main_pm10value,main_pm25value,content);
    
        for (var i = 0; i < mi_x.length; i ++) {
        	value10 = "";
        	value25 = "";
        	x = "";
        	y = "";
        	content = ""
            	
        	x = mi_x[i];
        	y = mi_y[i];
        	value10 = mi_pm10value[i];
        	value25 = mi_pm25value[i];
        	content = "<div style='text-align: center;'><h>위도 : "+x+ "</br> 경도 : "+y+"</h></div>";
        	
            var marker = new daum.maps.Marker({
                    map: map, // 마커를 표시할 지도
                    position: new daum.maps.LatLng(x,y), // 마커를 표시할 위치
                    title : '측정기 미세먼지 : '+value10+'㎍/㎥ , 측정기 초미세먼지 : '+value25+'㎍/㎥', // 마커의 타이틀, 마커에 마우스를 올리면 타이틀이 표시됩니다
                    image : markerImage2 // 마커 이미지
                });

            addEvent(marker,value10,value25,content);
        }
    
        for (var i = 0; i < op_x.length; i ++) {
        	value10 = "";
        	value25 = "";
        	x = "";
        	y = "";
        	x = op_x[i];
        	y = op_y[i];
        	content = "";
        	
        	value10 = op_pm10value[i];
        	value25 = op_pm25value[i];
        	stationName = op_stationName[i];
        	content = "<div style='text-align: center;'><h>"+stationName+"</h></div>";
    
            var marker = new daum.maps.Marker({
                    map: map, // 마커를 표시할 지도
                    position: new daum.maps.LatLng(x,y), // 마커를 표시할 위치
                    title : '관측소 미세먼지 : '+value10+'㎍/㎥ , 관측소 초미세먼지 : '+value25+'㎍/㎥', // 마커의 타이틀, 마커에 마우스를 올리면 타이틀이 표시됩니다
                    image : markerImage3 // 마커 이미지
                });
			
            addEvent(marker,value10,value25,content);

        }

        function addEvent(marker,value10,value25,content){
        	daum.maps.event.addListener(marker, 'click', function(mouseEvent) {
        		dustStatus(value25);
        		dustStatusInfo(value10,value25);
        		createPosition(content);
            });
        }

        function dustStatusInfo(value10,value25){
        	document.getElementById('10value').innerHTML = "";
        	document.getElementById('25value').innerHTML = "";
        	document.getElementById('10value').innerHTML = value10+" ㎍/㎥";
        	document.getElementById('25value').innerHTML = value25+" ㎍/㎥";
        }

		function dustStatus(value25){
			if(parseInt(value25) >= 0 && parseInt(value25) < 10){
				document.getElementById('status').innerHTML = "<div style='width: 100px;height: 60px;display: block;margin: 0 auto;background-color: #ffffff;border-radius: 50px;margin-bottom: 10px;text-align: center;color: #fff;font-weight: bold;padding-top: 30px;'><img src='./8.png' alt='매우좋음' width='35' height='30'></div><div style='text-align: center;'><br><font color='blue'>매우좋음</font></div><br>";
			}else if(parseInt(value25) >= 10 && parseInt(value25) < 20){
				document.getElementById('status').innerHTML = "<div style='width: 100px;height: 60px;display: block;margin: 0 auto;background-color: #ffffff;border-radius: 50px;margin-bottom: 10px;text-align: center;color: #fff;font-weight: bold;padding-top: 30px;'><img src='./9.png' alt='좋음' width='35' height='30'></div><div style='text-align: center;'><br><font color='blue'>좋음</font></div><br>";
			}else if(parseInt(value25) >= 20 && parseInt(value25) < 30){
				document.getElementById('status').innerHTML = "<div style='width: 100px;height: 60px;display: block;margin: 0 auto;background-color: #ffffff;border-radius: 50px;margin-bottom: 10px;text-align: center;color: #fff;font-weight: bold;padding-top: 30px;'><img src='./10.png' alt='보통' width='35' height='30'></div><div style='text-align: center;'><br><font color='blue'>보통</font></div><br>";
			}else if(parseInt(value25) >= 30 && parseInt(value25) < 40){
				document.getElementById('status').innerHTML = "<div style='width: 100px;height: 60px;display: block;margin: 0 auto;background-color: #ffffff;border-radius: 50px;margin-bottom: 10px;text-align: center;color: #fff;font-weight: bold;padding-top: 30px;'><img src='./6.png' alt='나쁨' width='35' height='30'></div><div style='text-align: center;'><br><font color='blue'>나쁨</font></div><br>";
			}else if(parseInt(value25) >= 40 && parseInt(value25) < 50){
				document.getElementById('status').innerHTML = "<div style='width: 100px;height: 60px;display: block;margin: 0 auto;background-color: #ffffff;border-radius: 50px;margin-bottom: 10px;text-align: center;color: #fff;font-weight: bold;padding-top: 30px;'><img src='./7.png' alt='매우나쁨' width='35' height='30'></div><div style='text-align: center;'><br><font color='blue'>매우나쁨</font></div><br>";
			}else{
				document.getElementById('status').innerHTML = "<div style='width: 100px;height: 60px;display: block;margin: 0 auto;background-color: #ffffff;border-radius: 50px;margin-bottom: 10px;text-align: center;color: #fff;font-weight: bold;padding-top: 30px;'><img src='./10.png' alt='보통' width='35' height='30'></div><div style='text-align: center;'><br><font color='blue'>보통</font></div><br>";
			}
		}

		function createPosition(content){
			document.getElementById('position').innerHTML = content;
		}

		document.getElementById('position').innerHTML = "<div style='text-align: center;'><h>위도 : "+local_x+ "</br> 경도 : "+local_y+"</h></div>";
		dustStatusInfo(main_pm10value,main_pm25value);
		dustStatus(main_pm25value);
		
		map.setCenter(locPosition);
    }

    function jusoSearch(){ 
		var juso = "";
		juso = 	document.searchFrm.juso.value;
		
    	// 주소로 좌표를 검색합니다
    	geocoder.addressSearch(juso, function(result, status) {

        	// 정상적으로 검색이 완료됐으면 
         	if (status === daum.maps.services.Status.OK) {

            	var coords = new daum.maps.LatLng(result[0].y, result[0].x);

            	map = new daum.maps.Map(mapContainer, mapOption); 

            	marker_main.setMap(null);

            	main_value = new Array();
				
            	$.ajax({
                    url: 'algo_return.php?x='+result[0].y+'&y='+result[0].x,
                    type: 'GET',
                    cache: false,
                    success: function(data) {
                    	main_pm10value = "";
                    	main_pm25value = "";
                    	main_pm10value = data.x;
    					main_pm25value = data.y;
                    },
                    error:function(request,status,error){
                        alert("다시 시도해주세요.\n" + "code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                    }
            	});
            	
            	displayMarker(coords, message);

				
            	
            	// 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
            	map.setCenter(coords);
        	} 
    	})
    }

    if (window.XMLHttpRequest){
    	xmlhttp=new XMLHttpRequest();
    }else{// code for IE6, IE5
    	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }

  	// 파라미터로 받은 지역값에 따라 기상청 지역예보를 가져옴.
  	xmlhttp.open("GET","http://www.kma.go.kr/weather/forecast/mid-term-xml.jsp?stnId=109",false);
  	xmlhttp.send();
  	xmlDoc = xmlhttp.responseXML; 

  	var now = new Date();
  	var hour = now.getHours();

  	// 기상청 xml은 3시간 단위로 예보를 보여줌. 현재시간 기준으로 어떤 예보를 가져올 것인지 정함.
  	if (hour == 0 || hour == 1 || hour == 2){
		var hourGubun = 3;
	}else if(hour == 3 || hour == 4 || hour == 5){
  		var hourGubun = 6;
	}else if(hour == 6 || hour == 7 || hour == 8){
  		var hourGubun = 9;
  	}else if(hour == 9 || hour == 10 || hour == 11){
  		var hourGubun = 12;
	}else if(hour == 12 || hour == 13 || hour == 14){
  		var hourGubun = 15;
  	}else if(hour == 15 || hour == 16 || hour == 17){
  		var hourGubun = 18;
	}else if(hour == 18 || hour == 19 || hour == 20){
  		var hourGubun = 21;
	}else if(hour == 21 || hour == 22 || hour == 23){
  		var hourGubun = 24;
	}

  	var x = xmlDoc.getElementsByTagName("data");

  	for (i=0;i<x.length;i++){
  		if (x[i].getElementsByTagName("hour")[0].childNodes[0].nodeValue == hourGubun){
  			if (x[i].getElementsByTagName("wfKor")[0].childNodes[0].nodeValue == "맑음"){
  				document.getElementById('weather_img').innerHTML = "<div style='width: 100px;height: 60px;display: block;margin: 0 auto;background-color: #ffffff;border-radius: 50px;margin-bottom: 10px;text-align: center;font-weight: bold;padding-top: 40px;'><img src='./4.png' alt='맑음' style='size: 100%'></div>";
			}
			if (x[i].getElementsByTagName("wfKor")[0].childNodes[0].nodeValue == "구름많음"){
				document.getElementById('weather_img').innerHTML = "<div style='width: 100px;height: 60px;display: block;margin: 0 auto;background-color: #ffffff;border-radius: 50px;margin-bottom: 10px;text-align: center;font-weight: bold;padding-top: 40px;'><img src='./1.png' alt='구름많음' style='size: 100%'></div>";
  		  	}
			if (x[i].getElementsByTagName("wfKor")[0].childNodes[0].nodeValue == "구름조금"){
				document.getElementById('weather_img').innerHTML = "<div style='width: 100px;height: 60px;display: block;margin: 0 auto;background-color: #ffffff;border-radius: 50px;margin-bottom: 10px;text-align: center;font-weight: bold;padding-top: 40px;'><img src='./2.png' alt='구름조금' style='size: 100%'></div>";
			}
			if (x[i].getElementsByTagName("wfKor")[0].childNodes[0].nodeValue == "흐리고 비" || x[i].getElementsByTagName("wfKor")[0].childNodes[0].nodeValue == "비"){
				document.getElementById('weather_img').innerHTML = "<div style='width: 100px;height: 60px;display: block;margin: 0 auto;background-color: #ffffff;border-radius: 50px;margin-bottom: 10px;text-align: center;font-weight: bold;padding-top: 40px;'><img src='./5.png' alt='흐리고비' style='size: 100%'></div>";
			}
			if (x[i].getElementsByTagName("wfKor")[0].childNodes[0].nodeValue == "눈/비"){
				document.getElementById('weather_img').innerHTML = "<div style='width: 100px;height: 60px;display: block;margin: 0 auto;background-color: #ffffff;border-radius: 50px;margin-bottom: 10px;text-align: center;font-weight: bold;padding-top: 40px;'><img src='./3.png' alt='눈/비' style='size: 100%'></div>";
			}
			document.getElementById('weather_text').innerHTML = "<div style='text-align: center;'><br><font color='blue'>"+x[i].getElementsByTagName('tmn')[0].childNodes[0].nodeValue+"</font>/<font color='orange'>"+x[i].getElementsByTagName('tmx')[0].childNodes[0].nodeValue+"</font>";
  		}
  	}
</script>
</body>
</html>
