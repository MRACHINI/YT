<?php
//$url = 'https://www.inoreader.com/stream/user/1005961156/tag/Youtube?n=99';
$url = 'https://www.inoreader.com/stream/user/1005961156/tag/Youtube/view/json?n=350';
$page = file_get_contents($url);

// https://www.youtube.com/watch?v=
// <a href="https://www.youtube.com/watch?v=-B_GaZDkhU0">https://www.youtube.com/watch?v=-B_GaZDkhU0</a>

//preg_match_all('/[^"]>(https:\/\/www\.youtube\.com\/watch\?v=.*)/',$page,$a);
//"url": "https:\/\/www.youtube.com\/watch?v=OZFbep8wG7E",
//"title": "Regenerative Braking - DIY Electric Scooter Part 4 [VESC]","url": "https:\/\/www.youtube.com\/watch?v=OZFbep8wG7E",
//preg_match_all('/"title": "(.*)",\n\s*"url": "(.*)",\n\s*.*,\n\s*.*,\n\s*.*,\n\s*"source": "(.*)"/',$page,$a);
preg_match_all('/"title": "(.*)",\n\s*"url": "(.*)",\n\s*.*,\n\s*"date_published": "([0-9]{4}-[0-9]{2}-[0-9]{2}).*",\n\s*.*,\n\s*"source": "(.*)"/',$page,$a);

$current="#EXTM3U\r\n";
$count = count($a[1]);
//echo "<b>Number of Urls</b> = " .$count."<p>";

for ($row = 0; $row < $count ; $row++) {
//echo "<a href=\"".rtrim($a[1]["$row"], "</link>")."\">".rtrim($a[1]["$row"], "</link>")."</a>"."<br/>\r\n";
//echo rtrim($a[1]["$row"], "</link>")."\n\r";
//$current .= rtrim($a[1]["$row"], "</link>")."\n";
$current .= stripslashes(rtrim("#EXTINF:-1,".$a[3]["$row"]." ".$a[4]["$row"].": ".$a[1]["$row"]."\r\n".$a[2]["$row"]."", '\\\\')."\r\n");
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
