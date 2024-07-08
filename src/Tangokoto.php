<?php
$tangokoto = @file("../Tangokoto.txt"); // [Type: Array] Now the datasize is very small. It's OK to use file(). 
if ($tangokoto === false) {
    die("无法读取文件");
}

$author = "唐老师";    // Mr.Tang
$length = sizeof($tangokoto);   // Get length of Tangokoto array
$randid = rand() % $length;     // Get random id

$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : null;
$type = isset($_GET['type']) ? filter_var($_GET['type'], FILTER_SANITIZE_STRING) : null;

$index = isset($id) && $id !== false && $id >= 0 && $id < $length ? $id : $randid;  // determine final id to output (if GET parameter 'id' isn't valid, use the random id).
$outputRaw = rtrim($tangokoto[$index]); // set outputRaw and do trimming

$json = json_encode(array('id' => $index, 'hitokoto' => $outputRaw, 'from' => $author)); // encode json
if ($json === false) {
    die("JSON 编码失败");
}

$type = isset($type) ? $type : "json";   // determine the type to output (default is json)

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
        $callback = isset($_GET['_callback']) ? filter_var($_GET['_callback'], FILTER_SANITIZE_STRING) : "doTangokoto";
        echo htmlspecialchars($callback) . "(" . $json . ")";
        break;
    default:        // json
        Header("Content-Type: text/json; charset=utf-8");
        echo $json;
        break;
}
