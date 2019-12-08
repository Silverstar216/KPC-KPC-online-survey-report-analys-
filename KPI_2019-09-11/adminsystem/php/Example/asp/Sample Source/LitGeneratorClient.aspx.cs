using System;
using System.Collections.Generic;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

public partial class LitGeneratorClient : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {

    }
    protected void Button1_Click(object sender, EventArgs e)
    {
        string sFileId = TextBox1.Text;
        string sFilePath = "C:\\Workspace_aspnet\\LitGeneratorClientSample\\LITGeneratorSample1.hwp";
        string sReturnURI = "";

        //LIT-Generator 에서 제공하는 16자리 file_id 값을 유지하고자 하는 경우 false 설정
        //자체적인 file id 값을 유지하고자 하는 경우 true 설정
        LitConvert.isLocal = RadioButton2.Checked;  

        if (LitConvert.exist(sFileId, sFilePath, true))
        {
            //컨버팅된 파일이 존재하는 경우
            sReturnURI = LitConvert.convert(sFileId, sFilePath, true);
        }
        else
        {
            //컨버팅된 파일 존재하지 않는 경우. 파일 업로드를 한다.
            string sNewFileId = LitConvert.upload(sFileId, sFilePath);        
            TextBox1.Text = sNewFileId;
            sReturnURI = LitConvert.convert(sNewFileId, sFilePath, true);
        }
        Response.Write("<script type='text/javascript'>window.open('"+sReturnURI+"','_blank','scrollbars=1' , 'resizable=1' , 'width=800' , 'height=600');</script>");
        
    }



}