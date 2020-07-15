<?php

/**
 * 職業実践2 - 掲示板アプリ
 */

require_once './thread.php';
$thread = new Thread("掲示板App");

session_start();

function setToken()
{
    $token = sha1(uniqid(mt_rand(), true));
    $_SESSION['token'] = $token;
}

function checkToken()
{
    if (empty($_SESSION['token'])) {
        echo "Sessionが空です";
        exit;
    }

    if (($_SESSION['token']) !== $_POST['token']) {
        echo "不正な投稿です。";
        exit;
    }

    $_SESSION['token'] = null;
}

if (empty($_SESSION['token'])) {
    setToken();
}
?>

<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link crossorigin="anonymous" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" rel="stylesheet">
    <title>掲示板App</title>
</head>
<body>

<div class="container">
    <div class="col-md-8">
        <h1 class="text-center text-primary py-3">掲示板App</h1>

        <h2 class="text-muted py-3">投稿フォーム</h2>
        <form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
            <input type="text" class="form-control" name="personal_name" placeholder="名前" required><br><br>
            <textarea name="contents" class="form-control" rows="8" cols="40" placeholder="内容" required></textarea>
            <br><br>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            <input class="btn btn-primary"  type="submit" name="btn" value="投稿する">
        </form>

        <hr>

        <h2 class="text-muted py-3">スレッド</h2>

        <form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
            <input type="hidden" name="method" value="DELETE">
            <button class="btn btn-danger" type="submit">投稿を全削除する</button>
        </form>

        <h2 class="text-muted py-3">スレッド</h2>
<?php

const THREAD_FILE = 'thread.txt';

require_once './Thread.php';
$thread = new Thread('掲示板App');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["method"]) && $_POST["method"] === "DELETE") {
        $thread->delete();
    } else {
        checkToken();
        $thread->post($_POST['personal_name'], $_POST['contents']);
    }
}

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    // ブラウザのリロード対策
    $redirect_url = $_SERVER['HTTP_REFERER'];
    header("Location: $redirect_url");
    exit;
}

$list = $thread->getList();
echo $list;

?>

 
<style>

body {             /* 背景のデータ　*/                 
  font-family:'Lato', sans-serif;
  font-size: 21px;
  padding: 20px;
  background: #00ffff;
}

.cool-link {
  margin-right:5px;
  display: inline-block;
}

.cool-link a {
  text-decoration:none;
  transition: 0.25s ease-in-out;
  color: #FFF;
}

.cool-link:hover a{
  color:#FFD166;
}




</style>
</div>
</div>

<!-- JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
</script>
<script type="module" src="main.js"></script>
<script>  alert("Hello World!"); </script>
<script src="javascript.js"></script>
</body>
</html>