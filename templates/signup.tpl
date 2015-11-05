<html>
<head>
<title>登録</title>
</head>
<body>

<form method="POST" action="signup.php">
    <table border="0">
    <tr>
        <td align="right">ID：</td>
        <td><input type = "text" name="id" size="20" maxlength="20" placeholder="(20文字まで)" />※半角英数字のみ</td>
    </tr>
    <tr>
        <td align="right">パスワード：</td>
    <td><input type = "password" name="password" size="20" maxlength="20" placeholder="(20文字まで)"/>※半角英数字のみ</td>
    </tr>
    <tr>
        <td><input type="submit" value="登録" /></td>
    </tr>
    <tr>
        <td><input type='button' onclick="location.href='http://192.168.33.10/workspace/BBS1/index.php'" value='戻る'></td>
    </tr>
    </table>
<p>{$err_msg1}</p>
<p>{$err_msg2}</p>
<p>{$err_msg_id}</p>
<p>{$signup_msg}</p>

</body>
</html>