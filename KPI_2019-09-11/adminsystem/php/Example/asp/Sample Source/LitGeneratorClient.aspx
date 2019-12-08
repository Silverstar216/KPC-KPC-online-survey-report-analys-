<%@ Page Language="C#" AutoEventWireup="true" CodeFile="LitGeneratorClient.aspx.cs" Inherits="LitGeneratorClient" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<html> 
<head>
	<title></title>
	<meta http-equiv=Content-Type content=text/html; charset=EUC-KR>
	<script language='JavaScript'>

	</script>
</head>
<body topmargin='0'  leftmargin='0' marginwidth='0' marginheight='0'  bgcolor=white >

	<table border=0 cellspacing=0 cellpadding=0 width=95% bgcolor=#E2E2E2>
		<tr height=20 bgcolor=#ffffff>
			<td  colspan=2></td>
		</tr>
		<tr>
			<td colspan=2 height=30 bgcolor=#ffffff>&nbsp;&nbsp;
				<span style="font-family:Arial;font-size:8pt;font-weight:bold;">
				<span style=font-size:15px;letter-spacing:-1px;>이 페이지는 LIT-Generator 의 클라이언트 샘플 화면 입니다.<br />&nbsp;<br />
                &nbsp;&nbsp;&nbsp;&nbsp;아래와 같은 게시판에서 문서 파일이 존재하는 경우를 가상한 것입니다.<br />
                &nbsp;&nbsp;&nbsp;&nbsp;[LIT-Generator 사용] 버튼을 누르시면 LIT-Generator 서버로 파일을 전송하는 테스트를 시작합니다.
                </span></span>
			</td>
		</tr>
        <tr>
		    <td colspan=2 bgcolor=#9F9F9F></td>
	    </tr>
<form id="form1" runat="server">
        <tr>
		    <td colspan=2 height=50 bgcolor=#ffffff>
                <asp:RadioButton ID="RadioButton1" runat="server" Checked="True" 
                    GroupName="isLocal" Text="표준 16자리 파일 아이디 사용" />
                <asp:RadioButton ID="RadioButton2" runat="server" GroupName="isLocal" 
                    Text="자체 파일 아이디 사용" />
            </td>
	    </tr>
		<tr height=20 bgcolor=#ffffff>
			<td  style=font-family:Arial;font-size:8pt;font-weight:bold; colspan=2>&nbsp;&nbsp;Home >> 게시판 >> 공지사항</td>
		</tr>
		<tr height=1>
			<td colspan=2 bgcolor=#9F9F9F></td>
		</tr>
		<tr height=23>
			<td align=right width=90 bgcolor=#9F9F9F style=color:#ffffff><b>제목</b></td>
			<td align=left width=500>
                <table border=0 cellpadding=0 cellspacing=0>
                    <tr>
                        <td></td>
				    </tr>
				    <tr>
					    <td>&nbsp;&nbsp;</td>
					    <td><b> <span>공지사항 입니다.</span>&nbsp;</td>
				    </tr>
			    </table>
		    </td>
	    </tr>
        <tr>
		    <td bgcolor=#ffffff height=1 colspan=2></td>
        </tr>
		<tr height=23>
			<td align=right width=90 bgcolor=#9F9F9F style=color:#ffffff><b>파일구분 ID</b></td>
			<td align=left>
                <table border=0 cellpadding=0 cellspacing=0>
                    <tr><td></td>
				    </tr>
				    <tr>
					    <td>&nbsp;&nbsp;</td>
					    <td><b> 
                            <asp:TextBox ID="TextBox1" runat="server" value="0120101108222707" Width="196px"></asp:TextBox>
                        </td>
				    </tr>
			    </table>
		    </td>
	    </tr>
	    <tr>
		    <td bgcolor=#ffffff height=1 colspan=2></td>
        </tr>
	    <tr height=23>
		    <td align=right width=90 bgcolor=#9F9F9F style=color:#ffffff><b>첨부파일 #1</b></td>
		    <td >&nbsp;&nbsp; <font class=thm8><a href='LITGeneratorSample1.hwp'>LITGeneratorSample1.hwp</a> &nbsp; 
                <font style=font-size:7pt;>        
                <asp:Button ID="Button1" runat="server" Height="25px" onclick="Button1_Click" Text="LIT-Generator 사용" Width="180px" />
            </font></font>
		    </td>
	    </tr>
</form>
	    <tr>
		    <td bgcolor=#ffffff height=1 colspan=2></td>
	    </tr>
	    <tr height=23>
		    <td align=right width=90 bgcolor=#9F9F9F style='word-break:break-all;color:#ffffff'><b>등록일자</b></td>
		    <td><br>&nbsp;&nbsp; <b>2010년 1월 1일</b></td>
	    </tr>
	    <tr>
		    <td bgcolor=#ffffff height=1 colspan=2></td>
		    </tr>
	    <tr>
		    <td colspan=2 bgcolor=#9F9F9F></td>
	    </tr>
    </table>
    <table border=0 cellspacing=0 cellpadding=0 width=95%>
	    <tr>
		    <td style='word-break:break-all;padding:10px;' bgcolor=#ffffff height=100 valign=top>
			    <span style=line-height:160%>
				    <table border=0 cellspacing=0 cellpadding=0 width=100% style="table-layout:fixed;">
				    <col width=100%></col>
				    <tr><td valign=top>  공지사항은 첨부된 문서를 확인하십시요.
				    </table>     
				    <br>
			    </span>
		    </td>
	    </tr>
	    <tr>
		    <td bgcolor=#9F9F9F></td>
	    </tr>
    </table>

    <table border=0 cellspacing=0 cellpadding=0 width=95%>
	    <tr height=10>
		    <td>&nbsp;</td>
	    </tr>
	    <tr align="center">
		    <td>
		    <input type="button" value="확인" width="40" onclick="">
		    </td>
	    </tr>
    </table>

</body>
</html>

