<html>
  <head>
    <title>編集・削除</title>
  </head>
  <body>
  <center><h3>編集・削除</h3></center>
  
    {if $edit_message=="4"}
        <p>数字を入力してください</p>
    {else if $edit_message=="3"}
        <p>投稿番号は1000までです</p>
    {else if $edit_message=="2"}
        <p>編集・削除できるのは自分の投稿だけです</p>
    {else if $edit_message=="1"}
        <p>投稿番号を入力してください</p>
    {else if $edit_message == "0"}
    
        {foreach $items as $itemdata}
        <p>{$itemdata.number|escape:'html'}&nbsp; 名前：{$itemdata.name|escape:'html'}</p>
        <p>{$itemdata.text|escape:'html'|nl2br}</p>
        {/foreach}
        <br/>
    <center>
      <form method="POST" action="edit_delete.php">
        <p>{$edited_msg}</p>
        <input type="hidden" name="text_number" value="{$itemdata.number|escape:'html'}" />
        <input type ="text" name="name" size="20" maxlength="20" placeholder=" 名前(20文字まで)" />{$error_msg_edit1}
        <br />
        <textarea name="text" rows="4" cols="40" maxlength="140" placeholder=" 本文(140文字まで)" ></textarea>{$error_msg_edit2}
        <br />
        <input type="submit" name="edit" value="編集" />
        <br/>
        <br/>
        <input type="submit" name="delete" value="この投稿を削除" />
        </form>
        <input type="button" onclick="location.href='http://192.168.33.10/workspace/BBS1/index.php'" value='戻る'/>
    </center>
    {/if}
  </body>
</html>