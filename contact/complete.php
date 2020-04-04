<?php
//セッションを開始
session_start(); 
//エスケープ処理やデータをチェックする関数を記述したファイルの読み込み
require '../libs/functions.php'; 

//POSTされたデータをチェック, URLの直たたき対策
$_POST = checkInput( $_POST );
//固定トークンを確認（CSRF対策）
if ( isset( $_POST[ 'ticket' ], $_SESSION[ 'ticket' ] ) ) {
  $ticket = $_POST[ 'ticket' ];
  if ( $ticket !== $_SESSION[ 'ticket' ] ) {
    //トークンが一致しない場合は処理を中止
    die( 'Access Denied!' );
  }
} else {
  //トークンが存在しない場合は処理を中止（直接このページにアクセスするとエラーになる）
  $_SERVER['HTTP_HOST'];
  dirname($_SERVER['PHP_SELF']);
  header('Location: /contact_form/contact/confirm.php');
  exit();
}

//メールアドレス等を記述したファイルの読み込み
require '../libs/mailvars.php'; 
 
//お問い合わせ日時を日本時間に
date_default_timezone_set('Asia/Tokyo'); 
 
//POSTされたデータをチェック
$_POST = checkInput( $_POST );
//固定トークンを確認（CSRF対策）
if ( isset( $_POST[ 'ticket' ], $_SESSION[ 'ticket' ] ) ) {
  $ticket = $_POST[ 'ticket' ];
  if ( $ticket !== $_SESSION[ 'ticket' ] ) {
    //トークンが一致しない場合は処理を中止
    die( 'Access denied' );
  }
} else {
  //トークンが存在しない場合（入力ページにリダイレクト）
  //die( 'Access Denied（直接このページにはアクセスできません）' );  //処理を中止する場合
  $dirname = dirname( $_SERVER[ 'SCRIPT_NAME' ] );
  $dirname = $dirname == DIRECTORY_SEPARATOR ? '' : $dirname;
  $url = ( empty( $_SERVER[ 'HTTPS' ] ) ? 'http://' : 'https://' ) . $_SERVER[ 'SERVER_NAME' ] . $dirname . '/contact.php';
  header( 'HTTP/1.1 303 See Other' );
  header( 'location: ' . $url );
  exit; 
}
 
//変数にエスケープ処理したセッション変数の値を代入
$name1 = h( $_SESSION[ 'name1' ] );
$name2 = h( $_SESSION[ 'name2' ] );
$name3 = h( $_SESSION[ 'name3' ] );
$name4 = h( $_SESSION[ 'name4' ] );
$email = h( $_SESSION[ 'email' ] ) ;
$tel =  h( $_SESSION[ 'tel' ] ) ;
$subject = h( $_SESSION[ 'subject' ] );
$body = h( $_SESSION[ 'body' ] );
 
//メール本文の組み立て
$mail_body = 'コンタクトページからのお問い合わせ' . "\n\n";
$mail_body .=  date("Y年m月d日 H時i分") . "\n\n"; 
$mail_body .=  "お名前/性： " .$name1 . "\n";
$mail_body .=  "お名前/名： " .$name3 . "\n";
$mail_body .=  "Email： " . $email . "\n"  ;
$mail_body .=  "お電話番号： " . $tel . "\n\n" ;
$mail_body .=  "＜お問い合わせ内容＞" . "\n" . $body;
  
//-------- sendmail（mb_send_mail）を使ったメールの送信処理------------
 
//メールの宛先（名前<メールアドレス> の形式）。値は mailvars.php に記載
$mailTo = mb_encode_mimeheader(MAIL_TO_NAME) ."<" . MAIL_TO. ">";
 
//Return-Pathに指定するメールアドレス
$returnMail = MAIL_RETURN_PATH; //
//mbstringの日本語設定
mb_language( 'ja' );
mb_internal_encoding( 'UTF-8' );
 
// 送信者情報（From ヘッダー）の設定
$header = "From: " . mb_encode_mimeheader($name) ."<" . $email. ">\n";
$header .= "Cc: " . mb_encode_mimeheader(MAIL_CC_NAME) ."<" . MAIL_CC.">\n";
$header .= "Bcc: <" . MAIL_BCC.">";
 
//メールの送信（結果を変数 $result に格納）
if ( ini_get( 'safe_mode' ) ) {
  //セーフモードがOnの場合は第5引数が使えない
  $result = mb_send_mail( $mailTo, $subject, $mail_body, $header );
} else {
  $result = mb_send_mail( $mailTo, $subject, $mail_body, $header, '-f' . $returnMail );
}
 
//メール送信の結果判定
if ( $result ) {
  //成功した場合はセッションを破棄
  $_SESSION = array(); //空の配列を代入し、すべてのセッション変数を消去 
  session_destroy(); //セッションを破棄
} else {
  //送信失敗時（もしあれば）
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>コンタクトフォーム（完了）</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link href="../style.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <h2>お問い合わせフォーム</h2>
  <?php if ( $result ): ?>
  <h3>送信完了!</h3>
  <p>お問い合わせいただきありがとうございます。</p>
  <p>送信完了いたしました。</p>

    <div class="table-responsive confirm_table">
    <table class="table table-bordered">
      <caption>送信内容</caption>

      <tr>
        <th>お名前/性</th>
        <td><?php var_dump($name1); ?></td>
      </tr>
      
      <tr>
        <th>お名前/セイ</th>
        <td><?php var_dump($name2); ?></td>
      </tr>      
      
      <th>お名前/名</th>
        <td><?php var_dump($name3); ?></td>
      </tr>      

      <th>お名前/メイ</th>
        <td><?php var_dump($name4); ?></td>
      </tr>

      <tr>
        <th>Email</th>
        <td><?php var_dump($email); ?></td>
      </tr>

      <tr>
        <th>お電話番号</th>
        <td><?php var_dump($tel); ?></td>
      </tr>

      <tr>
        <th>件名</th>
        <td><?php var_dump($subject); ?></td>
      </tr>

      <tr>
        <th>お問い合わせ内容</th>
        <td><?php var_dump(h($body)); ?></td>
      </tr>

    </table>
  </div>

  <?php else: ?>
  <p>申し訳ございませんが、送信に失敗しました。</p>
  <p>しばらくしてもう一度お試しになるか、メールにてご連絡ください。</p>
  <p>ご迷惑をおかけして誠に申し訳ございません。</p>
  <?php endif; ?>
</div>
</body>
</html>