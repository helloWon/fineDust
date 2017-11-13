var mapContainer = document.getElementById('map'), // 지도를 표시할 div
    mapOption = {
        center: new daum.maps.LatLng(36.450701, 127.570667), // 지도의 중심좌표 ㅎㅅㅎ 임의로 설정 아무곳이나 해놈
        level: 9 // 지도의 확대 레벨
    };

var map = new daum.maps.Map(mapContainer, mapOption); // 지도를 생성하려공

// HTML5의 geolocation으로 사용할 수 있는지 확인합니다
if (navigator.geolocation) {

    // GeoLocation을 이용해서 접속 위치를 얻어옵니다
    navigator.geolocation.getCurrentPosition(function(position) {

        var lat = position.coords.latitude, // 위도
            lon = position.coords.longitude, // 경도
            mayorlatlon = new daum.maps.LatLng(37.56676113501232,126.97866359586837);

        var locPosition = new daum.maps.LatLng(lat, lon), // 마커가 표시될 위치를 geolocation으로 얻어온 좌표로 생성합니다
            message = "위도:"+lat+"경도:"+lon;   // infowindow에 표시됨 이 내용이..


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



// 지도에 마커와 인포윈도우를 표시하는 함수입니다. 내 위치만 찍습니다. ㅇㅅㅇ
function displayMarker(locPosition, message) {

    var imageSrc = 'http://postfiles4.naver.net/MjAxNzA4MjFfMjAy/MDAxNTAzMjYwNzgxMDg1.c2nAs0w9xmXsEcW78M4G5KnpAKQ9_L_mfvzkgEIRqMcg.QgNt5bvH_Jb8_lkexcIwsvnd0lrBLBHzT4ZEZWkqiqsg.PNG.chewon0_0/image01.png?type=w2',
        imageSrc2 = 'http://www.clker.com/cliparts/I/l/L/S/W/9/map-marker-hi.png',
        imageSize = new daum.maps.Size(65, 65),
        imageSize2= new daum.maps.Size(22,32), // 마커이미지의 크기입니다
        imageOprion = locPosition;
        //imageOprion2 = {offset: new daum.maps.Point(27, 69)};
         // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.

    // 마커의 이미지정보를 가지고 있는 마커이미지를 생성합니다
    var markerImage = new daum.maps.MarkerImage(imageSrc, imageSize, imageOprion);
    var markerImage2 = new daum.maps.MarkerImage(imageSrc2, imageSize2, imageOprion);   //<- 여기 건드려 볼 거


    var positions = [
    {
        title: '중구, 실시간 수치: 10㎍/㎥',
        latlng: new daum.maps.LatLng(37.56455490632751,126.9756149637474)
    },
    {
        title: '용산구, 실시간 수치: 5㎍/㎥',
        latlng: new daum.maps.LatLng(37.55332779284032,126.97247701889009)
    },
    {
        title: '동작구, 실시간 수치: 15㎍/㎥',
        latlng: new daum.maps.LatLng(37.480955355639836,126.97149056050539)
    },
    {
        title: '종로구, 실시간 수치: 19㎍/㎥',
        latlng: new daum.maps.LatLng(37.57066252913257,126.99681691786611)
    },
    {
        title: '서초구 실시간 수치: 13㎍/㎥',
        latlng: new daum.maps.LatLng(37.50463713701881,126.99449898061644)
    },
    {
        title: '서초구2 실시간 수치: 6㎍/㎥',
        latlng: new daum.maps.LatLng(37.482946439883186,127.035678447806)
    },
    {
        title: '마포구 실시간 수치: 14㎍/㎥',
        latlng: new daum.maps.LatLng(37.5499847567416,126.9455585362941)
    },
    {
        title: '마포구2 실시간 수치: 5㎍/㎥',
        latlng: new daum.maps.LatLng(37.55496878989821,126.93768715023073)
    },
    {
        title: '구로구 실시간 수치: 11㎍/㎥',
        latlng: new daum.maps.LatLng(37.49884152632599,126.8903093029646)
    },
    {
        title: '성동구 실시간 수치: 5㎍/㎥',
        latlng: new daum.maps.LatLng(37.54776303571764,127.0500491728075)
    },
    {
        title: '동대문구 실시간 수치: 16㎍/㎥',
        latlng: new daum.maps.LatLng(37.57591362052384,127.02896672253881)
    },
    {
        title: '성북구 실시간 수치: 18㎍/㎥',
        latlng: new daum.maps.LatLng(37.60173672398595,127.02614791064846)
    },
    {
        title: '중랑구 실시간 수치: 16㎍/㎥',
        latlng: new daum.maps.LatLng(37.58490046946516,127.09403427575752)
    },
    {
        title: '노원구 실시간 수치: 11㎍/㎥',
        latlng: new daum.maps.LatLng(37.61731710469891,127.07533618664753)
    },
    {
        title: '강북구 실시간 수치: 9㎍/㎥',
        latlng: new daum.maps.LatLng(37.637862107640615,127.02881854241336)
    },
    {
        title: '광진구 실시간 수치: 27㎍/㎥',
        latlng: new daum.maps.LatLng(37.54655200810594,127.0921031032593)
    },
    {
        title: '도봉구 실시간 수치: 11㎍/㎥',
        latlng: new daum.maps.LatLng(37.65431042087085,127.02900621522392)
    },
    {
        title: '노원구 실시간 수치: 16㎍/㎥',
        latlng: new daum.maps.LatLng(37.658820618589914,127.0685239021955)
    },
    {
        title: '강동구 실시간 수치: 22㎍/㎥',
        latlng: new daum.maps.LatLng(37.5450490644283,127.13680302391775)
    },
    {
        title: '송파구 실시간 수치: 22㎍/㎥',
        latlng: new daum.maps.LatLng(37.52103247338045,127.1161665641009)
    },
    {
        title: '은평구 실시간 수치: 22㎍/㎥',
        latlng: new daum.maps.LatLng(37.61018281299187,126.93320146909106)
    },
    {
        title: '서대문구 실시간 수치: 22㎍/㎥',
        latlng: new daum.maps.LatLng(37.57654572076945,126.9374608768952)
    },
    {
        title: '영등포구 실시간 수치: 15㎍/㎥',
        latlng: new daum.maps.LatLng(37.51947620391593,126.90470403396715)
    }];


        //markerPosition = new daum.maps.LatLng(locPosition); // 마커가 표시될 위치입니다

    var marker = new daum.maps.Marker({
        map: map,
        position: locPosition,
        image: markerImage,
        title : '[현위치]위도:37.5955002경도:127.0509454      알고리즘을 거친 미세먼지 예상 수치:14.27㎍/㎥' // 마커이미지 설정
    });

    for (var i = 0; i < positions.length; i ++) {
        var marker = new daum.maps.Marker({
                map: map, // 마커를 표시할 지도
                position: positions[i].latlng, // 마커를 표시할 위치
                title : positions[i].title, // 마커의 타이틀, 마커에 마우스를 올리면 타이틀이 표시됩니다
                image : markerImage2 // 마커 이미지
            });
    }


   // 마커가 지도 위에 표시되도록 설정합니다
    //marker.setMap(map);

    var iwContent = message, // 인포윈도우에 표시할 내용
        iwRemoveable = false;

    /* 인포윈도우를 생성합니다
    var infowindow = new daum.maps.InfoWindow({
        content : iwContent,
        position : locPosition,
        removable : iwRemoveable
    });
    // 인포윈도우를 마커위에 표시합니다
    infowindow.open(map, marker); */

    // 지도 중심좌표를 접속위치로 변경합니다
    map.setCenter(locPosition);

}


// 서울 시내

//    marker.setMap(map);



