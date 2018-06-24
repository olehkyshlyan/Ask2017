<?
// 'as' - answer session
// the text of the answer is saved to session
session_start();
if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

if(isset($_POST['atxt'])){
  $at = mb_substr((string)$_POST['atxt'],0,1000,'UTF-8');
  if(is_string($at)){
    if(mb_strlen($at,'UTF-8') <= 1000){
	  if($at != ''){
	    $at = addslashes($at);
      $at = htmlentities($at,ENT_QUOTES,'UTF-8');
      $_SESSION['atext'] = $at;
	  }
	  else{
	    unset($_SESSION['atext']);
	  }
	}
  }
}

}

?>