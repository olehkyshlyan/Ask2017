<?
// deleting from the page 'question.php'
if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){

if(isset($_POST['qqdelete'])){
unset($_POST['qqdelete']);

$qasuccessdel = false;
if(isset($_POST['qid'])){
  $qn = preg_replace('/[^0-9]/','',substr((string)$_POST['qid'],0,9));
  if($qn != ''){
    if(is_numeric($qn)){
	  $qid = $qn;
	}
  }
}

if(isset($_POST['qadcatlink'])){
  $catlink = preg_replace('/[^a-z]/i','',substr((string)$_POST['qadcatlink'],0,17));
  if(isset($categories[$catlink])){
    $qadcatlink = $catlink;
	if(isset($_POST['qadsubcatlink'])){
	  $subcatlink = preg_replace('/[^a-z-]/i','',substr((string)$_POST['qadsubcatlink'],0,35));
	  if(isset($subcategories[$qadcatlink][$subcatlink])){
	    $qadsubcatlink = $subcatlink;
	  }
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
}

$qquery = $db->query("SELECT imgf, qimages FROM questions WHERE id='$qid';");
//print('$qquery: '); var_dump($qquery); print('<br />');
$qrow = $qquery->fetchAll(PDO::FETCH_ASSOC);
//print('$qrow: '); var_dump($qrow); print('<br />');
$qdel = $db->exec("DELETE FROM questions WHERE id='$qid';");
//print('$qdel: '); var_dump($qdel); print('<br />');

if($qdel != 1){ throw new Exception("Error when deleting from 'Questions'<br />"); }

if($arowsnum > 0){
if($adel == $arowsnum){
//print('$adel == $arowsnum<br />');

foreach($arows as $v){
  if($v['imgf'] != '' && $v['aimages'] != ''){
    print('$v[\'imgf\']: '.$v['imgf'].' | $v[\'aimages\']: '.$v['aimages'].'<br />');
	$apharr = explode("|sp|",$v['aimages']);
	foreach($apharr as $av){
	  print($v['imgf'].'/'.$av.'<br />');
	  unlink('images/'.$v['imgf'].'/'.$av);
	}
  }
}

}
}

if($qdel == 1){
if($qrow[0]['imgf'] != '' && $qrow[0]['qimages'] != ''){
  $qpharr = explode("|sp|",$qrow[0]['qimages']);
  print('$qpharr: '); var_dump($qpharr); print('<br />');
  foreach($qpharr as $qv){
    print($qrow[0]['imgf'].'/'.$qv.'<br />');
	unlink('images/'.$qrow[0]['imgf'].'/'.$qv);
  }
}
$qasuccessdel = true;
}

$db->commit();
}
catch(Exception $e){
$rollres = $db->rollBack();
//print('RollBack result: '); var_dump($rollres); print('<br />');
//print("Deleting transaction failed: ".$e->getMessage().'<br />');
$_SESSION['qqdel'] = $e->getMessage();
}

}

if($qasuccessdel == true){
if(isset($qadcatlink) && isset($qadsubcatlink)){
  if($host == 'localhost'){
    header('Location:http://'.$host.'/exp3/index.php?category='.$qadcatlink.'&subcategory='.$qadsubcatlink);
  }
  else{
    header('Location:http://'.$host.'/index.php?category='.$qadcatlink.'&subcategory='.$qadsubcatlink);
  }
  exit();
}
elseif(isset($qadcatlink)){
  if($host == 'localhost'){
    header('Location:http://'.$host.'/exp3/index.php?category='.$qadcatlink);
  }
  else{
    header('Location:http://'.$host.'/index.php?category='.$qadcatlink);
  }
  exit();
}
else{
  if($host == 'localhost'){
    header('Location:http://'.$host.'/exp3/index.php');
  }
  else{
    header('Location:http://'.$host.'/index.php');
  }
  exit();
}
}


header('Location:http://'.$currenturl); exit();
}
}
}


?>