PHP のPDO (MySQL) を使うサンプル001
===================


以下のテーブルが用意されていることを前提とする。

```
CREATE TABLE `fruit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `colour` varchar(32) DEFAULT NULL,
  `calories` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

```


参考
-------

- [PHP: PDO - Manual](http://php.net/manual/ja/book.pdo.php)
- [PHPのセキュリティ対策 – ラボラジアン](https://laboradian.com/sec-php/#3_SQL)

