/**
 * 레몬타임 컨버팅 메소드 공통 클래스
 * @author Administrator
 * @date 2010.10
 */

package com.lit.litgenerator;

public class LitConvert {
	
	private static final String URI_EXIST = "/php/litservice/exist.php";
    private static final String URI_UPLOAD = "/php/litservice/upload.php";
    private static final String URI_CONVERT = "/php/litservice/convert.php";
    //private static final String ALLOWED_EXTENSIONS[] = {".hwp", ".doc", ".docx", ".ppt", ".pptx", ".xls", ".xlsx", ".pdf"};
    //private static final String ALLOWED_EXTENSIONS[] = {".hwp", ".doc", ".docx", ".ppt", ".pptx", ".xls", ".xlsx", ".pdf", ".jpg", ".png", ".gif", ".bmp", ".tif", ".tiff"};
    private static final String ALLOWED_EXTENSIONS[] = {".hwp", ".doc", ".docx", ".ppt", ".pptx", ".xls", ".xlsx", ".pdf", ".png", ".jpg", ".gif", ".bmp", ".tif", ".tiff"};
    public static boolean isLocal = false;
    
    /** 
	 * 캐쉬된 파일이 있는지 체크한다.
	 * @param pId		: chched file id
	 * @param pIsHtml	: is html (default true) 
	 * @return			: boolean value
	 * @throws Exception
	 */
	public static boolean exist(String pFileId, boolean pIsHtml) throws Exception {
		return exist(pFileId, "", pIsHtml); 
	}
	
    public static boolean exist(String pFileId, String pFileFullPath, boolean pIsHtml) throws Exception {
		boolean bRet = false;
		try { 
			if(isLocal) {
				pFileId = getExtEnum(pFileFullPath) + pFileId; 
			} 
			String sParam = "mode="+(pIsHtml?"html":"epub")+"&job_id="+pFileId;
			String sVal = LitCommunicator.postHttp(URI_EXIST, sParam); 
			if(sVal.length()>0) {
		    	LitDecode ld = new LitDecode(sVal); 
		    	if(!"no".equals(ld.getVal("exist"))) 
		    		bRet = true;
			}
		} catch (Exception e) {
			System.out.println(e.getLocalizedMessage());
			e.printStackTrace();
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
	public static String upload(String pFileFullPath) throws Exception {
		return upload("", pFileFullPath);
	}
    public static String upload(String pFileId, String pFileFullPath) throws Exception {
    	String sRet = "";
		try { 
			if(isLocal) {
				pFileId = getExtEnum(pFileFullPath) + pFileId; 
			}			
			String sVal = LitCommunicator.postMultipart(URI_UPLOAD, pFileFullPath, pFileId);
			if(sVal.length()>0) {
		    	LitDecode ld = new LitDecode(sVal);
		    	if(!"".equals(ld.getVal("job_id"))) { 
					if(isLocal) {
						sRet = ld.getVal("job_id");
						sRet = sRet.substring(2);
					} else {
						sRet = ld.getVal("job_id");						
					}
		    	}
			}
		} catch (Exception e) {
			System.out.println(e.getLocalizedMessage());
			e.printStackTrace();
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
	public static String convert(String pFileId, boolean pIsHtml) throws Exception {
		return convert(pFileId, "", pIsHtml);
	}
	public static String convert(String pFileId, String pFileFullPath, boolean pIsHtml) throws Exception {
		String sRet = "";
		
		try { 			
			if(isLocal) {
				pFileId = getExtEnum(pFileFullPath) + pFileId;
			}			
			String sParam = "mode="+(pIsHtml?"html":"epub")+"&job_id="+pFileId+"&action=convert&sync=on";
			String sVal = LitCommunicator.postHttp(URI_CONVERT, sParam);
			if(sVal.length()>0) {
		    	LitDecode ld = new LitDecode(sVal);
		    	if(!"".equals(ld.getVal("url"))) sRet = ld.getVal("url");
			}
		} catch (Exception e) {
			System.out.println(e.getLocalizedMessage());
			e.printStackTrace();
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
	public static String convertEx(String pFileId, boolean pIsHtml) throws Exception {
		return convert(pFileId, "", pIsHtml);
	}
	public static String convertEx(String pFileId, String pFileFullPath, boolean pIsHtml) throws Exception {
		String sRet = "";
		try { 			
			if(isLocal) {
				pFileId = getExtEnum(pFileFullPath) + pFileId;
			}			
			String sParam = "mode="+(pIsHtml?"html":"epub")+"&job_id="+pFileId+"&action=convert&sync=on&tagstrip=on";
			String sVal = LitCommunicator.postHttp(URI_CONVERT, sParam);
			if(sVal.length()>0) {
		    	LitDecode ld = new LitDecode(sVal);
		    	if(!"".equals(ld.getVal("url"))) sRet = ld.getVal("url");
			}
		} catch (Exception e) {
			System.out.println(e.getLocalizedMessage());
			e.printStackTrace();
			throw e;
		}
		return sRet;
	}
	
    /**
	 * 파일구분자 키값을 생성한다.
	 * @param pFileName	: chched file id
	 * 
	 * @return			: String value
	 * @throws Exception
	 */	
	private static String getExtEnum(String pFileName) {
		String sRet = "";
		
		pFileName = pFileName.toLowerCase();
		
		for(int i=0; i<ALLOWED_EXTENSIONS.length; i++) {
			if(ALLOWED_EXTENSIONS[i].equalsIgnoreCase(pFileName.substring(pFileName.lastIndexOf(".")))) {
				sRet = String.format("%02d", i+1); 
				break;
			}
		}
		return sRet;
	}
 
}
