using System;
using System.Collections.Generic;
using System.Web;
using System.Collections;
/// <summary>
/// LitDecode의 요약 설명입니다.
/// </summary>
public class LitDecode
{
    private Hashtable htVal = null;

    public LitDecode(string pDecodeStr) {
		htVal = new Hashtable();
        pDecodeStr = pDecodeStr.Substring(pDecodeStr.IndexOf("{") + 1, pDecodeStr.LastIndexOf("}") - 1);
		pDecodeStr = pDecodeStr.Replace("\"", "");
		
		string[] aVal1 = pDecodeStr.Split(new Char [] {','});
		string sKey = "";
        string sVal = "";
		for(int i=0; i<aVal1.Length; i++) {
			//aVal1[i] = aVal1[i].replaceAll("\"", "");
			sKey = aVal1[i].Substring(0, aVal1[i].IndexOf(":"));
			sVal = aVal1[i].Substring(aVal1[i].IndexOf(":")+1);
			try {
			sVal = sVal.Replace("\\", "");
			} catch (Exception e) {
			}
			if(!((string)"").Equals(sKey))
                htVal.Add(sKey, sVal);
		}
	}

    public string getVal(string pIdenVal)
    {
        return (string)htVal[pIdenVal];
    }

}