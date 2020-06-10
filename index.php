<html>
<head>
    <title>掲示板</title>
</head>
<body>

<h1>掲示板App</h1>

<h2>投稿フォーム</h2>

<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
    <input type="text" name="personal_name" placeholder="名前" required><br><br>    
    <textarea name="contents" rows="8" cols="40" placeholder="内容" required>
</textarea><br><br>
    <input type="submit" name="btn" value="投稿する">
</form>

<h2>スレッド</h2>

<?php

const THREAD_FILE = 'thread.txt';      //THREAD_FILEに代入

function readData() {                  //thread.txtに書き込む
    // ファイルが存在しなければデフォルト空文字のファイルを作成する
    if (! file_exists(THREAD_FILE)) {
        $fp = fopen(THREAD_FILE, 'w'); //ファイルを開く
        fwrite($fp, '');               //ファイルに書き込む
        fclose($fp);                   //ファイルを閉じる
    }

    $thread_text = file_get_contents(THREAD_FILE);
    echo $thread_text;                 //thread_textを表示する
}

function writeData() {
    $personal_name = $_POST['personal_name'];           //投稿者の名前を代入
    $contents = $_POST['contents'];                     //内容を代入
    $contents = nl2br($contents);

    //書き込んだ際のテンプレート
    $data = "<hr>\n";
    $data = $data."<p>投稿者:".$personal_name."</p>\n"; //投稿者の名前を表示
    $data = $data."<p>内容:</p>\n";                     //前置きの表示
    $data = $data."<p>".$contents."</p>\n";             //書き込まれた内容の表示

    $fp = fopen(THREAD_FILE, 'a');                      //ファイルを開く

    //LOCK_SH	共有ロック
    //LOCK_EX	排他的ロック
    //LOCK_UN	ロック解除
    //LOCK_NB	ロック中にflock()でブロックさせない

    if ($fp){
        if (flock($fp, LOCK_EX)){                   //排他的ロック失敗した場合
            if (fwrite($fp,  $data) === FALSE){         //ファイル書き込みに失敗した場合
                print('ファイル書き込みに失敗しました');
            }

            flock($fp, LOCK_UN);
        }else{                                       //排他的ロック失敗した場合
            print('ファイルロックに失敗しました');
        }
    }

    fclose($fp);                                        //ファイルを閉じる

    // ブラウザのリロード対策
    $redirect_url = $_SERVER['HTTP_REFERER'];
    header("Location: $redirect_url");
    exit;
    //
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    writeData();

}

readData();

?>

</body>
</html>