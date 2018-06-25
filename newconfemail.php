<?
session_start();
$host = $_SERVER['HTTP_HOST'];
//print('$host: '.$host.'<br />');
$currenturl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//print('$currenturl: '.$currenturl.'<br />');

try{ include "db.php"; }
catch(Exception $e){ $dberr = $e->getMessage()."<br />"; }

if(isset($db)){
  if(isset($_POST['btnewem'])){
    include "getnewconfemail.php";
  }
}

//print('SESSION: '); print_r($_SESSION); print('<br />');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="robots" content="noindex,nofollow" />
  
  <title></title>
  
  <link rel="stylesheet" type="text/css" href="css/newconfemail.css" />
  <style type="text/css">
    
  </style>
  
  <script type='text/javascript' src='js/jquery.1.8.3.js'></script>
  <script type='text/javascript' src='js/jquery-ui.js'></script>
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
  <div class="msg">This e-mail was already used for registration<br />Click here to get another confirmation letter and complete the registration</div>
  <? if(isset($dberr)){ ?><div class="msg"><? print($dberr); ?></div><? } ?>
  <? if(isset($_SESSION['conferr'])){ ?><div class="msg"><? print($_SESSION['conferr']); ?></div><? unset($_SESSION['conferr']); } ?>
  <? if(isset($_SESSION['sentemail'])){ ?><div class="msg"><? print($_SESSION['sentemail']); ?></div><? unset($_SESSION['sentemail']); } ?>
  <? if(isset($_SESSION['tmpregerr'])){ ?><div class="msg"><? print($_SESSION['tmpregerr']); ?></div><? unset($_SESSION['tmpregerr']); } ?>
  <div id="wrdreg"><span>Confirmation e-mail</span></div>
  <div id="spline1"></div>
  <form method="post" action="getemail.php">
  <? if(isset($_SESSION['emailerr'])){ ?><div id="emailerr"><? print($_SESSION['emailerr']); ?></div><? unset($_SESSION['emailerr']); } ?>
	<div id="wemail"><input id="email" name="email" type="text" maxlength="50" placeholder="E-mail" value="<? if(isset($_SESSION['gnemail'])){ print($_SESSION['gnemail']); } ?>" onfocus="authfchc(this,'b');" onblur="authfchc(this,'g');" /></div>
	<div id="entemail">Enter the e-mail you used for registration</div>
	<div id="wbtnewem"><input id="btnewem" name="btnewem" type="submit" value="Get a new e-mail" /></div>
	<div id="spline2"></div>
	<div id="backto">
	  <div id="authpage"><a id="lauthpage" href="auth.php">Auth page</a></div>
	  <div id="mainpage"><a id="lmainpage" href="index.php">Main page</a></div>
	</div>
  </form>
</div>

</body>

</html>
