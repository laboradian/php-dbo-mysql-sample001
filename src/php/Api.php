<?php
namespace App;

class Api
{
    private $dbh;

    public function __construct()
    {
        $this->dbh = $this->createPdoObject();
    }

    private function createPdoObject()
    {
        $options = array(
            // 静的プレースホルダを指定
            \PDO::ATTR_EMULATE_PREPARES => false,
            // エラー発生時に例外を投げる
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        );

        // 複文を禁止する (この定数は、PHPのバージョン 5.5.21 および PHP 5.6.5 以上で使用できる)
        if (defined('PDO::MYSQL_ATTR_MULTI_STATEMENTS')) {
            $options[\PDO::MYSQL_ATTR_MULTI_STATEMENTS] = false;
        }

        // charset は set names でセットせず、この第1引数で指定すること(PHP5.3.6以降で可能)
        return new \PDO(
            getenv('DB_CONNECTION') . ':host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_DATABASE') . ';charset=utf8',
            getenv('DB_USERNAME'),
            getenv('DB_PASSWORD'),
            $options
        );
    }

    public function addRecords()
    {
        // 一旦削除する
        $this->clearRecords();

        //---------------
        // 追加操作
        //---------------

        $stmt = $this->dbh->prepare("INSERT INTO fruit (name, colour, calories) VALUES (:name, :colour, :calories)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':colour', $colour);
        $stmt->bindParam(':calories', $calories);

        // 1つ目のレコード
        $name = 'banana';
        $colour = 'yellow';
        $calories = 150;
        $stmt->execute();

        // 2つ目のレコード
        $name = 'apple';
        $colour = 'red';
        $calories = 120;
        $stmt->execute();

        // 3つ目のレコード
        $name = 'orange';
        $colour = 'orange';
        $calories = 130;
        $stmt->execute();
    }

    public function getRecords()
    {
        $arrRowObj = [];
        $sth = $this->dbh->prepare("SELECT name, colour, calories FROM fruit");
        $sth->execute();
        while ($row = $sth->fetchObject()) {
            array_push($arrRowObj, $row);
        }
        return $arrRowObj;
    }

    public function clearRecords()
    {
        $sth = $this->dbh->prepare("DELETE FROM fruit");
        return $sth->execute();
    }
}
