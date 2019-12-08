<%@ page language="java" contentType="text/html; charset=EUC-KR"
    pageEncoding="EUC-KR"%>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<%@page import="com.lit.litgenerator.*"%>

<%@page import="java.io.File"%>
<%@page import="java.io.FileNotFoundException"%><html>
<head>
<meta http-equiv="Content-Type" content="no-cache; text/html; charset=EUC-KR">
<title>Convert Client Sample</title>

<script type="text/javascript">
function submitfrm() {
	document.frm.submit();
}
</script> 

<%
	//============================================================================================================
	//이 영역에 DB에 저장되어 있는 파일 정보를 가지고 오는 루틴 추가.
	//아래 두개의 변수에 Converting_FileID 값과 파일 full path 를 할당한다. (Converting_FileID 값이 없는 경우 빈 문자열 할당)
	String sOldCachedFileId = "";	
	String sFileFullPath = "";
	//============================================================================================================
	//아래 코드는 DB에서 값을 가지고 온 경우를 가상하여 임의의 값을 세팅.
	String sType = request.getParameter("type");
	if("old".equals(sType)) {	
		sOldCachedFileId = "1234444";
		sFileFullPath = "C:\\Workspace_al\\LitGeneratorClientSample\\WebContent\\upload\\convertsample1.hwp";
	} else if("new".equals(sType)) {
		sOldCachedFileId = "";
		sFileFullPath = "C:\\Workspace_al\\LitGeneratorClientSample\\WebContent\\upload\\convertsample1.hwp";
			
		File file = new File(sFileFullPath);
		if(!file.isFile()) {
			sType = "nothing";
		}

	}
	//============================================================================================================
	
	
	if(sType!=null && ("old".equals(sType) || "new".equals(sType))) {
		
	String sReturnURI = "";
	try {
		//LIT-Generator 에서 제공하는 16자리 file_id 값을 유지하고자 하는 경우 false 설정
		//자체적인 file id 값을 유지하고자 하는 경우 true 설정
		LitConvert.isLocal = true;
		if(LitConvert.exist(sOldCachedFileId, sFileFullPath, true) == false) {
			//유효한 Converting_FileID 가 존재하지 않음(캐쉬 파일 없음) => 업로드 => 컨버트 실행
			System.out.println("캐쉬 파일 없음.");			
			String sNewCachedFileID = LitConvert.upload(sOldCachedFileId, sFileFullPath);
			sReturnURI = LitConvert.convert(sNewCachedFileID, sFileFullPath, true);
			//============================================================================================================
			//필요한 경우 서버에서 캐쉬된 파일의 Converting_FileID값을 저장하는 루틴 추가. 
			//...
			//============================================================================================================
			
		} else {
			//유효한 Converting_FileID 가 존재 함(캐쉬 파일 있음) => 컨버트만 실행
			System.out.println("캐쉬 파일 있음.");
			sReturnURI = LitConvert.convert(sOldCachedFileId, true);
		}
	} catch (Exception e) {
		//============================================================================================================
		//이 영역에 에러 처리 루틴 추가 
		//(위 사용하는 라이브러리 클래스들에서는 로그만 작성하고 에러 핸들러를 모두 throws 함.)
		//============================================================================================================
	}	
	
%>

<%	if(sReturnURI!=null && !"".equals(sReturnURI)){%>
	<script language=javascript>window.open('<%=sReturnURI%>','');</script>
<%	} else {%>
	<script language=javascript>alert('Warning : Server is not running.');history.go(-1);</script>
<%	}%>

<%} else if("nothing".equals(sType)) {%>
	<script language=javascript>alert('Warning : file not found. \r\n 테스트를 위한 파일이 존재하지 않습니다. 확인 후 다시 시도하여 주십시요.');history.go(-1);</script>
<%	}%>
</head>
<body>
	<table width="100%" border=0 cellspacing=0 cellpadding=5 align=center>
	<form name="frm" action="./LitGeneratorClient.jsp">
		<tr height="100"><td></td></tr>
		<tr height="30" align="center">
			<td><b>설명:</b> 아래의 옵션 중 하나를 선택한 후 [start] 버튼을 누르면 테스트를 진행합니다.</td>
		</tr>
		<tr align="center">
			<td>
			<input type="radio" name="type" value="old" />캐시 파일 : 기존에 컨버팅 작업을 하여 Converting_FileID 값이 존재 하는 경우를 가상한다.&nbsp;<br />
			<input type="radio" name="type" value="new" />새로운 파일 : 데이터베이스에 Converting_FileID 값이 없는 컨버팅 대상 파일을 가상한다.&nbsp;&nbsp;&nbsp;<br />
			</td>
		</tr>
		<tr align="center">
			<td>
			<input type="button" value="Start" onclick="javascript:submitfrm();">
			</td>
		</tr>
	</form>
	</table>
</body>
</html>







