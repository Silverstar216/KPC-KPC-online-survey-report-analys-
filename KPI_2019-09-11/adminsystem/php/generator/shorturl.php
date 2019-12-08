<?php
/**
 * @param : url
 */
$url = filter_input(INPUT_GET, 'url');
$url = trim($url);
 
function getShortURL($longURL) {
	
	//아이디 키값 변경해주세요//
	$bitly_id = "tongmaroo";
	$bitly_key = "R_885485c57ff1717b689dded3dea7edaa";
	//아이디 키값 변경해주세요//

	$data = file_get_contents("http://api.bit.ly/shorten?version=2.0.1&longUrl=".$longURL."&login=".$bitly_id."&apiKey=".$bitly_key);
	$data = json_decode($data);
	foreach($data->results as $row) {
			$surl = $row->shortCNAMEUrl;
	}
	return $surl;
}

$shorturl = getShortURL($url);

echo($shorturl);
?>