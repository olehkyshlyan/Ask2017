<?
session_start();

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){

$host = $_SERVER['HTTP_HOST'];
//print('$host: '.$host.'<br />');
$currenturl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$_SESSION['currenturl'] = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$actpage = 'cqadmin.php';
if($_SERVER['QUERY_STRING'] != ''){ $actpage = 'cqadmin.php?'.$_SERVER['QUERY_STRING']; }
//print('$currenturl: '.$currenturl.'<br />');
//print('SESSION currenturl: '.$_SESSION['currenturl'].'<br />');
//print('$actpage: '.$actpage.'<br />');
$suid = $_SESSION['uid'];

try{ include "db.php"; }
catch(Exception $e){ $dberr = $e->getMessage()."<br />"; }

if(isset($_POST['qdelete'])){ include "qdelete.php"; }

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
	// иначе переменная $tenrow = номер страницы, делённый на 10 и приведённый к целому + 0
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
$numres = $db->query("SELECT questions.id,users.id FROM questions,users WHERE questions.complaint='yes' AND users.uid=questions.complainant LIMIT $pnfrom,$rowlim;");
//print('$numres: '); var_dump($numres); print('<br />');
$row = $numres->fetchAll(PDO::FETCH_NUM);
//print('$row: '); var_dump($row); print('<br />');
$rowsNum = count($row);
//print('$rowsNum: '.$rowsNum.'<br/ >');

$ui = $db->query("SELECT * FROM users WHERE uid='$suid' AND utype='admin';");
$uinfo = $ui->fetchAll(PDO::FETCH_ASSOC);
//print('$uinfo: '); var_dump($uinfo); print('<br />');

$qresult = $db->query("
SELECT
 questions.*,
 users.id AS cid, users.utype AS cutype, users.uid AS cuid, users.fname AS cfname, users.lname AS clname, users.usphoto AS cuphoto, users.dt AS cdt, users.blocked
 FROM questions, users
 WHERE questions.complaint = 'yes' AND users.uid = questions.complainant
 ORDER BY dt Desc LIMIT $startrow,$perPage
;");

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
  
  <link rel="stylesheet" type="text/css" href="css/cqadmin.css" />
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
  <a id="qtl" class="tabs">Questions</a>
  <a class="tabs" href="caadmin.php">Answers</a>
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

<? if(isset($qrow) && $qrow != false){ foreach($qrow as $row){ ?>
<div class="MBQuestionBlock">

<div id="msg<? print($row['id']); ?>" class="msg"></div>

<div id="qrq<? print($row['id']); ?>" class="qrq">
  <div class="txtqrq">Remove complaint ?</div>
  <div class="yqrc" onclick="qremcompl(<? print($row['id']); ?>);">Yes</div>
  <div class="nqrc" onclick="cqrcform('qrq<? print($row['id']); ?>');">No</div>
</div>

<div id="qbu<? print($row['id']); ?>" class="qbu">
  <div class="txtqbu">Block this user ?</div>
  <div class="yqbu" onclick="blockuser('qbu<? print($row['id']); ?>','msg<? print($row['id']); ?>','<? print($row['uid']); ?>');">Yes</div>
  <div class="nqbu" onclick="cbuform('qbu<? print($row['id']); ?>');">No</div>
</div>

<div id="qdel<? print($row['id']); ?>" class="qdel">
  <form method="post" action="<? print($actpage); ?>">
  <input name="qid" type="hidden" value="<? print($row['id']); ?>" />
  <div class="txtqdel">Delete this question ?</div>
  <input class="yqdel" name="qdelete" type="submit" value="Yes" />
  <input class="nqdel" type="button" value="No" onclick="cdelform('qdel<? print($row['id']); ?>');" />
  </form>
</div>

<div class="MBPhoto">
  <a href="uq.php?uid=<? print($row['uid']); ?>" target="_blank">
    <div style="background-image: url('uphotos/<? if($row['uphoto'] != ''){ print($row['uphoto']); }else{ print('nouser50.png'); } ?>')"></div>
  </a>
</div>

<div class="MBQuestionDetails">
<span class="DetailsItems"><? print($row['fname'].' '.$row['lname']); ?></span>
<span id="MBQDSubcategory" class="DetailsItems"><? print($row['subcategoryname']); ?></span>
<span id="MBQDDate" class="DetailsItems"><? print($row['dt']); ?></span>
<span id="MBQDVotesNumber" class="DetailsItems">Answers: <? print($row['answers']); ?></span>
<span class="MBRightSideIcons">
<img class="RSIcons" title="Remove complaint" src="icons/normal.png" onclick="oqrcform('qrq<? print($row['id']); ?>');" />
<img class="RSIcons" title="Block user" src="icons/block.png" onclick="obuform('qbu<? print($row['id']); ?>');" />
<img class="RSIcons" title="Delete" src="icons/delete.png" onclick="odelform('qdel<? print($row['id']); ?>');" />
</span>
</div>

<div id="wqtxt<? print($row['id']); ?>" class="MBQuestionText">
  <a id="qtxt<? print($row['id']); ?>" class="qtxt" href="<? print('question.php?q='.$row['id']); ?>"><? print($row['qtext']); ?></a>
</div>
<script type='text/javascript'>
var wqtxt<? print($row['id']); ?> = document.getElementById('wqtxt<? print($row['id']); ?>');
var qtxt<? print($row['id']); ?> = document.getElementById('qtxt<? print($row['id']); ?>');
var hqtxt<? print($row['id']); ?> = qtxt<? print($row['id']); ?>.clientHeight;
if(hqtxt<? print($row['id']); ?> > 30){
  qtxt<? print($row['id']); ?>.style.height = '30px';
  wqtxt<? print($row['id']); ?>.insertAdjacentHTML('beforeend','<div class="wqshow"><div id="qshmore<? print($row['id']); ?>" class="qshow" onclick="uqqshowmore(<? print($row['id']); ?>,hqtxt<? print($row['id']); ?>);">Show more</div><div id="qshless<? print($row['id']); ?>" class="qshow" style="z-index:-1;" onclick="uqqshowless(<? print($row['id']); ?>,\'30\');">Show less</div></div>');
}
</script>

<? if($row['qdetails'] != ''){ ?>
<div id="wQTxtDet<? print($row['id']); ?>" class="wQTxtDet">
  <div id="qTxtDet<? print($row['id']); ?>" class="qTxtDet"><? print($row['qdetails']); ?></div>
</div>
<script type='text/javascript'>
var dwqtxt<? print($row['id']); ?> = document.getElementById('wQTxtDet<? print($row['id']); ?>');
var dqtxt<? print($row['id']); ?> = document.getElementById('qTxtDet<? print($row['id']); ?>');
var hdqtxt<? print($row['id']); ?> = dqtxt<? print($row['id']); ?>.clientHeight;
var mhdqtxt<? print($row['id']); ?> = 15;
if(hdqtxt<? print($row['id']); ?> > 15 && hdqtxt<? print($row['id']); ?> < 91){ mhdqtxt<? print($row['id']); ?> = hdqtxt<? print($row['id']); ?>; }
else if(hdqtxt<? print($row['id']); ?> > 90){ mhdqtxt<? print($row['id']); ?> = 90; }
if(hdqtxt<? print($row['id']); ?> > 15){
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
if($pnfrom != 0){ print('<a class="arrpgnum" href="cqadmin.php?page='.$prevten.'">Prev 10</a>'); }
for($i=$tenrow+1; $i<=$limpgn; $i++){ print('<a id="pgnum'.$i.'" class="pgnum" href="cqadmin.php?page='.$i.'">'.$i.'</a>'); }
if($rowsNum > ($rowlim - 1)){ print('<a class="arrpgnum" href="cqadmin.php?page='.$nextten.'">Next 10</a>'); }
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

<? if(isset($_SESSION['qdel'])){ ?>
document.getElementById('msg<? print($_SESSION['qdelid']); ?>').innerHTML = "<? print($_SESSION['qdel']); ?>";
jQuery('#msg<? print($_SESSION['qdelid']); ?>').slideDown({duration:1000}).delay(10000).slideUp({duration:1000});
<? unset($_SESSION['qdel']); unset($_SESSION['qdelid']); } ?>

</script>

</body>

</html>
<?
}
}
?>