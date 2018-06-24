function up_scrpos(){ document.cookie = "upy="+pageYOffset; }

function up_chfname(){
  var fname = document.getElementById('frbfname').value;
  fname = encodeURIComponent(fname);
  jQuery.post("chfname.php","fname="+fname,function(data,textStatus){
    if(data != ''){
      data = JSON.parse(data);
      if(data.msg){ document.getElementById('fnchanges').innerHTML = data.msg; }
      if(data.newfname){ document.getElementById('frbfname').value = data.newfname; }
      jQuery('#fnchanges').slideDown({duration:500}).delay(5000).slideUp({duration:500});
    }
  });
}

function up_chlname(){
  var lname = document.getElementById('frblname').value;
  lname = encodeURIComponent(lname);
  jQuery.post("chlname.php","lname="+lname,function(data,textStatus){
    if(data != ''){
      data = JSON.parse(data);
      if(data.msg){ document.getElementById('lnchanges').innerHTML = data.msg; }
      if(data.newlname){ document.getElementById('frblname').value = data.newlname; }
      jQuery('#lnchanges').slideDown({duration:500}).delay(5000).slideUp({duration:500});
    }
  });
}

function up_chcity(){
  var city = document.getElementById('frbcity').value;
  city = encodeURIComponent(city);
  jQuery.post("chcity.php","city="+city,function(data,textStatus){
    if(data != ''){
      data = JSON.parse(data);
      if(data.msg){ document.getElementById('chcity').innerHTML = data.msg; }
      if(data.newcity){ document.getElementById('frbcity').value = data.newcity; }
      jQuery('#chcity').slideDown({duration:500}).delay(5000).slideUp({duration:500});
    }
  });
}

function up_chlemail(){
  var lemail = document.getElementById('frblemail').value;
  lemail = encodeURIComponent(lemail);
  jQuery.post("chlemail.php","lemail="+lemail,function(data,textStatus){
    if(data != ''){
      data = JSON.parse(data);
      if(data.msg){ document.getElementById('chlemail').innerHTML = data.msg; }
      if(data.newemail){ document.getElementById('frblemail').value = data.newemail; }
      jQuery('#chlemail').slideDown({duration:500}).delay(5000).slideUp({duration:500});
    }
  });
}

function up_chcemail(){
  var cemail = document.getElementById('frbcemail').value;
  cemail = encodeURIComponent(cemail);
  jQuery.post("chcemail.php","cemail="+cemail,function(data,textStatus){
    if(data != ''){
      data = JSON.parse(data);
      if(data.msg){ document.getElementById('chсemail').innerHTML = data.msg; }
      if(data.newemail){ document.getElementById('frbcemail').value = data.newemail; }
      jQuery('#chсemail').slideDown({duration:500}).delay(5000).slideUp({duration:500});
    }
  });
}

function up_chphone(){
  var phone = document.getElementById('frbphone').value;
  phone = encodeURIComponent(phone);
  jQuery.post("chphone.php","phone="+phone,function(data,textStatus){
    if(data != ''){
      data = JSON.parse(data);
      if(data.msg){ document.getElementById('chphone').innerHTML = data.msg; }
      if(data.newphone){ document.getElementById('frbphone').value = data.newphone; }
      jQuery('#chphone').slideDown({duration:500}).delay(5000).slideUp({duration:500});
    }
  });
}

function up_contcountchars(th){
  var ccont = th.value;
  var lm = 200;
  var txtlength = ccont.length;
  var cursorPos = getCursorPosition(th);
  var left = lm - txtlength;
  
  var sccont = ccont.split(/[(\r\n)(\n)(\r)]/ig);
  var tlsccont = sccont.length;
  var nsccont = [];
  var stln = 37;
  for(var i=0;i<tlsccont;i++){
    if(sccont[i].length > stln){
      var pl = sccont[i].length-1;
      var npl = pl/stln;
      npl = Math.floor(npl);
      var sp = 0;
      var ep = sp+stln;
      for(var j=0;j<=npl;j++){
        var parr = sccont[i].substring(sp,ep);
        nsccont.push(parr);
        sp = sp+stln;
        ep = ep+stln;
      }
    }
    else{
      nsccont.push(sccont[i]);
    }
  }
  
  var nllm = 7;
  var lnsccont = nsccont.length;
  if(lnsccont > nllm){ left = -1; }
  var fptxtend = cursorPos+left;
  
  if(txtlength > lm || lnsccont > nllm){
    var fptxt = th.value.substring(0,fptxtend);
    var sptxt = th.value.substring(cursorPos);
    var total = fptxt + sptxt;
    th.value = total;
    ccont = total;
    setCursorPosition(th,fptxtend);
  }
  
  if(txtlength <= lm){
    document.getElementById('charscounts').innerHTML = ccont.length;
  }
  
}

function up_clchcontacts(){
  var cont = document.getElementById('frbcont').value;
  var mres = cont.match(/[\r\n\n\r]/ig);
  if(mres.length > 5){
  var pattern = /[\r\n\n\r]/ig;
  var exres;
  var count = 0;
  while((exres = pattern.exec(cont)) != null){
    if(count > 4){
      var fpart = cont.substring(0,exres.index);
      var spart = cont.substring(exres.index);
      var pspart = spart.replace(/[\r\n\n\r]/ig,'');
      document.getElementById('frbcont').value = fpart+pspart;
      break;
    }
    count++;
  }
  }
}

function up_chcontacts(){
  var cont = document.getElementById('frbcont').value;
  cont = encodeURIComponent(cont);
  jQuery.post("chcontacts.php","cont="+cont,function(data,textStatus){
    if(data != ''){
      data = JSON.parse(data);
      if(data.msg){ document.getElementById('chcont').innerHTML = data.msg; }
      if(data.newcontacts){ document.getElementById('frbcont').value = data.newcontacts; }
      jQuery('#chcont').slideDown({duration:500}).delay(5000).slideUp({duration:500});
    }
  });
}

