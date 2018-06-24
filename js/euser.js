
(function(){
  var arr = []; var notice = [];
  
  qeucompl = function(qid){
    if(arr.indexOf(qid) == -1){
	  var ntind = notice.indexOf('qc'+qid); if(ntind != -1){ clearTimeout(cqfid); notice.splice(ntind,1); jQuery('#qc'+qid).slideUp({duration:500}); }
	  arr.push(qid);
	  jQuery.post("qcomplain.php","qid="+qid,function(data,textStatus){
	    document.getElementById('qmsg'+qid).innerHTML = data;
      jQuery('#qmsg'+qid).slideDown({duration:500,complete:function(){ jQuery('#qmsg'+qid).delay(3000).slideUp({duration:500,complete:function(){
        var ind = arr.indexOf(qid);
        if(ind != -1){ arr.splice(ind,1); }
      }}); }});
	  });
	}
  }
  
  oqcform = function(id){
    if(notice.indexOf(id) == -1){
	  //alert('id: '+id);
	  notice.push(id);
	  jQuery('#'+id).slideDown({duration:500});
	  var closeNotice = function(){
	    var ntind = notice.indexOf(id);
      if(ntind != -1){ notice.splice(ntind,1); jQuery('#'+id).slideUp({duration:500}); }
	  }
	  cqfid = setTimeout(closeNotice,5000);
    }
  }
  
  cqcform = function(id){
    var ntind = notice.indexOf(id);
    if(ntind != -1){ clearTimeout(cqfid); notice.splice(ntind,1); jQuery('#'+id).slideUp({duration:500}); }
  }
})();

(function(){
  var arr = []; var notice = [];
  
  aeucompl = function(aid){
    if(arr.indexOf(aid) == -1){
	  var ntind = notice.indexOf('ac'+aid); if(ntind != -1){ clearTimeout(cafid); notice.splice(ntind,1); jQuery('#ac'+aid).slideUp({duration:500}); }
	  arr.push(aid);
	  jQuery.post("acomplain.php","aid="+aid,function(data,textStatus){
	    //alert('textStatus: '+textStatus); alert('data: '+data);
		document.getElementById('amsg'+aid).innerHTML = data;
		jQuery('#amsg'+aid).slideDown({duration:500,complete:function(){ jQuery('#amsg'+aid).delay(3000).slideUp({duration:500,complete:function(){
		  var ind = arr.indexOf(aid);
		  if(ind != -1){ arr.splice(ind,1); }
		}}); }});
	  });
	}
  }
  
  oacform = function(id){
    if(notice.indexOf(id) == -1){
	  notice.push(id);
	  jQuery('#'+id).slideDown({duration:500});
	  var closeNotice = function(){
	    var ntind = notice.indexOf(id);
		if(ntind != -1){ notice.splice(ntind,1); jQuery('#'+id).slideUp({duration:500}); }
	  }
	  cafid = setTimeout(closeNotice,5000);
	}
  }
  
  cacform = function(id){
    var ntind = notice.indexOf(id);
    if(ntind != -1){ clearTimeout(cafid); notice.splice(ntind,1); jQuery('#'+id).slideUp({duration:500}); }
  }
})();

(function(){
  var notice = [];
  
  uq_oqdelform = function(id){
    if(notice.indexOf(id) == -1){
	  //alert('id: '+id);
	  notice.push(id);
	  jQuery('#'+id).slideDown({duration:500});
	  var closeNotice = function(){
	    var ntind = notice.indexOf(id);
      if(ntind != -1){ notice.splice(ntind,1); jQuery('#'+id).slideUp({duration:500}); }
	  }
	  cqfid = setTimeout(closeNotice,5000);
    }
  }
  
  uq_cqdelform = function(id){
    var ntind = notice.indexOf(id);
    if(ntind != -1){ clearTimeout(cqfid); notice.splice(ntind,1); jQuery('#'+id).slideUp({duration:500}); }
  }
})();

(function(){
  var notice = [];
  
  ua_oadelform = function(id){
    if(notice.indexOf(id) == -1){
	  //alert('id: '+id);
	  notice.push(id);
	  jQuery('#'+id).slideDown({duration:500});
	  var closeNotice = function(){
	    var ntind = notice.indexOf(id);
      if(ntind != -1){ notice.splice(ntind,1); jQuery('#'+id).slideUp({duration:500}); }
	  }
	  cqfid = setTimeout(closeNotice,5000);
    }
  }
  
  ua_cadelform = function(id){
    var ntind = notice.indexOf(id);
    if(ntind != -1){ clearTimeout(cqfid); notice.splice(ntind,1); jQuery('#'+id).slideUp({duration:500}); }
  }
})();

(function(){
  var notice = [];
  
  uqa_oadelform = function(id){
    if(notice.indexOf(id) == -1){
	  //alert('id: '+id);
	  notice.push(id);
	  jQuery('#'+id).slideDown({duration:500});
	  var closeNotice = function(){
	    var ntind = notice.indexOf(id);
      if(ntind != -1){ notice.splice(ntind,1); jQuery('#'+id).slideUp({duration:500}); }
	  }
	  cqfid = setTimeout(closeNotice,5000);
    }
  }
  
  uqa_cadelform = function(id){
    var ntind = notice.indexOf(id);
    if(ntind != -1){ clearTimeout(cqfid); notice.splice(ntind,1); jQuery('#'+id).slideUp({duration:500}); }
  }
})();

function selsub(th){
  jQuery.post("selectsubcategory.php","optionValue="+th.value,function(data,textStatus){
	//alert('data: '+data);
	if(data != ''){ document.getElementById('AWSubCategoryField').innerHTML = data; }
	if(th.selectedIndex == 0){ document.getElementById('AWButtonAsk').setAttribute('disabled','disabled'); }
  });
}

function enableaskbt(th){
  jQuery.post("qs.php","subcat="+th.value);
  if(th.selectedIndex != 0){ document.getElementById('AWButtonAsk').removeAttribute('disabled'); }
  else{ document.getElementById('AWButtonAsk').setAttribute('disabled','disabled'); }
}

function formattxt(cont,dest,max,nlnum){
  var pattern = /[\r\n\n\r]/ig;
  var exres;
  var count = 0;
  var nlmax;
  if(nlnum <= max){
    nlmax = nlnum - 1;
  }
  else{
    nlmax = max - 1;
  }
  var diff = max - nlmax;
  var rest = diff * 70;
  
  while((exres = pattern.exec(cont)) != null){
    if(count >= nlmax){
      var fpart = cont.substring(0,exres.index);
      //alert('fpart: '+fpart);
      //alert('exres.index: '+exres.index);
      var spart = cont.substring(exres.index);
      spart = spart.replace(/[(\r\n)(\n)(\r)]/ig,'');
      spart = spart.substring(0,rest);
      var pspart = "\n"+spart;
      document.getElementById(dest).value = fpart+pspart;
      break;
    }
    count++;
  }
}

function getCursorPosition(th){
  if(th.selectionStart || th.selectionStart == 0){
    cursorPos = th.selectionStart;
    return cursorPos;
  }
  else if(document.selection){
    var textRange = document.selection.createRange();
    var extTextRange = document.body.createTextRange();
    extTextRange.moveToElementText(textRange.parentElement());
    extTextRange.setEndPoint("EndToEnd",textRange);
    cursorPos = extTextRange.text.length;
    return cursorPos;
  }
}

function setCursorPosition(th,cp){
  if(th.setSelectionRange){
    th.setSelectionRange(cp,cp);
  }
  if(document.selection){
	var range = th.createTextRange();
	var retval = range.move('character',cp);
	range.select();
  }
}

// counts characters in the question text
function qcountchar(th){
  var qcont = th.value;
  var lm = 140;
  var txtlength = qcont.length;
  var cursorPos = getCursorPosition(th);
  var left = lm - txtlength;
  
  var sqcont = qcont.split(/[(\r\n)(\n)(\r)]/ig);
  var tlsqdcont = sqcont.length;
  var nsqcont = [];
  var stln = 70;
  for(var i=0;i<tlsqdcont;i++){
    if(sqcont[i].length > stln){
      var pl = sqcont[i].length-1;
      var npl = pl/stln;
      npl = Math.floor(npl);
      var sp = 0;
      var ep = sp+stln;
      for(var j=0;j<=npl;j++){
        var parr = sqcont[i].substring(sp,ep);
        nsqcont.push(parr);
        sp = sp+stln;
        ep = ep+stln;
      }
    }
    else{
      nsqcont.push(sqcont[i]);
    }
  }
  
  var nllm = 2;
  var lnsqcont = nsqcont.length;
  
  if(lnsqcont > nllm){
    left = -1;
  }
  
  var fptxtend = cursorPos+left;
  
  if(txtlength > lm || lnsqcont > nllm){
    var fptxt = th.value.substring(0,fptxtend);
    var sptxt = th.value.substring(cursorPos);
    var total = fptxt + sptxt;
    th.value = total;
    qcont = total;
    setCursorPosition(th,fptxtend);
  }
  
  if(txtlength <= lm){
    document.getElementById('qtchars').innerHTML = qcont.length;
  }
  
  if(lnsqcont <= nllm){
    document.getElementById('qtlines').innerHTML = lnsqcont;
  }
  
  if(lnsqcont == 1 && sqcont[0] == ''){
    document.getElementById('qtlines').innerHTML = 0;
  }
  
  jQuery.post("qs.php","qtxt="+th.value);
}

// counts characters in the question details text
function qdcountchar(th){
  var qdcont = th.value;
  var lm = 1000;
  var txtlength = qdcont.length;
  var cursorPos = getCursorPosition(th);
  var left = lm - txtlength;
  
  var sqdcont = qdcont.split(/[(\r\n)(\n)(\r)]/ig);
  var tlsqdcont = sqdcont.length;
  var nsqdcont = [];
  var stln = 70;
  for(var i=0;i<tlsqdcont;i++){
    if(sqdcont[i].length > stln){
      var pl = sqdcont[i].length-1;
      var npl = pl/stln;
      npl = Math.floor(npl);
      var sp = 0;
      var ep = sp+stln;
      for(var j=0;j<=npl;j++){
        var parr = sqdcont[i].substring(sp,ep);
        nsqdcont.push(parr);
        sp = sp+stln;
        ep = ep+stln;
      }
    }
    else{
      nsqdcont.push(sqdcont[i]);
    }
  }
  
  var nllm = 20;
  var lnsqdcont = nsqdcont.length;
  
  if(lnsqdcont > nllm){
    left = -1;
  }
  
  var fptxtend = cursorPos+left;
  
  if(txtlength > lm || lnsqdcont > nllm){
    var fptxt = th.value.substring(0,fptxtend);
    var sptxt = th.value.substring(cursorPos);
    var total = fptxt + sptxt;
    th.value = total;
    qdcont = total;
    setCursorPosition(th,fptxtend);
  }
  
  /*
  if(lnsqdcont >= 3 && lnsqdcont <= nllm){
    var qdtxtheight = lnsqdcont * 16;
    th.style.height = qdtxtheight + 2 + 'px';
  }
  */
  
  qdtxthight.set(qdcont);
  
  if(txtlength <= lm){
    document.getElementById('qdtchars').innerHTML = qdcont.length;
  }
  
  if(lnsqdcont <= nllm){
    document.getElementById('qdlines').innerHTML = lnsqdcont;
  }
  
  if(lnsqdcont == 1 && sqdcont[0] == ''){
    document.getElementById('qdlines').innerHTML = 0;
  }
  
  jQuery.post("qs.php","qdtxt="+th.value);
}

var qdtxthight = new function(){
  this.set = function(cont){
	//var awQDTxtCopy = document.getElementById('AWQDTxtCopy');
	var awQDTxtCopy = document.getElementById('qdCopyTxtArea');
	//awQDTxtCopy.innerHTML = cont;
	awQDTxtCopy.value = cont;
	var hqdCopy = awQDTxtCopy.clientHeight;
	alert("hqdCopy: "+hqdCopy);
  }
}

// counts characters in the answer text
function acountchar(th){
  var acont = th.value;
  var lm = 1000;
  var txtlength = acont.length;
  var cursorPos = getCursorPosition(th);
  var left = lm - txtlength;
  
  var sacont = acont.split(/[(\r\n)(\n)(\r)]/ig);
  var tlsacont = sacont.length;
  var nsacont = [];
  var stln = 70;
  for(var i=0;i<tlsacont;i++){
    if(sacont[i].length > stln){
      var pl = sacont[i].length-1;
      var npl = pl/stln;
      npl = Math.floor(npl);
      var sp = 0;
      var ep = sp+stln;
      for(var j=0;j<=npl;j++){
        var parr = sacont[i].substring(sp,ep);
        nsacont.push(parr);
        sp = sp+stln;
        ep = ep+stln;
      }
    }
    else{
      nsacont.push(sacont[i]);
    }
  }
  
  var nllm = 20;
  var lnsacont = nsacont.length;
  
  if(lnsacont > nllm){
    left = -1;
  }
  
  var fptxtend = cursorPos+left;
  
  if(txtlength > lm || lnsacont > nllm){
    var fptxt = th.value.substring(0,fptxtend);
    var sptxt = th.value.substring(cursorPos);
    var total = fptxt + sptxt;
    th.value = total;
    acont = total;
    setCursorPosition(th,fptxtend);
  }
  
  if(lnsacont >= 3 && lnsacont <= nllm){
    var atxtheight = lnsacont * 16;
    th.style.height = atxtheight + 2 + 'px';
  }
  
  if(txtlength <= lm){
    document.getElementById('atchars').innerHTML = acont.length;
  }
  
  if(lnsacont <= nllm){
    document.getElementById('atlines').innerHTML = lnsacont;
  }
  
  if(lnsacont == 1 && sacont[0] == ''){
    document.getElementById('atlines').innerHTML = 0;
  }
  
  jQuery.post("as.php","atxt="+th.value);
}

function qwopen(){
  jQuery('#askwwrap').slideDown({duration:1000});
  jQuery.post("qw.php","open=yes");
}

function qwclose(){
  jQuery('#askwwrap').slideUp({duration:1000});
  jQuery.post("qw.php","close=yes");
}

function qCancImgUpl(){
  jQuery('#AWAddImg').slideUp({duration:500,complete:function(){
    document.getElementById('AWPhotoUpload').value = '';
  }});
}

function qDelImg(id){
  var awWrapSlider = document.getElementById('AWWrapSlider');
  var awSWrSl = document.getElementById('AWSecWrSl');
  var awPrevArrow = document.getElementById('awPrevArrow');
  var awNextArrow = document.getElementById('awNextArrow');
  var dImgAwBxSl = document.getElementById('AWBxSlider');
  jQuery.post("qdelimg.php","qphid="+id,function(data,textStatus){
	if(data != ''){
	  var imgarr = data.split('|sp|');
	  var lia = imgarr.length;
	  var imgrow = '';
	  for(var i=0;i<lia;i++){
	  imgrow = imgrow +
	  '<div class="awSlideWrap">'+
	  '<div class="awWrSlDelImg" onclick="qDelImg(\''+imgarr[i]+'\');"><img class="awSlDelImg" src="icons/delimage.png" /></div>'+
	  '<img src="tmpimg/'+imgarr[i]+'" class="awImgSlide" />'+
	  '</div>';
	  }
	  dImgAwBxSl.innerHTML = imgrow;
	  var awStSl = 0;
	  if(lia > 3){
	    if(awNextArrow == null){ awSWrSl.insertAdjacentHTML('afterbegin','<div id="awNextArrow" onclick="awBxSlider.goToNextSlide();"><img src="icons/next.png" /></div>'); }
      if(awPrevArrow == null){ awSWrSl.insertAdjacentHTML('afterbegin','<div id="awPrevArrow" onclick="awBxSlider.goToPrevSlide();"><img src="icons/prev.png" /></div>'); }
      awStSl = lia - 3;
	  }
	  else{
	    if(awPrevArrow != null){ awPrevArrow.parentNode.removeChild(awPrevArrow); }
      if(awNextArrow != null){ awNextArrow.parentNode.removeChild(awNextArrow); }
	  }
	  awBxSlider.reloadSlider({ startSlide: awStSl, slideMargin: 7, pager: false, controls: false, maxSlides: 3, moveSlides: 1, slideWidth: 160 });
	  document.getElementById('AWImgNum').innerHTML = lia;
	}
	else{
	  dImgAwBxSl.innerHTML = '';
	  jQuery(awWrapSlider).slideUp({duration:1000});
	}
  });
}

function deleteQuestion(){
  var awQuestTxtAr = document.getElementById('AWQuestTxtAr');
  var qtlines = document.getElementById('qtlines');
  var qtchars = document.getElementById('qtchars');
  var awDetTxtAr = document.getElementById('AWDetTxtAr');
  var qdlines = document.getElementById('qdlines');
  var qdtchars = document.getElementById('qdtchars');
  var dqAWAddImg = document.getElementById('AWAddImg');
  var dqAWWrapSlider = document.getElementById('AWWrapSlider');
  var awCatField = document.getElementById('AWCategoryField');
  var awSubCatField = document.getElementById('AWSubCategoryField');
  
  jQuery('#askwwrap').slideUp({duration:1000,complete:function(){
    awQuestTxtAr.value = '';
    qtlines.innerHTML = 0;
    qtchars.innerHTML = 0;
    awDetTxtAr.value = '';
    qdlines.innerHTML = 0;
    qdtchars.innerHTML = 0;
    dqAWAddImg.style.display = 'none';
    if(dqAWWrapSlider != null){
      awBxSlider.destroySlider();
      dqAWWrapSlider.parentNode.removeChild(dqAWWrapSlider);
    }
    awSubCatField.innerHTML = '<select disabled="disabled"><option selected="selected">Choose subcategory</option></select>';
  }});
  
  jQuery.post("qw.php","del=yes",function(data,textStatus){
    awCatField.innerHTML = data;
  });
}

function aCancImgUpl(){
  jQuery('#AFAddImg').slideUp({duration:500,complete:function(){
    document.getElementById('AFPhotoUpload').value = '';
  }});
}

function aDelImg(id){
  var afWrapSlider = document.getElementById('AFWrapSlider');
  var afSWrSl = document.getElementById('AFSecWrSl');
  var afPrevArrow = document.getElementById('afPrevArrow');
  var afNextArrow = document.getElementById('afNextArrow');
  var afBxSl = document.getElementById('AFBxSlider');
  jQuery.post("adelimg.php","aphid="+id,function(data,textStatus){
	if(data != ''){
	  var aimgarr = data.split('|sp|');
	  var laia = aimgarr.length;
	  var aimgrow = '';
	  for(var i=0;i<laia;i++){
	  aimgrow = aimgrow +
	  '<div class="afSlideWrap">'+
	  '<div class="afWrSlDelImg" onclick="aDelImg(\''+aimgarr[i]+'\');"><img class="afSlDelImg" src="icons/delimage.png" /></div>'+
	  '<img src="tmpimg/'+aimgarr[i]+'" class="afImgSlide" />'+
	  '</div>';
	  }
	  afBxSl.innerHTML = aimgrow;
	  var afStSl = 0;
	  if(laia > 3){
	    if(afNextArrow == null){ afSWrSl.insertAdjacentHTML('afterbegin','<div id="afNextArrow" onclick="afBxSlider.goToNextSlide();"><img src="icons/next.png" /></div>'); }
      if(afPrevArrow == null){ afSWrSl.insertAdjacentHTML('afterbegin','<div id="afPrevArrow" onclick="afBxSlider.goToPrevSlide();"><img src="icons/prev.png" /></div>'); }
      afStSl = laia - 3;
	  }
	  else{
	    if(afPrevArrow != null){ afPrevArrow.parentNode.removeChild(afPrevArrow); }
      if(afNextArrow != null){ afNextArrow.parentNode.removeChild(afNextArrow); }
	  }
	  afBxSlider.reloadSlider({ startSlide: afStSl, slideMargin: 7, pager: false, controls: false, maxSlides: 3, moveSlides: 1, slideWidth: 160 });
	  document.getElementById('AFImgNum').innerHTML = laia;
	}
	else{
	  jQuery(afWrapSlider).slideUp({duration:500,complete:function(){
	    afWrapSlider.parentNode.removeChild(afWrapSlider);
	  }});
	}
  });
}

function deleteAnswer(){
  document.getElementById('MBAnswTxtAr').value = '';
  document.getElementById('atchars').innerHTML = 0;
  document.getElementById('atlines').innerHTML = 0;
  var daAFAddImg = document.getElementById('AFAddImg');
  var daAFWrapSlider = document.getElementById('AFWrapSlider');
  var daAFSend = document.getElementById('MBAnsFormSend');
  
  if(daAFAddImg.style.display != 'none'){
    jQuery(daAFAddImg).slideUp({duration:500});
  }
  
  if(daAFWrapSlider != null){
    jQuery(daAFWrapSlider).slideUp({duration:500,complete:function(){
	  afBxSlider.destroySlider();
	  daAFWrapSlider.parentNode.removeChild(daAFWrapSlider);
	}});
  }
  
  if(daAFSend.style.display != 'none'){
    jQuery(daAFSend).slideUp({duration:500});
  }
  
  jQuery.post("deleteanswer.php","del=yes");
}

function qpscrpos(){ document.cookie = "qpy="+pageYOffset; }

// counts time till the next question or answer
function qcountdown(m,s){
  var awQFTimer = document.getElementById('awQFTimer');
  var awQFCount = document.getElementById('awQFCount');
  function timer(){
    var im = m;
    var is = s;
    if(m < 10){ im = '0'+m; }
    if(s < 10){ is = '0'+s; }
    var t = im + ':' + is;
    awQFCount.innerHTML = t;
    if(m == 0 && s == 0){
      clearInterval(tid);
      jQuery('#awQFTimer').delay(5000).slideUp({duration:500});
    }
    if(s == 0 && m > 0){ m--; s = 60; }
    if(s > 0){ s--; }
  }
  var tid = setInterval(timer,1000);
}

// counts time till the next answer or question
function acountdown(m,s){
  var mbAFTimer = document.getElementById('mbAFTimer');
  var mbAFCount = document.getElementById('mbAFCount');
  function timer(){
    var im = m;
    var is = s;
    if(m < 10){ im = '0'+m; }
    if(s < 10){ is = '0'+s; }
    var t = im + ':' + is;
    mbAFCount.innerHTML = t;
    if(m == 0 && s == 0){
      clearInterval(tid);
      jQuery('#mbAFTimer').delay(5000).slideUp({duration:500});
    }
    if(s == 0 && m > 0){ m--; s = 60; }
    if(s > 0){ s--; }
  }
  var tid = setInterval(timer,1000);
}
