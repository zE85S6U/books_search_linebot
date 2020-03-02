<?php

namespace App\Utils;


use PDO;
use PDOException;

class DBconnect
{
    // インスタンス
    protected static $db;

    // テーブル名
    const TABLE_NAME = 'UserData';

    // コンストラクタ
    public function __construct()
    {
        try {
            // 環境変数からデータベースへの接続情報を取得
//            $url = parse_url(getenv('DATABASE_URL'));
            $url = parse_url('postgres://kali:password@localhost:5432/line_bot');
            // データソース
            $dsn = sprintf('pgsql:host=%s;dbname=%s', $url['host'], substr($url['path'], 1));
            // 接続を確立
            self::$db = new PDO($dsn, $url['user'], $url['pass']);
            // エラー事例外を投げるように設定
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log('Connection Error: ' . $e->getMessage());
        }
    }

    // ユーザをデータベースに登録する
    public function registerUser($userId)
    {
        $dbh = self::$db;
        $sql = 'insert into ' . self::TABLE_NAME . ' (user_id) values (?) ';
        $sth = $dbh->prepare($sql);
        $sth->execute(array($userId));
    }

    // ユーザの情報をデータベースから削除
    function deleteUser($userId)
    {
        $dbh = self::$db;
        $sql = 'delete from ' . self::TABLE_NAME . ' where user_id =?';
        $sth = $dbh->prepare($sql);
        $sth->execute(array($userId));

    }
}