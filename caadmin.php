<?
session_start();

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){

$host = $_SERVER['HTTP_HOST'];
//print('$host: '.$host.'<br />');
$currenturl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$_SESSION['currenturl'] = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$actpage = 'caadmin.php';
if($_SERVER['QUERY_STRING'] != ''){ $actpage = 'caadmin.php?'.$_SERVER['QUERY_STRING']; }
//print('$currenturl: '.$currenturl.'<br />');
//print('SESSION currenturl: '.$_SESSION['currenturl'].'<br />');
//print('$actpage: '.$actpage.'<br />');
$suid = $_SESSION['uid'];

try{ include "db.php"; }
catch(Exception $e){ $dberr = $e->getMessage()."<br />"; }

if(isset($_POST['adelete'])){ include "adelete.php"; }

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
$numres = $db->query("SELECT answers.id,users.id FROM answers,users WHERE answers.complaint='yes' AND users.uid=answers.complainant LIMIT $pnfrom,$rowlim;");
//print('$numres: '); var_dump($numres); print('<br />');
$row = $numres->fetchAll(PDO::FETCH_NUM);
//print('$row: '); var_dump($row); print('<br />');
$rowsNum = count($row);
//print('$rowsNum: '.$rowsNum.'<br/ >');

$ui = $db->query("SELECT * FROM users WHERE uid='$suid' AND utype='admin';");
$uinfo = $ui->fetchAll(PDO::FETCH_ASSOC);
//print('$uinfo: '); var_dump($uinfo); print('<br />');

$aresult = $db->query("
SELECT
 answers.*,
 users.id AS cid, users.utype AS cutype, users.uid AS cuid, users.fname AS cfname, users.lname AS clname, users.usphoto AS cuphoto, users.dt AS cdt, users.blocked
 FROM answers, users
 WHERE answers.complaint = 'yes' AND users.uid = answers.complainant
 ORDER BY dt Desc LIMIT $startrow,$perPage
;");

$arow = $aresult->fetchAll(PDO::FETCH_ASSOC);
//print('$arow: '); var_dump($arow); print('<br />');
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
  
  <link rel="stylesheet" type="text/css" href="css/caadmin.css" />
  <style type="text/css">
    
  </style>
  
  <script type='text/javascript' src='js/jquery.1.8.3.js'></script>
  <script type='text/javascript' src='js/jquery-ui.js'></script>
  <script type='text/javascript' src='js/jquery.bxslider.min.js'></script>
  <script type='text/javascript' src='js/slimscroll.js'></script>
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
  <a id="atl" class="tabs">Answers</a>
  <a class="tabs" href="buadmin.php">Blocked users</a>
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

<? if(isset($arow) && $arow != false){ foreach($arow as $row){ ?>
<div class="MBAnswerBlock">

<div id="amsg<? print($row['id']); ?>" class="msg"></div>

<div id="arq<? print($row['id']); ?>" class="arq">
  <div class="txtarq">Remove complaint ?</div>
  <div class="yarc" onclick="aremcompl(<? print($row['id']); ?>);">Yes</div>
  <div class="narc" onclick="carcform('arq<? print($row['id']); ?>');">No</div>
</div>

<div id="abu<? print($row['id']); ?>" class="abu">
  <div class="txtabu">Block this user ?</div>
  <div class="yabu" onclick="blockuser('abu<? print($row['id']); ?>','amsg<? print($row['id']); ?>','<? print($row['uid']); ?>');">Yes</div>
  <div class="nabu" onclick="cbuform('abu<? print($row['id']); ?>');">No</div>
</div>

<div id="adel<? print($row['id']); ?>" class="adel">
  <form method="post" action="<? print($actpage); ?>">
  <input name="aid" type="hidden" value="<? print($row['id']); ?>" />
  <input name="qid" type="hidden" value="<? print($row['qid']); ?>" />
  <div class="txtadel">Delete this answer ?</div>
  <input class="yadel" name="adelete" type="submit" value="Yes" />
  <input class="nadel" type="button" value="No" onclick="cdelform('adel<? print($row['id']); ?>');" />
  </form>
</div>

<div class="MBPhoto">
  <a href="uq.php?uid=<? print($row['uid']); ?>" target="_blank">
    <div style="background-image: url('uphotos/<? if($row['uphoto'] != ''){ print($row['uphoto']); }else{ print('nouser50.png'); } ?>')"></div>
  </a>
</div>

<div class="MBAnswerDetails">
<span class="DetailsItems"><? print($row['fname'].' '.$row['lname']); ?></span>
<span id="MBADDate" class="DetailsItems"><? print($row['dt']); ?></span>
<span class="MBRightSideIcons">
  <img class="RSIcons" title="Remove complaint" src="icons/normal.png" onclick="oarcform('arq<? print($row['id']); ?>');" />
  <img class="RSIcons" title="Block user" src="icons/block.png" onclick="obuform('abu<? print($row['id']); ?>');" />
  <img class="RSIcons" title="Delete" src="icons/delete.png" onclick="odelform('adel<? print($row['id']); ?>');" />
</span>
</div>

<div id="watxt<? print($row['id']); ?>" class="MBAnswerText">
  <div id="atxt<? print($row['id']); ?>" class="atxt"><? print($row['atext']); ?></div>
</div>
<script type='text/javascript'>
var watxt<? print($row['id']); ?> = document.getElementById('watxt<? print($row['id']); ?>');
var atxt<? print($row['id']); ?> = document.getElementById('atxt<? print($row['id']); ?>');
var hatxt<? print($row['id']); ?> = atxt<? print($row['id']); ?>.clientHeight;
//alert('hatxt: '+hatxt<? print($row['id']); ?>);
var mhatxt<? print($row['id']); ?> = 30;
if(hatxt<? print($row['id']); ?> > 30 && hatxt<? print($row['id']); ?> < 91){ mhatxt<? print($row['id']); ?> = hatxt<? print($row['id']); ?>; }
else if(hatxt<? print($row['id']); ?> > 90){ mhatxt<? print($row['id']); ?> = 90; }
//alert('mhatxt: '+mhatxt<? print($row['id']); ?>);
if(hatxt<? print($row['id']); ?> > 30){
  atxt<? print($row['id']); ?>.style.height = mhatxt<? print($row['id']); ?>+'px';
  watxt<? print($row['id']); ?>.insertAdjacentHTML('beforeend','<div class="washow"><div id="ashmore<? print($row['id']); ?>" class="ashow" onclick="ashowmore(<? print($row['id']); ?>,hatxt<? print($row['id']); ?>);">Show more</div><div id="ashless<? print($row['id']); ?>" class="ashow" style="z-index:-1;" onclick="ashowless(<? print($row['id']); ?>,mhatxt<? print($row['id']); ?>);">Show less</div></div>');
}
</script>

<? if($row['imgf'] != '' && $row['aimages'] != ''){ $aexpimgs = explode('|sp|',$row['aimages']); $aeil = count($aexpimgs); ?>
<div id="wrapABBxSlider<? print($row['id']); ?>" class="wrapABBxSlider">
<div id="ABBxSlider<? print($row['id']); ?>">
<? for($i=0;$i<$aeil;$i++){ ?>
<div class="bxslidewrap">
<a href="images/<? print($row['imgf']); ?>/<? print($aexpimgs[$i]); ?>" target="_blank">
<img src="images/<? print($row['imgf']); ?>/<? print($aexpimgs[$i]); ?>" class="imgBxSlide" />
</a>
</div>
<? } ?>
</div>
</div>
<script type='text/javascript'>
var aBxSl<? print($row['id']); ?>Len = <? print($aeil); ?>;
if(aBxSl<? print($row['id']); ?>Len > 3){
var wrapABBxSl<? print($row['id']); ?> = document.getElementById('wrapABBxSlider<? print($row['id']); ?>');
wrapABBxSl<? print($row['id']); ?>.insertAdjacentHTML('afterbegin','<div class="bxSlNextArrow" onclick="jABBxSl<? print($row['id']); ?>.goToNextSlide();"><img src="icons/next.png" /></div>');
wrapABBxSl<? print($row['id']); ?>.insertAdjacentHTML('afterbegin','<div class="bxSlPrevArrow" onclick="jABBxSl<? print($row['id']); ?>.goToPrevSlide();"><img src="icons/prev.png" /></div>');
}
jABBxSl<? print($row['id']); ?> = jQuery(ABBxSlider<? print($row['id']); ?>).bxSlider({ slideMargin: 7, pager: false, controls: false, maxSlides: 3, moveSlides: 1, slideWidth: 160 });
</script>
<? } ?>

</div>

<div class="MBComplainant">

<div id="cmsg<? print($row['id']); ?>" class="msg"></div>

<div id="cbu<? print($row['id']); ?>" class="cbu">
  <div class="txtcbu">Block this user ?</div>
  <div class="ycbu" onclick="blockuser('cbu<? print($row['id']); ?>','cmsg<? print($row['id']); ?>','<? print($row['cuid']); ?>');">Yes</div>
  <div class="ncbu" onclick="cbuform('cbu<? print($row['id']); ?>');">No</div>
</div>

<div class="elwrap">
<div class="MBPhoto">
  <a href="uq.php?uid=<? print($row['cuid']); ?>" target="_blank">
    <div style="background-image: url('uphotos/<? if($row['cuphoto'] != ''){ print($row['cuphoto']); }else{ print('nouser50.png'); } ?>')"></div>
  </a>
</div>

<div class="MBComplainantDetails">
<span class="DetailsItems"><? print($row['cfname'].' '.$row['clname']); ?></span>
<span id="MBADDate" class="DetailsItems"><? print($row['cdt']); ?></span>
<span class="MBRightSideIcons">
  <img class="RSIcons" title="Block user" src="icons/block.png" onclick="obuform('cbu<? print($row['id']); ?>');" />
</span>
</div>
</div>

</div>
<? }} ?>

<? if(isset($rowsNum) && $rowsNum > $perPage){ ?>
<div id="pgnumrow">
<?
if($pnfrom != 0){ print('<a class="arrpgnum" href="caadmin.php?page='.$prevten.'">Prev 10</a>'); }
for($i=$tenrow+1; $i<=$limpgn; $i++){ print('<a id="pgnum'.$i.'" class="pgnum" href="caadmin.php?page='.$i.'">'.$i.'</a>'); }
if($rowsNum > ($rowlim - 1)){ print('<a class="arrpgnum" href="caadmin.php?page='.$nextten.'">Next 10</a>'); }
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

<? if(isset($_SESSION['adel'])){ ?>
document.getElementById('camsg<? print($_SESSION['adelid']); ?>').innerHTML = "<? print($_SESSION['adel']); ?>";
jQuery('#camsg<? print($_SESSION['adelid']); ?>').slideDown({duration:1000}).delay(10000).slideUp({duration:1000});
<? unset($_SESSION['adel']); unset($_SESSION['adelid']); } ?>

</script>

</body>

</html>
<?
}
}
?>