
# 図書検索 LINEBot
LINEのトークに入力したキーワードからおすすめの書籍を探します

## Demo
![result](https://github.com/zE85S6U/books_search_linebot/blob/develop/document/image/bot_demo.gif)
## あらかじめ必要なもの
-  git
-  composer
-  HerokuアカウントとHelokuCLI
-  LINE Developersアカウント
-  Rakuten Developersアカウント

## Usage
1. プロジェクトをHerokuへデプロイしてそのURLをコピー
1. 作成したLINEボットのWebhook URLに上のURLを設定
1. LINEアプリへのQRコードから友達登録
1. キーワードをトークに送信

## Install
1. composer install  
    ```
    $ composer install
    ```
1. herokuの環境変数にトークン等を設定  
    $ heroku loginの後
    ```
    $ heroku config:set LINEBOT_CHANNEL_SECRET="Channel Secret"
    $ heroku config:set LINEBOT_CHANNEL_TOKEN="アクセストークン"
    $ heroku config:set RAKUTEN_APP_ID="アプリID/デベロッパーID"
    $ heroku config:set RAKUTEN_AFF_ID="アフィリエイトID"
    ```
## Document
[作った時のメモ](https://github.com/zE85S6U/books_search_linebot/blob/develop/document/document.md)

## Licence
自由に改変して下さい。  
I want it simple and permissive.  
MIT Licence

## Author
[zE85S6U](https://github.com/zE85S6U)  
作ったLINEBotです、よかったら友達登録して下さい。  
![result](https://github.com/zE85S6U/books_search_linebot/blob/develop/document/image/qr_linebot.jpg)