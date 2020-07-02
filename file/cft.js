/* 
   /////////////////////////////////////////////////////////////////////////////////////
   //////JavaScript code for Comment Form Toolbar (c)Александр Петухов aka San40us//////
   ///////////////////////////////////////////////////////////////////////////////////// 
*/

//CONFIG////////>
  //Выравнивание панели
  var CommentFormToolbar_align = 'right';
  
  //Путь к папке с изображениями
  var CommentFormToolbar_imagesPath = WpQtSiteUrl+'/wp-content/plugins/comment-form-toolbar/img/';  
  
  //Путь к папке со смайлами, WpQtSiteUrl - содержит адрес блога;
  var CommentFormToolbar_smilesPath = WpQtSiteUrl+'/wp-includes/images/smilies/';

  //Кнопки
  var WpQtButtonsArray = new Array(
     new WpQtButton("<b>বোল্ড</b>", "insertHtmlTags(document.getElementById('comment'), 'strong', '', '', '')", "লেখা বোল্ড করুন"),
     new WpQtButton("<em>ইতালিক</em>", "insertHtmlTags(document.getElementById('comment'), 'em', '', '', '')", "ইতালিক করুন"),
     new WpQtButton("<u>আন্ডারলাইন</u>", "insertHtmlTags(document.getElementById('comment'), 'u', '', '', '')", "আন্ডারলাইন করুন"),
     new WpQtButton("<del>del</del>", "insertHtmlTags(document.getElementById('comment'), 'del', '', '', '')", "Del"),
     new WpQtButton("<img src=\""+CommentFormToolbar_imagesPath+"url.gif\" />", "insertHtmlTags(document.getElementById('comment'), 'a', {href: 'http://', target: '_blank'}, {href: 'URL টি এখানে পেস্ট করুন'}, '', '')", "লিংক যুক্ত করুন"),
     new WpQtButton("<img src=\""+CommentFormToolbar_imagesPath+"img.gif\" />", "insertHtmlTags(document.getElementById('comment'), 'img', {src: 'http://'}, {src: 'ইমেজ URL এখানে পেস্ট করুন'}, '', '')", "ছবি যুক্ত করুন"),
     new WpQtButton("<img src=\""+CommentFormToolbar_imagesPath+"ul.gif\" />", "insertHtmlTags(document.getElementById('comment'), 'ul', '', '', '')", "বুলেট পয়েন্ট লিস্ট"),
     new WpQtButton("<img src=\""+CommentFormToolbar_imagesPath+"ol.gif\" />", "insertHtmlTags(document.getElementById('comment'), 'ol', '', '', '')", "সংখ্যা যুক্ত লিস্ট"),
     new WpQtListOption("কোড", "pre", {php:'PHP', javascript:'JavaScript', html4strict:'HTML', css:'CSS'}, '', '', "কোড"),
     new WpQtSmiles("<img src=\""+CommentFormToolbar_imagesPath+"smile.gif\" />", "ইমোটিকন যুক্ত করুন")
  );
//CONFIG////////<

//FUNCTIOMS/////>
function WpQtButton(butt_text, hand_onclick, title) {
     this.button = '<a href="#" onclick="'+hand_onclick+'; return false" title="'+title+'">'+butt_text+'</a>';
}

function WpQtListOption(butt_text, tag_name, optArr, lt, gt, title) {
     this.button = '<a href="#" class="WpQtToolbarDropMenuButton" onclick="return false" onmouseover="WpQtListShowToggle(this.nextSibling, true)" onmouseout="WpQtListShowToggle(this.nextSibling, false)"  title="'+title+'">'+butt_text+'</a><ul onmouseover="WpQtListShowToggle(this, true)" onmouseout="WpQtListShowToggle(this, false)"  style="display: none;" class="dropMenu">';
     for (var i in optArr) {
	      this.button += '<li><a href="#" onclick="insertHtmlTags(document.getElementById(\'comment\'), \''+tag_name+'\', {\'lang\':\''+i+'\'}, \'\', \''+lt+'\', \''+gt+'\'); this.parentNode.parentNode.style.display=\'none\'; return false" style="background-image: none;">'+optArr[i]+'</a></li>';
	 }
	 this.button += '</ul>';
}
function WpQtSmiles(butt_text, title) {
  var smiles = {
     ':mrgreen:':'icon_mrgreen.gif',
     ':twisted:':'icon_twisted.gif',
     ':arrow:':'icon_arrow.gif',
     ':shock:':'icon_eek.gif',
     ':smile:':'icon_smile.gif',
     ':evil:':'icon_evil.gif',
     ':idea:':'icon_idea.gif',
     ':oops:':'icon_redface.gif',
     ':roll:':'icon_rolleyes.gif',
     ':cry:':'icon_cry.gif',
     ':lol:':'icon_lol.gif',
     ':-(':'icon_sad.gif',
     ':-)':'icon_smile.gif',
     ':-?':'icon_confused.gif',
     ':-D':'icon_biggrin.gif',
     ':-P':'icon_razz.gif',
     ':-o':'icon_surprised.gif',
     ':-x':'icon_mad.gif',
     ':-|':'icon_neutral.gif',
     ';-)':'icon_wink.gif',
     '8)':'icon_cool.gif',
     ':x':'icon_mad.gif',
     ':|':'icon_neutral.gif',
     ';)':'icon_wink.gif',
     ':!:':'icon_exclaim.gif',
     ':?:':'icon_question.gif'
  };
  this.button = '<a href="#" class="WpQtToolbarDropMenuButton" onclick="return false" onmouseover="WpQtListShowToggle(this.nextSibling, true)" onmouseout="WpQtListShowToggle(this.nextSibling, false)"  title="'+title+'">'+butt_text+'</a><ul onmouseover="WpQtListShowToggle(this, true)" onmouseout="WpQtListShowToggle(this, false)" style="display: none;" class="dropMenu"><li>';
  var i = 0;
  for (var key in smiles) {
	  this.button += '<img style="margin: 2px; cursor: pointer;" src="'+CommentFormToolbar_smilesPath+smiles[key]+'" onclick="insertHtmlTags(document.getElementById(\'comment\'), \'smile_'+key+'\')" title="'+key+'" />';
      i++;
	  if (i == 6) {
		  this.button += '<br />'
		  i = 0;
	  }
  }
  this.button += '</li><ul>'
}
function WpQtListShowToggle(listObj, mode) {
	 if (mode) {
	     listObj.style.display = 'block';
	 }
	 else if (!mode) {
	     listObj.style.display = 'none';
	 } 
}
function createList(tagName,strAttr, text, lt, gt) {
   var str = '';
   var spl = '\n'
   if (navigator.appName == 'Microsoft Internet Explorer' || navigator.appName == 'Opera')
	   spl = '\r\n';
   this.openTag = spl+lt+tagName+strAttr+gt;
   this.closeTag = lt+'/'+tagName+gt+spl;
   if (text) {
	   var list = text.split(spl);
	   for (var i = 0; i < list.length; i++) {
		   if (list[i] != '') str += lt+'li'+gt+list[i]+lt+'/li'+gt+spl;
	   }
	   str = spl+str;
   }
   else {
       this.openTag = spl+lt+tagName+strAttr+gt+spl+lt+'li'+gt;
       this.closeTag = lt+'/li'+gt+spl+lt+'/'+tagName+gt+spl;
   }
   this.text = str;
}

function insertHtmlTags(taObj, htmlTag, attributes, prompts, lt, gt) {
  if (!taObj === null) return false; 
  if (!lt || !gt) {
      lt = '<';
	  gt = '>';
  }
  var caretPos = 0; 
  var start = 0;
  var end = 0;
  var selText;
  var strAttr = '';
  var unpariedTags = new Array('img', 'br', 'hr', 'input', 'link');
  if (attributes) {
	var ic = 0;
    for (var i in attributes) {
	  if (prompts) {
	      if (prompts[i]) {
	          var req = prompt(prompts[i], attributes[i]);
			  req = (req) ? req:'';
	          if (req && req != 'udefined')  strAttr += ' '+i+'="'+req+'"';
			  else if ((!req || req == 'udefined') && (ic === 0)) return false;
		      else if ((!req || req == 'udefined') && (ic > 0)) break;
	       }
		   else strAttr += ' '+i+'="'+attributes[i]+'"'; 
	    }
	   else strAttr += ' '+i+'="'+attributes[i]+'"'; 
	   ic++;
	}
	var openTag = lt+htmlTag+strAttr+gt;
  }
  var unparied = false;
  for (var i = 0; i <unpariedTags.length; i++) {
     if (unpariedTags[i] == htmlTag.toLowerCase())
	     unparied = true;
  }
  if (unparied) {
     var openTag = lt+htmlTag+strAttr+" /"+gt;
     var closeTag = "";
  }
  else {
     var openTag = lt+htmlTag+strAttr+gt;
	 var closeTag = lt+"/"+htmlTag+gt;
  }
  taObj.focus();
  if (document.getSelection || window.getSelection)  {
      start = taObj.selectionStart;
      end = taObj.selectionEnd;
  }
  
  else if (document.selection) {
      var sel = document.selection.createRange();
      var clone = sel.duplicate();
      sel.collapse(true);
      clone.moveToElementText(taObj);
      clone.setEndPoint("EndToEnd", sel);
      start = clone.text.length;
      sel = document.selection.createRange();
      clone = sel.duplicate();
      sel.collapse(false);
      clone.moveToElementText(taObj);
      clone.setEndPoint("EndToEnd", sel);
      end = clone.text.length;
  }
  var selText = taObj.value.substring(start, end);
  switch (htmlTag.toLowerCase()) {
    case 'ul':
	   var extend = new createList(htmlTag, strAttr ,selText, lt, gt);
	   openTag = extend.openTag;
	   closeTag = extend.closeTag;
	   selText = extend.text;
	break;
    case 'ol':
	   var extend = new createList(htmlTag, strAttr ,selText, lt, gt);
	   openTag = extend.openTag;
	   closeTag = extend.closeTag;
	   selText = extend.text;
	break;
  }
  if (htmlTag.indexOf('smile_') == 0) {
	  openTag = '';
	  closeTag = '';
	  selText = htmlTag.substr(6);
  }
  if (selText === '') {
      var str = taObj.value;
	  var nPos = start+openTag.length;
	  var begText = str.substring(0, start);
	  if  (navigator.appName == 'Microsoft Internet Explorer') {
     	   var reSpl = /\r\n/g;
		   var i = 0;
		   var res;
		   while (reSpl.exec(begText+openTag) != null) {
		       i++;
		   }
		   nPos = nPos - i;
	  }
      taObj.value = begText+openTag+closeTag+str.substr(start);
      if (taObj.createTextRange) {
          var caret = taObj.createTextRange();
          caret.collapse();
          caret.moveStart("character", nPos);
          caret.select();
      }
      else if(window.getSelection) {
         taObj.setSelectionRange(nPos, nPos);
         taObj.focus();
      }
  }
  else if (selText) {
      var str = taObj.value;
      taObj.value = str.substring(0, start)+openTag+selText+closeTag+str.substr(end);
  }
}
function WpQtToolbarInit() {
	var WpQtTaObj = document.getElementById('comment');
    var WpQtToolBarBody = document.createElement('div');
    WpQtToolBarBody.id = 'WpQtToolBarBody';
	WpQtTaObj.style.marginTop = '2px';
	WpQtToolBarBody.align = CommentFormToolbar_align;
	WpQtToolBarWidth = WpQtTaObj.offsetWidth;
	WpQtToolBarButtons = '';
	for (var i = 0; i < WpQtButtonsArray.length; i++) {
        WpQtToolBarButtons += '<td>'+WpQtButtonsArray[i].button+'</td>';
	}
	WpQtToolBarBody.innerHTML += '<div style="width: '+WpQtToolBarWidth+'px;" align="left"><table border="0" cellpadding="0" cellspacing="1" ><tr>'+WpQtToolBarButtons+'</tr></table></div>';
	WpQtTaObj.parentNode.insertBefore(WpQtToolBarBody, WpQtTaObj);
}
//FUNCTIONS/////<