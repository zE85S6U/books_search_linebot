<?php

namespace App\Utils;

class Line
{
    private $accessToken = null;

    public function __construct()
    {
        $this->accessToken = getenv("LINEBOT_CHANNEL_TOKEN");
    }

    /**
     * 検索データを整形
     * @param $formattingData
     * @return array
     */
    public function trimSearchData($formattingData): array
    {
        $columns = array();

        if (empty($formattingData[0]['isbn'])) {
            // 検索結果が0
            $messageData = ['type' => 'text', 'text' => "見つかりませんでした..."];
        } else {
            $messageData = [
                'type' => 'template',
                'altText' => '見つかりました！',
                'template' => [
                    'type' => 'carousel',
                    'imageAspectRatio' => 'rectangle',
                    'imageSize' => 'contain'
                ]
            ];
            // ヒットした件数分カルーセルを作る
            foreach ($formattingData as $values) {
                if (empty($values['itemCaption'])) $values['itemCaption'] = "説明文なし"; // 説明文が無い場合
                $columns[] =
                    [
                        "thumbnailImageUrl" => $values['image'],
                        'title' => mb_substr($values['title'], 0, 40, "utf-8"),
                        'text' => mb_substr($values['itemCaption'], 0, 60, "utf-8"),
                        'actions' => [
                            [
                                'type' => 'uri',
                                'label' => 'カーリルで探す',
                                'uri' => "http://api.calil.jp/openurl?rft.isbn=" . $values['isbn']
                            ],
                            [
                                'type' => 'uri',
                                'label' => '楽天でみる',
                                'uri' => $values['itemUrl']
                            ]
                        ]
                    ];
            }
            $messageData['template']['columns'] = $columns;
        }
        return $messageData;
    }

    /**
     * メッセージがスタンプ
     * @return array
     */
    public function stickerType(): array
    {
        $stickerId = mt_rand(52002734, 52002770);
        $messageData = [
            'type' => 'sticker',
            'packageId' => '11537',
            "stickerId" => "$stickerId"
        ];
        return $messageData;
    }

    /**
     * 友だち追加された時
     * @return array
     */
    public function follow(): array
    {
        $messageData = ['type' => 'text', 'text' => '単語と単語の間にスペースを入れるとand検索出来るよ！'];
        return $messageData;
    }

    /**
     * TODO 別のクラスのインスタンスを毎回作のは良くない気がする
     */

    /**
     * ユーザ登録をする時
     * @param $user_id
     * @return array
     */
    public function registerUser($user_id): array
    {
        $messageData = ['type' => 'text', 'text' => '登録します'];
        $db = new DBconnect();
        $db->registerUser($user_id);
        return $messageData;
    }

    /**
     * ユーザーを削除する時
     * @param $user_id
     * @return array
     */
    public function deleteUser($user_id): array
    {
        $messageData = ['type' => 'text', 'text' => '削除します'];
        $db = new DBconnect();
        $db->deleteUser($user_id);
        return $messageData;
    }


    /**
     * 上記以外のデータタイプ
     * @return array
     */
    public function undefinedType(): array
    {
        $messageData = ['type' => 'text', 'text' => 'ちょっとわからない...'];
        return $messageData;
    }

    /**
     * 返信できる形に整形
     * @param $replyToken
     * @param $respondedData
     * @return array
     */
    public function createResponseMessage($replyToken, $respondedData): array
    {
        return ['replyToken' => $replyToken, 'messages' => [$respondedData]];
    }

    /**
     * 返信処理
     * @param $response
     */
    public function sendResponseMessage($response)
    {

        // cURL セッションを初期化する
        $ch = curl_init('https://api.line.me/v2/bot/message/reply');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=UTF-8', 'Authorization: Bearer ' . $this->accessToken));
        $result = curl_exec($ch);
        error_log($result);

        // cURL セッションを閉じる
        curl_close($ch);
    }
}
