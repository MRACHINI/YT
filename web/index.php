<?php
//$url = 'https://www.inoreader.com/stream/user/1005961156/tag/Youtube?n=99';
//$url = 'https://www.inoreader.com/stream/user/1005961156/tag/Youtube/view/json?n=400';
date_default_timezone_set('Asia/Beirut');

if (isset($_GET['Type'])) {
	$Type = strtolower(htmlspecialchars($_GET["Type"]));
}else{
	$Type = "xml";
}
//echo $Type."\n\n";

if($Type == strtolower("json")){
	$url = 'https://www.inoreader.com/stream/user/1005961156/tag/YouTube%20subscriptions/view/json?n=400'; //json
}else{
	$url = 'https://www.inoreader.com/stream/user/1005961156/tag/YouTube%20subscriptions?n=400'; //xml
}
//echo "URL=".$url."\n\n";

$page = file_get_contents($url);

// https://www.youtube.com/watch?v=
// <a href="https://www.youtube.com/watch?v=-B_GaZDkhU0">https://www.youtube.com/watch?v=-B_GaZDkhU0</a>

//preg_match_all('/[^"]>(https:\/\/www\.youtube\.com\/watch\?v=.*)/',$page,$a);
//"url": "https:\/\/www.youtube.com\/watch?v=OZFbep8wG7E",
//"title": "Regenerative Braking - DIY Electric Scooter Part 4 [VESC]","url": "https:\/\/www.youtube.com\/watch?v=OZFbep8wG7E",
//preg_match_all('/"title": "(.*)",\n\s*"url": "(.*)",\n\s*.*,\n\s*.*,\n\s*.*,\n\s*"source": "(.*)"/',$page,$a);
if($Type == strtolower("json")){
    preg_match_all('/"title": "(.*)",\n\s*"url": "(.*)",\n\s*.*,\n\s*"date_published": "([0-9]{4}-[0-9]{2}-[0-9]{2}).*",\n\s*.*.*\n\s.*\n\s.*\n\s.*,\n\s*"source": "(.*)"/',$page,$a);
if(count($a[1])==0){
	preg_match_all('/"title": "(.*)",\n\s*"url": "(.*)",\n\s*.*,\n\s*"date_published": "([0-9]{4}-[0-9]{2}-[0-9]{2}).*",\n\s*.*.*\n\s.*\n\s.*\n\s.*,*"source": "(.*)"/',$page,$a);
}
if(count($a[1])==0){
	preg_match_all('/"title": "(.*)",\n\s*"url": "(.*)",\n\s*.*,\n\s*"date_published": "([0-9]{4}-[0-9]{2}-[0-9]{2}).*",\n\s*.*.*\n\s.*\n\s.*\n\s.*,*/',$page,$a);
}
}else{
	$xml=simplexml_load_string($page) or die("Error: Cannot create object");
	//print_r($xml);
	//preg_match_all('/<item>[\n\s]<title>(.*)<\/title>[\n\s]<link>(.*)<\/link>[\n\s].*[\n\s].*[\n\s].*[\n\s]<pubDate>[a-zA-Z]{3}, ([0-9]{2} [a-zA-Z]{3} [0-9]{4}).* \+[0-9]{4}<\/pubDate>[\n\s].*[\n\s]<dc:creator>(.*)<\/dc:creator>[\n\s].*[\n\s].*[\n\s].*/',$page,$a);
}
//<item>[\n\s]<title>(.*)<\/title>[\n\s]<link>(.*)<\/link>[\n\s].*[\n\s].*[\n\s].*[\n\s]<pubDate>[a-zA-Z]{3}, ([0-9]{2} [a-zA-Z]{3} [0-9]{4}).* \+[0-9]{4}<\/pubDate>[\n\s].*[\n\s]<dc:creator>(.*)<\/dc:creator>[\n\s].*[\n\s].*[\n\s].*<\/item>


$current="#EXTM3U\r\n";
if($Type == strtolower("json")){
$count = count($a[1]);
echo "<b>Number of Urls</b> = " .$count."<p>";

for ($row = 0; $row < $count ; $row++) {
//echo "<a href=\"".rtrim($a[1]["$row"], "</link>")."\">".rtrim($a[1]["$row"], "</link>")."</a>"."<br/>\r\n";
//echo rtrim($a[1]["$row"], "</link>")."\n\r";
//$current .= rtrim($a[1]["$row"], "</link>")."\n";
$current .= stripslashes(rtrim("#EXTINF:-1,".$a[3]["$row"]." ".$a[4]["$row"].": ".$a[1]["$row"]."\r\n".$a[2]["$row"]."", '\\\\')."\r\n");
}
}else{
	foreach($xml->channel->item as $item)
	{
		$originalDate = $item->pubDate;
		$newDate = date("Y-m-d", strtotime($originalDate));
		//echo $newDate;
		//echo substr($item->pubDate, 12, 4);
		//echo substr($item->pubDate, 8, 3);
		//echo substr($item->pubDate, 5, 2);
		
		$current .= stripslashes(rtrim("#EXTINF:-1,".$newDate." ".$item->source.": ".$item->title."\r\n".$item->link."", '\\\\')."\r\n");
		//echo $item->pubDate;
		//echo $item->source;
		//echo $item->title;
		//echo $item->link;
		//print_r($item);
	}
}

#for ($row = $count-1; $row >= 0 ; $row--) {
//echo "<a href=\"".rtrim($a[1]["$row"], "</link>")."\">".rtrim($a[1]["$row"], "</link>")."</a>"."<br/>\r\n";
//echo rtrim($a[1]["$row"], "</link>")."\n\r";
//$current .= rtrim($a[1]["$row"], "</link>")."\n";
#$current .= stripslashes(rtrim("#EXTINF:-1,".$a[3]["$row"]." - ".$a[1]["$row"]."\r\n".$a[2]["$row"], '\\\\')."\r\n");
#}

//$file = 'people.txt';
//file_put_contents($file, $current);

//Generate text file on the fly

   //header("Content-type: text/plain");
   //header("Content-Disposition: attachment; filename=savethis.m3u");
   header("Content-type: text/xhtml+xml");
   //echo '<?xml version=\"1.0\" encoding=\"UTF-8\"\?\>';

   // do your Db stuff here to get the content into $content
   //print "This is some text...\n";
   print $current;
?>
