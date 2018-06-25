<?
session_start();
$host = $_SERVER['HTTP_HOST'];
$currenturl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

// the previous page is defined if to click "Back"
$prevpage = 'index.php';
if(isset($_SESSION['currenturl'])){
$cu = mb_strtolower($_SESSION['currenturl'],'UTF-8');
if(strpos($cu,'index.php') > 0){ $prevpage = strstr($cu,'index.php'); }
elseif(strpos($cu,'question.php') > 0){ $prevpage = strstr($cu,'question.php'); }
}
print('$prevpage: '.$prevpage.'<br />');

try{ include "db.php"; }
catch(Exception $e){ print($e->getMessage()."<br />"); }

if(isset($_POST['btlogin'])){
  include "doauth.php";
}

//print('SESSION: '); print_r($_SESSION); print('<br />');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="robots" content="noindex,nofollow" />
  
  <title></title>
  
  <link rel="stylesheet" type="text/css" href="css/auth.css" />
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
  <div id="wauth"><span>Authorization</span></div>
  <form method="post" action="auth.php">
  <? if(isset($_SESSION['autherr'])){ ?><div id="autherr"><? print($_SESSION['autherr']); ?></div><? unset($_SESSION['autherr']); } ?>
  <? if(isset($_SESSION['emautherr'])){ ?><div id="emautherr"><? print($_SESSION['emautherr']); ?></div><? unset($_SESSION['emautherr']); } ?>
	<div id="wemail"><input id="email" name="email" type="text" maxlength="50" placeholder="E-mail" onfocus="authfchc(this,'b');" onblur="authfchc(this,'g');" value="<? if(isset($_SESSION['authemail'])){ print($_SESSION['authemail']); unset($_SESSION['authemail']); } ?>" /></div>
  <? if(isset($_SESSION['pswautherr'])){ ?><div id="pswautherr"><? print($_SESSION['pswautherr']); ?></div><? unset($_SESSION['pswautherr']); } ?>
	<div id="wpsw"><input id="psw" name="psw" type="password" maxlength="20" placeholder="Password" onfocus="authfchc(this,'b');" onblur="authfchc(this,'g');" /></div>
	<div id="wbtlogin"><input id="btlogin" name="btlogin" type="submit" value="Log in" /></div>
  </form>
	<div id="sepline2"></div>
	<div id="backto">
	  <div id="prevpage"><a id="lprevpage" href="<? print($prevpage); ?>">Previous page</a></div>
	  <div id="mainpage"><a id="lmainpage" href="index.php">Main page</a></div>
	</div>
	<div id="sepline3"></div>
	<div id="wforgpsw"><a id="forgpsw" href="forgotpsw.php">Forgot your password ?</a></div>
	<div id="sepline4"></div>
	<div id="wcreateacc"><a id="btcracc" href="register.php">Create account</a></div>
</div>

</body>

</html>
