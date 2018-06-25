<?
// user questions page
session_start();

$host = $_SERVER['HTTP_HOST'];
//print('$host: '.$host.'<br />');
$currenturl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$_SESSION['currenturl'] = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$qstr = $_SERVER['QUERY_STRING'];
$actpage = 'up.php?'.$qstr;
//print('$currenturl: '.$currenturl.'<br />');
//print('SESSION currenturl: '.$_SESSION['currenturl'].'<br />');
//print('$actpage: '.$actpage.'<br />');
$suid = $_SESSION['uid'];

if(isset($_GET['uid'])){
  $uid = mb_substr((string)$_GET['uid'],0,50,'UTF-8');
  unset($_GET['uid']);
  $uid = preg_replace('/[^\p{N}\p{L}\p{Zs}\_\-\+\=\&\']+/u','',$uid);
}

if(isset($_GET['changeemail'])){
if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
  include "changeemail.php";
}
}

try{ include "db.php"; }
catch(Exception $e){ $dberr = $e->getMessage()."<br />"; }

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
if(isset($_POST['biguplph'])){ include "bchphoto.php"; }
if(isset($_POST['smalluplph'])){ include "schphoto.php"; }
}

if(isset($uid)){
if(isset($db)){
try{

$ui = $db->query("SELECT * FROM users WHERE uid='$uid';");
$uinfo = $ui->fetchAll(PDO::FETCH_ASSOC);
//print('$uinfo: '); var_dump($uinfo); print('<br />');
if($uinfo != false){
  if($uinfo[0]['сemail'] != ''){
    $сemail = $uinfo[0]['сemail'];
    $pem = explode('@',$сemail);
    $spem = str_replace('.',' | ',$pem[1]);
    $femail = $pem[0].' | '.$spem;
  }
}

}
catch(Exception $e){
  $dberr = $e->getMessage()."<br />";
}

}
}

//print('SESSION: '); print_r($_SESSION); print('<br />');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  <title></title>
  
  <link rel="stylesheet" type="text/css" href="css/up.css" />
  <style type="text/css">
    
  </style>
  
  <script type='text/javascript' src='js/jquery.1.8.3.js'></script>
  <script type='text/javascript' src='js/jquery-ui.js'></script>
  <script type='text/javascript' src='js/functions.js'></script>
  <? if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){ ?>
  <script type='text/javascript' src='js/euser.js'></script>
  <script type='text/javascript' src='js/up.js'></script>
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

<? if(isset($_SESSION['chemail'])){ ?>
<div id="changedemail"><? print($_SESSION['chemail']); ?></div>
<? unset($_SESSION['chemail']); } ?>

<div id="tabsline">
  <? if($uinfo[0]['uid'] == $suid){ ?>
  <a class="tabs" href="uq.php?uid=<? print($uinfo[0]['uid']); ?>">My questions</a>
  <a class="tabs" href="ua.php?uid=<? print($uinfo[0]['uid']); ?>">My answers</a>
  <a class="tabs" href="uqa.php?uid=<? print($uinfo[0]['uid']); ?>">Answers to my questions</a>
  <a id="tlmyp" class="tabs">My page</a>
  <? }else{ ?>
  <a class="tabs" href="uq.php?uid=<? print($uinfo[0]['uid']); ?>">Questions</a>
  <a class="tabs" href="ua.php?uid=<? print($uinfo[0]['uid']); ?>">Answers</a>
  <a class="tabs" href="uqa.php?uid=<? print($uinfo[0]['uid']); ?>">Answers to user questions</a>
  <a id="tlmyp" class="tabs">User page</a>
  <? } ?>
</div>

<div id="leftBlock">
  <div id="lbuinfo">
    <div id="wlbuimg">
      <img id="lbuimg" src="ulphotos/<? if($uinfo[0]['ulphoto'] != ''){ print($uinfo[0]['ulphoto']); }else{ print('nouser200.png'); } ?>" />
    </div>
  </div>
  <div id="LBAdv1"></div>
</div>

<? if($uinfo[0]['uid'] == $suid){ ?>
<div id="rightBlock">
  <div id="fnchanges" class="uichanges"></div>
  <div class="weurbunit">
    <div class="eurbunit">First name:</div>
    <input id="frbfname" class="rbinp" maxlength="30" value="<? print($uinfo[0]['fname']); ?>" />
    <div class="rbbutton" onclick="up_chfname();">Change</div>
  </div>
  <div class="utopline">
  <div id="lnchanges" class="uichanges"></div>
  <div class="weurbunit">
    <div class="eurbunit">Last name:</div>
    <input id="frblname" class="rbinp" maxlength="30" value="<? print($uinfo[0]['lname']); ?>" />
    <div class="rbbutton" onclick="up_chlname();">Change</div>
  </div>
  </div>
  <div class="utopline">
  <div id="chcity" class="uichanges"></div>
  <div class="weurbunit">
    <div class="eurbunit">City:</div>
    <input id="frbcity" class="rbinp" maxlength="50" value="<? if($uinfo[0]['city'] != ''){ print($uinfo[0]['city']); } ?>" />
    <div class="rbbutton" onclick="up_chcity();">Change</div>
  </div>
  </div>
  <div class="utopline">
  <div id="chlemail" class="uichanges"></div>
  <div class="weurbunit">
    <div class="eurbunit">Login e-mail:</div>
    <input id="frblemail" class="rbinp" maxlength="50" value="<? print($uinfo[0]['lemail']); ?>" />
    <div class="rbbutton" onclick="up_chlemail();">Change</div>
  </div>
  </div>
  <div class="utopline">
  <div id="chсemail" class="uichanges"></div>
  <div class="weurbunit">
    <div class="eurbunit">Contact e-mail:</div>
    <input id="frbcemail" class="rbinp" maxlength="50" value="<? print($uinfo[0]['cemail']); ?>" />
    <div class="rbbutton" onclick="up_chcemail();">Change</div>
  </div>
  </div>
  <div class="utopline">
  <div id="chphone" class="uichanges"></div>
  <div class="weurbunit">
    <div class="eurbunit">Phone:</div>
    <input id="frbphone" class="rbinp" maxlength="30" value="<? print($uinfo[0]['phone']); ?>" />
    <div class="rbbutton" onclick="up_chphone();">Change</div>
  </div>
  </div>
  <div class="utopline">
  <? if(isset($_SESSION['buph'])){ ?><div id="buph"><? print($_SESSION['buph']); ?></div><? unset($_SESSION['buph']); } ?>
  <div id="wchbigph"><img id="chbigph" src="ulphotos/<? if($uinfo[0]['ulphoto'] != ''){ print($uinfo[0]['ulphoto']); }else{ print('nouser200.png'); } ?>" /></div>
  <div class="weurbunit">
    <div class="eurbunit">Large photo 100-300 px:</div>
    <form method="post" enctype="multipart/form-data" action="<? print($actpage); ?>">
    <input id="bigphotoupl" type="file" name="bigphoto" onclick="up_scrpos();" />
    <input id="btbigphoto" type="submit" name="biguplph" value="Upload" />
    </form>
  </div>
  </div>
  <div class="utopline">
  <? if(isset($_SESSION['suph'])){ ?><div id="suph"><? print($_SESSION['suph']); ?></div><? unset($_SESSION['suph']); } ?>
  <div id="wchsmallph"><img id="chsmallph" src="usphotos/<? if($uinfo[0]['usphoto'] != ''){ print($uinfo[0]['usphoto']); }else{ print('nouser50.png'); } ?>" /></div>
  <div class="weurbunit">
    <div class="eurbunit">Small photo 50-100 px:</div>
    <form method="post" enctype="multipart/form-data" action="<? print($actpage); ?>">
    <input id="smallphotoupl" type="file" name="smallphoto" onclick="up_scrpos();" />
    <input id="btsmallphoto" type="submit" name="smalluplph" value="Upload" />
    </form>
  </div>
  </div>
  <div class="utopline">
  <div id="chcont" class="uichanges"></div>
  <div id="wcontacts">
    <div id="ntconts">More contacts:</div>
    <textarea id="frbcont" maxlength="200" oninput="up_contcountchars(this);"><? print($uinfo[0]['cont']); ?></textarea>
    <div class="rbbutton" onclick="up_chcontacts();">Change</div>
  </div>
  <div id="wcountconts">
    <div id="countconts"><span id="charscounts">0</span><span> chars from 200</span></div>
  </div>
  </div>
</div>
<? }else{ ?>
<div id="rightBlock">
  <div class="wrbunit">
    <div class="frbunit">First name:</div>
    <div id="frbfname" class="srbunit"><? print($uinfo[0]['fname']); ?></div>
  </div>
  <div class="wrbunit">
    <div class="frbunit">Last name:</div>
    <div id="frblname" class="srbunit"><? print($uinfo[0]['lname']); ?></div>
  </div>
  <div class="wrbunit">
    <div class="frbunit">City:</div>
    <div id="frbcity" class="srbunit"><? if($uinfo[0]['city'] != ''){ print($uinfo[0]['city']); } ?></div>
  </div>
  <div class="wrbunit">
    <div class="frbunit">E-mail:</div>
    <div id="frbemail" class="srbunit"><? if(isset($femail)){ print($femail); } ?></div>
  </div>
  <div class="wrbunit">
    <div class="frbunit">Phone:</div>
    <div id="frbphone" class="srbunit"><? if($uinfo[0]['phone'] != ''){ print($uinfo[0]['phone']); } ?></div>
  </div>
  <? if($uinfo[0]['cont'] != ''){ ?>
  <div id="wmconts">
    <div class="frbunit">More contacts:</div>
    <div id="mconts"><? print(nl2br($uinfo[0]['cont'])); ?></div>
  </div>
<? } ?>
</div>
<? } ?>

<? }else{ ?>
<div id="wrid">Wrong user id</div>
<? } ?>

<div class="footerClLine">
  <span>Questions and answers</span>
</div>

</div>
</div>

<script type='text/javascript'>
up_contcountchars(document.getElementById('frbcont'));
<? if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){ ?>
if('upy' in cookie && cookie.upy != ''){
	scrollBy(0,cookie.upy);
  document.cookie = "upy=";
}
<? } ?>
</script>

</body>

</html>
