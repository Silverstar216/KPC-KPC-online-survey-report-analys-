$(window).resize(function() { 
	blockResize();
}); 
function blockResize(){
	if( $("#leftBox").length > 0 ){
		scrollIntoView("leftBox");
	}
	if( $("#rightBox").length > 0 ){
		scrollIntoView("rightBox");
	}
	if( $("#centerBox").length > 0 ){
		scrollIntoView("centerBox");
	}
}
function scrollIntoView(nav) {
	var scrolling = document.getElementById(nav);
	var objHeight = $(window).height() - 170;
	scrolling.style.height = objHeight+"px";
}

function commify(n){
  var reg = /(^[+-]?\d+)(\d{3})/;   // 정규식
  n += '';                          // 숫자를 문자열로 변환

  while (reg.test(n))
    n = n.replace(reg, '$1' + ',' + '$2');

  return n;
}