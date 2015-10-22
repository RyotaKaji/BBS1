<?php
require_once 'DbManager.php';

$post = $_POST;

try {
    //データベースへ接続
    $db = getDb();
    
    //INSERT命令の準備
    $stt = $db->prepare("INSERT INTO bbs1(name, text) VALUES(:name, :text)");
    
    //INSERT命令にポストデータの内容をセット
    $stt->bindValue(':name', $post['name']);
    $stt->bindValue(':text', $post['text']);
    
    //INSERT命令実行
    $stt->execute();
    $db = NULL;
} catch (PDOException $e) {
    die ("エラーメッセージ：{$e->getMessage()}");
}
    //リダイレクト
    header ('Location: http://192.168.33.10/workspace/BBS1/index.php');
    
?>