<?php
require_once 'DbManager.php';
require_once('libs/Smarty.class.php'); 
$smarty = new Smarty();
$smarty->template_dir = 'BBS1/templates/';
$smarty->compile_dir  = 'BBS1/templates_c/';

session_start();
$post= $_POST;

///////////////////////////////////////////////////////////////
//投稿番号とセッションidの両方を含む行があるかチェック(自分の投稿かチェック)
///////////////////////////////////////////////////////////////

//投稿番号が入力されていなければ編集・削除できない
if (empty($post['text_number'])){
    $smarty->assign('edit_message', "1");
} else if (($post['text_number'])>1000){
    $smarty->assign('edit_message', "3");
} else if (!is_numeric ($post['text_number'])){
    $smarty->assign('edit_message', "4");
} else {
    try {
    //データベースと接続
    $db = getDb();

    //SELECT実行
    $stt = $db->prepare("SELECT * FROM bbs1 WHERE number = :number AND id = :id");
    $stt->bindValue(':number', $post['text_number']);
    $stt->bindValue(':id', $_SESSION['id']);
    $stt->execute();

    //結果の行数が０だったら失敗
    if($stt->rowCount()==0){
        $check = "ng";
    } 
    $db = NULL;
   } catch (PDOException $e) {
        die ("エラーメッセージ：{$e->getMessage() }");
   }

    if ($check == "ng"){
        $smarty->assign('edit_message', "2");
    } else {
        $smarty->assign('edit_message', "0");
        ////////////////////   
        //選択された投稿の表示
        ////////////////////
        try {
        //データベースと接続
        $db = getDb();

        //SELECT実行
        $stt = $db->prepare("SELECT number, name, text FROM bbs1 WHERE number=:number");
        $stt->bindValue(':number', $post['text_number']);
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
    }
}

////////////////
//レコードの更新
////////////////

if(isset($post['edit'])){
    
try {
    $db =  getDb();
    $stt = $db->prepare("UPDATE bbs1 SET name = :name, text = :text WHERE number = :number");
    
    //文字数チェック
    $error_check = 0;
    $stt->bindValue(':number', $post['text_number']);
    
    if(!isset($post['name']) || is_null($post['name']) || empty($post['name'])){
        $smarty->assign('error_msg_edit1','名前を入力してください');
        $error_check = 1;
    } else if (mb_strlen($post['name'])>20) {
        $smarty->assign('error_msg_edit1','名前は20文字までです');
        $error_check = 1;
    } else {
        $stt->bindValue(':name', $post['name']);
    }
    
    if(!isset($post['text']) || is_null($post['text']) || empty($post['text'])){
        $smarty->assign('error_msg_edit2','本文を入力してください');
        $error_check = 1;
    } else if(mb_strlen($post['text'])>140) {
        $smarty->assign('error_msg_edit2','本文は140文字までです');
        $error_check = 1;
    } else {
        $stt->bindValue(':text', $post['text']);
    }
    
    //エラーがなければUPDATE命令実行
    if($error_check == 0){
        $stt->execute();
        $smarty->assign('edited_msg', "編集しました");
    }
    $db = NULL;
} catch (PDOException $e) {
    die ("エラーメッセージ：{$e->getMessage() }");
}
}


////////////////
//レコードの削除
////////////////

if(isset($post['delete'])){
    try {
        $db = getDb();
        //DELETEの実行
        $stt = $db->prepare("DELETE FROM bbs1 WHERE number = :number");
        $stt->bindValue(':number', $post['text_number']);
        $stt->execute();
        $smarty->assign('edited_msg', "削除しました");
        $db = NULL;
    } catch (PDOException $e) {
        die ("エラーメッセージ：{$e->getMessage() }");
    }
}


//テンプレートへ出力
$smarty->display('edit_delete.tpl');

