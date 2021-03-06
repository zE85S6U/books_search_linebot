<?php
require_once 'vendor/autoload.php';

use App\Utils\Line;
use App\Utils\Rakuten;

// POSTデータを読む
$jsonString = file_get_contents('php://input');
$jsonObj = json_decode($jsonString);
$message = $jsonObj->{"events"}[0]->{"message"};
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};
$userId = $jsonObj->{"events"}[0]->{"source"}->{"userId"};

// インスタンス化
$line = new Line();
$raku = new Rakuten();

// Botの処理
if ($message->{"type"} == 'text') {
    if ($message->{"text"} == '登録') {
        $messageData = $line->registerUser($userId);
    } else if ($message->{"text"} == '削除') {
        $messageData = $line->deleteUser($userId);
    } else
        {
        $obj = $raku->search($message);
        $searchedData = $raku->objToArray($obj);
        $messageData = $line->trimSearchData($searchedData);
    }
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