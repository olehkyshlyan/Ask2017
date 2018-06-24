if(!Array.prototype.indexOf){ Array.prototype.indexOf = function(obj,start){ for(var i = (start || 0), j = this.length; i < j; i++){ if(this[i] === obj){ return i; } } return -1; }}

(function(){
var arr = [];
askaq = function(id){
  if(arr.indexOf(id) == -1){
	arr.push(id);
	document.getElementById(id).innerHTML = 'Log in to ask a question';
	jQuery('#'+id).slideDown({duration:500,complete:function(){ jQuery('#'+id).delay(3000).slideUp({duration:500,complete:function(){
	  var ind = arr.indexOf(id);
	  if(ind != -1){ arr.splice(ind,1); }
	}}) }});
  }
}
})();

(function(){
var arr = [];
qneucompl = function(id){
  if(arr.indexOf(id) == -1){
	arr.push(id);
	document.getElementById(id).innerHTML = 'Log in to complain the question';
	jQuery('#'+id).slideDown({duration:500,complete:function(){ jQuery('#'+id).delay(3000).slideUp({duration:500,complete:function(){
	  var ind = arr.indexOf(id);
	  if(ind != -1){ arr.splice(ind,1); }
	}}) }});
  }
}
})();

(function(){
var arr = [];
aneucompl = function(id){
  if(arr.indexOf(id) == -1){
	arr.push(id);
	document.getElementById(id).innerHTML = 'Log in to complain the answer';
	jQuery('#'+id).slideDown({duration:500,complete:function(){ jQuery('#'+id).delay(3000).slideUp({duration:500,complete:function(){
	  var ind = arr.indexOf(id);
	  if(ind != -1){ arr.splice(ind,1); }
	}}) }});
  }
}
})();

(function(){
var arr = [];
qentersite = function(id){
  if(arr.indexOf(id) == -1){
	arr.push(id);
	document.getElementById(id).innerHTML = 'Log in to answer the question';
	jQuery('#'+id).slideDown({duration:500,complete:function(){ jQuery('#'+id).delay(3000).slideUp({duration:500,complete:function(){
	  var ind = arr.indexOf(id);
	  if(ind != -1){ arr.splice(ind,1); }
	}}) }});
  }
}
})();

function sldn500(id){
  jQuery('#'+id).slideDown({duration:500});
}

function slup500(id){
  jQuery('#'+id).slideUp({duration:500});
}

function showCategories(){
  var element = document.getElementById('categories');
  var style = element.currentStyle || window.getComputedStyle(element, null);
  if(style.display == 'none'){
    jQuery('#categories').show('fade',250);
	jQuery('#categoriesWord').animate({backgroundColor: 'rgb(255,243,204)'},250);
  }
  else if(style.display == 'block'){
    jQuery('#categories').hide('fade',250);
	jQuery('#categoriesWord').animate({backgroundColor: 'rgb(255,255,240)'},250);
  }
}

function hideCategories(th,event)
{
var related = event.relatedTarget || event.toElement;

if(related.id != 'categoriesWord' && related.id != 'categoriesWordSpan' && related.id != 'categories' && related.className != 'categories' && related.className != 'categorieslink')
{
jQuery('#categories').hide('fade',250);
jQuery('#categoriesWord').animate({backgroundColor: 'rgb(255,255,240)'},250);
}
}

function qdqshowmore(h){
  //alert('h: '+h);
  document.getElementById('qdshmore').style.zIndex = -1;
  document.getElementById('qdshless').style.zIndex = 1;
  if(h <= 150){
    jQuery('#qTxtDet').animate({height:h+'px'},500);
  }
  else{
  var fcwqtxtd = document.getElementById('wQTxtDet').firstChild.nextSibling;
	if(fcwqtxtd != null && fcwqtxtd.className == 'slimScrollDiv'){
	  jQuery(fcwqtxtd).animate({height:'150px'},500);
	}
    jQuery('#qTxtDet').animate({height:'150px'},500,function(){
	  jQuery('#qTxtDet').slimScroll({height:'150px',alwaysVisible:true});
	});
  }
}

function qdqshowless(h){
  document.getElementById('qdshless').style.zIndex = -1;
  document.getElementById('qdshmore').style.zIndex = 1;
  var wqtxtd = document.getElementById('wQTxtDet');
  var dqtxt = document.getElementById('qTxtDet');
  var fcwqtxtd = wqtxtd.firstChild.nextSibling;
  jQuery(dqtxt).animate({height:h+'px'},500);
  if(fcwqtxtd != null && fcwqtxtd.className == 'slimScrollDiv'){
	jQuery(fcwqtxtd).animate({height:h+'px'},500,function(){
	  wqtxtd.replaceChild(dqtxt,fcwqtxtd);
	});
  }
}

function ashowmore(id,h){
  document.getElementById('ashmore'+id).style.zIndex = -1;
  document.getElementById('ashless'+id).style.zIndex = 1;
  if(h <= 150){
    jQuery('#atxt'+id).animate({height:h+'px'},500);
  }
  else{
  var fcwatxt = document.getElementById('watxt'+id).firstChild.nextSibling;
	if(fcwatxt != null && fcwatxt.className == 'slimScrollDiv'){
	  jQuery(fcwatxt).animate({height:'150px'},500);
	}
  jQuery('#atxt'+id).animate({height:'150px'},500,function(){
	  jQuery('#atxt'+id).slimScroll({height:'150px',alwaysVisible:true});
	});
  }
}

function ashowless(id,h){
  document.getElementById('ashless'+id).style.zIndex = -1;
  document.getElementById('ashmore'+id).style.zIndex = 1;
  var watxt = document.getElementById('watxt'+id);
  var atxt = document.getElementById('atxt'+id);
  var fcwatxt = watxt.firstChild.nextSibling;
  jQuery(atxt).animate({height:h+'px'},500);
  if(fcwatxt != null && fcwatxt.className == 'slimScrollDiv'){
	jQuery(fcwatxt).animate({height:h+'px'},500,function(){
	  watxt.replaceChild(atxt,fcwatxt);
	});
  }
}

function uqqdshmore(id,h){
  //alert('h: '+h);
  document.getElementById('qdshmore'+id).style.zIndex = -1;
  document.getElementById('qdshless'+id).style.zIndex = 1;
  if(h <= 150){
    jQuery('#qTxtDet'+id).animate({height:h+'px'},500);
  }
  else{
    var fcwqtxtd = document.getElementById('wQTxtDet'+id).firstChild.nextSibling;
	if(fcwqtxtd != null && fcwqtxtd.className == 'slimScrollDiv'){
	  jQuery(fcwqtxtd).animate({height:'150px'},500);
	}
    jQuery('#qTxtDet'+id).animate({height:'150px'},500,function(){
	  jQuery('#qTxtDet'+id).slimScroll({height:'150px',alwaysVisible:true});
	});
  }
}

function uqqdshless(id,h){
  document.getElementById('qdshless'+id).style.zIndex = -1;
  document.getElementById('qdshmore'+id).style.zIndex = 1;
  var wqtxtd = document.getElementById('wQTxtDet'+id);
  var dqtxt = document.getElementById('qTxtDet'+id);
  var fcwqtxtd = wqtxtd.firstChild.nextSibling;
  jQuery(dqtxt).animate({height:h+'px'},500);
  if(fcwqtxtd != null && fcwqtxtd.className == 'slimScrollDiv'){
	jQuery(fcwqtxtd).animate({height:h+'px'},500,function(){
	  wqtxtd.replaceChild(dqtxt,fcwqtxtd);
	});
  }
}

var cookie = {};
document.cookie = "test=t";
var cookiesEnabled = (document.cookie.indexOf("test")!=-1) ? true : false;
if(cookiesEnabled == true){
  var scookie = document.cookie.split(';');
  var lcookie = scookie.length;
  for(var i=0; i<lcookie; i++){
  var tscookie = scookie[i].trim();
	var sp = tscookie.indexOf('=');
	var ckey = tscookie.substring(0,sp);
	var cval = tscookie.substring(sp+1);
	cookie[ckey] = cval;
  }
}

jQuery(document).ready(function(){

if(cookiesEnabled == false){
  var cksnot = document.getElementById('cksnot');
  if(cksnot != null){
    cksnot.innerHTML = 'Cookies are disabled in your browser for this site';
    jQuery(cksnot).slideDown({duration:500}).delay(5000).slideUp({duration:500});
  }
}

});

function showMoreAnswers(qid,aid){
  //if(qid != '' && aid != ''){
  jQuery.post("shma.php","qid="+qid+"&aid="+aid,function(data,textStatus){
    //alert('data: '+data);
    if(data != ''){
      //alert('data: '+data);
      data = JSON.parse(data);
      if(data.abl){
        var cshowmore = document.getElementById('cshowmore');
        var labl = data.abl.length
        for(var i=0; i<labl; i++){
          var atid = data.abl[i].id;
          cshowmore.insertAdjacentHTML('beforebegin',data.abl[i].a);
          var watxt = document.getElementById('watxt'+atid);
          
          if(watxt != null){
            var atxt = document.getElementById('atxt'+atid);
            var hatxt = atxt.clientHeight;
            var mhatxt = 30;
            if(hatxt > 30 && hatxt < 91){ mhatxt = hatxt; }
            else if(hatxt > 90){ mhatxt = 90; }
            if(hatxt > 90){
              atxt.style.height = mhatxt+'px';
              watxt.insertAdjacentHTML('beforeend','<div class="washow"><div id="ashmore'+atid+'" class="ashow" onclick="ashowmore('+atid+','+hatxt+');">Show more</div><div id="ashless'+atid+'" class="ashow" style="z-index:-1;" onclick="ashowless('+atid+','+mhatxt+');">Show less</div></div>');
            }
          }
          
          if(data.abl[i].sl){
            var aBxSlLen = data.abl[i].sl;
            if(aBxSlLen > 3){
              var wrapABBxSl = document.getElementById('wrapABBxSlider'+atid);
              wrapABBxSl.insertAdjacentHTML('afterbegin','<div class="bxSlNextArrow" onclick="jABBxSl'+atid+'.goToNextSlide();"><img src="icons/next.png" /></div>');
              wrapABBxSl.insertAdjacentHTML('afterbegin','<div class="bxSlPrevArrow" onclick="jABBxSl'+atid+'.goToPrevSlide();"><img src="icons/prev.png" /></div>');
            }
            window['jABBxSl'+atid] = jQuery('#ABBxSlider'+atid).bxSlider({ slideMargin: 7, pager: false, controls: false, maxSlides: 3, moveSlides: 1, slideWidth: 160 });
          }
        }
      }
      if(data.qid){
        qpqnum = data.qid;
      }
      if(data.aid){
        if(data.aid != 'no'){
          qplaid = data.aid;
        }
        else{
          qpqnum = '';
          qplaid = '';
          jQuery('#cshowmore').slideUp({duration:500});
        }
      }
    }
  });
  //}
}
