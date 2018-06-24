  <div id="askwwrap" <? if(isset($_SESSION['qwopen']) && $_SESSION['qwopen'] == true){ print('style="display: block;"'); } ?>>
  <div id="askWindow">
  <div id="AWCloseButton"><img src="icons/close.png" onclick="qwclose();" /></div>
	<form name="sendForm" method="post" enctype="multipart/form-data" action="<? print($actpage); ?>">
	
  <? if(isset($nrecmin) && isset($nrecsec)){ $qlrmin = $nrecmin; $qlrsec = $nrecsec; if($qlrmin < 10){ $qlrmin = '0'.$qlrmin; } if($qlrsec < 10){ $qlrsec = '0'.$qlrsec; } ?>
  <div id="awQFTimer"><span>Next question in: </span><span id="awQFCount"><? print($qlrmin.':'.$qlrsec); ?></span></div>
  <? } ?>
  
	<div id="AWQuestionDiv">
	  <div id="AWQuestionWord"><span class="asterisk">*</span><span id="AWQuestionWord2">Question:</span></div>
	  <div id="AWQuestionField"><textarea id="AWQuestTxtAr" name="questiontext" maxlength="140" onkeyup="qcountchar(this);" oninput="qcountchar(this);"><? if(isset($_SESSION['qtext'])){ print($_SESSION['qtext']); } ?></textarea></div>
	  <div id="AWQuestMax"><span id="qtlines">0</span><span> lines from 2</span><span id="qtxtsp"></span><span id="qtchars">0</span><span> chars form 140</span></div>
	</div>
	
	<div id="AWExplanationDiv">
	  <div id="AWExplanationWord"><span id="AWExpW2">Details:</span></div>
	  <div id="AWQDTxtCopy"><textarea id="qdCopyTxtArea"></textarea></div>
	  <div id="AWExplanationField"><textarea id="AWDetTxtAr" name="questiondetails" maxlength="1000" onkeyup="qdcountchar(this);" oninput="qdcountchar(this);"><? if(isset($_SESSION['qdetails'])){ print($_SESSION['qdetails']); } ?></textarea></div>
	  <div id="AWExplMax"><span id="AWExpInsImage" onclick="sldn500('AWAddImg');">Insert image</span><span id="qdlines">0</span><span> lines from 20</span><span id="qdtxtsp"></span><span id="qdtchars">0</span><span> chars from 1000</span></div>
	</div>
	
	<script type='text/javascript'>
	var awQuestTxtAr = document.getElementById('AWQuestTxtAr');
	var awDetTxtAr = document.getElementById('AWDetTxtAr');
	qcountchar(awQuestTxtAr);
	qdcountchar(awDetTxtAr);
	</script>
	
	<div id="AWAddImg">
	  <input id="AWPhotoUpload" type="file" name="qimg" />
	  <input class="awInpAddImg" type="submit" name="quplimg" value="Upload" />
	  <input class="awInpAddImg" type="button" value="Отменить" onclick="qCancImgUpl();" />
	</div>
	
	<? if(isset($_SESSION['quplphoto'])){ $tqexpimgs = explode("|sp|",$_SESSION['quplphoto']); $tqeil = count($tqexpimgs); $awStSl = 0; if($tqeil > 3){ $awStSl = $tqeil - 3; } ?>
	<div id="AWWrapSlider">
	  <div id="AWSecWrSl">
	    <div id="AWBxSlider">
		<? for($i=0;$i<$tqeil;$i++){ ?>
		  <div class="awSlideWrap">
		    <div class="awWrSlDelImg" onclick="qDelImg('<? print($tqexpimgs[$i]); ?>');"><img class="awSlDelImg" src="icons/delimage.png" /></div>
        <img src="tmpimg/<? print($tqexpimgs[$i]); ?>" class="awImgSlide" />
		  </div>
		<? } ?>
		</div>
	  </div>
	  <div class="AWCountImg"><span id="AWImgNum"><? print($tqeil); ?></span><span> images from 10</span></div>
	
	<script type='text/javascript'>
	var awBxSlLen = <? print($tqeil); ?>;
	var awStSl = <? print($awStSl); ?>;
	if(awBxSlLen > 3){
	  var awSWrSl = document.getElementById('AWSecWrSl');
	  awSWrSl.insertAdjacentHTML('afterbegin','<div id="awNextArrow" onclick="awBxSlider.goToNextSlide();"><img src="icons/next.png" /></div>');
	  awSWrSl.insertAdjacentHTML('afterbegin','<div id="awPrevArrow" onclick="awBxSlider.goToPrevSlide();"><img src="icons/prev.png" /></div>');
	}
	awBxSlider = jQuery('#AWBxSlider').bxSlider({ startSlide: awStSl, slideMargin: 7, pager: false, controls: false, maxSlides: 3, moveSlides: 1, slideWidth: 160 });
	</script>
	
	</div>
	<? } ?>
	
	<div id="AWCategoryDiv">
	  <div id="AWCategoryWord"><span class="asterisk">*</span><span id="AWCategoryWord2">Category:</span></div>
	  <div id="AWCategoryField">
	    <select id="selectCategory" name="categorylink" onchange="selsub(this);">
		  <option value="chooseCategory" selected="selected">Choose category</option>
		  <? if(isset($_SESSION['cat'])){ $awcat = $_SESSION['cat']; foreach($categories as $k=>$v){ ?>
		  <option value="<? print($k); ?>" <? if($k == $awcat){ ?> selected="selected" <? } ?>><? print($v); ?></option>
		  <? }} else { foreach($categories as $k=>$v){ ?>
		  <option value="<? print($k); ?>"><? print($v); ?></option>
		  <? }} ?>
		</select>
	  </div>
	</div>
	
	<div id="AWSubCategoryDiv">
	  <div id="AWSubCategoryWord"><span class="asterisk">*</span><span id="AWSubCategoryWord2">Subcategory:</span></div>
	  <div id="AWSubCategoryField">
    <? if(isset($_SESSION['cat'])){ if(isset($_SESSION['subcat'])){ $awsubcat = $_SESSION['subcat']; } ?>
		<select id="selectSubcategory" name="subcategorylink" onchange="enableaskbt(this);">
		<option value="choose" selected="selected">Choose subcategory</option>
		<? if(isset($_SESSION['subcat'])){ $awsubcat = $_SESSION['subcat']; foreach($subcategories[$awcat] as $k=>$v){ ?>
		<option value="<? print($k); ?>" <? if($k == $awsubcat){ ?> selected="selected" <? } ?>><? print($v); ?></option>
		<? }} else { foreach($subcategories[$awcat] as $k=>$v){ ?>
		<option value="<? print($k); ?>"><? print($v); ?></option>
		<? }} ?>
		</select>
		<? } else { ?>
	    <select disabled="disabled">
		  <option selected="selected">Choose subcategory</option>
		</select>
		<? } ?>
	  </div>
	</div>
	
	<div id="AWButtonDiv">
	  <input id="AWButtonAsk" name="addquestion" type="submit" value="Ask" <? if(!isset($_SESSION['subcat'])){ ?> disabled="disabled" <? } ?> />
	  <input id="AWButtonCancel" type="button" value="Cancel" onclick="deleteQuestion();" />
	</div>
	
	</form>
  </div>
  </div>