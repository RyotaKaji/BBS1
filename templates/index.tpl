<html>
<head>
<title>掲示板</title>
</head>
<body>

    <p>{$loggedin_id} {$status}</p>
    <form method="POST" action="index.php">
        <input type="{$form_id}" name="id" size="20" maxlength="20" placeholder="IDを入力" />
        <input type="{$form_password}" name="password" size="20" maxlength="20" placeholder="パスワードを入力"/>
        <input type="{$login_button}" name="login"  value="ログイン" />
        <input type="{$logout_button}" onclick="location.href='http://192.168.33.10/workspace/BBS1/logout.php'" value='ログアウト'/>
        <input type="{$signup_button}" onclick="location.href='http://192.168.33.10/workspace/BBS1/signup.php'" value='新規登録'/>
        <input type="button" onclick="location.href='http://192.168.33.10/workspace/BBS1/index.php'" value='再読み込み'/>
    </form>
        
<center>
    <h1>掲示板</h1>

    <form method="POST" action="index.php">
        <input type ="text" name="name" size="20" maxlength="20" placeholder=" 名前(20文字まで)" />
        <br />
        <textarea name="text" rows="4" cols="40" maxlength="140" placeholder=" 本文(140文字まで)" ></textarea>
        <br />
        <input type="submit" name="post" value="投稿" />
    </form>
    
    <form method="POST" action="edit_delete.php">
        <input type="text" name="text_number" size="7" placeholder="投稿番号"/>
        <input type="submit" name="edit_delete" value="編集/削除"/>
    </form>
    <p>{$posted_msg}</p>
    <p>{$error_msg1}</p>
    <p>{$error_msg2}</p>
</center>

{foreach $items as $itemdata}
    <p>{$itemdata.number|escape:'html'}&nbsp; 名前：{$itemdata.name|escape:'html'}</p>
    <p>{$itemdata.text|escape:'html'|nl2br}</p>
{/foreach}


    
</body>
</html>
    
