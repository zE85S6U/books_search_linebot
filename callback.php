<?php
require_once 'vendor/autoload.php';

use App\Utils\Line;
use App\Utils\Messages;
use App\Utils\Rakuten;

// POSTデータを読む
$messages = new Messages(file_get_contents('php://input'));
$replyToken = $messages->getReplyToken();
$userId = $messages->getUserId();

// インスタンス化
$line = new Line();
$raku = new Rakuten();

// Botの処理
if ($messages->getMessageType() == 'text') {
    if ($messages->getMessage() == '登録') {
        $messageData = $line->registerUser($userId);
    } else if ($messages->getMessage() == '削除') {
        $messageData = $line->deleteUser($userId);
    } else
        {
        $obj = $raku->search($messages->getMessage());
        $searchedData = $raku->objToArray($obj);
        $messageData = $line->trimSearchData($searchedData);
    }
} elseif ($messages->getMessageType() == 'sticker') {
    $messageData = $line->stickerType();
} elseif ($messages->getMessageType() == 'follow') {
    $messageData = $line->follow();
} else {
    $messageData = $line->undefinedType();
}

// レスポンスメッセージ
$response = $line->createResponseMessage($replyToken, $messageData);

// レスポンスメーッセージを送信
$line->sendResponseMessage($response);