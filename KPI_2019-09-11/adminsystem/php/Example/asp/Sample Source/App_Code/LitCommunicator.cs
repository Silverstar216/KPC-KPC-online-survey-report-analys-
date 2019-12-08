using System;
using System.Collections.Generic;
using System.Web;
using System.Net;
using System.IO;

/// <summary>
/// Class1의 요약 설명입니다.
/// </summary>
public class LitCommunicator
{
    private static string HOST = "http://web.lemontimeit.com";


    public static string postHttp(string pUrl, string pParam) 
    {
        string sRet = "";
        try
        {
            pUrl = HOST + pUrl + "?" + pParam;
            Uri targetUri = new Uri(pUrl);
            HttpWebRequest http = (HttpWebRequest)HttpWebRequest.Create(targetUri);
            if ((http.GetResponse().ContentLength > 0))
            {
                System.IO.StreamReader sr = new System.IO.StreamReader(http.GetResponse().GetResponseStream());
                sRet = sr.ReadToEnd();
                if (sr != null) sr.Close();
            }
            http = null;
            targetUri = null;
        }
        catch (System.Net.WebException ex)
        {
            throw ex;
        }
        return sRet;
    }




    public static string postMultipart(string pUrl, string pFileFullPath, string pId)
    {
        string sRet = "";

        pUrl = HOST + pUrl;
        Uri targetUri = new Uri(pUrl);
        HttpWebRequest http = (HttpWebRequest)WebRequest.Create(targetUri);

        string boundary = "---------------------------7d115d2a20060c";
        http.ContentType = "multipart/form-data; boundary=" + boundary;
        http.Method = "POST";
        http.KeepAlive = true;
        http.Credentials = System.Net.CredentialCache.DefaultCredentials;
        Stream memStream = new System.IO.MemoryStream();
        byte[] boundarybytes = System.Text.Encoding.ASCII.GetBytes("\r\n--" + boundary + "\r\n");
        
        string formdataTemplate = "\r\n--" + boundary + "\r\nContent-Disposition: form-data; name=\"{0}\";\r\n\r\n{1}";
        string formitem = string.Format(formdataTemplate, "userid", pId);
        byte[] formitembytes = System.Text.Encoding.UTF8.GetBytes(formitem);
        memStream.Write(formitembytes, 0, formitembytes.Length);
        memStream.Write(boundarybytes, 0, boundarybytes.Length);
        string headerTemplate = "Content-Disposition: form-data; name=\"{0}\"; filename=\"{1}\"\r\nContent-Type: application/octet-stream\r\n\r\n";
        string header = string.Format(headerTemplate, "userfile", pFileFullPath);
        byte[] headerbytes = System.Text.Encoding.UTF8.GetBytes(header);
        memStream.Write(headerbytes, 0, headerbytes.Length);

        FileStream fileStream = new FileStream(pFileFullPath, FileMode.Open, FileAccess.Read);
        byte[] buffer = new byte[1024];

        int bytesRead = 0;
        while ((bytesRead = fileStream.Read(buffer, 0, buffer.Length)) != 0)
        {
            memStream.Write(buffer, 0, bytesRead);
        }
        memStream.Write(boundarybytes, 0, boundarybytes.Length);
        fileStream.Close();

        http.ContentLength = memStream.Length;
        Stream requestStream = http.GetRequestStream();

        memStream.Position = 0;
        byte[] tempBuffer = new byte[memStream.Length];
        memStream.Read(tempBuffer, 0, tempBuffer.Length);
        memStream.Close();
        requestStream.Write(tempBuffer, 0, tempBuffer.Length);
        requestStream.Close();


        WebResponse wr = http.GetResponse();
        StreamReader r = new StreamReader(wr.GetResponseStream());
        sRet = r.ReadToEnd();
        wr.Close();
        http = null;
        wr = null;

        return sRet;
    }

}