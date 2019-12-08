using System;
using System.Collections.Generic;
using System.Web;

/// <summary>
/// LitConvert의 요약 설명입니다.
/// </summary>
public class LitConvert
{
	private static string URI_EXIST = "/php/litservice/exist.php";
    private static string URI_UPLOAD = "/php/litservice/upload.php";
    private static string URI_CONVERT = "/php/litservice/convert.php";

    private static string[] ALLOWED_EXTENSIONS = new string[]{".hwp", ".doc", ".docx", ".ppt", ".pptx", ".xls", ".xlsx", ".pdf", ".png", ".jpg", ".gif", ".bmp", ".tif", ".tiff"};
    public static bool isLocal = false;


    /**
	 * 캐쉬된 파일이 있는지 체크한다.
	 * @param pId		: chched file id
	 * @param pIsHtml	: is html (default true)
	 * @return			: boolean value
	 * @throws Exception
	 */
    public static bool exist(string pId, bool pIsHtml)
    {
        return exist(pId, "", pIsHtml);
    }

	public static bool exist(string pId, string pFileFullPath, bool pIsHtml) 
    {
		bool bRet = false;
		try {
            if (isLocal)
            {
                pId = getExtEnum(pFileFullPath) + pId;
            }
            string sParam = "mode=" + (pIsHtml ? "html" : "epub") + "&job_id=" + pId;
			string sVal = LitCommunicator.postHttp(URI_EXIST, sParam);
			if(sVal.Length > 0) {
		    	LitDecode ld = new LitDecode(sVal);
		    	if(!((string)"no").Equals(ld.getVal("exist"))) 
		    		bRet = true;
			}
		} catch (Exception e) {
			throw e;
		}
		return bRet;
	}
	

	/**
	 *  파일을 업로드 한다.
	 * @param pPath		: chched file id
	 * @param pFileName	: is html (default true)
	 * @return			: boolean value (quest) 
	 * @throws Exception
	 */	
	public static string upload(string pFileFullPath) 
    {
		return upload("", pFileFullPath);
	}
    public static string upload(string pId, string pFileFullPath) 
    {
    	string sRet = "";
		try {
            if (isLocal)
            {
                pId = getExtEnum(pFileFullPath) + pId;
            }
            else
            {
                pId = "";
            }
            string sVal = LitCommunicator.postMultipart(URI_UPLOAD, pFileFullPath, pId);
			if(sVal.Length>0) {
		    	LitDecode ld = new LitDecode(sVal);
		    	if(!((string)"").Equals(ld.getVal("job_id"))) {
                    if (isLocal)
                    {
                        sRet = ld.getVal("job_id");
                        sRet = sRet.Substring(2);
                    }
                    else
                    {
                        sRet = ld.getVal("job_id");
                    }
		    	}
			}
		} catch (Exception e) {
            
			throw e;
		}
		return sRet;
    } 
 
    /**
	 * 파일을 로드한다.
	 * @param pId		: chched file id
	 * @param pIsHtml	: is html (default true)
	 * @return			: boolean value
	 * @throws Exception
	 */
	public static string convert(string pFileId, bool pIsHtml) 
    {
		return convert(pFileId, "", pIsHtml);
	}
	public static string convert(string pId, string pFileFullPath, bool pIsHtml) 
    {
		string sRet = "";
		try {
            if (isLocal)
            {
                pId = getExtEnum(pFileFullPath) + pId;
            }
            string sParam = "mode=" + (pIsHtml ? "html" : "epub") + "&job_id=" + pId + "&action=convert&sync=on";
			string sVal = LitCommunicator.postHttp(URI_CONVERT, sParam);
			if(sVal.Length>0) {
		    	LitDecode ld = new LitDecode(sVal);
		    	if(!((string)"").Equals(ld.getVal("url"))) 
                    sRet = ld.getVal("url");
			}
		} catch (Exception e) {
			throw e;
		}
		return sRet;
	}

// by DNK! 2012.06.04
    /**
	 * 파일을 로드한다.
	 * @param pId		: chched file id
	 * @param pIsHtml	: is html (default true)
	 * @return			: boolean value
	 * @throws Exception
	 */
	public static string convertEx(string pFileId, bool pIsHtml) 
    {
		return convert(pFileId, "", pIsHtml);
	}
	public static string convertEx(string pId, string pFileFullPath, bool pIsHtml) 
    {
		string sRet = "";
		try {
            if (isLocal)
            {
                pId = getExtEnum(pFileFullPath) + pId;
            }
            string sParam = "mode=" + (pIsHtml ? "html" : "epub") + "&job_id=" + pId + "&action=convert&sync=on&tagstrip=on";
			string sVal = LitCommunicator.postHttp(URI_CONVERT, sParam);
			if(sVal.Length>0) {
		    	LitDecode ld = new LitDecode(sVal);
		    	if(!((string)"").Equals(ld.getVal("url"))) 
                    sRet = ld.getVal("url");
			}
		} catch (Exception e) {
			throw e;
		}
		return sRet;
	}


    private static string getExtEnum(string pFileName)
    {
        string sRet = "";
        for (int i = 0; i < ALLOWED_EXTENSIONS.Length; i++)
        {
            if (ALLOWED_EXTENSIONS[i].Equals(pFileName.Substring(pFileName.LastIndexOf("."))))
            {
                sRet = Convert.ToString(string.Format("{0:00}", i + 1));
                break;
            }
        }

        return sRet;
    }

}