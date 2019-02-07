<?php // callback.php
date_default_timezone_set('Asia/Bangkok');
require "vendor/autoload.php";

include "functions.php";

// รับ input text
$json_string = file_get_contents('php://input');
$jsonObj = json_decode($json_string); //รับ JSON มา decode เป็น StdObj
$array = json_decode(json_encode($jsonObj), true);
$array11 = print_r($array, true);
debug_system($array11);

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => $channel_secret]);
//
if ($array['events'][0]['source']['type'] != 'group') {
    $to = $array['events'][0]['source']['userId']; //หาผู้ส่ง
} else {
    $to = $array['events'][0]['source']['groupId']; //หาผู้ส่ง
}

$text = $array['events'][0]['message']['text']; //หาข้อความที่โพสมา
$replyToken = $array['events'][0]['replyToken'];
$text = str_replace('  ', ' ', $text);
$text = str_replace("\r", null, $text);
$text = str_replace("\n", null, $text);
$displayName = str_replace("'", null, $displayName);
$displayName = str_replace(">", null, $displayName);
$displayName = str_replace("<", null, $displayName);

//ดึงชื่อ user
$a = $bot->getProfile($to);
$user_profle = getuser_profile($a);
$displayName = $user_profle['displayName'];
$userId = $user_profle['userId'];
$pictureUrl = $user_profle['pictureUrl'];
$statusMessage = $user_profle['statusMessage'];

$id_quns = $array['events'][0]['source']['userId'];
$text_ORI = replace_text($text);

//ให้ bot ตอบสนองต่อ ip  line ที่เรากำหนดเท่านั้น
$admin_group = array("C19e89314367dd8cxxxxx", "U01775d5db2200e331xxxxx");

if (!in_array($to, $admin_group)) {
    $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('ท่านไม่มีสิทธิ์ ใช้งานหนูคะ');
    $response = $bot->replyMessage($replyToken, $textMessageBuilder);
    exit();
}

//เช็คว่า อัพโหลดภาพ หรือ คลิป

if ($array['events'][0]['message']['type'] == 'image' or $array['events'][0]['message']['type'] == 'video') {

    if ($array['events'][0]['message']['type'] == 'video') {
        $F_TYPE = "mp4";

    }
    if ($array['events'][0]['message']['type'] == 'image') {
        $F_TYPE = "png";

    }

    $message_id = $array['events'][0]['message']['id'];

    $response = $bot->getMessageContent($message_id);
    $date_file = date("Y-m-d-H-i-s");

    if ($response->isSucceeded()) {

        $file_save_temp = "upload/$date_file.$F_TYPE";
        $fs = fopen($file_save_temp, "w");
        fwrite($fs, $response->getRawBody());
        fclose($fs);

        $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('save  data แล้วคร้าา' . "\r\n $response_data");
        $response = $bot->pushMessage($to, $textMessageBuilder);
    }
}
