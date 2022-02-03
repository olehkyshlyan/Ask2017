<?
session_start();
$host = $_SERVER['HTTP_HOST'];
//print('$host: '.$host.'<br />');
$currenturl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//print('$currenturl: '.$currenturl.'<br />');

try{ include "../db/db.php"; }
catch(Exception $e){ $dberr = $e->getMessage()."<br />"; }

if(isset($_POST['btcracc'])){
  include "regprocess.php";
}

if(isset($_GET['uid'])){
  include "regconfirm.php";
}

//print('SESSION: '); print_r($_SESSION); print('<br />');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="robots" content="noindex,nofollow" />
  
  <title></title>
  
  <link rel="stylesheet" type="text/css" href="../css/registration/registration.css" />
  <style type="text/css">
    
  </style>
  
  <script type='text/javascript' src='../js/jquery.3.4.1.js'></script>
  <script type='text/javascript' src='../js/jquery-ui.1.12.1.js'></script>
  <script type='text/javascript' src=''></script>
  <script type="text/javascript">
    function authfchc(t,c){
	  if(c == 'b'){ jQuery(t).animate({'border-color':'rgb(25,142,255)'},300); }
	  else if(c == 'g'){ jQuery(t).animate({'border-color':'rgb(215,215,215)'},300); }
    }
  </script>
</head>

<body>

<div id="auth">
  <? if(isset($dberr)){ ?><div class="msg"><? print($dberr); ?></div><? } ?>
  <? if(isset($_SESSION['conferr'])){ ?><div class="msg"><? print($_SESSION['conferr']); ?></div><? unset($_SESSION['conferr']); } ?>
  <? if(isset($_SESSION['sentemail'])){ ?><div class="msg"><? print($_SESSION['sentemail']); ?></div><? unset($_SESSION['sentemail']); } ?>
  <? if(isset($_SESSION['tmpregerr'])){ ?><div class="msg"><? print($_SESSION['tmpregerr']); ?></div><? unset($_SESSION['tmpregerr']); } ?>
  <div id="wrdreg"><span>Registration</span></div>
  <div id="spline1"></div>
  <form method="post" action="register.php">
  <? if(isset($_SESSION['fnlnerr'])){ ?><div id="fnlnerr"><? print($_SESSION['fnlnerr']); ?></div><? unset($_SESSION['fnlnerr']); } ?>
  <div id="wfnln">
	  <input id="fn" name="fn" type="text" maxlength="30" placeholder="First name" value="<? if(isset($_SESSION['regfn'])){ print($_SESSION['regfn']); } ?>" onfocus="authfchc(this,'b');" onblur="authfchc(this,'g');" />
	  <input id="ln" name="ln" type="text" maxlength="30" placeholder="Last name" value="<? if(isset($_SESSION['regln'])){ print($_SESSION['regln']); } ?>" onfocus="authfchc(this,'b');" onblur="authfchc(this,'g');" />
	</div>
	<div id="rfnln">Allowed characters: letters, numbers, apostrophe</div>
  <? if(isset($_SESSION['emailerr'])){ ?><div id="emailerr"><? print($_SESSION['emailerr']); ?></div><? unset($_SESSION['emailerr']); } ?>
	<div id="wemail"><input id="email" name="email" type="text" maxlength="50" placeholder="E-mail" value="<? if(isset($_SESSION['regemail'])){ print($_SESSION['regemail']); } ?>" onfocus="authfchc(this,'b');" onblur="authfchc(this,'g');" /></div>
	<div id="entemail">Enter your real e-mail</div>
	<? if(isset($_SESSION['pswerr'])){ ?><div id="pswerr"><? print($_SESSION['pswerr']); ?></div><? unset($_SESSION['pswerr']); } ?>
	<div id="wpsw"><input id="psw" name="psw" type="password" maxlength="20" placeholder="Password" onfocus="authfchc(this,'b');" onblur="authfchc(this,'g');" /></div>
	<div id="rpsw1">Only numbers and latin letters, from 10 to 20 characters</div>
	<div id="wbtcracc"><input id="btcracc" name="btcracc" type="submit" value="Create account" /></div>
	<div id="spline2"></div>
	<div id="backto">
	  <div id="prevpage"><a id="lprevpage" href="auth.php">Previous page</a></div>
	  <div id="mainpage"><a id="lmainpage" href="index.php">Main page</a></div>
	</div>
  </form>
</div>

</body>

</html>
