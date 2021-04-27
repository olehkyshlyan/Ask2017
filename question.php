<?
session_start();

$host = $_SERVER['HTTP_HOST'];
$currenturl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$_SESSION['currenturl'] = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$currentRequestURI = $_SERVER['REQUEST_URI'];
//print('$currentRequestURI: '.$currentRequestURI.'<br />');
$_SESSION['currentRequestURI'] = $currentRequestURI;
//print('$currenturl: '.$currenturl.'<br />');
//print('SESSION currenturl: '.$_SESSION['currenturl'].'<br />');
//$actpage = basename($currenturl);
//$actpage = basename($currenturl);
//print('$actpage: '.$actpage.'<br />');

try{
	include_once "db/db.php";
}
catch(Exception $e){
	$dberr = $e->getMessage()."<br />";
}

include "categories.php";
include "subcategories.php";
use DB as DBNS;

if(isset($_POST['aentbt'])){ include "aauth.php"; }

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

if(isset($_POST['quplimg'])){
  if(isset($_SESSION['blocked']) && $_SESSION['blocked'] == 'no'){
    include "quplimg.php";
  }
}

if(isset($_POST['addquestion'])){
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

if(isset($_POST['auplimg'])){
  if(isset($_SESSION['blocked']) && $_SESSION['blocked'] == 'no'){
    include "auplimg.php";
  }
}

if(isset($_POST['addanswer'])){
  if(isset($_SESSION['lrec'])){
    $acdate = gmdate('Y-m-d H:i:s');
    $alrec = $_SESSION['lrec'];
    $alrec = strtotime($alrec);
    $alrec = strtotime('+ 2 minutes',$alrec);
    $arecdt = date('Y-m-d H:i:s',$alrec);
    if($acdate > $arecdt){
      include "addanswer.php";
    }
    elseif($arecdt > $acdate){
      header('Location:http://'.$currenturl); exit();
    }
  }
}

if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){
if(isset($_POST['qqdelete'])){ include "qqdelete.php"; }
if(isset($_POST['adelete'])){ include "adelete.php"; }
}
}

$digitid = false;
$qrow = false;
if(isset($_GET['q'])){
  $qid = preg_replace('/[^0-9]/','',substr((string)$_GET['q'],0,9));
  if($qid != ''){ $digitid = true; }
}

if($digitid == true){
try{
// выборка вопроса
$qresult = DBNS\Database::$dbHandler->query("SELECT * FROM questions WHERE id='".$qid."';");
//print('$qresult: '); var_dump($qresult); print('<br />');
$qrow = $qresult->fetch(PDO::FETCH_ASSOC);
//print('$qrow: '); var_dump($qrow); print('<br />');
if($qrow != false){
  // выборка ответов
  $qnum = $qid;
  $actpage = 'question.php?q='.$qid;
  $qauthor = $qrow['uid'];
  $perpage = 10;
  $limit = $perpage + 1;
  $mopp = $perpage - 1;
  $aresult = DBNS\Database::$dbHandler->query("SELECT * FROM answers WHERE qid='$qid' ORDER BY dt Desc LIMIT $limit;");
  //print('$aresult: '); var_dump($aresult); print('<br />');
  $arow = $aresult->fetchAll(PDO::FETCH_ASSOC);
  //print('$arow: '); var_dump($arow); print('<br />');
  $larow = count($arow);
  //print('$larow: '.$larow.'<br />');
  if($larow > $perpage){
    $laid = $arow[$mopp]['id'];
    //print('$laid: '.$laid.'<br />');
    unset($arow[$perpage]);
  }
  if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
    $auid = $_SESSION['uid'];
    $ulrec = DBNS\Database::$dbHandler->query("SELECT lrec FROM users WHERE uid='$auid';")->fetchAll(PDO::FETCH_ASSOC);
  }
}
}
catch(Exception $e){
  $dberr = $e->getMessage()."<br />";
}
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

// функция заполнения выпадающего diva категориями
function fillInCategories(){
  global $categories;
  foreach($categories as $k=>$v)
  { print('<span class="categories"><a href="index.php?category='.$k.'" class="categorieslink">'.$v.'</a></span>'); }
}

//print('$_SESSION: '); print_r($_SESSION); print('<br />');

//print('$_FILES: '); print_r($_FILES); print('<br />');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  <title><? if($digitid == true && $qrow != false){ print(nl2br($qrow['qtext'])); } ?></title>
  
  <link rel="stylesheet" type="text/css" href="css/askwindow.css" />
  <link rel="stylesheet" type="text/css" href="css/question.css" />
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
  var qpqnum; var qplaid;
  <? if(isset($qnum)){ print('qpqnum = '.$qnum.';'); } ?>
  <? if(isset($laid)){ print('qplaid = '.$laid.';'); } ?>
  </script>
  
</head>

<body>

<? if(isset($dberr)){ ?><div id="wdberr"><div id="dberr"><? print($dberr); ?></div></div><? } ?>

<div id="wmcont">
<div id="mcont">
  
  <div id="mainTopPanel">
	<? if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){ ?>
	<? if(isset($_SESSION['utype'])){ ?>
	<a id="mypagemtp" href="uq.php?uid=<? print($_SESSION['uid']); ?>">My page</a><span id="spmypage"></span>
	<? if($_SESSION['utype'] != 'admin'){ ?>
  <span id="MTPUsersName"><? print($_SESSION['fname'].' '.$_SESSION['lname']); ?></span>
	<? }elseif($_SESSION['utype'] == 'admin'){ ?>
	<a href="cqadmin.php"><span id="MTPUsersName"><? print($_SESSION['fname']); ?></span></a>
	<? }} ?>
	<form style="display:inline;" method="post" action="index.php">
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
  
  <? if(isset($_SESSION['aautherr']) && $_SESSION['aautherr'] != ''){ ?>
  <div id="aautherr"><img id="crossbt" onclick="slup500('aautherr');" src="icons/close.png" />
  <? print($_SESSION['aautherr']); ?>
  </div>
  <? unset($_SESSION['aautherr']); } ?>
  
  <? if(isset($_SESSION['uautherr'])){ if($_SESSION['uautherr'] != ''){ ?>
  <div id="uautherr"><img id="crossbt" onclick="slup500('uautherr');" src="icons/close.png" />
  <? print($_SESSION['uautherr']); ?>
  </div>
  <? unset($_SESSION['uautherr']); }} ?>
  
  <? if(isset($_SESSION['aqerr']) && $_SESSION['aqerr'] != ''){ ?>
  <div id="qadderr">
  <div id="aqeredline">Error when adding the question<img id="aqecrossbt" onclick="slup500('qadderr');" src="icons/close.png /></div>
  <div id="aqecont"><? print($_SESSION['aqerr']); ?></div>
  </div>
  <? unset($_SESSION['aqerr']); } ?>
  
  <? if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){ if(isset($_SESSION['blocked']) && $_SESSION['blocked'] == 'no'){ include "askwindow.php"; }} ?>
  
  <div id="leftBlock">
    <div id="LBAdv1"></div>
    <div id="LBAdv2"></div>
    <div id="LBAdv3"></div>
  </div>
  
  <div id="mbmainbts">
    <div id="categoriesWord" onmouseout="hideCategories(this,event);"><span id="categoriesWordSpan" onclick="showCategories();">Categories</span></div>
    <div id="MBAskWord"><span <? if(isset($_SESSION['euser'])){ if(isset($_SESSION['blocked']) && $_SESSION['blocked'] == 'no'){ ?>onclick="qwopen();"<? }} else { ?>onclick="askaq('aaqmsg');"<? } ?>>Ask a question !</span></div>
  </div>
  
  <div id="categwrap">
    <div id="categories" onmouseout="hideCategories(this,event);"><? fillInCategories(); ?></div>
  </div>
  
  <? if(!isset($_SESSION['euser'])){ ?><div id="aaqmsg"></div><? } ?>
  
  <div id="MBCatHistory">
  <a href="index.php" id="MBAllCat" class="MBCatElem">Main page</a>
  <? if($qrow != false){ ?>
  <span>> </span><a href="index.php?category=<? print($qrow['categorylink']); ?>" class="MBCatElem"><? print($qrow['categoryname']); ?></a>
  <span>> </span><a href="index.php?category=<? print($qrow['categorylink']); ?>&subcategory=<? print($qrow['subcategorylink']); ?>" class="MBCatElem"><? print($qrow['subcategoryname']); ?></a>
  <? } ?>
  </div>
  
  <div id="middleBlock">
  
	<? if($digitid == true){ if($qrow != false){ ?>
	
	<div id="MBQuestionBlock">
	  
	  <div id="qmsg<? print($qrow['id']); ?>" class="msg"></div>
	  <div id="cq" class="msg"></div>
	  <div id="answq" class="msg"></div>
	  
	  <? if(isset($_SESSION['euser'])&& $_SESSION['euser'] == true){ ?>
	  <? if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){ ?>
	  <div id="qdelmsg" class="qdel">
    <form method="post" action="<? print($actpage); ?>">
		  <input name="qid" type="hidden" value="<? print($qrow['id']); ?>" />
		  <input name="qadcatlink" type="hidden" value="<? print($qrow['categorylink']); ?>" />
		  <input name="qadsubcatlink" type="hidden" value="<? print($qrow['subcategorylink']); ?>" />
		  <div class="txtqdel">Delete this question ?</div>
		  <input class="yqdel" name="qqdelete" type="submit" value="Yes" />
		  <input class="nqdel" type="button" value="No" onclick="cdelform('qdelmsg');" />
		</form>
	  </div>
	  
	  <div id="qbu" class="qbu">
    <div class="txtqbu">Block this user ?</div>
		<div class="yqbu" onclick="blockuser('qbu','qmsg<? print($qrow['id']); ?>','<? print($qrow['uid']); ?>')">Yes</div>
		<div class="nqbu" onclick="cbuform('qbu');">No</div>
	  </div>
	  <? }else{ ?>
	  <div id="qc<? print($qrow['id']); ?>" class="qc">
    <div id="qctxt">Complain about this question ?</div>
		<div id="qcyes" onclick="qeucompl('<? print($qrow['id']); ?>');">Yes</div>
		<div id="qcno" onclick="cqcform('qc<? print($qrow['id']); ?>');">No</div>
	  </div>
	  <? }} ?>
	  
	  <div id="MBQuestionPhoto">
      <a href="uq.php?uid=<? print($qrow['uid']); ?>" target="_blank">
        <div style="background-image: url('uphotos/<? if($qrow['uphoto'] != ''){ print($qrow['uphoto']); }else{ print('nouser50.png'); } ?>')"></div>
      </a>
    </div>
	  
    <div class="MBQuestionDetails">
		<? if($qrow['utype'] != 'admin'){ ?>
    <span id="MBQDName" class="MBQuestionDetailsItems"><? print($qrow['fname'].' '.$qrow['lname']); ?></span>
		<? }elseif($qrow['utype'] == 'admin'){ ?>
		<span id="MBQDName" class="MBQuestionDetailsItems DIAdmin"><? print($qrow['fname']); ?></span>
		<? } ?>
    <span id="MBQDSubcategory" class="MBQuestionDetailsItems"><? print($qrow['subcategoryname']); ?></span>
    <span id="MBQDDate" class="MBQuestionDetailsItems"><? print($qrow['dt']); ?></span>
    <span id="MBQDVotesNumber" class="MBQuestionDetailsItems">Answers: <? print($qrow['answers']); ?></span>
		<span class="MBRightSideIcons">
		  <? if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){ ?>
		  <? if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){ ?>
		  <img class="RSIcons" title="Block user" src="icons/block.png" onclick="obuform('qbu');" />
		  <img class="RSIcons" title="Delete" src="icons/delete.png" onclick="odelform('qdelmsg');" />
		  <? }else{ ?>
		  <img class="RSIcons" title="Complain" src="icons/flag.png" onclick="oqcform('qc<? print($qrow['id']); ?>');" />
		  <? } ?>
		  <? }else{ ?>
		  <img class="RSIcons" title="Complain" src="icons/flag.png" onclick="qneucompl('qmsg<? print($qrow['id']); ?>');" />
		  <? } ?>
		</span>
    </div>
	  
    <div id="MBQuestionText">
	    <div id="MBQTxt"><? print(nl2br($qrow['qtext'])); ?></div>
	  </div>
	  
	  <? if($qrow['qdetails'] != ''){ ?>
	  <div id="wQTxtDet">
	    <div id="qTxtDet"><? print(nl2br($qrow['qdetails'])); ?></div>
	  </div>
	  <script type='text/javascript'>
	  var dwqtxt = document.getElementById('wQTxtDet');
	  var dqtxt = document.getElementById('qTxtDet');
	  var hdqtxt = dqtxt.clientHeight;
	  var mhdqtxt = 15;
	  if(hdqtxt > 15 && hdqtxt < 91){ mhdqtxt = hdqtxt; }
	  else if(hdqtxt > 90){ mhdqtxt = 90; }
	  if(hdqtxt > 90){
		dqtxt.style.height = mhdqtxt+'px';
		dwqtxt.insertAdjacentHTML('beforeend','<div class="wqshow"><div id="qdshmore" class="qshow" onclick="qdqshowmore(hdqtxt);">Show more</div><div id="qdshless" class="qshow" style="z-index:-1;" onclick="qdqshowless(mhdqtxt);">Show less</div></div>');
	  }
	  </script>
	  <? } ?>
	  
	  <? if($qrow['qimages'] != ''){ $qexpimgs = explode('|sp|',$qrow['qimages']); $qeil = count($qexpimgs); ?>
	  <div id="MBWrapQuestBxSlider">
    <div id="MBQuestBxSlider">
		<? for($i=0;$i<$qeil;$i++){ ?>
		  <div class="bxslidewrap">
		    <a href="images/<? print($qrow['imgf']); ?>/<? print($qexpimgs[$i]); ?>" target="_blank">
			  <img src="images/<? print($qrow['imgf']); ?>/<? print($qexpimgs[$i]); ?>" class="imgBxSlide" />
			</a>
		  </div>
		<? } ?>
		</div>
	  </div>
	  <script type='text/javascript'>
		var mbQBxSlLen = <? print($qeil); ?>;
		if(mbQBxSlLen > 3){
		  var mbWrapQBxSl = document.getElementById('MBWrapQuestBxSlider');
		  mbWrapQBxSl.insertAdjacentHTML('afterbegin','<div class="bxSlNextArrow" onclick="jMbQBxSl.goToNextSlide();"><img src="icons/next.png" /></div>');
		  mbWrapQBxSl.insertAdjacentHTML('afterbegin','<div class="bxSlPrevArrow" onclick="jMbQBxSl.goToPrevSlide();"><img src="icons/prev.png" /></div>');
		}
		jMbQBxSl = jQuery('#MBQuestBxSlider').bxSlider({ slideMargin: 7, pager: false, controls: false, maxSlides: 3, moveSlides: 1, slideWidth: 160 });
	  </script>
	  <? } ?>
	  
	  <? if(!isset($_SESSION['euser'])){ ?>
	  <div id="MBQuestionAnswer" onclick="qentersite('answq');">Answer</div>
	  <? } ?>
    </div>
	
	<? if(isset($_SESSION['aaerr']) && $_SESSION['aaerr'] != ''){ ?>
	<div id="addanswerror">
	<div id="aaeredline">Error when adding the answer<img id="aaecrossbt" onclick="slup500('addanswerror');" src="icons/close.png" /></div>
	<div id="aaecont"><? print($_SESSION['aaerr']); ?></div>
	</div>
	<? unset($_SESSION['aaerr']); } ?>
	
	<? if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){ if(isset($_SESSION['blocked']) && $_SESSION['blocked'] == 'no'){ ?>
	<div id="MBAnswerForm">
	  <form method="post" enctype="multipart/form-data" action="<? print($actpage); ?>">
    <input type="hidden" name="qnum" value="<? print($qnum); ?>" />
    <input type="hidden" name="qauthor" value="<? print($qauthor); ?>" />
		<? if(isset($nrecmin) && isset($nrecsec)){ $alrmin = $nrecmin; $alrsec = $nrecsec; if($alrmin < 10){ $alrmin = '0'.$alrmin; } if($alrsec < 10){ $alrsec = '0'.$alrsec; } ?>
    <div id="mbAFTimer"><span>Next answer in: </span><span id="mbAFCount"><? print($alrmin.':'.$alrsec); ?></span></div>
    <? } ?>
    
    <textarea id="MBAnswTxtAr" name="atext" maxlength="1000" onkeyup="acountchar(this);" oninput="acountchar(this);"><? if(isset($_SESSION['atext'])){ print($_SESSION['atext']); } ?></textarea><br />
		<div id="MBAnsFormChar"><span id="AFAnswBt" onclick="sldn500('MBAnsFormSend');">Answer</span><span id="AFInsImg" onclick="sldn500('AFAddImg');">Insert image</span><span id="atlines">0</span><span> lines from 20</span><span id="atxtsp"></span><span id="atchars">0</span><span> chars from 1000</span></div>
		
		<script type='text/javascript'>var mbAnswTxtAr = document.getElementById('MBAnswTxtAr'); acountchar(mbAnswTxtAr);</script>
		
		<div id="AFAddImg">
		  <input id="AFPhotoUpload" type="file" name="aimg" />
		  <input class="afInpAddImg" type="submit" name="auplimg" value="Upload" onclick="qpscrpos();" />
		  <input class="afInpAddImg" type="button" value="Cancel" onclick="aCancImgUpl();" />
		</div>
		
		<? if(isset($_SESSION['auplphoto'])){ $taexpimgs = explode("|sp|",$_SESSION['auplphoto']); $taeil = count($taexpimgs); $afStSl = 0; if($taeil > 3){ $afStSl = $taeil - 3; } ?>
		<div id="AFWrapSlider">
		  <div id="AFSecWrSl">
      <div id="AFBxSlider">
			<? for($i=0;$i<$taeil;$i++){ ?>
			  <div class="afSlideWrap">
			    <div class="afWrSlDelImg" onclick="aDelImg('<? print($taexpimgs[$i]); ?>');"><img class="afSlDelImg" src="icons/delimage.png" /></div>
				<img src="tmpimg/<? print($taexpimgs[$i]); ?>" class="afImgSlide" />
			  </div>
			<? } ?>
			</div>
		  </div>
		  <div class="AFCountImg"><span id="AFImgNum"><? print($taeil); ?></span><span> images of 10</span></div>
		
		<script type='text/javascript'>
		var afBxSlLen = <? print($taeil); ?>;
		var afStSl = <? print($afStSl); ?>;
		if(afBxSlLen > 3){
		  var afWrSl = document.getElementById('AFWrapSlider');
		  afWrSl.insertAdjacentHTML('afterbegin','<div id="afNextArrow" onclick="afBxSlider.goToNextSlide();"><img src="icons/next.png" /></div>');
		  afWrSl.insertAdjacentHTML('afterbegin','<div id="afPrevArrow" onclick="afBxSlider.goToPrevSlide();"><img src="icons/prev.png" /></div>');
		}
		afBxSlider = jQuery('#AFBxSlider').bxSlider({ startSlide: afStSl, slideMargin: 7, pager: false, controls: false, maxSlides: 3, moveSlides: 1, slideWidth: 160 });
		</script>
		</div>
		<? } ?>
    
		<div id="MBAnsFormSend">
		  <span id="AFSendQuest">Send the answer ?</span>
		  <input type="submit" name="addanswer" value="Send" />
		  <input type="button" value="Cancel" onclick="deleteAnswer();" />
		</div>
	  </form>
	</div>
	<? }} ?>
	
	<? if($arow != false){ foreach($arow as $row){ ?>
	<div class="MBAnswerBlock">
	  
	  <div id="amsg<? print($row['id']); ?>" class="amsg"></div>
	  
	  <? if(isset($_SESSION['euser'])&& $_SESSION['euser'] == true){ ?>
	  <? if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){ ?>
	  <div id="adelmsg<? print($row['id']); ?>" class="aadm">
    <form method="post" action="<? print($actpage); ?>">
		  <input name="aid" type="hidden" value="<? print($row['id']); ?>" />
		  <input name="qid" type="hidden" value="<? print($row['qid']); ?>" />
		  <div class="txtaadm">Delete this answer ?</div>
		  <input class="yadel" name="adelete" type="submit" value="Yes" onclick="qpscrpos();" />
		  <input class="nadel" type="button" value="No" onclick="cdelform('adelmsg<? print($row['id']); ?>');" />
		</form>
	  </div>
	  
	  <div id="abu<? print($row['id']); ?>" class="abu">
    <div class="txtabu">Block this user ?</div>
    <div class="yabu" onclick="blockuser('abu<? print($row['id']); ?>','amsg<? print($row['id']); ?>','<? print($row['uid']); ?>');">Yes</div>
		<div class="nabu" onclick="cbuform('abu<? print($row['id']); ?>');">No</div>
	  </div>
	  <? }else{ ?>
	  <div id="ac<? print($row['id']); ?>" class="ac">
    <div class="actxt">Complain about this answer ?</div>
		<div class="acyes" onclick="aeucompl('<? print($row['id']); ?>');">Yes</div>
		<div class="acno" onclick="cacform('ac<? print($row['id']); ?>');">No</div>
	  </div>
	  <? }} ?>
	  
	  <div class="MBAnswerPhoto">
      <a href="uq.php?uid=<? print($row['uid']); ?>" target="_blank">
        <div style="background-image: url('uphotos/<? if($row['uphoto'] != ''){ print($row['uphoto']); }else{ print('nouser50.png'); } ?>')"></div>
      </a>
    </div>
	  
    <div class="MBAnswerDetails">
		<? if($row['utype'] != 'admin'){ ?>
		<span class="MBAnswerDetailsItems"><? print($row['fname'].' '.$row['lname']); ?></span>
		<? }else{ ?>
		<span class="MBAnswerDetailsItems DIAdmin"><? print($row['fname']); ?></span>
		<? } ?>
    <span id="MBADDate" class="MBAnswerDetailsItems"><? print($row['dt']); ?></span>
		<span class="MBRightSideIcons">
		  <? if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){ ?>
		  <? if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){ ?>
		  <img class="RSIcons" title="Block user" src="icons/block.png" onclick="obuform('abu<? print($row['id']); ?>');" />
		  <img class="RSIcons" title="Delete" src="icons/delete.png" onclick="odelform('adelmsg<? print($row['id']); ?>');" />
		  <? }else{ ?>
		  <img class="RSIcons" title="Complain" src="icons/flag.png" onclick="oacform('ac<? print($row['id']); ?>');" />
		  <? } ?>
		  <? }else{ ?>
		  <img class="RSIcons" title="Complain" src="icons/flag.png" onclick="aneucompl('amsg<? print($row['id']); ?>','answer');" />
		  <? } ?>
		</span>
    </div>
	  
	  <div id="watxt<? print($row['id']); ?>" class="MBAnswerText">
	    <div id="atxt<? print($row['id']); ?>" class="atxt"><? print(nl2br($row['atext'])); ?></div>
	  </div>
	  <script type='text/javascript'>
	  var watxt<? print($row['id']); ?> = document.getElementById('watxt<? print($row['id']); ?>');
	  var atxt<? print($row['id']); ?> = document.getElementById('atxt<? print($row['id']); ?>');
	  var hatxt<? print($row['id']); ?> = atxt<? print($row['id']); ?>.clientHeight;
	  var mhatxt<? print($row['id']); ?> = 30;
	  if(hatxt<? print($row['id']); ?> > 30 && hatxt<? print($row['id']); ?> < 91){ mhatxt<? print($row['id']); ?> = hatxt<? print($row['id']); ?>; }
	  else if(hatxt<? print($row['id']); ?> > 90){ mhatxt<? print($row['id']); ?> = 90; }
	  if(hatxt<? print($row['id']); ?> > 90){
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
	  jABBxSl<? print($row['id']); ?> = jQuery('#ABBxSlider<? print($row['id']); ?>').bxSlider({ slideMargin: 7, pager: false, controls: false, maxSlides: 3, moveSlides: 1, slideWidth: 160 });
	  </script>
	  <? } ?>
	
  </div>
	<? }} ?>
	<? }else{ ?>
	<div id="dexist">The question with this 'id' doesn't exist</div>
	<? }}else{ ?>
	<div id="wrid">Question 'id' is wrong</div>
	<? } ?>
	
  <? if(isset($laid)){ ?>
  <div id="cshowmore"><input id="btshmore" type="button" value="Show more" onclick="showMoreAnswers(qpqnum,qplaid);" /></div>
  <? } ?>
  
	<div class="MBQuestionClearLine"></div>
  </div>
  
  <div id="ftcategories">
  <? foreach($categories as $k=>$v){ print('<span class="ftcategories"><a href="index.php?category='.$k.'" class="ftcatlink">'.$v.'</a></span>'); } ?>
  </div>
  
  <div class="footerClLine">
    <div id="sitename">Questions and answers</div>
  </div>

</div>
</div>

<script type='text/javascript'>
<?
if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){
if(isset($_SESSION['qqdel'])){
?>
document.getElementById('cq').innerHTML = "<? print($_SESSION['qqdel']); ?>";
jQuery('#cq').slideDown({duration:1000}).delay(10000).slideUp({duration:1000});
<?
unset($_SESSION['qqdel']); }
if(isset($_SESSION['adel'])){
?>
document.getElementById('amsg<? print($_SESSION['adelid']); ?>').innerHTML = "<? print($_SESSION['adel']); ?>";
jQuery('#amsg<? print($_SESSION['adelid']); ?>').slideDown({duration:1000}).delay(10000).slideUp({duration:1000});
<? unset($_SESSION['adel']); unset($_SESSION['adelid']); } ?>
<? } ?>

<? if(isset($nrecmin) && isset($nrecsec)){ ?>
qcountdown(<? print($nrecmin); ?>,<? print($nrecsec); ?>);
acountdown(<? print($nrecmin); ?>,<? print($nrecsec); ?>);
<? } ?>

if('qpy' in cookie && cookie.qpy != ''){
	scrollBy(0,cookie.qpy);
  document.cookie = "qpy=";
}

</script>

<?
if(isset($_SESSION['qsent'])){ unset($_SESSION['qsent']); }
if(isset($_SESSION['asent'])){ unset($_SESSION['asent']); }
?>

</body>

</html>