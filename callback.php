<?php
require_once 'Rakuten.php';
require_once 'Line.php';

// POSTデータを読む
$jsonString = file_get_contents('php://input');
$jsonObj = json_decode($jsonString);
$message = $jsonObj->{"events"}[0]->{"message"};
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};

// インスタンス化
$raku = new Rakuten();
$line = new Line();

// Botの処理
if ($message->{"type"} == 'text') {
    $obj = $raku->search($message);
    $searchedData = $raku->objToArray($obj);
    $messageData = $line->trimSearchData($searchedData);
} elseif ($message->{"type"} == 'sticker') {
    $messageData = $line->stickerType();
} elseif ($jsonObj->{"events"}[0]->{"type"} == 'follow') {
    $messageData = $line->follow();
} else {
    $messageData = $line->undefinedType();
}

// レスポンスメッセージ
$response = $line->createResponseMessage($replyToken, $messageData);

// レスポンスメーッセージを送信
$line->sendResponseMessage($response);