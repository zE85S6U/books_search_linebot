# 作業メモ
自分の環境とバージョン確認方法のメモ
```
$ cat /etc/debian_version 
9.9

$ php -v
PHP 7.3.7-1+0~20190710.40+debian9~1.gbp032aec (cli) (built: Jul 10 2019 07:38:52) ( NTS )
Copyright (c) 1997-2018 The PHP Group
Zend Engine v3.3.7, Copyright (c) 1998-2018 Zend Technologies
    with Zend OPcache v7.3.7-1+0~20190710.40+debian9~1.gbp032aec, Copyright (c) 1999-2018, by Zend Technologies
    with Xdebug v2.7.1, Copyright (c) 2002-2019, by Derick Rethans

$ /usr/sbin/apache2ctl -v
Server version: Apache/2.4.25 (Debian)
Server built:   2019-04-02T19:05:13

$ git --version
git version 2.11.0

$ heroku -v
heroku/7.26.2 linux-x64 node-v11.14.0

t$ ngrok -v
ngrok version 2.3.33

$ code -v
1.36.1
2213894ea0415ee8c85c5eea0d0ff81ecc191529
x64
```

### LINEMessaging API
[LINE Developers](https://developers.line.biz/ja/)へアクセスしアカウント登録
- アクセストークン：再発行
- Webhook送信：利用する
- 自動応答メッセージ：利用しない

### Rakuten Developers API
検索ジャンル
```
booksGenreId = "001005"
```
参考:[楽天ジャンルリスト(テーブルバージョン)](https://github.com/NoguHiro/hacky_and_rocky/wiki/)

### Heroku
[Heroku](https://dashboard.heroku.com/apps)へアクセスしアカウント登録  
[Heroku CLI](https://devcenter.heroku.com/articles/heroku-cli) をインストール
```
$ apt-get install -y heroku
```
Heroku CLIからアプリ作成
```
$ heroku create アプリ名
```
プロジェクトをHerokuへデプロイ
```
$ git push heroku master
```
設定の確認
```
$heroku config
```
アプリ一覧を確認
```
heroku apps
```

#### タイムゾーンを日本に
現在の時間を確認
```
$ heroku run bash --app アプリ名
~ $ date
Mon May 29 12:50:21 UTC 2017
```

タイムゾーンの変更
```
heroku config:add TZ=Asia/Tokyo --app アプリ名
Setting TZ and restarting ⬢ <アプリ名>... done, v18
TZ: Asia/Tokyo
```

## ローカルサーバで開発したい
### Apache
Apacheの起動時に記述ミスがないか確認
```
sudo apache2ctl configtest
```

作業用ディレクトリを追加

```
$ vi /etc/apacheapache2.conf 
~
<Directory /home/<UserName>/<アプリケーションのディレクトリ>>
	Options Indexes FollowSymLinks
	AllowOverride None
	Require all granted
</Directory>

```

Apacheのphpのバージョンを変える
```
# a2enconf php7.3
# a2enmod php7.3
```

Apache用設定を追加(xdebugから怒られたため80番ポートから8080番ポートに変更)
```
# vi /etc/apache2/sites-available/my-develop.conf 
<VirtualHost *:8080>
	# The ServerName directive sets the request scheme, hostname and port that
	# the server uses to identify itself. This is used when creating
	# redirection URLs. In the context of virtual hosts, the ServerName
〜中略〜
</VirtualHost>
```
適用する
```
# a2ensite my-develop
# systemctl restart apache2
```
Apacheの待受ポートを変更
```
# vi /etc/apache2/ports.conf
Listen 8080
```
Apacheの環境変数にトークン等を設定  
/etc/apache2/envvarsに追記
```
export LINEBOT_CHANNEL_SECRET="Channel Secret"
export LINEBOT_CHANNEL_TOKEN="アクセストークン"
export RAKUTEN_APP_ID="アプリID/デベロッパーID"
export RAKUTEN_AFF_ID="アフィリエイトID"
```
Apache再起動
```
# systemctl restart apache2
```

### ngrok
[ngrok](https://ngrok.com/)をインストールすればローカルPCのWebサーバを外部公開することができる

公開したいアプリケーションのディレクトリに移動してから
```
$ ngrok http localhost:8080
```

### デバック
#### Herokuの場合
phpのコードに`error_log($変数名);`を仕込んで
```
heroku logs -t
```

#### ngrokの場合
`http://127.0.0.1:4040`のWEBインターフェースで確認

#### ステップ実行したい(Apache + ngrok +Visual Studio Code + PHP Debug)
xdebugの設定
```
$ vi/etc/php/7.3/apache2/conf.d/20-xdebug.ini
zend_extension=xdebug.so
xdebug.remote_enable = 1
xdebug.remote_port = 9000
xdebug.remote_autostart = 1
xdebug.remote_connect_back = 0;
xdebug.remote_host= "localhost"
```

Visual Studio Codeのlaunch.jsonに追加
```
    {
      "name": "Remote Debug",
      "type": "php",
      "request": "launch",
      "pathMappings": {
        "serverSourceRoot": "localhost:8080",
        "localSourceRoot": "localhost:8080"
      },
      "port": 9000
    }
```

### Git
[.gitignore の書き方](https://qiita.com/inabe49/items/16ee3d9d1ce68daa9fff)

### 参考にしたサイト
- [PHP×LINE Messaging APIで作るチャットボット](https://qiita.com/ryo_hisano/items/da85ee205fb6c8fd3fee)  
- [PHPで作成したLINEBOTをHerokuで動かしてみた](https://qiita.com/masaki-ogawa/items/2521d29c17eb8664cab8)  
- [PHP+HerokuでLINE BOT作ってみた](https://qiita.com/ttskch/items/7c148fcc595cec4aa59a)  
- [HerokuとPHPでLineのDB接続されたチャットボットを作る](https://note.mu/rik114/n/nee16388d0eaa)
- [ffmpegで動画を綺麗なgifに変換するコツ](https://life.craftz.dog/entry/generating-a-beautiful-gif-from-a-video-with-ffmpeg)