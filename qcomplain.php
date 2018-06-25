<?
session_start();
if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

if(isset($_SESSION['blocked']) && $_SESSION['blocked'] == 'no'){

$uid = $_SESSION['uid'];

if(isset($_POST['qid'])){
    $qn = substr((string)$_POST['qid'],0,11);
    unset($_POST['qid']);
	  $qn = preg_replace('/[^0-9]/','',$qn);
	  if($qn != ''){
      $qid = $qn;
    }
	  else{
      print("Question id is empty");
    }
}
else{
  print("Question id is not set");
}

if(isset($qid)){

try{
include "db.php";

$qrow = $db->query("SELECT complaint FROM questions WHERE id='$qid';")->fetch(PDO::FETCH_ASSOC);
//print('$qrow: '); var_dump($qrow); print('<br />');
//print('$qrow: '.$qrow['complaint'].'<br />');

if($qrow != false){
  if($qrow['complaint'] != 'yes'){
    $updres = $db->exec("UPDATE questions SET complaint='yes',complainant='$uid' WHERE id='$qid';");
    //print('UPDATE: '); var_dump($updres); print('<br />');
    if($updres != 0){
      print("You have complained about this question");
    }
    else{
      print("Update query failed");
    }
  }
  else{
    print('Someone has complained about this question already');
  }
}
else{
  print('Query failed');
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