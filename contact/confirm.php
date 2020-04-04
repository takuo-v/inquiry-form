<?php
//セッションを開始
session_start();

//エスケープ処理やデータチェックを行う関数のファイルの読み込み
require '../libs/functions.php';

//POSTされたデータをチェック　URLの直たたき対策
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
  header('Location: /contact_form/contact/contact.php');
  exit();
}

//POSTされたデータを変数に格納
$name1 = isset( $_POST[ 'name1' ] ) ? $_POST[ 'name1' ] : NULL;
$name2 = isset( $_POST[ 'name2' ] ) ? $_POST[ 'name2' ] : NULL;
$name3 = isset( $_POST[ 'name3' ] ) ? $_POST[ 'name3' ] : NULL;
$name4 = isset( $_POST[ 'name4' ] ) ? $_POST[ 'name4' ] : NULL;
$email = isset( $_POST[ 'email' ] ) ? $_POST[ 'email' ] : NULL;
$email_check = isset( $_POST[ 'email_check' ] ) ? $_POST[ 'email_check' ] : NULL;
$tel = isset( $_POST[ 'tel' ] ) ? $_POST[ 'tel' ] : NULL;
$subject = isset( $_POST[ 'subject' ] ) ? $_POST[ 'subject' ] : NULL;
$body = isset( $_POST[ 'body' ] ) ? $_POST[ 'body' ] : NULL;
//POSTされたデータを整形（前後にあるホワイトスペースを削除）
$name1 = trim( $name1 );
$name2 = trim( $name2 );
$name3 = trim( $name3 );
$name4 = trim( $name4 );
$email = trim( $email );
$email_check = trim( $email_check );
$tel = trim( $tel );
$subject = trim( $subject );
$body = trim( $body );

//エラーメッセージを保存する配列の初期化
$error = array();
//値の検証（入力内容が条件を満たさない場合はエラーメッセージを配列 $error に設定）

if ( $name1 == '' ) {
  $error[ 'name1' ] = '*お名前は必須項目です。';
  //制御文字でないことと文字数をチェック
} else if ( preg_match( '/\A[[:^cntrl:]]{1,30}\z/u', $name1 ) == 0 ) {
  $error[ 'name1' ] = '*お名前は30文字以内でお願いします。';
}

if ( $name2 == '' ) {
  $error[ 'name2' ] = '*お名前/セイ は必須項目です。';
  //制御文字でないことと文字数をチェック
} else if ( preg_match('/[^a-zA-Z0-9ぁ-ん一-龠]{1,30}+$/u', $name2 ) == 0 ) {
  $error[ 'name2' ] = '*30文字以内のカタカナでお願いします。';
}

if ( $name3 == '' ) {
  $error[ 'name3' ] = '*お名前は必須項目です。';
  //制御文字でないことと文字数をチェック
} else if ( preg_match( '/\A[[:^cntrl:]]{1,30}\z/u', $name3 ) == 0 ) {
  $error[ 'name3' ] = '*お名前は30文字以内でお願いします。';
}

if ( $name4 == '' ) {
  $error[ 'name4' ] = '*お名前/メイ は必須項目です。';
  //制御文字でないことと文字数をチェック
} else if ( preg_match( '/[^a-zA-Z0-9ぁ-ん一-龠]{1,30}+$/u', $name4 ) == 0 ) {
  $error[ 'name4' ] = '*30文字以内のカタカナでお願いします。';
}

if ( $email == '' ) {
  $error[ 'email' ] = '*メールアドレスは必須です。';
} else { //メールアドレスを正規表現でチェック
  $pattern = '/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/uiD';
  if ( !preg_match( $pattern, $email ) ) {
    $error[ 'email' ] = '*メールアドレスの形式が正しくありません。';
  }
}

if ( $email_check == '' ) {
  $error[ 'email_check' ] = '*確認用メールアドレスは必須です。';
} else { //メールアドレスを正規表現でチェック
  if ( $email_check !== $email ) {
    $error[ 'email_check' ] = '*メールアドレスが一致しません。';
  }
}

if ( preg_match( '/^[0-9]{2,3}[0-9]{2,4}[0-9]{3,4}$/', $tel ) == 0 ) {
  $error[ 'tel' ] = '*電話番号は9~11桁のハイフン無しでお願いします。';
}

if ( $tel != '' && preg_match( '/\A\(?\d{2,5}\)?[-(\.\s]{0,2}\d{1,4}[-)\.\s]{0,2}\d{3,4}\z/u', $tel ) == 0 ) {
  $error[ 'tel_format' ] = '*電話番号の形式が正しくありません。';
}

if ( $subject == '' ) {
  $error[ 'subject' ] = '*件名は必須項目です。';
  //制御文字でないことと文字数をチェック
} else if ( preg_match( '/\A[[:^cntrl:]]{1,100}\z/u', $subject ) == 0 ) {
  $error[ 'subject' ] = '*件名は100文字以内でお願いします。';
}

if ( preg_match( '/\A[\r\n\t[:^cntrl:]]{0,1050}\z/u', $body ) == 0 ) {
  $error['body'] = '*内容は1000文字以内でお願いします。';
}

//POSTされたデータとエラーの配列をセッション変数に保存
$_SESSION[ 'name1' ] = $name1;
$_SESSION[ 'name2' ] = $name2;
$_SESSION[ 'name3' ] = $name3;
$_SESSION[ 'name4' ] = $name4;
$_SESSION[ 'email' ] = $email;
$_SESSION[ 'email_check' ] = $email_check;
$_SESSION[ 'tel' ] = $tel;
$_SESSION[ 'subject' ] = $subject;
$_SESSION[ 'body' ] = $body;
$_SESSION[ 'error' ] = $error;

//チェックの結果にエラーがある場合は入力フォームに戻す
if ( count( $error ) > 0 ) {
  //エラーがある場合
  $dirname = dirname( $_SERVER[ 'SCRIPT_NAME' ] );
  $dirname = $dirname == DIRECTORY_SEPARATOR ? '' : $dirname;
  $url = ( empty( $_SERVER[ 'HTTPS' ] ) ? 'http://' : 'https://' ) . $_SERVER[ 'SERVER_NAME' ] . $dirname . '/contact.php';
  header( 'HTTP/1.1 303 See Other' );
  header( "location: /contact_form/contact/contact.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>コンタクトフォーム（確認）</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<link href="../style.css" rel="stylesheet">
</head>
</head>
<body>

<div class="container">
  <h2>お問い合わせ確認画面</h2>
  <p>以下の内容でよろしければ「送信する」をクリックしてください。<br>
    内容を変更する場合は「戻る」をクリックして入力画面にお戻りください。</p>

  <div class="table-responsive confirm_table">
    <table class="table table-bordered">
      <caption>ご入力内容</caption>

      <tr>
        <th>お名前/性</th>
        <td><?php echo h($name1); ?></td>
      </tr>

      <tr>
        <th>お名前/セイ</th>
        <td><?php echo h($name2); ?></td>
      </tr>
      
      <tr>
        <th>お名前/名</th>
        <td><?php echo h($name3); ?></td>
      </tr>      

      <tr>
        <th>お名前/メイ</th>
        <td><?php echo h($name4); ?></td>
      </tr>

      <tr>
        <th>Email</th>
        <td><?php echo h($email); ?></td>
      </tr>

      <tr>
        <th>お電話番号</th>
        <td><?php echo h($tel); ?></td>
      </tr>

      <tr>
        <th>件名</th>
        <td><?php echo h($subject); ?></td>
      </tr>

      <tr>
        <th>お問い合わせ内容</th>
        <td><?php echo nl2br(h($body)); ?></td>
      </tr>
    </table>

  </div>

  <form action="contact.php" method="post" class="confirm">
    <button type="submit" class="btn btn-secondary">戻る</button>
  </form>

  <form action="complete.php" method="post" class="confirm">
    <!-- 完了ページへ渡すトークンの隠しフィールド -->
    <input type="hidden" name="ticket" value="<?php echo h($ticket); ?>">
    <button type="submit" class="btn btn-success">送信する</button>
  </form>

</div>
</body>
</html>