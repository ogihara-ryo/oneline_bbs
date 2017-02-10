<?php
// TODO: 第一引数が 'localhost' だと Access denied になる
$link = mysql_connect('127.0.0.1', 'root', '');
if (!$link) {
  die('データベースに接続できません: ' . mysql_error());
}

mysql_select_db('oneline_bbs', $link);

$errors = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validete name
  $name = null;
  if (!isset($_POST['name']) || !strlen($_POST['name'])) {
    $errors['name'] = '名前を入力してください';
  } else if (strlen($_POST['name']) > 40) {
    $errors['name'] = '名前は40文字以内で入力してください';
  } else {
    $name = $_POST['name'];
  }

  // Validate comment
  $comment = null;
  if (!isset($_POST['comment']) || !strlen($_POST['comment'])) {
    $errors['comment'] = 'ひとことを入力してください';
  } else if (strlen($_POST['comment']) > 200) {
    $errors['comment'] = 'ひとことは200文字以内で入力してください';
  } else {
    $comment = $_POST['comment'];
  }

  // Save
  if (count($errors) === 0) {
    $sql = "INSERT INTO `post` (`name`, `comment`, `created_at`) VALUES ('"
      . mysql_real_escape_string($name) . "', '"
      . mysql_real_escape_string($comment) . "', '"
      . date('Y-m-d H:i:s') . "')";
    mysql_query($sql, $link);
  }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <title>ひとこと掲示板</title>
  </head>

  <body>
    <h1>ひとこと掲示板</h1>

    <form action="bbs.php" method="post">
      <?php if (count($errors)): ?>
        <ul class="error_list">
          <?php foreach ($errors as $error): ?>
            <li>
              <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      名前: <input type="text" name="name" /><br />
      ひとこと: <input type="text" name="comment" size="60" /><br />
      <input type="submit" name="submit" value="送信" />
    </form>

    <?php
    $sql = "SELECT * FROM `post` ORDER BY `created_at` DESC";
    $result = mysql_query($sql, $link);
    ?>
  </body>
</html>
