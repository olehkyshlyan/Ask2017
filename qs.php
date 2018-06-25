<?
// 'qs' - question session
// the text of the question and its details are saved to session
session_start();
if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

require "subcategories.php";

// QUESTION TEXT
if(isset($_POST['qtxt'])){
  $qt = mb_substr((string)$_POST['qtxt'],0,140,'UTF-8');
  if(is_string($qt)){
    if(mb_strlen($qt,'UTF-8') <= 140){
	  if($qt != ''){
	    $qt = addslashes($qt);
      $qt = htmlentities($qt,ENT_QUOTES,'UTF-8');
      $_SESSION['qtext'] = $qt;
	  }
	  else{
	    unset($_SESSION['qtext']);
	  }
	}
  }
}

// QUESTION DETAILS
if(isset($_POST['qdtxt'])){
  $qd = mb_substr((string)$_POST['qdtxt'],0,1000,'UTF-8');
  if(is_string($qd)){
    if(mb_strlen($qd,'UTF-8') <= 1000){
	  if($qd != ''){
	    $qd = addslashes($qd);
      $qd = htmlentities($qd,ENT_QUOTES,'UTF-8');
      $_SESSION['qdetails'] = $qd;
	  }
	  else{
	    unset($_SESSION['qdetails']);
	  }
	}
  }
}

// CATEGORY SESSION '[cat]' IS SET IN THE FILE 'selectsubcategory.php'

// SUBCATEGORY
if(isset($_POST['subcat'])){
  if(isset($_SESSION['cat'])){
	$cat = preg_replace('/[^a-z]/i','',substr((string)$_SESSION['cat'],0,17));
	$subcat = preg_replace('/[^a-z-]/i','',substr((string)$_POST['subcat'],0,35));
	if(is_string($subcat)){
	  if(isset($subcategories[$cat][$subcat])){
	    $_SESSION['subcat'] = $subcat;
	  }
	  else{
	    unset($_SESSION['subcat']);
	  }
	}
  }  
}

}
?>