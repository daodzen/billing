<?php 
//1. Блок переменных
//------------------
$ver=0.01;
$koef=1.5;











//2.Подключение к БД
//------------------ 

//Подключение к БД tarif
require_once 'login.php';
$db_server = mysql_connect($db_hostname, $db_username, $db_password);
//Настройка кодировки utf8
mysql_query("SET NAMES 'utf8_general_ci'");
mysql_query("SET CHARACTER SET 'utf8_general_ci'");
//Подключение к таблице
mysql_select_db($db_database, $db_server);










//3. Оболочка Системы
//-------------------

echo '<head>' . '<meta http-equiv="Content-type" content="text/html; charset=utf-8" />';
echo '<html><head><title>Система билллинга Aishat ver: ' . $ver . '</title>' . '</head>';
echo '<body>' . '<p>Система билллинга Aishat ver: ' . $ver . '</p><hr>';

//3.1 Подблок ввода новых данных
echo '<center><h2>Ввод данных</h2></center>';
echo '<center><table border=0 width=95%><tr>';
echo '<td>Номер счета</td><td>Фамилия</td><td>Имя</td><td>Отчество</td><td>Предыдущие показания</td><td>Текущие показания</td><td>Записать в БД</td></tr>';
echo '<form action="main.php" method="post">';
echo '<td><input type="text" name="HSchn"></td>' . 
'<td><input type="text" name="HFam"></td>' . 
'<td><input type="text" name="HIm"></td>' . 
'<td><input type="text" name="HOtch"></td>' .
'<td><input type="text" name="HPPok"></td>' .
'<td><input type="text" name="HTPok"></td>' .
'<td><input type="submit" name="addData" value="Создать запись"></td></tr>';
echo '</tr></table></center>';
echo '</form>';


//3.2 Подблок критериев поиска информации
//echo '<center><h2>Поиск</h2></center>';

//3.3 Подблок отображения результатов
echo '<center><h2>Результат</h2></center>';
echo '<center><table border=0 width=95%><tr style="vertical-align:top; height:30px;">';
echo '<td><b>Номер счета</b></td><td><b>Фамилия</b></td><td><b>Имя</b></td><td><b>Отчество</b></td><td><b>Предыдущие показания</b></td><td><b>Текущие показания</b></td><td><b>Сумма</b></td><td><b>Действия</b></td></tr>';

$query = "SELECT * FROM tariff";
$result = mysql_query($query);
$rows = mysql_num_rows($result);

for ($j=0; $j<$rows; ++$j)
	{
	echo '<tr style="vertical-align:top; height:30px;"><td>' . mysql_result($result,$j,'Schn') . '</td>' . ' ';
	echo '<td>' . mysql_result($result,$j,'Fam').'</td>';  
	echo '<td>' . mysql_result($result,$j,'Im').'</td>';
	echo '<td>' . mysql_result($result,$j,'Otch').'</td>';
	echo '<td>' . mysql_result($result,$j,'PPok').'</td>';
	echo '<td>' . mysql_result($result,$j,'TPok').'</td>';
	echo '<td>' . (mysql_result($result,$j,'TPok')- mysql_result($result,$j,'PPok')) * $koef . ' руб.</td>';
	
//Прорисовка кнопок редактировать и удалить с сохранением в hidden объекте начения переменной j
	echo '<td>' . '<form action="main.php" method="post">' . 
	'<input type="submit" name="editData" value="..." />' . 
	'<input type="hidden" name="PEditNum" value="' . $j . '"> '.
	
	'<input type="submit" name="delData" value="х" />' . 
	'<input type="hidden" name="Num" value="' . $j . '">' . '
	
	</form></td></tr>';

	}
echo '</tr></table></center>';
echo '</body></html>';
//Конец Блока оболочки









// 4 Блок обработки событий
//------------------------- 

//Кнопка добавить данные
if (isset($_POST['addData']))
{
  $HSchn=$_POST['HSchn'];
  $HFam=$_POST['HFam'];
  $HIm=$_POST['HIm'];
  $HOtch=$_POST['HOtch'];
  $HPPok=$_POST['HPPok'];
  $HTPok=$_POST['HTPok'];
  $res=mysql_query("SELECT Schn FROM `tariff` WHERE Schn='$HSchn'");
  $myrow = mysql_fetch_array($res);
  if (empty($myrow['Schn']))
  {
    $query="INSERT INTO `tariff` (Schn, Fam, Im, Otch, PPok, TPok) VALUES 
    ('$HSchn', '$HFam', '$HIm', '$HOtch', '$HPPok', '$HTPok')";
     $q = mysql_query($query);
  }
}	

// Кнопка удалить данные
if(isset($_POST['delData']))
{
  $Num=$_POST['Num'];
  $DSchn=mysql_result($result,$Num,'Schn');
  $dquery = "DELETE FROM `tariff` WHERE `Schn`='$DSchn'";
  $q=mysql_query($dquery);
}



//Кнопка редактировать данные
if(isset($_POST['editData']))
{
//1 Сохранение значения перемнной PEditNum в таблице subsys, поле SEditNum
//$query2 = "SELECT * FROM subsys";
//$result2 = mysql_query($query2);
//$rows = mysql_num_rows($result);
//echo '<form action="main.php" method="post">';
//$PEditNum=$_POST['PEditNum'];
//$query2="INSRT INTO 'subsys' (SEditNum) VALUES ('PEditNum')";


echo '<form action="main.php" method="post">';

$PEditNum=$_POST['PEditNum'];
#echo $PEditNum;
//Теперь в переменной PEditNum содержится чило соответствующее записи в Бд напротив которого бвла нажата кнопка ...	

//Присвоение JS переменной значения переменной PHP речь об PEditNum и JEditNum
//Вызов модального окна editData.php

echo '<script type="text/javascript">
var JEditNum =' . $PEditNum . ';
document.write(JEditNum);
showModalDialog("editData.php", JEditNum, "dialogWidth=350px, dialogHeight=200px");
</script>';

//  echo '<form action="main.php" method="post">';
//  echo '<td><input type="text" name="ESchn" value="' . mysql_result($result,$PEditNum,'Schn') . '"></td>' . 
//  '<td><input type="text" name="EFam" value="' . mysql_result($result,$PEditNum,'Fam') . '"></td>' . 
//  '<td><input type="text" name="EIm" value="'. mysql_result($result,$PEditNum,'Im') . '"></td>' . 
//  '<td><input type="text" name="EOtch" value="' . mysql_result($result,$PEditNum,'Otch') . '"></td>' .
//  '<td><input type="text" name="EPPok" value="' . mysql_result($result,$PEditNum,'PPok') . '"></td>' .
//  '<td><input type="text" name="ETPok" value="' . mysql_result($result,$PEditNum,'TPok') . '"></td>' .
//  '<td><input type="submit" name="editData1" value="Отредактировать запись"></td><input type="hidden" name="Num2" value="' . $PEditNum . '"></tr>';
  
//  echo '</tr></table></center>';
echo '</form>';


}
//if(isset($_POST['editData1']))

//{
//  $PEditNum=$_POST['Num2'];
//  $ESchn=$_POST['ESchn'];
//  $EFam=$_POST['EFam'];
//  $EIm=$_POST['EIm'];
//  $EOtch=$_POST['EOtch'];
//  $EPPok=$_POST['EPPok'];
//  $ETPok=$_POST['ETPok'];
//  $qt=mysql_result($result,$PEditNum,'Schn');
//  $q="UPDATE `tariff` SET `Schn`= '$ESchn', `Fam`='$EFam', `Im`='$EIm', `Otch`='$EOtch', `PPok`='$EPPok', `TPok`='$ETPok' WHERE `Schn`='$qt'";
//  $query = mysql_query ($q);
//}
mysql_close($db_server);
?>