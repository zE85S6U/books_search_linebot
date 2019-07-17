<?php
require_once './Rakutehelper.php';
require_once './LINEhelper.php';

// POSTデータを読む
$jsonString  = file_get_contents('php://input');
$jsonObj = json_decode($jsonString);
$message = $jsonObj->{"events"}[0]->{"message"};
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

// インスタンス化
$raku = new Rakuten_helper();
$line = new LINE_helper();

// 
if ($message->{"type"} == 'text') {
  $obj = $raku->serch($message);
  $searchedData = $raku->objToArray($obj);
  $messageData = $line->trimSerchData($searchedData);
} elseif ($message->{"type"} == 'sticker') {
  $messageData = $line->stickerType();
} else {
  $messageData = $line->undefinedType();
}

// レスポンスメッセージ
$response = $line->createResponseMessage($replyToken, $messageData);

// レスポンスメーッセージを送信
$line->sendResponseMessage($response);