<?
session_start();

if(isset($_POST['qid'])){
  $qid = substr((string)$_POST['qid'],0,9);
  unset($_POST['qid']);
  $qid = preg_replace('/[^0-9]/','',$qid);
  if($qid != ''){
    $fqid = $qid;
  }
}

if(isset($_POST['aid'])){
  $aid = substr((string)$_POST['aid'],0,9);
  unset($_POST['aid']);
  $aid = preg_replace('/[^0-9]/','',$aid);
  if($aid != ''){
    $faid = $aid;
  }
}

if(isset($fqid) && isset($faid)){

try{
include "db.php";

$limit = 10;
$ql = $limit + 1;

$aresult = $db->query("SELECT * FROM answers WHERE qid='$fqid' AND id<$faid ORDER BY dt Desc LIMIT $ql;");
//print('$aresult: '); var_dump($aresult);
$arow = $aresult->fetchAll(PDO::FETCH_ASSOC);
//print('$arow: '); var_dump($arow);
$larow = count($arow);
//print('$larow: '.$larow);
if($larow > 0){

if($larow > $limit){
  $mol = $limit - 1;
  $shmore['aid'] = $arow[$mol]['id'];
  unset($arow[$limit]);
}
else{
  $shmore['aid'] = 'no';
}

$shmore['qid'] = $fqid;
$actpage = 'question.php?q='.$fqid;

$abl = array();
$rid = 0;

foreach($arow as $row){

$abl[$rid]['id'] = $row['id'];

$abl[$rid]['a'] = '<div class="MBAnswerBlock">
<div id="amsg'.$row['id'].'" class="amsg"></div>';
if(isset($_SESSION['euser'])&& $_SESSION['euser'] == true){
if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){
$abl[$rid]['a'] .= '<div id="adelmsg'.$row['id'].'" class="aadm">
<form method="post" action="'.$actpage.'">
<input name="aid" type="hidden" value="'.$row['id'].'" />
<input name="qid" type="hidden" value="'.$row['qid'].'" />
<div class="txtaadm">Delete this answer ?</div>
<input class="yadel" name="adelete" type="submit" value="Yes" onclick="qpscrpos();" />
<input class="nadel" type="button" value="No" onclick="slup500(\'adelmsg'.$row['id'].'\');" />
</form>
</div>

<div id="abu'.$row['id'].'" class="aadm">
<div class="txtaadm">Block this user ?</div>
<div class="yqabu" onclick="blockuser(\'abu'.$row['id'].'\',\'amsg'.$row['id'].'\',\''.$row['uid'].'\');">Yes</div>
<div class="nqabu" onclick="slup500(\'abu'.$row['id'].'\');">No</div>
</div>';
}else{
$abl[$rid]['a'] .= '<div id="ac'.$row['id'].'" class="ac">
<div class="actxt">Complain about this answer ?</div>
<div class="acyes" onclick="aeucompl(\''.$row['id'].'\');">Yes</div>
<div class="acno" onclick="cacform(\'ac'.$row['id'].'\');">No</div>
</div>';
}}

if($row['uphoto'] != ''){ $uphoto = $row['uphoto']; }else{ $uphoto = 'nouser50.png'; }

$abl[$rid]['a'] .= '<div class="MBAnswerPhoto">
<a href="uq.php?uid='.$row['uid'].'" target="_blank">
<div style="background-image: url(\'uphotos/'.$uphoto.'\')"></div>
</a>
</div>';

$abl[$rid]['a'] .= '<div class="MBAnswerDetails">';
if($row['utype'] != 'admin'){
$abl[$rid]['a'] .= '<span class="MBAnswerDetailsItems">'.$row['fname'].' '.$row['lname'].'</span>';
}else{
$abl[$rid]['a'] .= '<span class="MBAnswerDetailsItems DIAdmin">'.$row['fname'].'</span>';
}
$abl[$rid]['a'] .= '<span id="MBADDate" class="MBAnswerDetailsItems">'.$row['dt'].'</span>
<span class="MBRightSideIcons">';
if(isset($_SESSION['euser']) && $_SESSION['euser'] == true){
if(isset($_SESSION['utype']) && $_SESSION['utype'] == 'admin'){
$abl[$rid]['a'] .= '<img class="RSIcons" title="Block user" src="icons/block.png" onclick="sldn500(\'abu'.$row['id'].'\');" />
<img class="RSIcons" title="Delete" src="icons/delete.png" onclick="sldn500(\'adelmsg'.$row['id'].'\');" />';
}else{
$abl[$rid]['a'] .= '<img class="RSIcons" title="Complain" src="icons/flag.png" onclick="oacform(\'ac'.$row['id'].'\');" />';
}
}else{
$abl[$rid]['a'] .= '<img class="RSIcons" title="Complain" src="icons/flag.png" onclick="aneucompl(\'amsg'.$row['id'].'\',\'answer\');" />';
}
$abl[$rid]['a'] .= '</span>
</div>';

$atext = nl2br($row['atext']);
$abl[$rid]['a'] .= '<div id="watxt'.$row['id'].'" class="MBAnswerText">
<div id="atxt'.$row['id'].'" class="atxt">'.$atext.'</div>
</div>';

if($row['imgf'] != '' && $row['aimages'] != ''){
$aexpimgs = explode('|sp|',$row['aimages']);
$aeil = count($aexpimgs);

$abl[$rid]['sl'] = $aeil;

$abl[$rid]['a'] .= '<div id="wrapABBxSlider'.$row['id'].'" class="wrapABBxSlider">
<div id="ABBxSlider'.$row['id'].'">';
for($i=0;$i<$aeil;$i++){
$abl[$rid]['a'] .= '<div class="bxslidewrap">
<a href="images/'.$row['imgf'].'/'.$aexpimgs[$i].'" target="_blank">
<img src="images/'.$row['imgf'].'/'.$aexpimgs[$i].'" class="imgBxSlide" />
</a>
</div>';
}
$abl[$rid]['a'] .= '</div>
</div>';
}

$abl[$rid]['a'] .= '</div>';

$rid++;
}

$shmore['abl'] = $abl;

}
else{
  $shmore['aid'] = 'no';
}

}
catch(Exception $e){
  $shmore['msg'] = $e->getMessage();
}

$pshmore = json_encode($shmore);
print($pshmore);
}

?>