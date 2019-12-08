/**
 * 레몬타임 컨버팅 메소드 공통 클래스
 * @author Administrator
 * @date 2010.10
 */
package com.lit.litgenerator;

import java.io.BufferedInputStream;
import java.io.BufferedOutputStream;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

public class LitCommunicator {

	private static final String PROTOCOL_HTTP = "http";
	private static final String HOST = "web.lemontimeit.com";
	private static final String CRLF = "\r\n";
	/**
	 * urlconnection 을 사용하여 서버와 통신을 한다.
	 * @param pUri		: host 를 제외한 URI
	 * @param pQuery	: parameters
	 * @return			: 서버에서 수신되는 리턴 값
	 * @throws Exception
	 */
	public static String postHttp(String pUri, String pParam) throws Exception {
		String sRet = "";
		HttpURLConnection httpConn = null;
		BufferedReader in = null;
		try { 
		    URL httpUrl = new URL(PROTOCOL_HTTP, HOST, pUri+"?"+pParam);
		    httpConn = (HttpURLConnection)httpUrl.openConnection(); 
		    httpConn.connect();
		    InputStream is = httpConn.getInputStream();
		     
		    in = new BufferedReader(new InputStreamReader(is));
		    StringBuffer sbBuff = new StringBuffer();
		    String line = null; 
		    while ((line = in.readLine()) != null) { 
		    	sbBuff.append(line);
		    }
		    if(sbBuff!=null && sbBuff.length()!=0) {
		    	sRet = sbBuff.toString();
		    }
		} catch (Exception e) {
			System.out.println(e.getLocalizedMessage());
			e.printStackTrace();
			throw e;
		} finally {
			if (in != null) try {in.close();} catch (Exception e){}
		    if (httpConn != null) try {httpConn.disconnect();} catch (Exception e){} 
	    } 
		return sRet;
	}
	
	
    /**
     * multi-part form file upload
     * @param pUri
     * @param pFileFullPath
     * @return
     * @throws Exception
     * 
     * Error Code Value Des.
     * ERRCODE_INVALID_FILETYPE : 10
	 * ERRCODE_UPLOAD_FAIL : 11
	 * ERRCODE_INVALID_JOB_ID : 12
	 * ERRCODE_FAIL_TO_MOVE : 13
	 * ERRCODE_FAIL_TO_READ_FILE : 14
	 * ERRCODE_FAIL_TO_WRITE_FILE : 15
	 * ERRCODE_WAIT_TIMEOUT : 16
	 * 
     * Error Code Detail Value Des. (For Debug Mode Only)
     * UPLOAD_ERR_OK 값: 0; 오류 없이 파일 업로드가 성공했습니다.
	 * UPLOAD_ERR_INI_SIZE 값: 1; 업로드한 파일이 php.ini upload_max_filesize 지시어보다 큽니다.
	 * UPLOAD_ERR_FORM_SIZE 값: 2; 업로드한 파일이 HTML 폼에서 지정한 MAX_FILE_SIZE 지시어보다 큽니다.
	 * UPLOAD_ERR_PARTIAL 값: 3; 파일이 일부분만 전송되었습니다.
	 * UPLOAD_ERR_NO_FILE 값: 4; 파일이 전송되지 않았습니다.
     */
    public static String postMultipart(String pUri, String pFileFullPath, String pFileId) throws IOException {
    	String sRet = "";
    	URL httpUrl = new URL(PROTOCOL_HTTP, HOST, pUri);
        HttpURLConnection httpConn = (HttpURLConnection)httpUrl.openConnection();
        
        // Delimeter 생성
        String delimeter = makeDelimeter();        
        byte[] newLineBytes = CRLF.getBytes();
        byte[] delimeterBytes = delimeter.getBytes();
        byte[] dispositionBytes = "Content-Disposition: form-data; name=".getBytes();
        byte[] quotationBytes = "\"".getBytes();
        byte[] contentTypeBytes = "Content-Type: application/octet-stream".getBytes();
        byte[] fileNameBytes = "; filename=".getBytes();
        byte[] twoDashBytes = "--".getBytes();
        
        httpConn.setRequestMethod("POST");
        httpConn.setRequestProperty("Content-Type", "multipart/form-data; boundary="+delimeter);
        httpConn.setDoInput(true);
        httpConn.setDoOutput(true);
        httpConn.setUseCaches(false);
        
        BufferedOutputStream out = null;
        try {
            out = new BufferedOutputStream(httpConn.getOutputStream());

            // Delimeter 전송
            out.write(twoDashBytes);
            out.write(delimeterBytes);
            out.write(newLineBytes);
            out.write(dispositionBytes);
            out.write(quotationBytes);
            out.write(("userid").getBytes());
            out.write(quotationBytes);
            out.write(";".getBytes());
            out.write(newLineBytes);
            out.write(newLineBytes);
            out.write(pFileId.getBytes());
            out.write(newLineBytes);
            out.write(twoDashBytes);
            out.write(delimeterBytes);
            out.write(newLineBytes);
            // 파라미터 이름 출력
            out.write(dispositionBytes);
            out.write(quotationBytes);
            out.write( ("userfile").getBytes() );
            out.write(quotationBytes);

            File f = new File(pFileFullPath);
            out.write(fileNameBytes);
            out.write(quotationBytes);
            out.write(f.getAbsolutePath().getBytes() );
            out.write(quotationBytes);
            out.write(newLineBytes);
            out.write(contentTypeBytes);
            out.write(newLineBytes);
            out.write(newLineBytes);
            // file에 있는 내용을 전송한다.
            BufferedInputStream is = null;
            try {
                is = new BufferedInputStream(new FileInputStream(f));
                byte[] fileBuffer = new byte[1024 * 8]; // 8k
                int len = -1;
                while ( (len = is.read(fileBuffer)) != -1) {
                    out.write(fileBuffer, 0, len);
                }
            } finally {
                if (is != null) try { is.close(); } catch(IOException ex) {}
            }

            out.write(newLineBytes);
            // 마지막 Delimeter 전송
            out.write(twoDashBytes);
            out.write(delimeterBytes);
            out.write(twoDashBytes);
            out.write(newLineBytes);
            out.flush();
        } finally {
            if (out != null) out.close();
        }
		        
        // read & parse the response 
		InputStream in = httpConn.getInputStream(); 
        StringBuilder sbBuff = new StringBuilder(); 
        byte[] respBuffer = new byte[4096]; 
        while (in.read(respBuffer) >= 0) { 
        	sbBuff.append(new String(respBuffer).trim()); 
        } 
        in.close();
	    if(sbBuff!=null && sbBuff.length()!=0) {
	    	sRet = sbBuff.toString();
	    }
	    
	    return sRet;
    }    	
    private static String makeDelimeter() {
        return "---------------------------7d115d2a20060c";
    }        
        
}
