<?php
require_once 'DbManager.php';
require_once 'Encode.php';
?>
<html>
<head>
<title>掲示板１</title>
</head>
<body>

<form method="POST" action="bbs1_process.php">
名前：
<input type ="text" name="name" size="20" maxlength="20" placeholder="(20文字まで)" />
<br />
<textarea name="text" rows="4" cols="40" maxlength="140" placeholder=" (140文字まで)"></textarea>
<br />
<input type="submit" value="投稿" />
<br />
<br />

<?php
try {
    //データベースと接続
    $db = getDb();
    
    //SELECT実行
    $stt = $db->prepare("SELECT * FROM bbs1 ORDER BY number DESC");
    $stt->execute();
    
    //内容の表示
     while($row = $stt->fetch(PDO::FETCH_ASSOC)) {
        print e($row['number']);
        print "&nbsp;"."名前：";
        
        //名前は空欄なら「名無し」に
        if ($row['name'] == NULL) {
            print "名無し<br />";
        } else {
            print e($row['name'])."<br />";
        }
        
        //本文は空欄なら「(本文はありません )」に
        if ($row['text'] == NULL) {
            print "(本文はありません)<br /><br />";
        } else {
        print e($row['text'])."<br /><br />";
        }
    }
    $db = NULL;
} catch (PDOException $e) {
    die ("エラーメッセージ：{$e->getMessage() }");
}
?>
        
</body>
</html>