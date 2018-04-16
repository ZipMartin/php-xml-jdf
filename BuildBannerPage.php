<?php
	
	$pdfconstructor = '/Applications/Apago/pdfconstructor/pdfconstructor -license /Applications/Apago/pdfconstructor/license.psl -fontMap /Applications/Apago/pdfconstructor/pdfconstructor.fontmap';

	$jobDir = $argv[1];
	
	if ($handle = opendir($jobDir)) {

    while (false !== ($entry = readdir($handle))) {
        
        if( $entry == '.' || $entry == '..')
        	continue;
		
		$extension = substr(strrchr($entry, "."), 1);
		
			if ($extension == "xml") {
				$xmlFile = $entry;
			} else {
				$pdfFile = $entry;
			}

    	}
    closedir($handle);
	}

	$theOrderXML = simplexml_load_file($jobDir."/".$xmlFile);
    
    	$orderNum = $theOrderXML->OrderNumber;
    	$cartNum = $theOrderXML->CartNumber;
    	$orderType = $theOrderXML->OrderType;
    	$techName = $theOrderXML->TechName;
    	$delivAddr = $theOrderXML->DelivAddr;
    	$orderDate = $theOrderXML->OrderDate;

        $xmlfn = "/tmp/order".getmypid().".xml";
		$pdffn = dirname($argv[1])."/".basename($argv[1], ".xml").".pdf";

        $f = fopen($xmlfn, "w");
        
        fwrite($f, "<?xml version='1.0' encoding='UTF-8'?>\n");
        fwrite($f, "<docasm version='1.6'>\n");
        fwrite($f, "<resources>\n");
        fwrite($f, "</resources>\n");
        fwrite($f, "<pages>\n");
        fwrite($f, "<page>\n");
        fwrite($f, "<boxes>\n");
        fwrite($f, "<MediaBox width='252' height='144' x='0' y='0' />\n");
        fwrite($f, "</boxes>\n");
        fwrite($f, "<elements>\n");
    	fwrite($f, "<document href='".$jobDir."/".$pdfFile."' page='0'/>\n");
    	fwrite($f, "</elements>\n");
        fwrite($f, "</page>\n");
        
        fwrite($f, "<page>\n");
        fwrite($f, "<boxes>\n");
        fwrite($f, "<MediaBox width='252' height='144' x='0' y='0' />\n");
        fwrite($f, "</boxes>\n");
        fwrite($f, "<elements>\n");
        fwrite($f, "<richtext frame='63 10 243 135' style='vertical-align:middle'>\n");
        fwrite($f, "<paragraph style='text-align:left;font-family:Arial;font-size:8;fill:[0 0 0 100];line-height:110%'>\n");
        fwrite($f, "<chunk>"."Cart # ".$cartNum."</chunk>\n");
        fwrite($f, "</paragraph>\n");
        fwrite($f, "<paragraph style='text-align:left;font-family:Arial;font-size:8;fill:[0 0 0 100];line-height:110%'>\n");
        fwrite($f, "<chunk>"."Order # ".$orderNum."</chunk>\n");
        fwrite($f, "</paragraph>\n");
        fwrite($f, "<paragraph style='text-align:left;font-family:Arial;font-size:8;fill:[0 0 0 100];line-height:110%'>\n");
        fwrite($f, "<chunk>"."Order Date: ".$orderDate."</chunk>\n");
        fwrite($f, "</paragraph>\n");
        fwrite($f, "<paragraph style='text-align:left;font-family:Arial;font-size:8;fill:[0 0 0 100];line-height:110%'>\n");
        fwrite($f, "<chunk>"."Order Type: ".htmlspecialchars($orderType)."</chunk>\n");
        fwrite($f, "</paragraph>\n");        
        fwrite($f, "<paragraph style='text-align:left;font-family:Arial;font-size:8;fill:[0 0 0 100];line-height:110%'>\n");
        fwrite($f, "<chunk>"."Name: ".$techName."</chunk>\n");
        fwrite($f, "</paragraph>\n");
        fwrite($f, "<paragraph style='text-align:left;font-family:Arial;font-size:8;fill:[0 0 0 100];line-height:110%'>\n");
        fwrite($f, "<chunk>"."Shipping To: ".htmlspecialchars($delivAddr)."</chunk>\n");        
        fwrite($f, "</paragraph>\n");                 
        fwrite($f, "</richtext>\n");
        fwrite($f, "</elements>\n");
        fwrite($f, "</page>\n");
        fwrite($f, "</pages>\n");
        fwrite($f, "</docasm>\n");

        fclose($f);

        $out = null;
        $exitCode = 0;
        
        $out = exec($pdfconstructor . " -f \"". $xmlfn ."\" -o \"" . $pdffn ."\"", $out, $exitCode);
        if ($exitCode != 0) {
                echo "An error occurred while building form:" . $out;
        }
		exit($exitCode);

?>
