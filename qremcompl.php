<?
session_start();

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){

if(isset($_POST['id'])){
  if($_POST['id'] != 'undefined'){
    if(is_numeric($_POST['id'])){
      $id = $_POST['id'];
      if($id != ''){
        $rowid = $id;
      }
      else{
        print("Row id is empty<br />");
      }
    }
    else{
      print("Row id is not a number<br />");
    }
  }
  else{
    print("Row id is undefined<br />");
  }
}
else{
  print("Row id does not exist<br />");
}

if(isset($rowid)){

try{
include "db.php";

$result = $db->query("SELECT complaint FROM questions WHERE id='$rowid';");
$row = $result->fetch(PDO::FETCH_ASSOC);
//print('$row: '); var_dump($row); print('<br />');

if(isset($row['complaint'])){
  if($row['complaint'] == 'yes'){
    $res = $db->exec("UPDATE questions SET complaint='no',complainant=NULL WHERE id='$rowid';");
    //print('$res: '); var_dump($res); print('<br />');
    if($res == 1){
      print('Complaint has been removed<br />');
    }
    else{
      print("Removing the complaint failed<br />");
    }
  }
  else{
    print("This question is not complained<br />");
  }
}
}
catch(Exception $e){
  print("Exception: ".$e->getMessage()."<br />");
}
}

}
}

?>