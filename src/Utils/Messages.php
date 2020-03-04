<?php


namespace App\Utils;


class Messages
{
    private $postMessage;

    public function __construct($postMessage)
    {
        // Lineプロバイダーから送られたメッセージを連想配列に変換
        $this->postMessage = json_decode($postMessage, true);
    }

    public function getMessage() {
        return $this->postMessage['events'][0]['message']['text'];
    }

    public function getReplyToken() {
        return $this->postMessage['events'][0]['replyToken'];
    }

    public function getUserId() {
        return $this->postMessage['events'][0]['source']['userId'];
    }

    public function getMessageType(){
        return $this->postMessage['events'][0]['message']['type'];
    }

}