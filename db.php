<?
$db = @new PDO("mysql:host=localhost;dbname=db1;charset=UTF8","root","12345");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>