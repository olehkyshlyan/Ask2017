<?
// user questions page
session_start();

$host = $_SERVER['HTTP_HOST'];
//print('$host: '.$host.'<br />');
$currenturl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$_SESSION['currenturl'] = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$actpage = 'uq.php?'.$_SERVER['QUERY_STRING'];
//print('$currenturl: '.$currenturl.'<br />');
//print('SESSION currenturl: '.$_SESSION['currenturl'].'<br />');
//print('$actpage: '.$actpage.'<br />');
$suid = $_SESSION['uid'];

if(isset($_GET['uid'])){
  $uid = mb_substr((string)$_GET['uid'],0,50,'UTF-8');
  unset($_GET['uid']);
  $uid = preg_replace('/[^\p{N}\p{L}\p{Zs}\_\-\+\=\&\']+/u','',$uid);
}

try{ include "db.php"; }
catch(Exception $e){ $dberr = $e->getMessage()."<br />"; }

if(isset($_POST['uqdelete'])){ include "uqdelete.php"; }

$pagen = 0;
$tenrow = 0;
if(isset($_GET['page'])){
  $page = preg_replace('/[^0-9]/','',substr((string)$_GET['page'],0,10));
  unset($_GET['page']);
  if(is_numeric($page)){
	// $pgn - номер страницы
	$pgn = intval($page);
	//print('$pgn: '.$pgn.'<br />');
	// если номер страницы меньше или равен 10, то $tenrow = 0
	// иначе переменная $tenrow = номер страницы, делённый на 10 + '0'
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

if(isset($uid)){
if(isset($db)){

try{
$numres = $db->query("SELECT id FROM questions WHERE uid='$uid' LIMIT $pnfrom,$rowlim;");
//print('$numres: '); var_dump($numres); print('<br />');
$row = $numres->fetchAll(PDO::FETCH_NUM);
//print('$row: '); var_dump($row); print('<br />');
$rowsNum = count($row);
//print('$rowsNum: '.$rowsNum.'<br/ >');

$ui = $db->query("SELECT * FROM users WHERE uid='$uid';");
$uinfo = $ui->fetchAll(PDO::FETCH_ASSOC);
//print('$uinfo: '); var_dump($uinfo); print('<br />');
if($uinfo != false){
  if($uinfo[0]['cemail'] != ''){
    $cemail = $uinfo[0]['cemail'];
    $pem = explode('@',$cemail);
    $spem = str_replace('.',' | ',$pem[1]);
    $femail = $pem[0].' | '.$spem;
  }
}

$qresult = $db->query("SELECT * FROM questions WHERE uid='$uid' ORDER BY dt Desc LIMIT $startrow,$perPage;");
$qrow = $qresult->fetchAll(PDO::FETCH_ASSOC);
//print('$qrow: '); var_dump($qrow); print('<br />');
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
}

// если на странице 'uq.php' остаётся один вопрос,
// то в случае его удаления пользователя перенаправляет на страницу с номером меньшим на единицу
if(isset($_SESSION['pgnqdel']) && $_SESSION['pgnqdel'] == true){
if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
  if(isset($pgn) && $pgn != 1){
	if(isset($qrow) && count($qrow) == 0){
	  $pred = $pgn - 1;
	  $curl = str_ireplace('page='.$pgn,'page='.$pred,$currenturl);
	  header('Location:http://'.$curl);
	  unset($_SESSION['pgnqdel']);
	  exit();
	}
  }
}
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
  
  <link rel="stylesheet" type="text/css" href="css/uq.css" />
  <style type="text/css">
    
  </style>
  
  <script type='text/javascript' src='js/jquery.1.8.3.js'></script>
  <script type='text/javascript' src='js/jquery-ui.js'></script>
  <script type='text/javascript' src='js/jquery.bxslider.min.js'></script>
  <script type='text/javascript' src='js/slimscroll.js'></script>
  <script type='text/javascript' src='js/functions.js'></script>
  <? if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){ ?>
  <script type='text/javascript' src='js/euser.js'></script>
  <? if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){ ?>
  <script type='text/javascript' src='js/admin.js'></script>
  <? }} ?>
  <script type='text/javascript'>
    
  </script>
  
</head>

<body>

<? if(isset($dberr)){ ?><div id="wdberr"><div id="dberr"><? print($dberr); ?></div></div><? } ?>

<div id="wmcont">
<div id="mcont">

<div id="mainTopPanel">
  <a id="mainpagemtp" href="index.php">Main page</a><span id="spmainpage"></span>
  <? if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){ ?>
  <a id="mypagemtp" href="uq.php?uid=<? print($suid); ?>">My page</a><span id="spmypage"></span>
  <? if($_SESSION['utype'] != 'admin'){ ?>
  <span id="MTPUsersName"><? print($_SESSION['fname'].' '.$_SESSION['lname']); ?></span>
  <? }elseif($_SESSION['utype'] == 'admin'){ ?>
  <a href="cqadmin.php"><span id="MTPUsersName"><? print($_SESSION['fname']); ?></span></a>
  <? } ?>
  <form id="MTPForm" method="post" action="index.php">
    <input id="MTPLogoutBt" name="logout" type="submit" value="Log Out" />
  </form>
  <? }else{ ?>
  <a id="MTPLoginBt" href="auth.php">Log In</a>
  <? } ?>
</div>

<? if(isset($uinfo) && $uinfo != false){ ?>

<div id="tabsline">
  <? if($uinfo[0]['uid'] == $suid){ ?>
  <a id="tlmyq" class="tabs">My questions</a>
  <a class="tabs" href="ua.php?uid=<? print($uinfo[0]['uid']); ?>">My answers</a>
  <a class="tabs" href="uqa.php?uid=<? print($uinfo[0]['uid']); ?>">Answers to my questions</a>
  <a class="tabs" href="up.php?uid=<? print($uinfo[0]['uid']); ?>">My page</a>
  <? }else{ ?>
  <a id="tlmyq" class="tabs">Questions</a>
  <a class="tabs" href="ua.php?uid=<? print($uinfo[0]['uid']); ?>">Answers</a>
  <a class="tabs" href="uqa.php?uid=<? print($uinfo[0]['uid']); ?>">Answers to user questions</a>
  <a class="tabs" href="up.php?uid=<? print($uinfo[0]['uid']); ?>">User page</a>
  <? } ?>
</div>

<div id="leftBlock">
  <div id="lbuinfo">
    <div id="wlbuimg">
      <img id="lbuimg" src="ulphotos/<? if($uinfo[0]['ulphoto'] != ''){ print($uinfo[0]['ulphoto']); }else{ print('nouser200.png'); } ?>" />
    </div>
    <div id="ufnln"><span><? print($uinfo[0]['fname']); ?></span><? if($uinfo[0]['utype'] != 'admin'){ ?><span id="uln"><? print($uinfo[0]['lname']); ?></span><? } ?></div>
    <? if($uinfo[0]['city'] != ''){ ?><div id="ucity"><span>city: </span><span><? print($uinfo[0]['city']); ?></span></div><? } ?>
    <? if(isset($femail)){ ?><div id="wemail"><span>e-mail: </span><span id="email"><? print($femail); ?></span></div><? } ?>
    <? if($uinfo[0]['phone'] != ''){ ?><div id="wphone"><span>phone: </span><span><? print($uinfo[0]['phone']); ?></span></div><? } ?>
    <? if($uinfo[0]['cont'] != ''){ ?><div id="conts"><? $conts = nl2br($uinfo[0]['cont']); print($conts); ?></div><? } ?>
  </div>
  <div id="LBAdv1"></div>
  <div id="LBAdv2"></div>
  <div id="LBAdv3"></div>
</div>

<div id="MBRowsColumn">

<? if(isset($qrow) && $qrow != false){ foreach($qrow as $row){ ?>
<div class="MBQuestionBlock" id="qb<? print($row['id']); ?>">

<div id="uqmsg<? print($row['id']); ?>" class="msg"></div>

<? if(isset($_SESSION['euser'])&& $_SESSION['euser'] == true){ ?>
<? if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){ ?>
<div id="delmsg<? print($row['id']); ?>" class="qadm">
  <form method="post" action="<? print($actpage); ?>">
  <input name="qid" type="hidden" value="<? print($row['id']); ?>" />
  <div class="txtqadm">Delete this question ?</div>
  <input class="yqdel" name="uqdelete" type="submit" value="Yes" />
  <input class="nqdel" type="button" value="No" onclick="slup500('delmsg<? print($row['id']); ?>');" />
  </form>
</div>
<div id="bu<? print($row['id']); ?>" class="qadm">
  <div class="txtqadm">Block this user ?</div>
  <div class="yqbu" onclick="blockuser('bu<? print($row['id']); ?>','uqmsg<? print($row['id']); ?>','<? print($row['uid']); ?>');">Yes</div>
  <div class="nqbu" onclick="slup500('bu<? print($row['id']); ?>');">No</div>
</div>
<? }else{ ?>
<? if($row['uid'] == $suid){ ?>
<div id="delmsg<? print($row['id']); ?>" class="qadm">
  <form method="post" action="<? print($actpage); ?>">
  <input name="qid" type="hidden" value="<? print($row['id']); ?>" />
  <div class="txtqadm">Delete this question ?</div>
  <input class="yqdel" name="uqdelete" type="submit" value="Yes" />
  <input class="nqdel" type="button" value="No" onclick="uq_cqdelform('delmsg<? print($row['id']); ?>');" />
  </form>
</div>
<? }else{ ?>
<div id="qc<? print($row['id']); ?>" class="qc">
  <div class="qctxt">Complain about this question ?</div>
  <div class="qcyes" onclick="qeucompl('<? print($row['id']); ?>');">Yes</div>
  <div class="qcno" onclick="cqcform('qc<? print($row['id']); ?>');">No</div>
</div>
<? } ?>
<? }} ?>

<div class="MBPhoto">
  <a href="uq.php?uid=<? print($row['uid']); ?>" target="_blank">
    <div style="background-image: url('uphotos/<? if($row['uphoto'] != ''){ print($row['uphoto']); }else{ print('nouser50.png'); } ?>')"></div>
  </a>
</div>

<div class="MBQuestionDetails">
  <? if($row['utype'] != 'admin'){ ?>
  <span class="DetailsItems"><? print($row['fname'].' '.$row['lname']); ?></span>
  <? }elseif($row['utype'] == 'admin'){ ?>
  <span class="DetailsItems DIAdmin"><? print($row['fname']); ?></span>
  <? } ?>
  <a id="MBQDSubcategory" class="DetailsItems" href="index.php?category=<? print($row['categorylink']); ?>&subcategory=<? print($row['subcategorylink']); ?>"><? print($row['subcategoryname']); ?></a>
  <span id="MBQDDate" class="DetailsItems"><? print($row['dt']); ?></span>
  <span id="MBQDVotesNumber" class="DetailsItems">Answers: <? print($row['answers']); ?></span>
  <span class="MBRightSideIcons">
    <? if(isset($_SESSION['euser'])&& $_SESSION['euser'] == true){ ?>
    <? if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){ ?>
    <img class="RSIcons" title="Block user" src="icons/block.png" onclick="sldn500('bu<? print($row['id']); ?>');" />
    <img class="RSIcons" title="Delete" src="icons/delete.png" onclick="sldn500('delmsg<? print($row['id']); ?>');" />
    <? }else{ ?>
    <? if($row['uid'] == $suid){ ?>
    <img class="RSIcons" title="Delete" src="icons/delete.png" onclick="uq_oqdelform('delmsg<? print($row['id']); ?>');" />
    <? }else{ ?>
    <img class="RSIcons" title="Complain" src="icons/flag.png" onclick="oqcform('qc<? print($row['id']); ?>');" />
    <? }}}else{ ?>
    <img class="RSIcons" title="Complain" src="icons/flag.png" onclick="qneucompl('uqmsg<? print($row['id']); ?>');" />
    <? } ?>
  </span>
</div>

<div id="wqtxt<? print($row['id']); ?>" class="MBQuestionText">
  <a id="qtxt<? print($row['id']); ?>" class="qtxt" href="<? print('question.php?q='.$row['id']); ?>"><? print(nl2br($row['qtext'])); ?></a>
</div>

<? if($row['qdetails'] != ''){ ?>
<div id="wQTxtDet<? print($row['id']); ?>" class="wQTxtDet">
  <div id="qTxtDet<? print($row['id']); ?>" class="qTxtDet"><? print(nl2br($row['qdetails'])); ?></div>
</div>
<script type='text/javascript'>
var dwqtxt<? print($row['id']); ?> = document.getElementById('wQTxtDet<? print($row['id']); ?>');
var dqtxt<? print($row['id']); ?> = document.getElementById('qTxtDet<? print($row['id']); ?>');
var hdqtxt<? print($row['id']); ?> = dqtxt<? print($row['id']); ?>.clientHeight;
var mhdqtxt<? print($row['id']); ?> = 15;
if(hdqtxt<? print($row['id']); ?> > 15 && hdqtxt<? print($row['id']); ?> < 91){ mhdqtxt<? print($row['id']); ?> = hdqtxt<? print($row['id']); ?>; }
else if(hdqtxt<? print($row['id']); ?> > 90){ mhdqtxt<? print($row['id']); ?> = 90; }
if(hdqtxt<? print($row['id']); ?> > 90){
  dqtxt<? print($row['id']); ?>.style.height = mhdqtxt<? print($row['id']); ?>+'px';
  dwqtxt<? print($row['id']); ?>.insertAdjacentHTML('beforeend','<div class="wqdshow"><div id="qdshmore<? print($row['id']); ?>" class="qdshow" onclick="uqqdshmore(<? print($row['id']); ?>,hdqtxt<? print($row['id']); ?>);">Show more</div><div id="qdshless<? print($row['id']); ?>" class="qdshow" style="z-index:-1;" onclick="uqqdshless(<? print($row['id']); ?>,mhdqtxt<? print($row['id']); ?>);">Show less</div></div>');
}
</script>
<? } ?>

<? if($row['qimages'] != ''){ $qexpimgs = explode('|sp|',$row['qimages']); $qeil = count($qexpimgs); ?>
<div id="wrapQBBxSlider<? print($row['id']); ?>" class="wrapQBBxSlider">
<div id="QBBxSlider<? print($row['id']); ?>">
<? for($i=0;$i<$qeil;$i++){ ?>
<div class="bxslidewrap">
<a href="images/<? print($row['imgf']); ?>/<? print($qexpimgs[$i]); ?>" target="_blank">
<img src="images/<? print($row['imgf']); ?>/<? print($qexpimgs[$i]); ?>" class="imgBxSlide" />
</a>
</div>
<? } ?>
</div>
</div>
<script type='text/javascript'>
var aBxSl<? print($row['id']); ?>Len = <? print($qeil); ?>;
if(aBxSl<? print($row['id']); ?>Len > 3){
var wrapQBBxSl<? print($row['id']); ?> = document.getElementById('wrapQBBxSlider<? print($row['id']); ?>');
wrapQBBxSl<? print($row['id']); ?>.insertAdjacentHTML('afterbegin','<div class="bxSlNextArrow" onclick="jQBBxSl<? print($row['id']); ?>.goToNextSlide();"><img src="icons/next.png" /></div>');
wrapQBBxSl<? print($row['id']); ?>.insertAdjacentHTML('afterbegin','<div class="bxSlPrevArrow" onclick="jQBBxSl<? print($row['id']); ?>.goToPrevSlide();"><img src="icons/prev.png" /></div>');
}
jQBBxSl<? print($row['id']); ?> = jQuery(QBBxSlider<? print($row['id']); ?>).bxSlider({ slideMargin: 7, pager: false, controls: false, maxSlides: 3, moveSlides: 1, slideWidth: 160 });
</script>
<? } ?>

</div>

<? } ?>

<? if(isset($rowsNum) && $rowsNum > $perPage){ ?>
<div id="pgnumrow">
<?
if($pnfrom != 0){ print('<a class="arrpgnum" href="uq.php?uid='.$uid.'&page='.$prevten.'">Prev 10</a>'); }
for($i=$tenrow+1; $i<=$limpgn; $i++){ print('<a id="pgnum'.$i.'" class="pgnum" href="uq.php?uid='.$uid.'&page='.$i.'">'.$i.'</a>'); }
if($rowsNum > ($rowlim - 1)){ print('<a class="arrpgnum" href="uq.php?uid='.$uid.'&page='.$nextten.'">Next 10</a>'); }
?>
</div>
<? } ?>
<? }else{ ?>
<div id="noquest">No questions of this user</div>
<? } ?>

</div>

<? }else{ ?>
<div id="wrid">Wrong user id</div>
<? } ?>

<div class="footerClLine">
  <span>Questions and answers</span>
</div>

</div>
</div>

<script type='text/javascript'>

var page; <? if(isset($pgn)){ print('page = '.$pgn); } ?>;
if(page != undefined){ document.getElementById('pgnum'+page).style.backgroundColor = 'rgb(232,232,232)'; }
else if(document.getElementById('pgnum1')){ document.getElementById('pgnum1').style.backgroundColor = 'rgb(232,232,232)'; }

<? if(isset($_SESSION['uqdel'])){ ?>
document.getElementById('uqmsg<? print($_SESSION['uqdelid']); ?>').innerHTML = "<? print($_SESSION['uqdel']); ?>";
jQuery('#uqmsg<? print($_SESSION['uqdelid']); ?>').slideDown({duration:500}).delay(10000).slideUp({duration:500});
<? unset($_SESSION['uqdel']); unset($_SESSION['uqdelid']); } ?>

</script>

</body>

</html>
