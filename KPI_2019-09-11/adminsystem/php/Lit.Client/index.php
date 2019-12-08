<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="robots" content="noindex, nofollow" />

<link rel="stylesheet" type="text/css" href="./css/style.css" media="all" />
<script type="text/javascript" src="./js/script.js"></script>
<title>LIT 클라이언트 관리</title>
</head>
<body onload="document.frm.user_id.focus();">
<div id="adminIndex">
	<form name="frm" action="login.php" method="post" onsubmit="return goLogin()">
	<input type="hidden" name="cmd" value="login"/>
	
	<table cellpadding="0" cellspacing="0" border="0" style="margin:0 auto;">
	<caption>관리자 로그인</caption>
		<tr>
			<td class="tr">아이디</td>
			<td align="left"><input type="text" name="user_id" tabindex="1" value="" style="width:90%"/></td>
			<td rowspan="2"><input type="submit" value="LOGIN" class="logInbtn" onclick="goLogin();"/></td>
		</tr>
		<tr>
			<td class="tr">비밀번호</td>
			<td align="left"><input type="password" name="user_pass" tabindex="2" value="" style="width:90%"/></td>
		</tr>
	</table>
	</form>
</div>

</body>
</html>
