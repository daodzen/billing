<?php 
//Блок подключения к БД (подключение к таблице Subsys)
require_once 'login.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);
//Настройка кодировки utf8
mysql_query("SET NAMES 'utf8_general_ci'");
mysql_query("SET CHARACTER SET 'utf8_general_ci'");
//Подключение к таблице
mysql_select_db($db_database, $db_server);

//Блок интерфейса
echo '<html><head><title>Редактирование данных</title></head>' . '<body>';
echo  '<center>' . '<p>Редактирование пользовательской карточки</p>' . '</center>';

echo '<form action="EditData.php" method="post">';

echo 'Номер счета <br>';
echo '<input type="text" name="HSchn"> <br> <br>';

echo 'Фамилия <br>';
echo '<input type="text" name="HFam" > <br> <br>';

echo 'Имя <br>';
echo '<input type="text" name="HIm"> <br> <br>';

echo 'Отчество <br>';
echo '<input type="text" name="HOtch"> <br> <br>';

echo 'Предыдущие показания <br>';
echo '<input type="text" name="HPPok"> <br> <br>';

echo 'Текущие показания <br>';
echo '<input type="text" name="HTPok"> <br> <br>';

echo '<input type="submit" name="HSchn" value="Записать">';
echo '</body></html>';

//Извлекаем значение переменной из основного окна в переменную (JDEditNum)
echo '<script type="text/javascript">
JDEditNum = window.dialogArguments;
document.write(JDEditNum);

</script>';

echo '<form action="main.php" method="post">';


//if(isset($_POST['PEditNum']))
//{
//echo $PEditNum;
//}

?>