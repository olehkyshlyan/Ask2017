<?
session_start();
if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

if(isset($_SESSION['blocked']) && $_SESSION['blocked'] == 'no'){

$uid = $_SESSION['uid'];

if(isset($_POST['aid'])){
  $an = substr((string)$_POST['aid'],0,11);
  unset($_POST['aid']);
  $an = preg_replace('/[^0-9]/','',$an);
    if($an != ''){
      $aid = $an;
    }
    else{
      print("Answer id is empty");
    }
}
else{
  print("Answer id is not set");
}

if(isset($aid)){

try{
include "db.php";

$arow = $db->query("SELECT complaint FROM answers WHERE id='$aid';")->fetch(PDO::FETCH_ASSOC);
//print('$arow: '); var_dump($arow); print('<br />');
//print('$arow: '.$arow['complaint'].'<br />');

if($arow != false){
  if($arow['complaint'] != 'yes'){
	  $updres = $db->exec("UPDATE answers SET complaint='yes',complainant='$uid' WHERE id='$aid';");
	  //print('UPDATE: '); var_dump($updres); print('<br />');
	  if($updres != 0){
	    print("You have complained about this answer");
	  }
	  else{
	    print("Complaining about the answer failed");
	  }
  }
  else{
    print('Someone has complained about this answer already');
  }
}
else{
  print("Answer with this 'id' does not exist");
}

}
catch(Exception $e){
  print("Exception: ".$e->getMessage());
}

}

}
else{
  print("You are blocked on this site and can't complain about questions and answers");
}

}

?>