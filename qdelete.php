<?

if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){

if(isset($_POST['qdelete'])){
unset($_POST['qdelete']);

if(isset($_POST['qid'])){
  $qn = preg_replace('/[^0-9]/','',substr((string)$_POST['qid'],0,9));
  unset($_POST['qid']);
  if($qn != ''){
    if(is_numeric($qn)){
	  $qid = $qn;
	}
  }
}

if(isset($qid)){

try{
$db->beginTransaction();

$aquery = $db->query("SELECT imgf, aimages FROM answers WHERE qid='$qid';");
//print('$aquery: '); var_dump($aquery); print('<br />');
$arows = $aquery->fetchAll(PDO::FETCH_ASSOC);
//print('$arows: '); var_dump($arows); print('<br />');
$arowsnum = count($arows);
//print('$arowsnum: '.$arowsnum.'<br />');

if($arowsnum > 0){
  $adel = $db->exec("DELETE FROM answers WHERE qid='$qid';");
  //print('$adel: '); var_dump($adel); print('<br />');
  if($adel < $arowsnum){ throw new Exception("Error when deleting 'Answers'<br />"); }
  //else{ throw new Exception("Error when deleting 'Answers' 111<br />"); }
}

$qquery = $db->query("SELECT imgf, qimages FROM questions WHERE id='$qid';");
//print('$qquery: '); var_dump($qquery); print('<br />');
$qrow = $qquery->fetchAll(PDO::FETCH_ASSOC);
//print('$qrow: '); var_dump($qrow); print('<br />');
$qdel = $db->exec("DELETE FROM questions WHERE id='$qid';");
//print('$qdel: '); var_dump($qdel); print('<br />');

if($qdel != 1){ throw new Exception("Error when deleting from 'Questions'<br />"); }
//else{ throw new Exception("Error when deleting from 'Questions' 111<br />"); }

if($arowsnum > 0){
if($adel == $arowsnum){
//print('$adel == $arowsnum<br />');

foreach($arows as $v){
  if($v['imgf'] != '' && $v['aimages'] != ''){
    //print('$v[\'imgf\']: '.$v['imgf'].' | $v[\'aimages\']: '.$v['aimages'].'<br />');
	$apharr = explode("|sp|",$v['aimages']);
	foreach($apharr as $av){
	  //print($v['imgf'].'/'.$av.'<br />');
	  unlink('images/'.$v['imgf'].'/'.$av);
	}
  }
}

}
}

if($qdel == 1){
if($qrow[0]['imgf'] != '' && $qrow[0]['qimages'] != ''){
  $qpharr = explode("|sp|",$qrow[0]['qimages']);
  //print('$qpharr: '); var_dump($qpharr); print('<br />');
  foreach($qpharr as $qv){
    //print($qrow[0]['imgf'].'/'.$qv.'<br />');
    unlink('images/'.$qrow[0]['imgf'].'/'.$qv);
  }
}
$_SESSION['delq'] = true;
}

$db->commit();
}
catch(Exception $e){
$rollres = $db->rollBack();
//print('RollBack result: '); var_dump($rollres); print('<br />');
//print("Deleting transaction failed: ".$e->getMessage().'<br />');
$_SESSION['qdel'] = $e->getMessage();
$_SESSION['qdelid'] = $qid;
}

}

header('Location:http://'.$currenturl); exit();
}
}
}


?>