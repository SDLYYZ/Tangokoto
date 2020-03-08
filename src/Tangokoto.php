<?php
$tangokoto = file("**/Tangokoto.txt"); // [Type: Array] Now the datasize is very small. It's OK to use file(). 
$author = "唐老师";    // Mr.Tang
$length = sizeof($tangokoto);   // Get length of Tangokoto array
$randid = rand() % $length;     // Get random id
$index = array_key_exists("id", $_GET) ? ( intval($_GET['id']) >= $length ? $randid : intval($_GET['id']) ) : $randid;  // determine final id to output (if GET parameter 'id' isn't valid, use the random id).
$outputRaw = rtrim($tangokoto[$index]); // set outputRaw and do trimming
$json = json_encode( array( 'id' => $index, 'hitokoto' => $outputRaw, 'from' => $author ) ); // encode json
$type = array_key_exists("type", $_GET) ? $_GET['type'] : "json";   // determine the type to output (default is json)
switch ($type) {
    case 'plain':   // plain text
        Header("Content-Type: text/plain; charset=utf-8");
        echo $outputRaw;
        break;
    case 'js':      // javascript func
        Header("Content-Type: application/javascript; charset=utf-8");
        echo "function doTangokoto(){document.write(\"<span class='hitokoto' title='Tangokoto'>" . htmlspecialchars($outputRaw) . "</span>\");}";
        break;
    case 'jsonp':   // jsonp
        Header("Content-Type: application/javascript; charset=utf-8");
        $callback = array_key_exists("_callback", $_GET) ? $_GET['_callback'] : "doTangokoto";
        echo htmlspecialchars($callback)."(".$json.")";
        break;
    default:        // json
        Header("Content-Type: text/json; charset=utf-8");
        echo $json;
        break;
}
