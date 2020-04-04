<?php

//セッションを開始
session_start();

//セッションIDを更新して変更（セッションハイジャック対策）
session_regenerate_id( TRUE );

//エスケープ処理やデータチェックを行う関数のファイルの読み込み
require '../libs/functions.php';

//初回以外ですでにセッション変数に値が代入されていれば、その値を。そうでなければNULLで初期化
$name1 = isset( $_SESSION[ 'name1' ] ) ? $_SESSION[ 'name1' ] : NULL;
$name2 = isset( $_SESSION[ 'name2' ] ) ? $_SESSION[ 'name2' ] : NULL;
$name3 = isset( $_SESSION[ 'name3' ] ) ? $_SESSION[ 'name3' ] : NULL;
$name4 = isset( $_SESSION[ 'name4' ] ) ? $_SESSION[ 'name4' ] : NULL;
$email = isset( $_SESSION[ 'email' ] ) ? $_SESSION[ 'email' ] : NULL;
$email_check = isset( $_SESSION[ 'email_check' ] ) ? $_SESSION[ 'email_check' ] : NULL;
$tel = isset( $_SESSION[ 'tel' ] ) ? $_SESSION[ 'tel' ] : NULL;
$subject = isset( $_SESSION[ 'subject' ] ) ? $_SESSION[ 'subject' ] : NULL;
$body = isset( $_SESSION[ 'body' ] ) ? $_SESSION[ 'body' ] : NULL;
$error = isset( $_SESSION[ 'error' ] ) ? $_SESSION[ 'error' ] : NULL;

//個々のエラーを初期化
$error_name1 = isset( $error['name1'] ) ? $error['name1'] : NULL;
$error_name2 = isset( $error['name2'] ) ? $error['name2'] : NULL;
$error_name3 = isset( $error['name3'] ) ? $error['name3'] : NULL;
$error_name4 = isset( $error['name4'] ) ? $error['name4'] : NULL;
$error_email = isset( $error['email'] ) ? $error['email'] : NULL;
$error_email_check = isset( $error['email_check'] ) ? $error['email_check'] : NULL;
$error_tel = isset( $error['tel'] ) ? $error['tel'] : NULL;
$error_tel_format = isset( $error['tel_format'] ) ? $error['tel_format'] : NULL;
$error_subject = isset( $error['subject'] ) ? $error['subject'] : NULL;
$error_body = isset( $error['body'] ) ? $error['body'] : NULL;

//CSRF対策の固定トークンを生成
if ( !isset( $_SESSION[ 'ticket' ] ) ) {
  //セッション変数にトークンを代入
  $_SESSION[ 'ticket' ] = sha1( uniqid( mt_rand(), TRUE ) );
}

//トークンを変数に代入
$ticket = $_SESSION[ 'ticket' ];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>コンタクトフォーム</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link href="../style.css" rel="stylesheet">
</head>
<body>
  
<div class="container">
  <h2>お問い合わせフォーム</h2>
  <p>以下のフォームからお問い合わせください。</p>
  
  <form id="main_contact" method="post" action="confirm.php">

    <div class="form-group">
      <label for="name1">名前/ 性（必須） 
        <span class="error"><?php echo h( $error_name1 ); ?></span>
      </label>
      <input type="text" class="form-control validate max50 required" id="name" name="name1" placeholder="性" value="<?php echo h($name1); ?>">
    </div>

    <div class="form-group">
      <label for="name2">フリガナ/ セイ（必須） 
        <span class="error"><?php echo h( $error_name2 ); ?></span>
      </label>
      <input type="text" class="form-control validate max50 required" id="name" name="name2" placeholder="セイ" value="<?php echo h($name2); ?>">
    </div>    

    <div class="form-group">
      <label for="name3">名前/ 名（必須） 
        <span class="error"><?php echo h( $error_name3 ); ?></span>
      </label>
      <input type="text" class="form-control validate max50 required" id="name" name="name3" placeholder="名" value="<?php echo h($name3); ?>">
    </div>

    <div class="form-group">
      <label for="name4">フリガナ/ メイ（必須） 
        <span class="error"><?php echo h( $error_name4 ); ?></span>
      </label>
      <input type="text" class="form-control validate max50 required" id="name" name="name4" placeholder="メイ" value="<?php echo h($name4); ?>">
    </div>    


    <div class="form-group">
      <label for="email">Email（必須） 
        <span class="error"><?php echo h( $error_email ); ?></span>
      </label>
      <input type="text" class="form-control validate mail required" id="email" name="email" placeholder="Email アドレス" value="<?php echo h($email); ?>">
    </div>

    <div class="form-group">
      <label for="email_check">Email（確認用 必須） 
        <span class="error"><?php echo h( $error_email_check ); ?></span>
      </label>
      <input type="text" class="form-control validate email_check required" id="email_check" name="email_check" placeholder="Email アドレス（確認のためもう一度ご入力ください。）" value="<?php echo h($email_check); ?>">
    </div>

    <div class="form-group">
      <label for="tel">お電話番号（半角英数字 必須） 
        <span class="error"><?php echo h( $error_tel_empty ); ?></span>
        <span class="error"><?php echo h( $error_tel ); ?></span>
        <span class="error"><?php echo h( $error_tel_format ); ?></span>
      </label>
      <input type="text" class="validate max30 tel form-control required" id="tel" name="tel" value="<?php echo h($tel); ?>" placeholder="お電話番号（半角英数字でご入力ください）">
    </div>

    <div class="form-group">
      <label for="subject">件名（必須） 
        <span class="error"><?php echo h( $error_subject ); ?></span> 
      </label>
      <input type="text" class="form-control validate max100 required" id="subject" name="subject" placeholder="件名" value="<?php echo h($subject); ?>">
    </div>

    <div class="form-group">
      <label for="body">お問い合わせ内容 
        <span class="error"><?php echo h( $error_body ); ?></span>
      </label>
      <span id="count"> </span>/1000
      <textarea class="form-control validate max1000 " id="body" name="body" placeholder="お問い合わせ内容（1000文字まで）をお書きください" rows="3"><?php echo h($body); ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary">確認画面へ</button>
    <!--確認ページへトークンをPOSTする、隠しフィールド「ticket」-->
    <input type="hidden" name="ticket" value="<?php echo h($ticket); ?>">

  </form>

</div>
</body>
</html>