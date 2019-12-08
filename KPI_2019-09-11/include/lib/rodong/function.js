var g_bIE			= ( /msie/i.test(navigator.userAgent) && !/opera/i.test(navigator.userAgent) );
var g_bIE5			= ( g_bIE && /msie 5\.0/i.test(navigator.userAgent) ); 
var g_bKHtml		= /Konqueror|Safari|KHTML/i.test(navigator.userAgent);
var g_iTimerID		= null; 
var g_strMenuID		= null; 
var g_strComboID	 	= null; 
var g_objEvent; 
String.prototype.trim = function() { return this.replace(/^\s+|\s+$/, ''); };
function trimInt(strSrc, iMaxPlace) {
	try {
		var strResult = parseInt(strSrc, 10);
		if (!isNaN(strResult) && strResult != Number.POSITIVE_INFINITY && strResult != Number.NEGATIVE_INFINITY) {
			if (strResult.length > iMaxPlace)
				strResult = 0;
		}
		else {
			strResult = 0;
		}

		if (strResult == 0)
			strResult = "";
	}
	catch(e) {strResult = "";};
	
	return strResult;
}
function trimFloat(strSrc, iMaxPlace, iPointPlace) {
	try {
		var strResult = parseFloat(strSrc);
		if (!isNaN(strResult) && strResult != Number.POSITIVE_INFINITY && strResult != Number.NEGATIVE_INFINITY) {
			if (strResult >= Math.pow(10, (iMaxPlace - iPointPlace)))
				strResult = 0;
			else
				strResult	= Math.round(strResult * Math.pow(10, iPointPlace)) / Math.pow(10, iPointPlace);
		}
		else {
			strResult = 0;
		}
		
		if (strResult == 0)
			strResult = "";
	}
	catch(e) {strResult = "";};
	
	return strResult;
}

function trimDate(strSrc) {
	try {
		for (var i = 0; i < strSrc.length; i++) {
			if (strSrc.charCodeAt(i) < 0x30 || strSrc.charCodeAt(i) > 0x39) {
				strSrc = strSrc.substring(0, i) + "-" + strSrc.substring(i + 1, strSrc.length);
			}
		}
		var strResult = "";
		var dToday		= new Date();
		var arrValue	= strSrc.split("-");
		if ((arrValue.length == 3) &&
			(parseInt(arrValue[0], 10) >= START_YEAR && parseInt(arrValue[0], 10) <= END_YEAR) &&
			(parseInt(arrValue[1], 10) >= 1 && parseInt(arrValue[1], 10) <= 12) &&
			(parseInt(arrValue[2], 10) >= 1 && parseInt(arrValue[2], 10) <= jsGetMaxDays(parseInt(arrValue[0], 10), parseInt(arrValue[1], 10)))) {
			strResult	= parseInt(arrValue[0], 10) + "-";
			strResult	+= (parseInt(arrValue[1], 10) < 10? "0": "") + parseInt(arrValue[1], 10) + "-";
			strResult	+= (parseInt(arrValue[2], 10) < 10? "0": "") + parseInt(arrValue[2], 10);
		}
		else if ((arrValue.length == 2) &&
			(parseInt(arrValue[0], 10) >= 1 && parseInt(arrValue[0], 10) <= 12) &&
			(parseInt(arrValue[1], 10) >= 1 && parseInt(arrValue[1], 10) <= jsGetMaxDays(parseInt(dToday.getFullYear(), 10), parseInt(arrValue[0], 10)))) {
			strResult	= dToday.getFullYear() + "-";
			strResult	+= (parseInt(arrValue[0], 10) < 10? "0": "") + parseInt(arrValue[0], 10) + "-";
			strResult	+= (parseInt(arrValue[1], 10) < 10? "0": "") + parseInt(arrValue[1], 10);
		}
		else if ((arrValue.length == 1) &&
			(parseInt(arrValue[0], 10) >= 1 && parseInt(arrValue[0], 10) <= jsGetMaxDays(parseInt(dToday.getFullYear(), 10), parseInt(dToday.getMonth(), 10)))) {
			strResult	= dToday.getFullYear() + "-";
			strResult	+= ((dToday.getMonth() + 1) < 10? "0": "") + (dToday.getMonth() + 1) + "-";
			strResult	+= (parseInt(arrValue[0], 10) < 10? "0": "") + parseInt(arrValue[0], 10);
		}
	}
	catch(e) {};
	return strResult;
}
function trimTime(strSrc) {
	try {
		for (var i = 0; i < strSrc.length; i++) {
			if (strSrc.charCodeAt(i) < 0x30 || strSrc.charCodeAt(i) > 0x39) {
				strSrc = strSrc.substring(0, i) + ":" + strSrc.substring(i + 1, strSrc.length);
			}
		}
		var strResult = "";
		var arrValue	= strSrc.split(":");
		if ((arrValue.length == 2) &&
			(parseInt(arrValue[0], 10) >= 0 && parseInt(arrValue[0], 10) <= 23) &&
			(parseInt(arrValue[1], 10) >= 0 && parseInt(arrValue[1], 10) <= 59)) {
			strResult	= (parseInt(arrValue[0], 10) < 10? "0": "") + parseInt(arrValue[0], 10) + ":";
			strResult	+= (parseInt(arrValue[1], 10) < 10? "0": "") + parseInt(arrValue[1], 10);
		}
		else if ((arrValue.length == 1) &&
			(parseInt(arrValue[0], 10) >= 0 && parseInt(arrValue[0], 10) <= 23)) {
			strResult	= (parseInt(arrValue[0], 10) < 10? "0": "") + parseInt(arrValue[0], 10) + ":00";
		}
	}
	catch(e) {};
	
	return strResult;
}
function jsGetSubStrCnt(strSrc, strSpec) {
	var iCount = 0;
	for (i = 0; i < strSrc.length; i++) {
		if (strSrc.substring(i, strSpec.length + i) == strSpec)
			iCount ++;
	}
	return iCount;
}
function jsGetMaxDays(strYear, strMonth) {
	if (parseInt(strMonth, 10) == 2 && parseInt(strYear, 10) % 4 == 0) {
		if (parseInt(strYear, 10) % 400 != 0 && parseInt(strYear, 10) % 100 == 0)
			return 28;
		else
			return 29;
	}
	return MAX_DAYS[parseInt(strMonth, 10) - 1];
}
function jsGetPrevDate(strObjID) {
	try {
		var strDate	= document.getElementById(strObjID + "Text").value;
		if (trimDate(strDate).length < 1)
			return "";
		var arrDate	= strDate.split('-');
		var iYear	= parseInt(arrDate[0], 10) ;
		var iMonth	= parseInt(arrDate[1], 10) ;
		var iDate	= parseInt(arrDate[2], 10) - 1 ;
		if (iDate < 1) { 
			iMonth = iMonth - 1;
			if (iMonth < 1)	{ 
				iYear	= iYear - 1;
				iMonth	= 12;
			}
			iDate = jsGetMaxDays(iYear, iMonth);
		}
		arrDate[0]	= iYear;
		arrDate[1]	= iMonth < 10? "0" + iMonth: iMonth;
		arrDate[2]	= iDate < 10? "0" + iDate: iDate;
		document.getElementById(strObjID + "Text").value = arrDate[0] + "-" + arrDate[1] + "-" + arrDate[2];
		document.getElementById(strObjID + "Text").onchange();
	}
	catch(e) {}
	return;
}
function jsGetNextDate(strObjID) {
	try {
		var strDate	= document.getElementById(strObjID + "Text").value;
		if (trimDate(strDate).length < 1)
			return "";
		var arrDate	= strDate.split('-');
		var iYear	= parseInt(arrDate[0], 10) ;
		var iMonth	= parseInt(arrDate[1], 10) ;
		var iDate	= parseInt(arrDate[2], 10) + 1 ;
		if (iDate > jsGetMaxDays(iYear, iMonth)) {	 
			iMonth = iMonth + 1;
			if (iMonth > 12)	{ 
				iYear	= iYear + 1;
				iMonth	= 12;
			}
			iDate = 1;
		}
		arrDate[0]	= iYear;
		arrDate[1]	= iMonth < 10? "0" + iMonth: iMonth;
		arrDate[2]	= iDate < 10? "0" + iDate: iDate;
		document.getElementById(strObjID + "Text").value = arrDate[0] + "-" + arrDate[1] + "-" + arrDate[2];
		document.getElementById(strObjID + "Text").onchange();
	}
	catch(e) {}
	return;
}
function jsGetAbsolutePosition(objSource) {
	try {
		var objPos	= { x:objSource.offsetLeft, y:objSource.offsetTop };
		var bIsDiv	= /^[DIV|div]$/i.test(objSource.tagName);
		if (bIsDiv && objSource.scrollLeft)
			objPos.x -= objSource.scrollLeft;
		if (bIsDiv && objSource.scrollTop)
			objPos.y -= objSource.scrollTop;
		if (objSource.offsetParent) {
			var objTempPos	= this.jsGetAbsolutePosition(objSource.offsetParent);
			objPos.x += objTempPos.x;
			objPos.y += objTempPos.y;
		}
		return objPos;
	}
	catch(e) {
		return {x:0, y:0};
	};
}
function jsSetFocusNextObj(objCurrent) {
	try {
		var objNext = objCurrent.nextSibling;
		while (objNext != null && (objNext.tagName != "INPUT" || objNext.type == "hidden") && objNext.tagName != "TEXTAREA" && objNext.tagName != "SELECT") {
			if (objNext.nextSibling == null && objNext.parentNode.nextSibling != null) {
				var objNextTD = objNext.parentNode.nextSibling;
				while (objNextTD != null && objNextTD.tagName != "TD") {
					if (objNextTD.nextSibling == null && objNextTD.parentNode.nextSibling != null) {
						var objNextTR = objNextTD.parentNode.nextSibling;
						while (objNextTR != null && objNextTR.tagName != "TR") {
							objNextTR = objNextTR.nextSibling;
						}
						objNextTD = (objNextTR != null && objNextTR.childNodes.length > 0? objNextTR.childNodes.item(0): null);
					}
					else {
						objNextTD = objNextTD.nextSibling;
					}
				}
				objNext = (objNextTD != null && objNextTD.childNodes.length > 0? objNextTD.childNodes.item(0): null);
			}
			else {
				objNext = objNext.nextSibling;
			}
		}
		if (objNext != null) {
			objNext.focus();
			objNext.select();
		}
	}
	catch(e) {};
}
function jsOnChangeDateTime(strObjID) {
	var arrHiddenObj	= document.getElementsByName("dt" + strObjID);
	var arrDateObj		= document.getElementsByName("d" + strObjID);
	var arrTimeObj		= document.getElementsByName("t" + strObjID);
	arrHiddenObj[0].value = trimDate(arrDateObj[0].value) + " " + trimTime(arrTimeObj[0].value);
	return;
}
function jsOnBlurIntText(objText, iMaxPlace) {
	objText.value = trimInt(objText.value, iMaxPlace);
}
function jsOnBlurFloatText(objText, iMaxPlace, iPointPlace) {
	objText.value = trimFloat(objText.value, iMaxPlace, iPointPlace);
}
function jsOnBlurCalendar(objText) {
	objText.value = trimDate(objText.value);
}
function jsOnBlurTime(objText) {
	objText.value = trimTime(objText.value);
}
function jsOnFocusCombo(strObjName, iRowNum) {
		if (g_strComboID != null) {
			var objOldDIV	= document.getElementById(g_strComboID);
			if (objOldDIV.getElementsByTagName("OPTION").length > 0)
				objOldDIV.style.display	= "none";
			else
				objOldDIV.innerHTML	= "";
			g_strComboID = null;
		}
		var objText		= document.getElementById("str" + strObjName + "Name" + (!isNaN(parseInt(iRowNum))? iRowNum: ""));
		var objSelect	= document.getElementById("i" + strObjName + "ID" + (!isNaN(parseInt(iRowNum))? iRowNum: ""));
		var objDIV		= document.getElementById(strObjName + "DIV" + (!isNaN(parseInt(iRowNum))? iRowNum: ""));
		var objTextPos		= jsGetAbsolutePosition(objText);
		if (g_bIE) {
			var objParentPos	= jsGetAbsolutePosition(objDIV.offsetParent);
			objDIV.style.left	= objTextPos.x - objParentPos.x;
			objDIV.style.top	= objTextPos.y - objParentPos.y + objText.offsetHeight;
		}
		else {
			if ((objText.offsetParent).offsetParent.id == "") {
				objDIV.style.left	= objTextPos.x;
				objDIV.style.top	= objTextPos.y + objText.offsetHeight;
			}
			else {
				var objTable			= (objText.offsetParent).offsetParent;
				var objTableDIV			= document.getElementById(objTable.id + "DIV");
				var objTableOuterDIV	= (objTableDIV != null? document.getElementById(objTable.id + "OuterDIV"): null);

				objDIV.style.left	= objTextPos.x - objTable.left;
				objDIV.style.top	= objTextPos.y - (objTableDIV != null? objTableOuterDIV.offsetTop: 0) + objText.offsetHeight - (objTableDIV != null? objTableDIV.scrollTop: 0);
			}
		}
		objSelect.style.width	= objText.style.width;
		objDIV.style.display	= "";
		g_strComboID			= objDIV.id;
		var iOffset 			= document.body.scrollHeight;
		document.body.scrollTop = document.body.scrollHeight - iOffset;
}

function jsOnKeyUpCombo(strObjName, iRowNum) {
		var objText		= document.getElementById("str" + strObjName + "Name" + (!isNaN(parseInt(iRowNum))? iRowNum: ""));
		var objSelect	= document.getElementById("i" + strObjName + "ID" + (!isNaN(parseInt(iRowNum))? iRowNum: ""));
		var objDIV		= document.getElementById(strObjName + "DIV" + (!isNaN(parseInt(iRowNum))? iRowNum: ""));
		switch (g_objEvent.keyCode) {
			case 8: 
			case 46: 
				return;
			case 38: 
				if (objSelect.selectedIndex - 1 >  -1) {
					objSelect.selectedIndex	= objSelect.selectedIndex - 1;
					objText.value	= objSelect.options[objSelect.selectedIndex].innerHTML;
				}
				break;
			case 40: 
				if (objSelect.selectedIndex + 1 < objSelect.options.length) {
					objSelect.selectedIndex	= objSelect.selectedIndex + 1;
					objText.value	= objSelect.options[objSelect.selectedIndex].innerHTML;
				}
				break;
			case 13:	 
				if (objSelect.selectedIndex > -1 && objText.value == objSelect.options[objSelect.selectedIndex].innerHTML) {
					var arrName		= (strTextObjID.replace("str", "")).split("Name");
					var strObjName	= (arrName.length > 0? arrName[0]: "");
					var iRowNum		= (arrName.length > 1? arrName[1]: "");
					jsOnBlurCombo(strObjName, iRowNum);
				}
				else if (objText.value.length < 1) {
					objSelect.selectedIndex	= 0;
					objText.value	= objSelect.options[objSelect.selectedIndex].innerHTML;
				}
				else {
					var bSearch	= false;
					for (i = 0; i < objSelect.options.length; i++) {
						var strText = objSelect.options[i].innerHTML;
						if (strText.search(objText.value) > -1) {
							objSelect.selectedIndex	= i;
							objText.value	= objSelect.options[objSelect.selectedIndex].innerHTML;
							bSearch			= true;
							break;
						}
					}
					if (!bSearch) {
						objText.value	= "";
						objSelect.selectedIndex	= -1;
						alert(STR21);
					}
				}
				break;
		}		
}
function jsOnBlurCombo(strObjName, iRowNum) {
		var objText		= document.getElementById("str" + strObjName + "Name" + (!isNaN(parseInt(iRowNum))? iRowNum: ""));
		var objSelect	= document.getElementById("i" + strObjName + "ID" + (!isNaN(parseInt(iRowNum))? iRowNum: ""));
		var objDIV		= document.getElementById(strObjName + "DIV" + (!isNaN(parseInt(iRowNum))? iRowNum: ""));
		objText.value	= objSelect.options[objSelect.selectedIndex].innerHTML;
		objDIV.style.display	= "none";
		g_strComboID			= null;
		jsSetFocusNextObj(objDIV);
}
function jsOnClickMoveBar(strObjName, iValue) {
	try {
		var arrObject		= document.getElementsByName(strObjName);
		arrObject[0].value	= iValue;

		var arrForm			= document.getElementsByTagName("FORM");
		arrForm[0].submit();
	}
	catch(e) {};
}
function jsOnClickSortCol(strCurObjName, iCurCol) {
	try {
		var arrObject		= document.getElementsByName(strCurObjName);
		arrObject[0].value	= iCurCol;

		var arrForm			= document.getElementsByTagName("FORM");
		arrForm[0].submit();
	}
	catch(e) {};
}
function jsFindFromTable(strTableID, iFindCol, strTextObjID) {
	try {
		var strFindText	= document.getElementById(strTextObjID).value;
		if (strFindText == "")
			return;
		var objTable	= document.getElementById(strTableID);
		var iSelRow		= -1;
		for (i = 0; i < objTable.rows.length; i++) {
			if (objTable.rows[i].className == "SelRow") {
				iSelRow = i;
				break;
			}
		}
		var iFindRow	= -1;
		for (i = (iSelRow > -1? iSelRow + 1: 0); i < objTable.rows.length; i++) {
			if (objTable.rows[i].cells[0].tagName == "TH")	 
				continue;
			
			var strText	= objTable.rows[i].cells[iFindCol].innerHTML;
			var arrTextObj	= objTable.rows[i].cells[iFindCol].getElementsByTagName("INPUT");
			if (arrTextObj.length > 0)
				strText	= arrTextObj[0].value;
			if (strText.search(strFindText) > -1) {
				iFindRow = i;
				break;
			}
		}
		if  (iSelRow < 1 && iFindRow == -1) {
			alert(STR21);
			return;
		}
		else if (iSelRow > 0 && iFindRow == -1) {
			for (i = 0; i < iSelRow; i++) {
				if (objTable.rows[i].cells[0].tagName == "TH") 
					continue;
				var strText	= objTable.rows[i].cells[iFindCol].innerHTML;
				var arrTextObj	= objTable.rows[i].cells[iFindCol].getElementsByTagName("INPUT");
				if (arrTextObj.length > 0)
					strText	= arrTextObj[0].value;
				if (strText.search(strFindText) > -1) {
					iFindRow = i;
					break;
				}
			}
		}
		if  (iSelRow < 1 && iFindRow == -1) {
			alert(STR21);
			return;
		}
		if (iSelRow != iFindRow && iFindRow > -1) {
			if (iSelRow > -1) {
					objTable.rows[iSelRow].className	= "ContentRow2";
			}
			objTable.rows[iFindRow].className	= "SelRow";
			var objDIV		= document.getElementById(strTableID + "DIV");
			if (objDIV == null)
				objDIV		= document.body;
			objDIV.scrollTop = objTable.rows[iFindRow].offsetTop;
		}
		else {
			alert(STR21);
			return;
		}
	}
	catch(e) {};
}
function jsShowChildDIV(strMenuID) {
	try {
		if (g_strMenuID != null) {
			document.getElementById(g_strMenuID + "_ChildDIV").style.display	= "none";
			document.getElementById(g_strMenuID + "_DIV").className				= "FuncOutDIV";
			g_strMenuID	= null;
		}
		if (g_iTimerID) {
			window.clearTimeout(g_iTimerID);
			g_iTimerID	= null;
		}
		var objParent	= document.getElementById(strMenuID + "_DIV");
		var objDIV		= document.getElementById(strMenuID + "_ChildDIV");
		var objPos		= jsGetAbsolutePosition(objParent);
		var iLevel		= jsGetSubStrCnt(strMenuID, "_");
		if (iLevel < 1) {
			objDIV.style.left	= objPos.x + "px";
			objDIV.style.top	= objPos.y - (g_bIE? -3: 2) + objParent.offsetHeight + "px";
		}
		else {
			objDIV.style.left	= objPos.x + objParent.offsetWidth + 20 + "px";
			objDIV.style.top	= objPos.y + "px";
		}
		objDIV.style.display	= "";
		objParent.className		= "FuncOverDIV";
		g_strMenuID	= strMenuID;
	}
	catch(e) {};
}
function jsRealHideChildDIV(strMenuID) {
	try {
		var objParent	= document.getElementById(strMenuID + "_DIV");
		var objDIV		= document.getElementById(strMenuID + "_ChildDIV");
		objDIV.style.display	= "none";
		objParent.className		= "FuncOutDIV";
		g_strMenuID	= null;
	}
	catch(e) {};
}
function jsHideChildDIV(strMenuID) {
	try {
		g_iTimerID	= window.setTimeout("jsRealHideChildDIV('" + strMenuID + "')", 50);
	}
	catch(e) {};
}
function jsOpenWindow(strPageID, iWidth, iHeight) {
	try {
		var iLeft	= 0;
		var iTop	= 0;
		if (g_bIE) {
			iLeft	= (window.screen.width - iWidth) / 2;
			iTop	= (window.screen.height - iHeight) / 2;
		}
		else {
			iLeft	= (window.outerWidth - iWidth) / 2;
			iTop	= (window.outerHeight - iHeight) / 2;
		}
		var strPageURL	= "index.php?strPageID=" + strPageID + "&iPageType=3";
		window.open(strPageURL, "", "location=0, menubar=0, toolbar=0, scrollbars=0, status=0, resizable=1, width=" + iWidth + ", height=" + iHeight + ", left=" + iLeft + ", top=" + iTop);
	}
	catch(e) {};
}
function jsGoToPage(strFormID, strPageID) {
	try {
		if (strFormID.length == 0) {
			top.window.location.href	= "index.php?strPageID=" + strPageID;
			return;
		}
		var objForm	= document.getElementById(strFormID);
		if (objForm == null) {
			var arrForm	= document.getElementsByTagName("FORM");
			objForm		= arrForm[0];
		}
		objForm.action	= "index.php?strPageID=" + strPageID;
		objForm.target	= "_top";
		objForm.submit();
	}
	catch(e) {
		top.window.location.href	= "index.php?strPageID=" + strPageID;
	};
}
function jsGoToActionPage(strFormID, strPageID) {
	try {
		var objForm	= document.getElementById(strFormID);
		if (objForm == null) {
			var arrForm	= document.getElementsByTagName("FORM");
			objForm		= arrForm[0];
		}
		
		objForm.action	= "index.php?strPageID=" + strPageID + "&iPageType=2";
		objForm.target	= "ActionFrame";
		objForm.submit();
	}
	catch(e) {
		top.window.location.href	= "index.php?strPageID=" + strPageID+ "&iPageType=2";
	};
}
function jsGoToInnerPage(strFormID, strPageID) {
	try {
		var objForm	= document.getElementById(strFormID);
		if (objForm == null) {
			var arrForm	= document.getElementsByTagName("FORM");
			objForm		= arrForm[0];
		}
		
		objForm.action	= "index.php?strPageID=" + strPageID + "&iPageType=4";
		objForm.target	= "InnerFrame";
		objForm.submit();
	}
	catch(e) {
		top.window.location.href	= "index.php?strPageID=" + strPageID+ "&iPageType=4";
	};
}
document.onmouseup = function(objEvent) {
		var objTarget;
		if (g_bIE)	objTarget = window.event.srcElement;
		else		objTarget = objEvent.target;
		if ( objTarget.className.substring(0, 5) != "Combo" && g_strComboID != null &&
			objTarget.tagName != "OPTION" && objTarget.tagName != "A" && objTarget.tagName != "scrollbar") {
			var objDIV	= document.getElementById(g_strComboID);
			if (objDIV.getElementsByTagName("TABLE").length > 0)
				objDIV.innerHTML		= "";
			else
				objDIV.style.display	= "none";
			g_strComboID		= null;
		}
}
document.onkeyup = function(objEvent) {
	try {
		if (g_bIE)	g_objEvent = window.event;
		else		g_objEvent = objEvent;
	}
	catch(e) {};
}
