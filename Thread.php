<?php

date_default_timezone_set('Asia/Tokyo');

class Thread
{
    private $name;
    private const THREAD_FILE = 'thread.txt';
    private $dbh;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->dbh = new PDO('mysql:dbname=thread;host=127.0.0.1', 'root', 'password');
    }

    public function getList()
    {
        $res = "";
        $sql = "SELECT * FROM `thread` WHERE `deleted_at` IS NULL ORDER BY `created_at` DESC";
        $stmt = $this->dbh->query($sql);
        while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
            $created_at = $row["created_at"];
            $name = $row["name"];
            $contents = $row["content"];
            $res .= "<hr>\n";
            $res .= "<p>投稿日時: ".date("Y/m/d H:i:s", strtotime($created_at))."</p>\n";
            $res .= "<p>投稿者:".$name."</p>\n";
            $res .= "<p>内容:</p>\n";
            $res .= "<p>".nl2br($contents)."</p>\n";
        }

        return $res;
    }

    /**
     * @param string $personal_name
     * @param string $contents
     */
    public function post(string $personal_name, string $contents)
    {
        $sql ="INSERT INTO `thread` (name, content) VALUE (:name, :content)";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':name', $personal_name, PDO::PARAM_STR);
        $stmt->bindParam(':content', $contents, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function delete() {
        $sql = "UPDATE `thread` SET `deleted_at` = NOW()";
        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();
    }
}