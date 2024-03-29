<?
session_start();

$host = $_SERVER['HTTP_HOST'];
//print('$host: '.$host.'<br />');
$currenturl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$_SESSION['currenturl'] = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$currentRequestURI = $_SERVER['REQUEST_URI'];
//print('$currentRequestURI: '.$currentRequestURI.'<br />');
$_SESSION['currentRequestURI'] = $currentRequestURI;
$indexRequestURI = $_SERVER['REQUEST_URI'];
$_SESSION['indexRequestURI'] = $indexRequestURI;
//$actpage = basename($currenturl);
$actpage = 'index.php';
if($_SERVER['QUERY_STRING'] != ''){ $actpage = 'index.php?'.$_SERVER['QUERY_STRING']; }
//print('$currenturl: '.$currenturl.'<br />');
//print('SESSION currenturl: '.$_SESSION['currenturl'].'<br />');
//print('$actpage: '.$actpage.'<br />');

try{
	include_once "db/db.php";
}
catch(Exception $e){
	$dberr = $e->getMessage()."<br />";
}

include_once "categories/categories.php";
include_once "subcategories/subcategories.php";
include_once "indexpage/indexpageelements.php";
include_once "leftblock/leftblock.php";
use DB as DBNS;
use Categories as CatNS;
use Subcategories as SubcatNS;
use IndexPageElements as IndexPgElNS;
use LeftBlock as LB;

if(isset($_POST['aentbt'])){ include "aauth.php"; }
if(isset($_SESSION['authuser']) && $_SESSION['authuser'] == true){

if(isset($_POST['logout'])){ include "logout.php"; }

if(isset($_POST['quplimg'])){
  if(isset($_SESSION['blocked']) && $_SESSION['blocked'] == 'no'){
    include "quplimg.php";
  }
}

if(isset($_POST['addquestion'])){
  if(isset($_SESSION['blocked']) && $_SESSION['blocked'] == 'no'){
  if(isset($_SESSION['lrec'])){
    $qcdate = gmdate('Y-m-d H:i:s');
    $qlrec = $_SESSION['lrec'];
    $qlrec = strtotime($qlrec);
    $qlrec = strtotime('+ 2 minutes',$qlrec);
    $qrecdt = date('Y-m-d H:i:s',$qlrec);
    if($qcdate > $qrecdt){
      include "addquestion.php";
    }
    elseif($qrecdt > $qcdate){
      header('Location:http://'.$currenturl); exit();
    }
  }
  }
}

if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){
if(isset($_POST['qdelete'])){ include "qdelete.php"; }
}

}

$tenRow = 0;
$pageNumber = 0;
if(isset($_GET['page'])){
  $page = preg_replace('/[^0-9]/','',substr((string)$_GET['page'],0,10));
  unset($_GET['page']);
  if(is_numeric($page)){
	$pgn = intval($page);
	//print('$pgn: '.$pgn.'<br />');
	$itenRow = intval($pgn/10);
	if($pgn > 10){ $tenRow = $itenRow.'0'; }
	$pageNumber = $pgn-1;
  }
}
//print('$tenRow: '.$tenRow.'<br />');
//print('$pageNumber: '.$pageNumber.'<br />');

// количество рядов на страницу
$perPage = 25;
//print('$perPage: '.$perPage.'<br/ >');

// номер ряда, с которого начинается выборка для вывода нумерации страниц
$rowFrom = $tenRow * $perPage;
//print('$rowFrom: '.$rowFrom.'<br/ >');

// лимит рядов, выбираемых для вывода нумерации страниц
$rowsLimit = $perPage * 10 + 1;
//print('$rowsLimit: '.$rowsLimit.'<br/ >');

$startRow = $pageNumber * $perPage;
//print('$startRow: '.$startRow.'<br/ >');

$pagelink = '';
if(DBNS\Database::$dbHandler != NULL){
try{
if($categories->category != NULL && $subcategories->subcategory != NULL)
{
$pnrow = DBNS\Database::$dbHandler->query("SELECT id FROM questions WHERE categorylink='".$categories->category."' and subcategorylink='".$subcategories->subcategory."' LIMIT ".$rowFrom.",".$rowsLimit.";")->fetchAll(PDO::FETCH_NUM);
$rowsNum = count($pnrow);
$qresult = DBNS\Database::$dbHandler->query("SELECT * FROM questions WHERE categorylink='".$categories->category."' and subcategorylink='".$subcategories->subcategory."' ORDER BY dt Desc LIMIT ".$startRow.",".$perPage.";");
$qrow = $qresult->fetchAll(PDO::FETCH_ASSOC);
//print('$qrow: '); var_dump($qrow); print('<br />');
$pagelink = '?category='.$categories->category.'&subcategory='.$subcategories->subcategory.'&page=';
}
elseif($categories->category != NULL)
{
$pnrow = DBNS\Database::$dbHandler->query("SELECT id FROM questions WHERE categorylink='".$categories->category."' LIMIT ".$rowFrom.",".$rowsLimit.";")->fetchAll(PDO::FETCH_NUM);
$rowsNum = count($pnrow);
$qresult = DBNS\Database::$dbHandler->query("SELECT * FROM questions WHERE categorylink='".$categories->category."' ORDER BY dt Desc LIMIT ".$startRow.",".$perPage.";");
$qrow = $qresult->fetchAll(PDO::FETCH_ASSOC);
//print('$qrow: '); var_dump($qrow); print('<br />');
$pagelink = '?category='.$categories->category.'&page=';
}
else
{
$pnrow = DBNS\Database::$dbHandler->query("SELECT id FROM questions LIMIT ".$rowFrom.",".$rowsLimit.";")->fetchAll(PDO::FETCH_NUM);
$rowsNum = count($pnrow);
//print('$rowsNum: '.$rowsNum.'<br />');
$qresult = DBNS\Database::$dbHandler->query("SELECT * FROM questions ORDER BY dt Desc LIMIT ".$startRow.",".$perPage.";");
//print('$qresult: '); var_dump($qresult); print('<br />');
$qrow = $qresult->fetchAll(PDO::FETCH_ASSOC);
//print('$qrow: '); var_dump($qrow); print('<br />');
$pagelink = '?page=';
}

if(isset($_SESSION['authuser']) && $_SESSION['authuser'] == true){
  $iuid = $_SESSION['uid'];
  $ulrec = DBNS\Database::$dbHandler->query("SELECT lrec FROM users WHERE uid='$iuid';")->fetchAll(PDO::FETCH_ASSOC);
}

}
catch(Exception $e){
  $dberr = $e->getMessage()."<br />";
}

if(isset($ulrec)){
  $_SESSION['lrec'] = $ulrec[0]['lrec'];
  $ltrec = $ulrec[0]['lrec'];
  $currdt = gmdate('Y-m-d H:i:s');
  $ltrec = strtotime($ltrec);
  $ltrec = strtotime('+ 2 minutes',$ltrec);
  $slrec = date('Y-m-d H:i:s',$ltrec);
  if($slrec > $currdt){
    $trecdt = $ltrec;
    $tcdate = strtotime($currdt);
    $diff = $trecdt - $tcdate;
    $diff = gmdate('Y-m-d H:i:s',$diff);
    $dparr = date_parse($diff);
    $nrecmin = $dparr['minute'];
    $nrecsec = $dparr['second'];
  }
}

if($rowsNum < ($rowsLimit - 1)){
  $limitPageNum = $tenRow + ceil($rowsNum / $perPage);
}
else{
  $limitPageNum = $tenRow + 10;
}
//print('$limitPageNum: '.$limitPageNum.'<br/ >');
}

// если на странице 'index.php' остаётся один вопрос,
// то в случае его удаления пользователя перенаправляет на страницу с номером меньшим на единицу
if(isset($_SESSION['delq']) && $_SESSION['delq'] == true){
if(isset($_SESSION['authuser']) && $_SESSION['authuser'] == true){
  if(isset($pgn) && $pgn != 1){
	if(isset($qrow) && count($qrow) == 0){
	  $pred = $pgn - 1;
	  $curl = str_ireplace('page='.$pgn,'page='.$pred,$currenturl);
	  header('Location:http://'.$curl);
	  unset($_SESSION['delq']);
	  exit();
	}
  }
}
}

$nextten = $tenRow+11;
//print('$nextten: '.$nextten.'<br/ >');
$prevten = $tenRow-9;
//print('$prevten: '.$prevten.'<br/ >');

//$_SESSION['authuser'] = true; $_SESSION['uid'] = 194290693; $_SESSION['fname'] = 'Олег'; $_SESSION['lname'] = 'Шевченко'; $_SESSION['utype'] = 'vk'; $_SESSION['uphoto'] = 'uimages/vk50.jpg';
//$_SESSION['authuser'] = true; $_SESSION['uid'] = 222; $_SESSION['fname'] = 'Admin'; $_SESSION['lname'] = 'AdminLN'; $_SESSION['utype'] = 'admin'; $_SESSION['uphoto'] = 'uimages/admin.jpg';

//print('SESSION: '); print_r($_SESSION); print('<br />');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  <script type='text/javascript'>
  var page; <? if(isset($pgn)){ print('page = '.$pgn); } ?>;
  </script>
  <title>Questions and answers</title>
  
  <link rel="stylesheet" type="text/css" href="css/askwindow.css" />
  <link rel="stylesheet" type="text/css" href="css/index.css" />
	<link rel="stylesheet" type="text/css" href="css/leftblock/leftblock.css" />
  <style type="text/css">
    
  </style>
  
  <script type='text/javascript' src='js/jquery.3.4.1.js'></script>
  <script type='text/javascript' src='js/jquery-ui.1.12.1.js'></script>
  <script type='text/javascript' src='js/jquery.bxslider.min.js'></script>
  <script type='text/javascript' src='js/slimscroll.js'></script>
  <script type='text/javascript' src='js/functions.js'></script>
  <? if(isset($_SESSION['authuser']) && $_SESSION['authuser'] == true){ ?>
  <script type='text/javascript' src='js/authuser.js'></script>
  <? if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){ ?>
  <script type='text/javascript' src='admin/js/adminindexpage.js'></script>
  <? }} ?>
  
  <script>
  
  </script>
  
</head>

<body>

<? if(isset($dberr)){ ?><div id="wdberr"><div id="dberr"><? print($dberr); ?></div></div><? } ?>

<div id="wmcont">
<div id="mcont">
  
  <div id="mainTopPanel">
	<? if(isset($_SESSION['authuser']) && $_SESSION['authuser'] == true){ ?>
	<? if(isset($_SESSION['utype'])){ ?>
	<a id="mypagemtp" href="uq.php?uid=<? print($_SESSION['uid']); ?>">My page</a><span id="spmypage"></span>
	<? if($_SESSION['utype'] != 'admin'){ ?>
	<span id="MTPUsersName"><? print($_SESSION['fname'].' '.$_SESSION['lname']); ?></span>
	<? }elseif($_SESSION['utype'] == 'admin'){ ?>
  <a href="cqadmin.php"><span id="MTPUsersName"><? print($_SESSION['fname']); ?></span></a>
	<? }} ?>
	<form style="display:inline;" method="post" action="<? print($actpage); ?>">
	  <input id="MTPLogoutBt" name="logout" type="submit" value="Log Out" />
	</form>
	<? }else{ ?>
  <a id="MTPLoginBt" href="auth/auth.php">Log In</a>
  <? } ?>
  </div>
  
  <div id="cksnot"></div>
  
  <? if(isset($_SESSION['blocked']) && $_SESSION['blocked'] == 'yes'){ ?>
  <div id="blocked">You are blocked on this site. You can't add questions and answers.</div>
  <? } ?>
  
  <? if(isset($_SESSION['aautherr'])){ if($_SESSION['aautherr'] != ''){ ?>
  <div id="aautherr"><img id="crossbt" onclick="slup500('aautherr');" src="icons/close.png" />
  <? print($_SESSION['aautherr']); ?>
  </div>
  <? unset($_SESSION['aautherr']); }} ?>
  
  <? if(isset($_SESSION['uautherr'])){ if($_SESSION['uautherr'] != ''){ ?>
  <div id="uautherr"><img id="crossbt" onclick="slup500('uautherr');" src="icons/close.png" />
  <? print($_SESSION['uautherr']); ?>
  </div>
  <? unset($_SESSION['uautherr']); }} ?>
  
  <? if(isset($_SESSION['aqerr']) && $_SESSION['aqerr'] != ''){ ?>
  <div id="qadderr">
  <div id="aqeredline">Error when adding the question<img id="aqecrossbt" onclick="slup500('qadderr');" src="icons/close.png" /></div>
  <div id="aqecont"><? print($_SESSION['aqerr']); ?></div>
  </div>
  <? unset($_SESSION['aqerr']); } ?>
  
  <? if(isset($_SESSION['authuser']) && $_SESSION['authuser'] == true){ if(isset($_SESSION['blocked']) && $_SESSION['blocked'] == 'no'){ include "askwindow.php"; }} ?>
  
	<? if(LB\LeftAdvBlock::$advBlock != NULL){ print(LB\LeftAdvBlock::$advBlock); } ?>
  
	<? if(IndexPgElNS\MainButtons::$openTag != NULL){ print(IndexPgElNS\MainButtons::$openTag); } ?>
		<? if(IndexPgElNS\MainButtons::$categoriesButton != NULL){ print(IndexPgElNS\MainButtons::$categoriesButton); } ?>
    <? if(IndexPgElNS\MainButtons::$askAQuestionButton != NULL){ print(IndexPgElNS\MainButtons::$askAQuestionButton); } ?>
	<? if(IndexPgElNS\MainButtons::$closeTag != NULL){ print(IndexPgElNS\MainButtons::$closeTag); } ?>
  
	<? if(CatNS\Categories::$topSectionCategories != NULL){ print(CatNS\Categories::$topSectionCategories); } ?>
  
  <? if(!isset($_SESSION['authuser'])){ ?><div id="aaqmsg"></div><? } ?>
	
	<? if(IndexPgElNS\IndexPageRoute::$route != NULL){ print(IndexPgElNS\IndexPageRoute::$route); } ?>
  
	<? if(SubcatNS\Subcategories::$subcategoriesSection != NULL){ print(SubcatNS\Subcategories::$subcategoriesSection); } ?>
  
	<? if(IndexPgElNS\MiddleBlock::$openTag != NULL){ print(IndexPgElNS\MiddleBlock::$openTag); } ?>
	
	<? if(isset($qrow) && $qrow != false){ foreach($qrow as $row){ ?>
	<div id="<? print($row['id']); ?>" class="MBQuestionBlock">
	  
		<div id="<? print($row['uid']); ?>" class="questionUserID"></div>
	  <div id="qmsg<? print($row['id']); ?>" class="msg"></div>
	  
	  <? if(isset($_SESSION['authuser'])&& $_SESSION['authuser'] == true){ ?>
	  <? if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){ ?>
	  <div id="delmsg<? print($row['id']); ?>" class="qdel">
    <form method="post" action="<? print($actpage); ?>">
		  <input name="qid" type="hidden" value="<? print($row['id']); ?>" />
		  <div class="txtqdel">Delete this question ?</div>
		  <input class="yqdel" name="qdelete" type="submit" value="Yes" />
		  <input class="nqdel" type="button" value="No" onclick="cdelform('delmsg<? print($row['id']); ?>');" />
		</form>
	  </div>
	  
	  <div id="bu<? print($row['id']); ?>" class="qbu">
    <div class="txtqbu">Block this user ?</div>
		<!-- <div class="yqbu" onclick="blockuser('bu<? //print($row['id']); ?>','qmsg<? //print($row['id']); ?>','<? //print($row['uid']); ?>');">Yes</div> -->
		<div class="confirmButtonBlockUserForm">Yes</div>
		<div class="cancelButtonBlockUserForm">No</div>
	  </div>
	  <? }else{ ?>
	  <div id="qc<? print($row['id']); ?>" class="qc">
    <div class="qctxt">Complain about this question ?</div>
		<div class="qcyes" onclick="qeucompl('<? print($row['id']); ?>');">Yes</div>
		<div class="qcno" onclick="cqcform('qc<? print($row['id']); ?>');">No</div>
	  </div>
	  <? }} ?>
	  
	  <div class="MBQuestionPhoto">
      <a href="uq.php?uid=<? print($row['uid']); ?>" target="_blank">
        <div style="background-image: url('uphotos/<? if($row['uphoto'] != ''){ print($row['uphoto']); }else{ print('nouser50.png'); } ?>')"></div>
      </a>
    </div>
	  
    <div class="MBQuestionDetails">
		<? if($row['utype'] != 'admin'){ ?>
		<span class="QDItems"><? print($row['fname'].' '.$row['lname']); ?></span>
		<? }else{ ?>
		<span class="QDItems QDIAdmin"><? print($row['fname']); ?></span> 
		<? } ?>
    <a id="QDSubcategory" class="QDItems QDSubcategory" href="index.php?category=<? print($row['categorylink']); ?>&subcategory=<? print($row['subcategorylink']); ?>"><? print($row['subcategoryname']); ?></a>
    <span id="MBQDDate" class="QDItems"><? print($row['dt']); ?></span>
    <span id="MBQDVotesNumber" class="QDItems">Answers: <? print($row['answers']); ?></span>
		<span class="MBRightSideIcons">
		  <? if(isset($_SESSION['authuser'])&& $_SESSION['authuser'] == true){ ?>
		  <? if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){ ?>
		  <img class="iconBlockUser" title="Block user" src="icons/block.png" />
		  <img class="RSIcons" title="Delete" src="icons/delete.png" onclick="odelform('delmsg<? print($row['id']); ?>');" />
		  <? }else{ ?>
		  <img class="RSIcons" title="Complain" src="icons/flag.png" onclick="oqcform('qc<? print($row['id']); ?>');" />
		  <? } ?>
		  <? }else{ ?>
		  <img class="RSIcons" title="Complain" src="icons/flag.png" onclick="qneucompl('qmsg<? print($row['id']); ?>');" />
		  <? } ?>
		</span>
    </div>
	  
    <div id="wqtxt<? print($row['id']); ?>" class="MBQuestionText">
      <a id="qtxt<? print($row['id']); ?>" class="qtxt" href="<? print('question.php?q='.$row['id']); ?>"><?  print(nl2br($row['qtext'])); ?></a>
	  </div>
	  
    </div>
	<? }} ?>
	
	<? if(isset($rowsNum) && $rowsNum > $perPage){ ?>
	<div id="pgnumrow">
	<?
	if($rowFrom != 0){ print('<a class="arrpgnum" href="index.php'.$pagelink.$prevten.'">Prev 10</a>'); }
	for($i=$tenRow+1; $i<=$limitPageNum; $i++){ print('<a id="pgnum'.$i.'" class="pgnum" href="index.php'.$pagelink.$i.'">'.$i.'</a>'); }
	if($rowsNum > ($rowsLimit - 1)){ print('<a class="arrpgnum" href="index.php'.$pagelink.$nextten.'">Next 10</a>'); }
	?>
	</div>
	<? } ?>
	
	<? if(IndexPgElNS\MiddleBlock::$closeTag != NULL){ print(IndexPgElNS\MiddleBlock::$closeTag); } ?>
  
	<? if(CatNS\Categories::$footerSectionCategories != NULL){ print(CatNS\Categories::$footerSectionCategories); } ?>
  
	<? if(IndexPgElNS\Footer::$footer != NULL){ print(IndexPgElNS\Footer::$footer); } ?>

</div>
</div>

<script type='text/javascript'>

if(page != undefined){ document.getElementById('pgnum'+page).style.backgroundColor = 'rgb(232,232,232)'; }
else if(document.getElementById('pgnum1')){ document.getElementById('pgnum1').style.backgroundColor = 'rgb(232,232,232)'; }

<?
if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){
if(isset($_SESSION['qdel'])){
?>
document.getElementById('qmsg<? print($_SESSION['qdelid']); ?>').innerHTML = "<? print($_SESSION['qdel']); ?>";
jQuery('#qmsg<? print($_SESSION['qdelid']); ?>').slideDown({duration:1000}).delay(10000).slideUp({duration:1000});
<?
unset($_SESSION['qdel']); unset($_SESSION['qdelid']); }
}

if(isset($_SESSION['qsent'])){ unset($_SESSION['qsent']); }
?>

<? if(isset($nrecmin) && isset($nrecsec)){ ?>
qcountdown(<? print($nrecmin); ?>,<? print($nrecsec); ?>);
<? } ?>

</script>

</body>

</html>