<?
session_start();
if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){

if(isset($_POST['uid'])){
  if($_POST['uid'] != 'undefined'){
    $un = substr((string)$_POST['uid'],0,50);
    unset($_POST['uid']);
    $un = preg_replace('/[^a-z0-9\_\-\=\&\.]/i','',$un);
    if($un != ''){
      $uid = $un;
    }
    else{
      print("User 'id' is empty");
    }
  }
  else{
    print("User 'id' is undefined");
  }
}
else{
  print("User 'id' is not set");
}

if(isset($uid)){

try{
include "db.php";

$qresult = $db->query("SELECT blocked FROM users WHERE uid='$uid';");
$row = $qresult->fetch(PDO::FETCH_ASSOC);
//print('$row: '); var_dump($row); print('<br />');

if(isset($row['blocked'])){
  if($row['blocked'] == 'yes'){
    $upd = $db->exec("UPDATE users SET blocked='no' WHERE uid='$uid';");
    //print('$upd: '); var_dump($upd); print('<br />');
    if($upd == 1){
      print('User has been unblocked');
    }
    else{
      print("User unblocking failed");
    }
  }
  else{
    print("This user is already unblocked");
  }
}

}
catch(Exception $e){
  print("Exception: ".$e->getMessage());
}
}

}
}

?>