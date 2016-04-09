<?php
//mirror web sites
//capture user IP address
//geolocate lat/long of user based on IP address
//calculate distance between user location and server1
//calculate distance between user location and server2
//if distance between user<=>server1 < distance users<=>server2 then forward to server1
//else forward to server2
//The purpose of this procedure is to limit transmission latency.

//define ip adresses of mirror servers
$server1 = '209.129.16.5'; //San Diego
$server1_name = "Americas";
$server2 = '161.202.72.164';//Japan
$server2_name = "Asia";
$server3 = '130.159.62.106';//Great Britian
$server3_name = "Europe";

//collect user IP address

//$ipaddr = $_SERVER['REMOTE_ADDR'];
$ipaddr = exec('curl http://ipecho.net/plain; echo');
$ipaddr_name = "http://ipecho.net/plain";

    //SD (Americas)
    $server1_lat = '32.7773';
    $server1_long = '-117.1008';
    //Japan (Asia)
    $server2_lat = '35.6850';
    $server2_long = '139.7514';
    //UK (Europe)
    $server3_lat = '51.5000';
    $server3_long = '-0.1300';
    //User
    $user_lat = ulat;
    $user_long = $ulong;



// ****Uncomment a test user location****
//$ipaddr = '72.192.130.116';
//$ipaddr_name = "San Diego"; 

//$ipaddr = '161.202.72.164';
//$ipaddr_name ="Japan";



//$ipaddr_name = "Brasil";
//$ipaddr = '89.160.199.128';//Iceland
//$ipaddr_name = "Iceland";
//$ipaddr = '14.139.69.207';//India
//$ipaddr_name = "India";
//$ipaddr = '129.125.165.66';//Netherlands
//$ipaddr_name = 'Netherlands';
//$ipaddr = '128.192.149.157';
//$ipaddr_name = "Georgia";
//$ipaddr = '128.135.121.194';





    $coordinates = get_geolocation($ipaddr);

 
  //print_r($coordinates); 

//sample object   loc=lat,long  returned for IP address
// stdClass Object ( [ip] => 72.192.130.116 [hostname] => ip72-192-130-116.sd.sd.cox.net [city] => San Diego [region] =>
//  California [country] => US [loc] => 32.7191,-117.1607 [org] => AS22773 Cox Communications Inc. [postal] => 92101 ) 


//echo "<br />";

//retrieve lat/long of ip location
    $lat_long = $coordinates->loc;
    $ary_lat_long = explode(",", $lat_long);
    $ulat = $ary_lat_long[0];
    $ulong = $ary_lat_long[1];
 echo "USER =====> lat/long:  ".$ulat." / ".$ulong;


//define lat/long of 3 mirror servers 
    //SD (Americas)
    $server1_lat = '32.7773';
    $server1_long = '-117.1008';
    //Japan (Asia)
    $server2_lat = '35.6850';
    $server2_long = '139.7514';
    //UK (Europe)
    $server3_lat = '51.5000';
    $server3_long = '-0.1300';

//determine user distance to each mirror server
    $dist_to_server1 = distance($ulat,$ulong,$server1_lat,$server1_long);
    $dist_to_server2 = distance($ulat,$ulong,$server2_lat,$server2_long);
    $dist_to_server3 = distance($ulat,$ulong,$server3_lat,$server3_long);

echo "       - IP ADDRESS:  ".$ipaddr."   ";
echo "  - IP ADDRESS SOURCE:  ".$ipaddr_name."<br />";
echo "DISTANCE TO AMERICAS:  ".$dist_to_server1."     ";
echo "    -  DISTANCE TO ASIA:  ".$dist_to_server2." ";
echo "      -  DISTANCE TO EUROPE:  ".$dist_to_server3."<br />"; 
//determine closest mirror server  
    $min_dist = min($dist_to_server1, $dist_to_server2, $dist_to_server3);
    switch ($min_dist) {
    case $dist_to_server1:
        $winner = $server1_name;
        $winner_IP = $server1;
        break;
    case $dist_to_server2:
        $winner = $server2_name;
        $winner_IP = $server2;
        break;
    case $dist_to_server3:
        $winner = $server3_name;
        $winner_IP = $server3;
        break;
} 
/*another approach
    if ($dist_to_server1 > $dist_to_server2){
        if ($dist_to_server2 > $dist_to_server3){
            $winner = $server3_name;
        }else{
            $winner = $server2_name;
        }
    }elseif ($
*/

echo "CLOSEST MIRROR:  ======>  ".$winner;
echo " -                       ";
echo "              IP:  >>>>>>  ".$winner_IP;


//function gets location info re: IP address
    function get_geolocation($ip){
        $details = json_decode(file_get_contents( "http://ipinfo.io/{$ip}"));
        return $details;
    }

//function retrieves distance between user and a server by converting lat/long to radians
    //Source: http://www.mullie.eu/ geographic-searches/
    function distance($lat1, $lng1, $lat2, $lng2) {
    // convert latitude/longitude degrees for both coordinates
    // to radians: radian = degree * π / 180
    $lat1 = deg2rad($lat1);
    $lng1 = deg2rad($lng1);
    $lat2 = deg2rad($lat2);
    $lng2 = deg2rad($lng2);
    // calculate great-circle distance
    $distance = acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lng1 - $lng2));
    // distance in human-readable format:
    // earth's radius in km = ~6371
    return 6371 * $distance;
    
}          
?>  



<html>    
       <!--    mirrmap.html
	plot points on open street map of servers and user
	Leaflet JavaScript Library
	Jeff Goldberg				-->




<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
   <head profile="http://gmpg.org/xfn/11">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 
      <link rel="stylesheet" type="text/css" href="leaflet.css" />
      
      <script type='text/javascript' src='//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js'></script>
      <script type='text/javascript' src='leaflet.js'></script>
   </head>
 
   <body>
      <h4 style="text-align:center;color:blue" >Jeff Goldberg -  PHP / LAMP Developer - San Diego, CA</h4>
      <!--<h5 style="text-align:center;color:blue"> PHP / LAMP Developer - San Diego, CA </h5> -->
      <h5 style="text-align:center;color:blue"><a href="http://www.linkedin.com/in/jeffgoldberg01">linkedin.com/in/jeffgoldberg01</a></h5> <!--<h6 style="color:gray; text-align:center">Open Streetmaps Example - Using Leaflet library and OpenStreetMap / MapQuest</h6>  -->
      <h6 style="text-align:center;color:gray"> 3 Mirror Servers strategically placed to manage transmission latency</h6>


      <div id="map" style="height: 580px; border: 1px solid #AAA;"></div> 
 
      <script type='text/javascript' src='maps/markers.json'></script> <!--2 lines for expansion -->
      <script type='text/javascript' src='maps/leaf-demo.js'></script> <!--2 lines for expansion -->
</html>

<script>

//Build Map constructor
var map = L.map( 'map', {
    center: [45.0, -10.1],
    minZoom: 1,
    zoom: 2
});

//Build Map detail
L.tileLayer( 'http://{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright" title="OpenStreetMap" target="_blank">OpenStreetMap</a> contributors | Tiles Courtesy of <a href="http://www.mapquest.com/" title="MapQuest" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png" width="16" height="16">',
    subdomains: ['otile1','otile2','otile3','otile4']
}).addTo( map );




 //SD (Americas)
    $server1_lat = '32.7773';
    $server1_long = '-117.1008';
    //Japan (Asia)
    $server2_lat = '35.6850';
    $server2_long = '139.7514';
    //UK (Europe)
    $server3_lat = '51.5000';
    $server3_long = '-0.1300';
markers = [
   {
     "name": "SD (Americas)",  
     "url": "https://en.wikipedia.org/wiki/americas",
     "lat": 32.7773,
     "lng": -117.1008
   },
   {
     "name": "Japan (Asia)",
     "url": "https://en.wikipedia.org/wiki/asia",
     "lat": 35.6850,
     "lng": 139.7514
   },
   {
     "name": "UK (Europe)",
     "url": "https://en.wikipedia.org/wiki/europe",
     "lat": 51.5000,
     "lng": -0.1300 
   }
]; 
var marker = L.marker([<?php echo $ulat.",".$ulong; ?>]).bindPopup( '<a href="http://www.showmyip.gr" target="_blank">' + "USER LOCATION" + '</a>' )
      .addTo( map );
L.circle( [<?php echo $ulat.",".$ulong; ?>], 1800, {
    color: 'yellow',
    fillColor: 'black',
    fillOpacity: 0.6
  }).addTo( map );
//var marker = L.marker([<?php echo $ulat.",".$ulong; ?>]).addTo(map);

  
//plot all points on map  - markers and red circles
 for ( var i=0; i < markers.length; ++i ) 
{
  
  L.marker( [markers[i].lat, markers[i].lng] )
      .bindPopup( '<a href="' + markers[i].url + '" target="_blank">' + markers[i].name + '</a>' )
      .addTo( map );
 
  L.circle( [markers[i].lat, markers[i].lng], 500, {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.7
  }).addTo( map );  

}




//var myURL = jQuery( 'script[src$="leaf-demo.js"]' ).attr( 'src' ).replace( 'leaf-demo.js', '' );
 

 

 



</script> 

