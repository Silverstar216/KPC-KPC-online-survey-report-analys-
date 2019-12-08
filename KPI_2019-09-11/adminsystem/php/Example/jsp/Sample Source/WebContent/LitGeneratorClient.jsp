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
	//�� ������ DB�� ����Ǿ� �ִ� ���� ������ ������ ���� ��ƾ �߰�.
	//�Ʒ� �ΰ��� ������ Converting_FileID ���� ���� full path �� �Ҵ��Ѵ�. (Converting_FileID ���� ���� ��� �� ���ڿ� �Ҵ�)
	String sOldCachedFileId = "";	
	String sFileFullPath = "";
	//============================================================================================================
	//�Ʒ� �ڵ�� DB���� ���� ������ �� ��츦 �����Ͽ� ������ ���� ����.
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
		//LIT-Generator ���� �����ϴ� 16�ڸ� file_id ���� �����ϰ��� �ϴ� ��� false ����
		//��ü���� file id ���� �����ϰ��� �ϴ� ��� true ����
		LitConvert.isLocal = true;
		if(LitConvert.exist(sOldCachedFileId, sFileFullPath, true) == false) {
			//��ȿ�� Converting_FileID �� �������� ����(ĳ�� ���� ����) => ���ε� => ����Ʈ ����
			System.out.println("ĳ�� ���� ����.");			
			String sNewCachedFileID = LitConvert.upload(sOldCachedFileId, sFileFullPath);
			sReturnURI = LitConvert.convert(sNewCachedFileID, sFileFullPath, true);
			//============================================================================================================
			//�ʿ��� ��� �������� ĳ���� ������ Converting_FileID���� �����ϴ� ��ƾ �߰�. 
			//...
			//============================================================================================================
			
		} else {
			//��ȿ�� Converting_FileID �� ���� ��(ĳ�� ���� ����) => ����Ʈ�� ����
			System.out.println("ĳ�� ���� ����.");
			sReturnURI = LitConvert.convert(sOldCachedFileId, true);
		}
	} catch (Exception e) {
		//============================================================================================================
		//�� ������ ���� ó�� ��ƾ �߰� 
		//(�� ����ϴ� ���̺귯�� Ŭ�����鿡���� �α׸� �ۼ��ϰ� ���� �ڵ鷯�� ��� throws ��.)
		//============================================================================================================
	}	
	
%>

<%	if(sReturnURI!=null && !"".equals(sReturnURI)){%>
	<script language=javascript>window.open('<%=sReturnURI%>','');</script>
<%	} else {%>
	<script language=javascript>alert('Warning : Server is not running.');history.go(-1);</script>
<%	}%>

<%} else if("nothing".equals(sType)) {%>
	<script language=javascript>alert('Warning : file not found. \r\n �׽�Ʈ�� ���� ������ �������� �ʽ��ϴ�. Ȯ�� �� �ٽ� �õ��Ͽ� �ֽʽÿ�.');history.go(-1);</script>
<%	}%>
</head>
<body>
	<table width="100%" border=0 cellspacing=0 cellpadding=5 align=center>
	<form name="frm" action="./LitGeneratorClient.jsp">
		<tr height="100"><td></td></tr>
		<tr height="30" align="center">
			<td><b>����:</b> �Ʒ��� �ɼ� �� �ϳ��� ������ �� [start] ��ư�� ������ �׽�Ʈ�� �����մϴ�.</td>
		</tr>
		<tr align="center">
			<td>
			<input type="radio" name="type" value="old" />ĳ�� ���� : ������ ������ �۾��� �Ͽ� Converting_FileID ���� ���� �ϴ� ��츦 �����Ѵ�.&nbsp;<br />
			<input type="radio" name="type" value="new" />���ο� ���� : �����ͺ��̽��� Converting_FileID ���� ���� ������ ��� ������ �����Ѵ�.&nbsp;&nbsp;&nbsp;<br />
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







