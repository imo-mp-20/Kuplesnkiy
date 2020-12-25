<!doctype html>
<html lang="ru">
<head>
<link href="main.css" rel="stylesheet">
<meta charset="utf-8">
<title>Реализация бизнес процессов</title>
</head>
<body>
  <?php
require_once 'connection.php'; // подключаем скрипт
// подключаемся к серверу
$link = mysqli_connect($host, $user, $password, $database) 
        or die("Ошибка " . mysqli_error($link)); 

    //Если переменная Name передана
    if (isset($_POST["Name"])) {
      //Если это запрос на обновление, то обновляем
      if (isset($_GET['red_id'])) {
          $sql = mysqli_query($link, "UPDATE `products` SET `Name` = '{$_POST['Name']}',`Kol` = '{$_POST['Kol']}',`Price` = '{$_POST['Price']}', `Artikol` = '{$_POST['Artikol']}' WHERE `ID`={$_GET['red_id']}");
      } else {
          //Иначе вставляем данные, подставляя их в запрос
          $sql = mysqli_query($link, "INSERT INTO `products` (`Name`,`Kol`, `Price`,`Artikol`) VALUES ('{$_POST['Name']}', '{$_POST['Kol']}', '{$_POST['Price']}', '{$_POST['Artikol']}')");
      }

      //Если вставка прошла успешно
      if ($sql) {
        echo '<p>Успешно!</p>';
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }

    if (isset($_GET['del_id'])) { //проверяем, есть ли переменная
      //удаляем строку из таблицы
      $sql = mysqli_query($link, "DELETE FROM `products` WHERE `ID` = {$_GET['del_id']}");
      if ($sql) {
        echo "<p>Товар удален.</p>";
      } else {
        echo '<p>Произошла ошибка: ' . mysqli_error($link) . '</p>';
      }
    }

    //Если передана переменная red_id, то надо обновлять данные. Для начала достанем их из БД
    if (isset($_GET['red_id'])) {
      $sql = mysqli_query($link, "SELECT `ID`, `Name`,`Kol`, `Price`,`Artikol` FROM `products` WHERE `ID`={$_GET['red_id']}");
      $product = mysqli_fetch_array($sql);
    }
  ?>
  <div class="conf">
  <div class="table1">
  <form action="" method="post">
    <table>
      <tr>
        <td>Наименование:</td>
        <td><input type="text" name="Name" value="<?= isset($_GET['red_id']) ? $product['Name'] : ''; ?>"></td>
      </tr>
      <tr>
        <td>Колличество:</td>
        <td><input type="text" name="Kol" size="3" value="<?= isset($_GET['red_id']) ? $product['Kol'] : ''; ?>"> шт.</td>
      </tr>
      <tr>
        <td>Цена:</td>
        <td><input type="text" name="Price" size="3" value="<?= isset($_GET['red_id']) ? $product['Price'] : ''; ?>"> руб.</td>
       </tr> 
      <tr>
        <td>Артикул:</td>
        <td><input type="text" name="Artikol" size="3" value="<?= isset($_GET['red_id']) ? $product['Artikol'] : ''; ?>"></td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" value="OK"></td>
      </tr>
    </table>
  </form>
  </div>
  <div class="table2">
  <table>
    <tr>
      <td>Идентификатор</td>
      <td>Наименование</td>
      <td>Колличество:</td>
      <td>Цена</td>
      <td>Артикул</td>
      <td>Удаление</td>
      <td>Редактирование</td>
    </tr>
    <?php
      $sql = mysqli_query($link, 'SELECT `ID`, `Name`,`Kol`, `Price`,`Artikol` FROM `products`');
      while ($result = mysqli_fetch_array($sql)) {
        echo '<tr>' .
             "<td>{$result['ID']}</td>" .
             "<td>{$result['Name']}</td>" .
             "<td>{$result['Kol']}</td>" .
             "<td>{$result['Price']} ₽</td>" .
             "<td>{$result['Artikol']}</td>" .
             "<td><a href='?del_id={$result['ID']}'>Удалить</a></td>" .
             "<td><a href='?red_id={$result['ID']}'>Изменить</a></td>" .
             '</tr>';
      }
    ?>
  </table>
</div>
</div>
<p><a href="?add=new">Добавить новый товар</a></p>
</body>
</html>