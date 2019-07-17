<?php

class LINE_helper
{
  private $accessToken = null;

  public function __construct()
  {
    $this->accessToken = getenv("CHANNEL_ACCESS_TOKEN");
  }

  // 検索結果を整形
  public function trimSerchData($formating_data)
  {
    if (empty($formating_data[0]['isbn'])) {
      // 検索結果が0
      $messageData = ['type' => 'text', 'text' => "見つかりませんでした..."];
    } else {
      $messageData = [
        'type' => 'template',
        'altText' => 'カルーセル',
        'template' => [
          'type' => 'carousel',
          'imageAspectRatio'  => 'rectangle',
          'imageSize' => 'contain'
        ]
      ];
      // ヒットした件数分カルーセルを作る
      foreach ($formating_data as $values) {
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

  // メッセージがスタンプ
  public function stickerType()
  {
    $stickerid = mt_rand(52002734, 52002770);
    $messageData = [
      'type' => 'sticker',
      'packageId' => '11537',
      "stickerId" => "$stickerid"
    ];
    return $messageData;
  }

  // 友だち追加された時
  public function follow() {
    $messageData = ['type' => 'text', 'text' => 'Java Androidとかスペースでand検索出来るよ！'];
    return $messageData;
  }
  // 上記以外のデータタイプ
  public function undefinedType()
  {
    $messageData = ['type' => 'text', 'text' => 'ちょっとわからない...'];
    return $messageData;
  }

  // 返信できる形に整形
  public function createResponseMessage($replyToken, $responsdData)
  {
    return ['replyToken' => $replyToken, 'messages' => [$responsdData]];
  }

  // 返信処理
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
