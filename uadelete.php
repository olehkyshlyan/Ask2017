<?

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){

if(isset($_POST['uadelete'])){
unset($_POST['uadelete']);

if(isset($_POST['aid'])){
  $an = preg_replace('/[^0-9]/','',substr((string)$_POST['aid'],0,9));
  unset($_POST['aid']);
  if($an != ''){
    if(is_numeric($an)){
	  $aid = $an;
	}
  }
}

if(isset($_POST['qid'])){
  $qn = preg_replace('/[^0-9]/','',substr((string)$_POST['qid'],0,9));
  unset($_POST['qid']);
  if($qn != ''){
    if(is_numeric($qn)){
	  $qid = $qn;
	}
  }
}

if(isset($aid) && isset($qid)){

try
{
//throw new Exception("Error when deleting 'Answers' 111<br />");

$aquery = $db->query("SELECT uid, imgf, aimages FROM answers WHERE id='$aid';");
//print('$aquery: '); var_dump($aquery); print('<br />');
$arow = $aquery->fetchAll(PDO::FETCH_ASSOC);
//print('$arow: '); var_dump($arow); print('<br />');

if($arow[0]['uid'] == $_SESSION['uid'] || $_SESSION['utype'] == 'admin'){
//throw new Exception("You are not the author of this answer 111<br />");

$adel = $db->exec("DELETE FROM answers WHERE id='$aid';");
//print('$adel: '); var_dump($adel); print('<br />');

if($adel == 1){

if($arow[0]['imgf'] != '' && $arow[0]['aimages'] != ''){
  $apharr = explode("|sp|",$arow[0]['aimages']);
  //print('$apharr: '); var_dump($apharr); print('<br />');
  foreach($apharr as $av){
    //print($arow[0]['imgf'].'/'.$av.'<br />');
    unlink('images/'.$arow[0]['imgf'].'/'.$av);
  }
}

$qupd = $db->exec("UPDATE questions SET answers = answers-1 WHERE id='$qid';");
//print('$qupd: '); var_dump($qupd); print('<br />');
$_SESSION['pgnadel'] = true;
}
else{
  throw new Exception("Error when deleting 'Answers'<br />");
}

}
else{
  throw new Exception("You are not the author of this answer<br />");
}

}
catch(Exception $e)
{
//print("Deleting from 'Answers' failed: ".$e->getMessage()."<br />");
$_SESSION['uadel'] = $e->getMessage();
$_SESSION['uadelid'] = $aid;
}

}

header('Location:http://'.$currenturl); exit();
}
}

?>