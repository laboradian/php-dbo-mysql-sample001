<?php
require __DIR__ . '/../vendor/autoload.php';

function e($str) {
    return htmlspecialchars($str, ENT_QUOTES);
}

$errorMessage = '';
$output = '';

$dotenv = new Dotenv\Dotenv(__DIR__ . '/..');
$dotenv->load();

try {

    $apiObj = new \App\Api();

    if (isset($_GET['action-type'])) {
        switch ($_GET['action-type']) {
        case 'add':
            $apiObj->addRecords();
            header('Content-type: application/json');
            echo json_encode([]);
            exit;
            break;
        case 'get':
            $arrRowObj = $apiObj->getRecords();
            header('Content-type: application/json');
            echo json_encode([ 'records' => $arrRowObj ]);
            exit;
            break;
        case 'clear':
            $res = $apiObj->clearRecords();
            header('Content-type: application/json');
            echo json_encode($res);
            exit;
            break;
        default:
            //
        }
    }

} catch (PDOException $e) {
    $errorMessage = $e->getMessage();
    //$errorMessage = 'Error!';
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge"  >
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--  Font Awesome の CDN  -->
  <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css">
  <link rel="stylesheet" href="css/main.css">
  <title>PHP のPDO (MySQL) を使うサンプル001</title>
</head>

<body>
  <header>
    <h1>PHP のPDO (MySQL) を使うサンプル001</h1>
  </header>

  <div class="well">
    <p>PHPの <a href="http://php.net/manual/ja/book.pdo.php">PDO</a> を使ってMySQLのレコードを操作するサンプルです。</p>


    <p>サーバー上には以下のテーブルが作成してあります。</p>
    <pre><code>CREATE TABLE `fruit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) DEFAULT NULL,
  `colour` varchar(32) DEFAULT NULL,
  `calories` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
</code></pre>
  </div>

<?php if ($errorMessage != ''): ?>
    <div class="alert alert-danger" role="alert"><?php echo e($errorMessage); ?></div>
<?php endif; ?>

  <div class="panel panel-success">
    <div class="panel-heading">動作サンプル</div>
    <div class="panel-body mainContent">
      <h2>操作</h2>
      <div class="mainContent__buttons">
        <section class="sectControll">
          <section>
            <button type="button" id="btnAddRecords">レコードを作成する</button>
            <output id="outputAddRecords"></output>
          </section>

          <section>
            <button type="button" id="btnClearRecords">レコードを全て削除する</button>
            <output id="outputClearRecords"></output>
          </section>
        </section>

        <section>
          <button type="button" id="btnGetRecords">レコードを取得して表示する</button>
          <output id="outputGetRecords"></output>
        </section>
      </div>

      <section class="mainContent__description">
        <h2>説明</h2>
        <ul>
          <li>[レコードを作成する]ボタン → [レコードを取得して表示する]ボタンの順番で実行すると、作成したレコードが表示されます。</li>
          <li>[レコードを全て削除する]ボタン → [レコードを取得して表示する]ボタンの順番で実行すると、レコードが表示されなくなります。</li>
        </ul>
      </section>

    </div>
  </div>

  <div class="panel panel-success">
    <div class="panel-heading">コード</div>
    <div class="panel-body codeSample">
      <h2>DBへの接続</h2>
      <pre><code>$options = array(
  // 静的プレースホルダを指定
  PDO::ATTR_EMULATE_PREPARES =&gt; false,
  // エラー発生時に例外を投げる
  PDO::ATTR_ERRMODE =&gt; PDO::ERRMODE_EXCEPTION
);
// 複文を禁止する (この定数は、PHPのバージョン 5.5.21 および PHP 5.6.5 以上で使用できる)
if (defined('PDO::MYSQL_ATTR_MULTI_STATEMENTS')) {
  $options[PDO::MYSQL_ATTR_MULTI_STATEMENTS] = false;
}

// charset は set names でセットせず、この第1引数で指定すること(PHP5.3.6以降で可能)
$dbh = new PDO('mysql:host=localhost;dbname=foo;charset=utf8',
        'username', 'password', $options);
</code></pre>

      <h2>レコードを作成する</h2>
      <pre><code>$stmt = $dbh-&gt;prepare("INSERT INTO fruit (name, colour, calories) VALUES (:name, :colour, :calories)");
$stmt-&gt;bindParam(':name', $name);
$stmt-&gt;bindParam(':colour', $colour);
$stmt-&gt;bindParam(':calories', $calories);

// 1つ目のレコード
$name = 'banana';
$colour = 'yellow';
$calories = 150;
$stmt-&gt;execute();

// 2つ目のレコード
$name = 'apple';
$colour = 'red';
$calories = 120;
$stmt-&gt;execute();

// 3つ目のレコード
$name = 'orange';
$colour = 'orange';
$calories = 130;
$stmt-&gt;execute();
</code></pre>

      <h2>レコードを全て削除する</h2>
      <pre><code>$sth = $dbh-&gt;prepare("DELETE FROM fruit");
$sth-&gt;execute();
</code></pre>

      <h2>レコードを取得する</h2>
      <pre><code>$arrRowObj = [];
$sth = $dbh-&gt;prepare("SELECT name, colour, calories FROM fruit");
$sth-&gt;execute();
while ($row = $sth-&gt;fetchObject()) {
    array_push($arrRowObj, $row);
}
</code></pre>

    </div>
  </div>

  <div class="well refContent">
    <h2>参考</h2>
    <ul>
        <li><a href="http://php.net/manual/ja/book.pdo.php">PHP: PDO - Manual</a></li>
        <li><a href="https://laboradian.com/sec-php/#3_SQL">PHPのセキュリティ対策 – ラボラジアン</a></li>
    </ul>

  </div>

  <hr>
  <footer>© 2017 <a href="http://laboradian.com/">Laboradian</a></footer>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
  <script>hljs.initHighlightingOnLoad();</script>
  <script src="js/main.js" charset="utf-8"></script>
</body>
</html>
