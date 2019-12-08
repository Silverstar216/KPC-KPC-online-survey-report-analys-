package com.lit.litgenerator;

import java.util.HashMap;

public class LitDecode {
	private HashMap<String, String> hmVal = null; 
	
	public LitDecode(String pDecodeStr) {
		hmVal = new HashMap<String, String>();
		pDecodeStr = pDecodeStr.substring(pDecodeStr.indexOf("{")+1 , pDecodeStr.indexOf("}")-1);
		pDecodeStr = pDecodeStr.replaceAll("\"", "");
		
		String[] aVal1 = pDecodeStr.split(",");
		String sKey = "", sVal = "";
		for(int i=0; i<aVal1.length; i++) {
			sKey = aVal1[i].substring(0, aVal1[i].indexOf(":"));
			sVal = aVal1[i].substring(aVal1[i].indexOf(":")+1);
			try {
			sVal = sVal.replace("\\", "");
			} catch (Exception e) {
				System.out.println("");
			}
			if(!"".equals(sKey))
				hmVal.put(sKey, sVal); 
		}
	}

	public String getVal(String pIdenVal) {
		return hmVal.get(pIdenVal);
	}
}
