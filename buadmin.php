<?
session_start();

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){

$host = $_SERVER['HTTP_HOST'];
//print('$host: '.$host.'<br />');
$currenturl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$_SESSION['currenturl'] = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$actpage = 'buadmin.php';
if($_SERVER['QUERY_STRING'] != ''){ $actpage = 'buadmin.php?'.$_SERVER['QUERY_STRING']; }
//print('$currenturl: '.$currenturl.'<br />');
//print('SESSION currenturl: '.$_SESSION['currenturl'].'<br />');
//print('$actpage: '.$actpage.'<br />');
$suid = $_SESSION['uid'];

try{ include "db.php"; }
catch(Exception $e){ $dberr = $e->getMessage()."<br />"; }

$pagen = 0;
$tenrow = 0;
if(isset($_GET['page'])){
  $page = preg_replace('/[^0-9]/','',substr((string)$_GET['page'],0,10));
  unset($_GET['page']);
  if(is_numeric($page)){
	// $pgn - номер страницы, приведённый к целому
	$pgn = intval($page);
	//print('$pgn: '.$pgn.'<br />');
	// если номер страницы меньше или равен 10, то $tenrow = 0
	// иначе к переменная $tenrow = номер страницы, делённый на 10 и приведённый к целому + 0
	if($pgn > 10){ $tenrow = intval($pgn/10).'0'; }
	//print('$tenrow: '.$tenrow.'<br />');
	$pagen = $pgn-1;
  }
}

// $perPage - количество строк на страницу
$perPage = 20;
//print('$perPage: '.$perPage.'<br/ >');

// $pnfrom - номер ряда, с которого начинается выборка
$pnfrom = $tenrow * $perPage;
//print('$pnfrom: '.$pnfrom.'<br/ >');

// $rowlim - лимит выборки
$rowlim = $perPage * 10 + 1;
//print('$rowlim: '.$rowlim.'<br/ >');

$startrow = $pagen * $perPage;
//print('$startrow: '.$startrow.'<br/ >');

if(isset($db)){
try{
$numres = $db->query("SELECT id FROM users WHERE blocked='yes' LIMIT $pnfrom,$rowlim;");
//print('$numres: '); var_dump($numres); print('<br />');
$row = $numres->fetchAll(PDO::FETCH_NUM);
//print('$row: '); var_dump($row); print('<br />');
$rowsNum = count($row);
//print('$rowsNum: '.$rowsNum.'<br/ >');

$ui = $db->query("SELECT * FROM users WHERE uid='$suid' AND utype='admin';");
$uinfo = $ui->fetchAll(PDO::FETCH_ASSOC);
//print('$uinfo: '); var_dump($uinfo); print('<br />');

$buresult = $db->query("SELECT * FROM users WHERE blocked='yes' ORDER BY dt Desc LIMIT $startrow,$perPage;");
$burow = $buresult->fetchAll(PDO::FETCH_ASSOC);
//print('$burow: '); var_dump($burow); print('<br />');
}
catch(Exception $e){
  $dberr = $e->getMessage()."<br />";
}

if($rowsNum < ($rowlim - 1)){
  $limpgn = $tenrow + ceil($rowsNum / $perPage);
}
else{
  $limpgn = $tenrow + 10;
}
//print('$limpgn: '.$limpgn.'<br/ >');
}

$nextten = $tenrow+11;
//print('$nextten: '.$nextten.'<br/ >');
$prevten = $tenrow-9;
//print('$prevten: '.$prevten.'<br/ >');

//print('SESSION: '); print_r($_SESSION); print('<br />');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  <title></title>
  
  <link rel="stylesheet" type="text/css" href="css/buadmin.css" />
  <style type="text/css">
    
  </style>
  
  <script type='text/javascript' src='js/jquery.1.8.3.js'></script>
  <script type='text/javascript' src='js/jquery-ui.js'></script>
  <script type='text/javascript' src='js/functions.js'></script>
  <script type='text/javascript' src='js/admin.js'></script>
  <script type='text/javascript'>
    
  </script>
  
</head>

<body>

<? if(isset($dberr)){ ?><div id="wdberr"><div id="dberr"><? print($dberr); ?></div></div><? } ?>

<div id="wmcont">
<div id="mcont">

<div id="mainTopPanel">
  <a id="mainpagemtp" href="index.php">Main page</a><span id="spmainpage"></span>
  <a id="mypagemtp" href="uq.php?uid=<? print($suid); ?>">My page</a><span id="spmypage"></span>
  <span id="MTPUsersName"><? print($_SESSION['fname']); ?></span>
  <form id="MTPForm" method="post" action="index.php">
    <input id="MTPLogoutBt" name="logout" type="submit" value="Log Out" />
  </form>
</div>

<div id="tabsline">
  <a class="tabs" href="cqadmin.php">Questions</a>
  <a class="tabs" href="caadmin.php">Answers</a>
  <a id="butl" class="tabs">Blocked users</a>
</div>

<div id="leftBlock">
  <div id="lbuinfo">
    <? if(isset($uinfo) && $uinfo != false){ ?>
    <div id="wlbuimg">
      <img id="lbuimg" src="ulphotos/<? if($uinfo[0]['ulphoto'] != ''){ print($uinfo[0]['ulphoto']); }else{ print('nouser200.png'); } ?>" />
    </div>
    <div id="ufnln"><span><? print($uinfo[0]['fname']); ?></span></div>
    <? } ?>
  </div>
</div>

<div id="MBRowsColumn">

<? if(isset($burow) && $burow != false){ foreach($burow as $row){ ?>
<div class="MBBlockedUsers">

<div id="unbmsg<? print($row['uid']); ?>" class="msg"></div>

<div id="unb<? print($row['uid']); ?>" class="ubu">
  <div class="txtubu">Unblock this user ?</div>
  <div class="yubu" onclick="unblockuser('<? print($row['uid']); ?>');">Yes</div>
  <div class="nubu" onclick="cunbuform('<? print($row['uid']); ?>');">No</div>
</div>

<div class="elwrap">
<div class="MBPhoto">
  <a href="uq.php?uid=<? print($row['uid']); ?>" target="_blank">
    <div style="background-image: url('uphotos/<? if($row['usphoto'] != ''){ print($row['usphoto']); }else{ print('nouser50.png'); } ?>')"></div>
  </a>
</div>

<div class="MBBlUserDet">
<span class="DetailsItems"><? print($row['fname'].' '.$row['lname']); ?></span>
<span id="MBADDate" class="DetailsItems"><? print($row['dt']); ?></span>
<span class="MBRightSideIcons">
<img class="RSIcons" title="Unblock user" src="icons/block.png" onclick="ounbuform('<? print($row['uid']); ?>');" />
</span>
</div>
</div>

</div>
<? }} ?>

<? if(isset($rowsNum) && $rowsNum > $perPage){ ?>
<div id="pgnumrow">
<?
if($pnfrom != 0){ print('<a class="arrpgnum" href="buadmin.php?page='.$prevten.'">Prev 10</a>'); }
for($i=$tenrow+1; $i<=$limpgn; $i++){ print('<a id="pgnum'.$i.'" class="pgnum" href="buadmin.php?page='.$i.'">'.$i.'</a>'); }
if($rowsNum > ($rowlim - 1)){ print('<a class="arrpgnum" href="buadmin.php?page='.$nextten.'">Next 10</a>'); }
?>
</div>
<? } ?>

</div>

<div class="footerClLine">
<span>Questions and answers</span>
</div>

</div>
</div>

<script type='text/javascript'>
var page; <? if(isset($pgn)){ print('page = '.$pgn); } ?>;
if(page != undefined){ document.getElementById('pgnum'+page).style.backgroundColor = 'rgb(232,232,232)'; }
else if(document.getElementById('pgnum1')){ document.getElementById('pgnum1').style.backgroundColor = 'rgb(232,232,232)'; }
</script>

</body>

</html>
<?
}
}
?>