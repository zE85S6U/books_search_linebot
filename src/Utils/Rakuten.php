<?php

namespace App\Utils;

// SDKを読み込み
use RakutenRws_ApiResponse_AppRakutenResponse;
use RakutenRws_Client;

class Rakuten
{
    private $raku_app_id = null;
    private $raku_aff_id = null;
    protected $hits = null;
    protected $sort = null;
    protected $booksGenreId = null;

    public function __construct()
    {
        $this->raku_app_id = getenv("RAKUTEN_APP_ID");; // アプリID
        $this->raku_aff_id = getenv("RAKUTEN_AFF_ID"); // アフィリエイトID
        $this->hits = '5';                  // 商品データの取得数
        $this->sort = 'sales';              // 売れている順
        $this->booksGenreId = '001005';     // 楽天ブックスジャンルID(パソコン・システム開発)
    }

    /**
     * 楽天bookサーチ
     * @param $keyword
     * @return mixed
     */
    public function search($keyword)
    {
        $client = new RakutenRws_Client();
        $client->setApplicationId($this->raku_app_id);
        $client->setAffiliateId($this->raku_aff_id);
        $response = $client->execute(
            'BooksTotalSearch',
            array(
                'hits' => $this->hits,
                'sort' => $this->sort,
                'keyword' => mb_convert_kana($keyword->{'text'}, 'as', 'UTF-8'),  // 全角英字とスペースを半角
                'booksGenreId' => $this->booksGenreId
            )
        );
        return $response;
    }

    /**
     * 検索結果を配列に変換する
     * @param RakutenRws_ApiResponse_AppRakutenResponse $obj
     * @return array
     */
    public function objToArray(RakutenRws_ApiResponse_AppRakutenResponse $obj)
    {
        $data = $obj->getData();
        if ($data['count'] != 0) {
            foreach ($obj as $item) {
                $title = $item['title'];
                $isbn = $item['isbn'];
                $mediumImageUrl = $item['largeImageUrl'];
                $itemUrl = $item['itemUrl'];
                $itemCaption = $item['itemCaption'];
                $formattingData[] = [
                    'title' => $title,
                    'isbn' => $isbn,
                    'image' => $mediumImageUrl,
                    'itemUrl' => $itemUrl,
                    'itemCaption' => $itemCaption
                ];
            }
        } else {
            // 検索結果が0だった場合のダミー
            $formattingData[] = [
                'title' => null,
                'isbn' => null,
                'image' => null,
                'itemUrl' => null,
                'itemCaption' => null
            ];
        }
        return $formattingData;
    }
}
