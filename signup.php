<?php

require_once 'DbManager.php';
require_once('libs/Smarty.class.php');
$smarty = new Smarty();
$smarty->template_dir = 'BBS1/templates/';
$smarty->compile_dir = 'BBS1/templates_c/';

$post = $_POST;

try {
    //データベースへ接続
    $db = getDb();

    //POSTかどうかの判別
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $error_check = 0;
        //IDチェック
        if (!isset($post['id']) || is_null($post['id']) || empty($post['id'])) {
            $smarty->assign('err_msg1', 'IDを入力してください');
            $error_check = 1;
        } else if (mb_strlen($post['id']) > 20) {
            $smarty->assign('err_msg1', 'IDは20文字までです');
            $error_check = 1;
        } else if (!preg_match('/^[a-zA-Z0-9]+$/', $post['id'])) {
            $smarty->assign('err_msg1', 'IDは半角英数字のみです');
            $error_check = 1;
        }


        //パスワードチェック
        if (!isset($post['password']) || is_null($post['password']) || empty($post['password'])) {
            $smarty->assign('err_msg2', 'パスワードを入力してください');
            $error_check = 1;
        } else if (mb_strlen($post['password']) > 20) {
            $smarty->assign('err_msg2', 'パスワードは20文字までです');
            $error_check = 1;
        } else if (!preg_match('/^[a-zA-Z0-9]+$/', $post['password'])) {
            $smarty->assign('err_msg2', 'パスワードは半角英数字のみです');
            $error_check = 1;
        }


        //何もエラーがなければ認証へ
        if ($error_check == 0) {
            //IDが重複していないかのチェック
            $sth = $db->prepare("SELECT id FROM member where id = :id");
            $sth->bindValue(':id', $post['id']);
            $sth->execute();
            //結果の行数が1じゃなければOK
            if ($sth->rowCount() == 1) {
                $smarty->assign('err_msg_id', "入力されたIDはすでに使用されています");
            } else {

                //INSERT命令の準備
                $stt = $db->prepare("INSERT INTO member(id, password) VALUES(:id, :password)");

                //INSERT命令にポストデータの内容をセット
                $stt->bindValue(':id', $post['id']);
                $stt->bindValue(':password', $post['password']);

                //INSERT命令実行
                $stt->execute();
                $smarty->assign('signup_msg', "登録完了しました");
            }
            $db = NULL;
        }
    }
} catch (PDOException $e) {
    die("エラーメッセージ：{$e->getMessage()}");
}
//テンプレートへ出力
$smarty->display('signup.tpl');

