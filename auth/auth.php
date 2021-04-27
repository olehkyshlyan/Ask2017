<?
session_start();
$host = $_SERVER['HTTP_HOST'];
$currenturl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

class Authorization{
	public $separatingLine = NULL;
	public $indexRequestURI = NULL;
	public $previousPageURI = NULL;
	public $returnLinksSection = NULL;
	public $passwordRecoverySection = NULL;
	public $newAccountSection = NULL;
	
	function __construct(){
		$this->createSeparatingLine();
		$this->setIndexRequestURI();
		$this->setPreviousPageURI();
		$this->createReturnLinksSection();
		$this->createPasswordRecoverySection();
		$this->createNewAccountSection();
	}
	
	private function createSeparatingLine(){
		$this->separatingLine = '<div class="separatingLine"></div>';
	}
	
	private function setIndexRequestURI(){
		if(isset($_SESSION['indexRequestURI'])){
			$this->indexRequestURI = mb_strtolower($_SESSION['indexRequestURI'],'UTF-8');
		}
	}
	
	private function setPreviousPageURI(){
		if(isset($_SESSION['currentRequestURI'])){
			$this->previousPageURI = mb_strtolower($_SESSION['currentRequestURI'],'UTF-8');
		}
	}
	
	private function createReturnLinksSection(){
		$rls = '<div id="returnLinksSection">';
		$rls .= '<div id="previousPage"><a id="prevPageLink" href="'.$this->previousPageURI.'">Previous page</a></div>';
		$rls .= '<div id="mainPage"><a id="mainPageLink" href="'.$this->indexRequestURI.'">Main page</a></div>';
		$rls .= '</div>';
		$this->returnLinksSection = $rls;
	}
	
	private function createPasswordRecoverySection(){
		$pswRecSec = '<div id="passwordRecovery">';
		$pswRecSec .= '<a id="pswRecLink" href="forgotpsw.php">Forgot your password ?</a>';
		$pswRecSec .= '</div>';
		$this->passwordRecoverySection = $pswRecSec;
	}
	
	private function createNewAccountSection(){
		$newAccount = '<div id="createAccount">';
		$newAccount .= '<a id="crAccLink" href="register.php">Create account</a>';
		$newAccount .= '</div>';
		$this->newAccountSection = $newAccount;
	}
}

$authorization = new Authorization();

try{
	include_once "../db/db.php";
}
catch(Exception $e){
	print($e->getMessage()."<br />");
}

if(isset($_POST['btlogin'])){
  include_once "authProcess.php";
}

//print('SESSION: '); print_r($_SESSION); print('<br />');

?>
<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="robots" content="noindex,nofollow" />
  
  <title></title>
  
  <link rel="stylesheet" type="text/css" media="screen" href="../css/auth/auth.css" />
	<link rel="stylesheet" type="text/css" media="screen and (max-width: 1440px) and (max-height:2560px)" href="../css/auth/auth.css" />
  <style type="text/css">
    
  </style>
  
  <script type='text/javascript' src='../js/jquery.1.8.3.js'></script>
  <script type='text/javascript' src='../js/jquery-ui.js'></script>
  <script type='text/javascript' src=''></script>
  <script type="text/javascript">
    function authfchc(t,c){
	  if(c == 'b'){ jQuery(t).animate({'border-color':'rgb(25,142,255)'},300); }
	  else if(c == 'g'){ jQuery(t).animate({'border-color':'rgb(215,215,215)'},300); }
	}
  </script>
</head>

<body>

<div id="authWrapper">
  <div id="title"><span>Authorization</span></div>
  <form method="post" action="auth/auth.php">
  <? if(isset($_SESSION['autherr'])){ ?><div id="autherr"><? print($_SESSION['autherr']); ?></div><? unset($_SESSION['autherr']); } ?>
  <? if(isset($_SESSION['emautherr'])){ ?><div id="emautherr"><? print($_SESSION['emautherr']); ?></div><? unset($_SESSION['emautherr']); } ?>
	<div id="wemail"><input id="email" name="email" type="text" maxlength="50" placeholder="E-mail" onfocus="authfchc(this,'b');" onblur="authfchc(this,'g');" value="<? if(isset($_SESSION['authemail'])){ print($_SESSION['authemail']); unset($_SESSION['authemail']); } ?>" /></div>
  <? if(isset($_SESSION['pswautherr'])){ ?><div id="pswautherr"><? print($_SESSION['pswautherr']); ?></div><? unset($_SESSION['pswautherr']); } ?>
	<div id="wpsw"><input id="psw" name="psw" type="password" maxlength="20" placeholder="Password" onfocus="authfchc(this,'b');" onblur="authfchc(this,'g');" /></div>
	<div id="wbtlogin"><input id="btlogin" name="btlogin" type="submit" value="Log in" /></div>
  </form>
	<? print($authorization->separatingLine); ?>
	<? print($authorization->returnLinksSection); ?>
	<? print($authorization->separatingLine); ?>
	<? print($authorization->passwordRecoverySection); ?>
	<? print($authorization->separatingLine); ?>
	<? print($authorization->newAccountSection); ?>
</div>

</body>

</html>
