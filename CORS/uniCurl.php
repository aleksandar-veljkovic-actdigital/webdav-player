<?php 
session_start();
session_write_close();
if (isset($_GET['url'])) {
  $url = $_GET['url'];
}
else {
  die('URL Should be supplyed true ...?url={some url}');
}

$headRq = apache_request_headers();
$headRqCurl = array();

foreach($headRq as $k => $v){
  $headRqCurl[]=$k.': '.$v;
}

$ch = curl_init();
curl_setopt($ch,CURLOPT_CUSTOMREQUEST,$_SERVER['REQUEST_METHOD']);
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_HEADER, true); //
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
curl_setopt($ch,CURLOPT_HTTPHEADER,$headRqCurl);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
$response = curl_exec($ch);

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$headRsCurl = substr($response, 0, $header_size);
$body = substr($response, $header_size);
curl_close($ch);

//$headRsCurl = trim( $headRsCurl, '\n' );
$headRsCurl = explode("\r\n", $headRsCurl);

foreach($headRsCurl as $header){
  //echo($header)."\n\r".stripos($header, "chunked")."\n\r";
  if ( stripos($header, "Transfer-Encoding" ) !== false && stripos($header, "chunked") ){
    header("Content-Length: " . mb_strlen($body));
    continue;
  }  
  header($header);
}

echo $body;
