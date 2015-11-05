<?php
require_once 'DbManager.php';
require_once('libs/Smarty.class.php'); 
$smarty = new Smarty();
$smarty->template_dir = 'BBS1/templates/';
$smarty->compile_dir  = 'BBS1/templates_c/';

$post = $_POST;


///////////////
//ログイン
///////////////
session_start();
$smarty->assign('status', "");
$smarty->assign('form_id',"text"); 
$smarty->assign('form_password',"password");
$smarty->assign('login_button', "submit");
$smarty->assign('logout_button',"hidden");


//セッションにセットされていればログイン済み
if(isset($_SESSION['id'])){
    $smarty->assign('loggedin_id', $_SESSION['id']);
    $smarty->assign('status', "でログイン中です");
    $smarty->assign('form_id',"hidden");
    $smarty->assign('form_password',"hidden");
    $smarty->assign('login_button', "hidden");
    $smarty->assign('logout_button',"button");
} else if (isset ($post['login'])){
    //ログイン開始
    if (empty($post['id']) or empty($post['password'])){
        $smarty->assign('status', "ID・パスワードを入力してください");
} else {
    
    //idとパスワードが一致する行を探す
    try {
    //データベースと接続
    $db = getDb();
    
    //SELECT実行
    $stt = $db->prepare("SELECT * FROM member WHERE id = :id AND password = :password");
    $stt->bindValue(':id', $post['id']);
    $stt->bindValue(':password', $post['password']);
    $stt->execute();
    
    //結果の行数が１だったら成功
    if($stt->rowCount()==1){
        $smarty->assign('form_id',"hidden");
        $smarty->assign('form_password',"hidden");
        $smarty->assign('login_button', "hidden");
        $smarty->assign('logout_button',"button");
        $smarty->assign('status', "ログインしました");
        //セッションにidを保存
        $_SESSION['id'] = $post['id'];
    } else {
        $smarty->assign('status', "IDまたはパスワードが一致しません");
    }
    //データベース接続終了
    $db = NULL;
    
} catch (PDOException $e) {
    die ("エラーメッセージ：{$e->getMessage() }");
}
    
}
}


/////////////////////////////////
//投稿された内容をデータベースへ書き込む
/////////////////////////////////
try {
    //データベースへ接続
    $db = getDb();
    
    //INSERT命令の準備
    $stt = $db->prepare("INSERT INTO bbs1(id, name, text) VALUES(:id, :name, :text)");
    
    //本文投稿かどうかの判別
    if(isset($post['post'])){
    //INSERT命令にポストデータの内容をセット
    $stt->bindValue(':id', $_SESSION['id']);
    //エラーチェック
    $error_check1 = 0;
    if($post['name']==NULL){
        $smarty->assign('error_msg1','名前を入力してください');
        $error_check1 = 1;
    } else if (mb_strlen($post['name'])>20) {
        $smarty->assign('error_msg1','名前は20文字までです');
        $error_check1 = 1;
    } else {
        $stt->bindValue(':name', $post['name']);
    }
    
    if($post['text']==NULL){
        $smarty->assign('error_msg2','本文を入力してください');
        $error_check1 = 1;
    } else if(mb_strlen($post['text'])>140) {
        $smarty->assign('error_msg2','本文は140文字までです');
        $error_check1 = 1;
    } else {
        $stt->bindValue(':text', $post['text']);
    }
    
    //エラーがなければINSERT命令実行
    if($error_check1 == 0){
        $stt->execute();
        $smarty->assign('posted_msg', "投稿しました");
    }
    $db = NULL;
    }
} catch (PDOException $e) {
    die ("エラーメッセージ：{$e->getMessage()}");
}


//////////////////////////////
//データベースから投稿内容を読み込む
//////////////////////////////

try {
    //データベースと接続
    $db = getDb();
    
    //SELECT実行
    $stt = $db->prepare("SELECT number, name, text FROM bbs1 ORDER BY number DESC");
    $stt->execute();
    
    //データベースから取ってきたものを配列へ割り当てる
     while($row = $stt->fetch(PDO::FETCH_ASSOC)) {
        $items[] = $row;
    }
    
    //Smartyに配列を渡す
    $smarty->assign('items', $items);
    
    //データベース接続終了
    $db = NULL;
    
} catch (PDOException $e) {
    die ("エラーメッセージ：{$e->getMessage() }");
}


//テンプレートへ出力
$smarty->display('index.tpl');


