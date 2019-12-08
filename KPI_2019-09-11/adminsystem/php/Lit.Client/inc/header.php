<? 
session_start();

if ($_SESSION['valid_user']!="lemontimeit" && $_SESSION['valid_user']=="") {
	header( 'Location: ../../index.php' ) ;
}
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>LIT 클라이언트 관리</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="robots" content="noindex, nofollow" />

<link rel="stylesheet" type="text/css" href="/Php/lit.client/css/style.css" media="all" />

<script type="text/javascript" src="/Php/lit.client/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/Php/lit.client/js/script.js"></script>

</head>
<body>

<div id="wrap"><!-- WRAP -->

	<div id="fixTop"><!-- fixTop -->
	
		<div id="TopLogo"> 
			<ul>
				<li id="logo">LemonTimeIT Inc.</li>
				<li id="login">
					<img src="/Php/Lit.Client/images/login_icon.gif" alt="" style="vertical-align:top;"/>&nbsp;<b><?=$_SESSION['valid_user'] ?></b>님이 로그인하셨습니다.
				</li>
			</ul>
		</div>

		<div id="TopMenu">
			<ul>
				<li><a href="../../page/main/main.php"><span>고객관리</span></a></li>
				<li><a href="../../logout.php"><span>로그아웃</span></a></li>
			</ul>
		</div>

		<div id="SubMenu">
			<ul>
				<li><a href="../../page/main/main.php">고객리스트</a></li>
				<li><a href="../../page/main/edit.php">고객등록</a></li>
				<li class="navi">Home > 고객관리</li>	
			</ul>
		</div>
		
	</div><!-- fixTop -->
	
	<div id="contents">