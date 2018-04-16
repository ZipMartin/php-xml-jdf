<?php

	$pdfconstructor = '/Applications/Apago/pdfconstructor/pdfconstructor -license /Applications/Apago/pdfconstructor/license.psl -fontMap /Applications/Apago/pdfconstructor/pdfconstructor.fontmap';

	$jobDir = $argv[1];
	$pitsAction = $argv[2];
	
	if ($handle = opendir($jobDir)) 
		{
    	while (false !== ($entry = readdir($handle))) 
    		{
        	if( $entry == '.' || $entry == '..')
        	continue;
			$extension = substr(strrchr($entry, "."), 1);
		
			if ($extension == "xml") 
				{
				$xmlFile = $entry;
				} else {
					$pdfFile = $entry;
				}
    		}
    	closedir($handle);
		}
	
	$theOrderXML = simplexml_load_file($jobDir."/".$xmlFile);
    
    $jobNum = $theOrderXML->JobNumber;
    $customer = $theOrderXML->Customer;
    $description = $theOrderXML->JobDescr;
    $stock = $theOrderXML->Stock;
    $quantity = $theOrderXML->OrderQuant;
    $sheets = $theOrderXML->PrintQuant;
    $sheetSize = $theOrderXML->SheetSize;
    $sheetX = $theOrderXML->SheetX;
    $sheetY = $theOrderXML->SheetY;
    $trimSize = $theOrderXML->TrimSize;
    $trimX = $theOrderXML->TrimX;
    $trimY = $theOrderXML->TrimY;
    $perSheet = $theOrderXML->PerSheet;
    $sides = $theOrderXML->Sides;
    $jobName = $jobNum." ".$customer;
    $trimWidth = floatval($trimX);
    $trimHeight = floatval($trimY);
    
    if ($pitsAction == "Standard Geo Bleed")
    	{
    	$bleedWidth = $trimWidth + .12;
    	$bleedHeight = $trimHeight + .12;
    	} else {
    		$bleedWidth = $trimWidth;
    		$bleedHeight = $trimHeight;
    	}	    
	
	if ($sheetX and $sheetY <= 19)
		{
		$mediaWidth = floatval($sheetX) - .5;
		$mediaHeight = floatval($sheetY) - .75;
		} else {
			$mediaWidth = floatval($sheetX) - .75;
			$mediaHeight = floatval($sheetY) - .5;
		}

	if ($bleedWidth > $trimWidth)
		{
		$hcrop = "#hcropb";
		$vcrop = "#vcropb";
		$cropShift = 4.32;
		} else {
			$hcrop = "#hcrop";
			$vcrop = "#vcrop";
			$cropShift = 0;
		}
	
	$widthValA = intval($mediaWidth / $bleedWidth);
	$heightValA = intval($mediaHeight / $bleedWidth);
	$widthValB = intval($mediaWidth / $bleedHeight);
	$heightValB = intval($mediaHeight / $bleedHeight);
	$orientA = $widthValA * $heightValB;
	$orientB = $widthValB * $heightValA;
	
	if ($orientA > $orientB)
		{ 
		$layout = array($widthValA, $heightValB);
		$rotation = 0;
			} else {
			$layout = array($widthValB, $heightValA);
			$rotation = 90;
		}
			
	$columns = $layout[0];
	$rows = $layout[1];
	if ($rotation != "0")
		{
		$workWidth = $bleedHeight * $columns;
		$workHeight = $bleedWidth * $rows;
		} else {
			$workWidth = $bleedWidth * $columns;
			$workHeight = $bleedHeight * $rows;
		}
	$xOrigin = ($sheetX - $workWidth) / 2;
	$yOrigin = ($sheetY - $workHeight) / 2;
	
	$xmlfn = "/tmp/order".getmypid().".xml";
	$pdffn = dirname($argv[1])."/".basename($argv[1], ".xml").".pdf";

    $f = fopen($xmlfn, "w");
    fwrite($f, "<?xml version='1.0' encoding='UTF-8'?>\n");
    fwrite($f, "<docasm version='1.6'>\n");
    fwrite($f, "<resources>\n");      
	fwrite($f, "<input id='OneUpCard' href=\"".$jobDir."/".$pdfFile."\"/>\n");				
    fwrite($f, "<object id='hcrop' width='12' height='12'>\n");
    fwrite($f, "<line x1='0' y1='0' x2='9' y2='0' style='stroke:black; stroke-width:.5'/>\n");
    fwrite($f, "</object>\n");
    fwrite($f, "<object id='vcrop' width='12' height='12'>\n");
    fwrite($f, "<line x1='0' y1='0' x2='0' y2='9' style='stroke:black; stroke-width:.5'/>\n");
    fwrite($f, "</object>\n");
    fwrite($f, "<object id='hcropb' width='8.64' height='8.64'>\n");
    fwrite($f, "<line x1='0' y1='0' x2='8.64' y2='0' style='stroke:black; stroke-width:.5'/>\n");
    fwrite($f, "<line x1='0' y1='8.64' x2='8.64' y2='8.64' style='stroke:black; stroke-width:.5'/>\n");
    fwrite($f, "</object>\n");
    fwrite($f, "<object id='vcropb' width='8.64' height='8.64'>\n");
	fwrite($f, "<line x1='0' y1='0' x2='0' y2='8.64' style='stroke:black; stroke-width:.5'/>\n");
	fwrite($f, "<line x1='8.64' y1='0' x2='8.64' y2='8.64' style='stroke:black; stroke-width:.5'/>\n");
    fwrite($f, "</object>\n");        
    fwrite($f, "</resources>\n");  
    fwrite($f, "<pages>\n");
    
    fwrite($f, "<page>\n");
    fwrite($f, "<boxes>\n");
    fwrite($f, "<MediaBox width='".($sheetX * 72)."' height='".($sheetY * 72)."' x='0' y='0' />\n");
    fwrite($f, "</boxes>\n");
    fwrite($f, "<elements>\n");
    fwrite($f, "<richtext frame='24 72 576 84' rotation='90' style='vertical-align:middle'>\n");
    fwrite($f, "<paragraph style='text-align:left;font-family:Arial;font-size:8;fill:[0 0 0 100];line-height:110%'>\n");
    fwrite($f, "<chunk>"."Job Name: ".$jobName." Sheet: ".$sheetSize." Stock: ".$stock." Trim: ".$trimWidth." x ".$trimHeight." Qty: ".$quantity."</chunk>\n");
    fwrite($f, "</paragraph>\n");
    fwrite($f, "</richtext>\n");	
	
	for ($c = 0; $c < $columns; $c++)
		{
		for ($r = 0; $r < $rows; $r++)
			{
			if ($rotation == "0")
				{
				$xVal = ($xOrigin * 72) + ($bleedWidth * 72 * $c);
				$yVal = ($yOrigin * 72) + ($bleedHeight * 72 * $r);
				} 
			if ($rotation == "90")
				{
				$xVal = ($xOrigin * 72) + ($bleedHeight * 72 * $c) + ($bleedHeight * 72);
				$yVal = ($yOrigin * 72) + ($bleedWidth * 72 * $r);
				}
			fwrite($f, "<document href='#OneUpCard' x='".$xVal."' y='".$yVal."' rotation='".$rotation."' page='0'/>\n");
			}
		}
	
	for ($r = 0; $r <= $rows; $r++)
			{
			if ($rotation == "0")
				{	
				fwrite($f, "<use name='".$hcrop."' x='".($xOrigin * 72 - 18)."' y='".($yOrigin * 72 + $bleedHeight * 72 * $r - $cropShift)."'/>\n");
				fwrite($f, "<use name='".$hcrop."' x='".($xOrigin * 72 + $bleedWidth * 72 * $columns + 9)."' y='".($yOrigin * 72 + $bleedHeight * 72 * $r - $cropShift)."'/>\n");
				}
			if ($rotation == "90")
				{	
				fwrite($f, "<use name='".$hcrop."' x='".($xOrigin * 72 - 18)."' y='".($yOrigin * 72 + $bleedWidth * 72 * $r - $cropShift)."'/>\n");
				fwrite($f, "<use name='".$hcrop."' x='".($xOrigin * 72 + $bleedHeight * 72 * $columns + 9)."' y='".($yOrigin * 72 + $bleedWidth * 72 * $r - $cropShift)."'/>\n");
				}	
			}
		for ($c = 0; $c <= $columns; $c++)
			{
			if ($rotation == "0")
				{	
				fwrite($f, "<use name='".$vcrop."' x='".($xOrigin * 72 + $bleedWidth * 72 * $c - $cropShift)."' y='".($yOrigin * 72 - 18)."'/>\n");
				fwrite($f, "<use name='".$vcrop."' x='".($xOrigin * 72 + $bleedWidth * 72 * $c - $cropShift)."' y='".($yOrigin * 72 + $bleedHeight * 72 * $rows + 9)."'/>\n");
				}
			if ($rotation == "90")
				{	
				fwrite($f, "<use name='".$vcrop."' x='".($xOrigin * 72 + $bleedHeight * 72 * $c - $cropShift)."' y='".($yOrigin * 72 - 18)."'/>\n");
				fwrite($f, "<use name='".$vcrop."' x='".($xOrigin * 72 + $bleedHeight * 72 * $c - $cropShift)."' y='".($yOrigin * 72 + $bleedWidth * 72 * $rows + 9)."'/>\n");
				}
			}
	
	fwrite($f, "</elements>\n");
    fwrite($f, "</page>\n");
    
    if ($sides == "2")
    	{
    	$rotFactor = 3;
    	fwrite($f, "<page>\n");
		fwrite($f, "<boxes>\n");
		fwrite($f, "<MediaBox width='".($sheetX * 72)."' height='".($sheetY * 72)."' x='0' y='0' />\n");
		fwrite($f, "</boxes>\n");
		fwrite($f, "<elements>\n");
		fwrite($f, "<richtext frame='24 72 576 84' rotation='90' style='vertical-align:middle'>\n");
		fwrite($f, "<paragraph style='text-align:left;font-family:Arial;font-size:8;fill:[0 0 0 100];line-height:110%'>\n");
		fwrite($f, "<chunk>"."Job Name: ".$jobName." Sheet: ".$sheetSize." Stock: ".$stock." Trim: ".$trimWidth." x ".$trimHeight." Qty: ".$quantity."</chunk>\n");
		fwrite($f, "</paragraph>\n");
		fwrite($f, "</richtext>\n");	
	
		for ($c = 0; $c < $columns; $c++)
			{
			for ($r = 0; $r < $rows; $r++)
				{
				if ($rotation == "0")
					{
					$xVal = ($xOrigin * 72) + ($bleedWidth * 72 * $c);
					$yVal = ($yOrigin * 72) + ($bleedHeight * 72 * $r);
					} 
				if ($rotation == "90")
					{
					$xVal = ($xOrigin * 72) + ($bleedHeight * 72 * $c);
					$yVal = ($yOrigin * 72) + ($bleedWidth * 72 * $r) + ($bleedWidth * 72);
					}

				fwrite($f, "<document href='#OneUpCard' x='".$xVal."' y='".$yVal."' rotation='".$rotation * $rotFactor."' page='1'/>\n");
				}
			}
	
		for ($r = 0; $r <= $rows; $r++)
				{
				if ($rotation == "0")
					{	
					fwrite($f, "<use name='".$hcrop."' x='".($xOrigin * 72 - 18)."' y='".($yOrigin * 72 + $bleedHeight * 72 * $r - $cropShift)."'/>\n");
					fwrite($f, "<use name='".$hcrop."' x='".($xOrigin * 72 + $bleedWidth * 72 * $columns + 9)."' y='".($yOrigin * 72 + $bleedHeight * 72 * $r - $cropShift)."'/>\n");
					}
				if ($rotation == "90")
					{	
					fwrite($f, "<use name='".$hcrop."' x='".($xOrigin * 72 - 18)."' y='".($yOrigin * 72 + $bleedWidth * 72 * $r - $cropShift)."'/>\n");
					fwrite($f, "<use name='".$hcrop."' x='".($xOrigin * 72 + $bleedHeight * 72 * $columns + 9)."' y='".($yOrigin * 72 + $bleedWidth * 72 * $r - $cropShift)."'/>\n");
					}	
				}
			for ($c = 0; $c <= $columns; $c++)
				{
				if ($rotation == "0")
					{	
					fwrite($f, "<use name='".$vcrop."' x='".($xOrigin * 72 + $bleedWidth * 72 * $c - $cropShift)."' y='".($yOrigin * 72 - 18)."'/>\n");
					fwrite($f, "<use name='".$vcrop."' x='".($xOrigin * 72 + $bleedWidth * 72 * $c - $cropShift)."' y='".($yOrigin * 72 + $bleedHeight * 72 * $rows + 9)."'/>\n");
					}
				if ($rotation == "90")
					{	
					fwrite($f, "<use name='".$vcrop."' x='".($xOrigin * 72 + $bleedHeight * 72 * $c - $cropShift)."' y='".($yOrigin * 72 - 18)."'/>\n");
					fwrite($f, "<use name='".$vcrop."' x='".($xOrigin * 72 + $bleedHeight * 72 * $c - $cropShift)."' y='".($yOrigin * 72 + $bleedWidth * 72 * $rows + 9)."'/>\n");
					}
				}
	
		fwrite($f, "</elements>\n");
		fwrite($f, "</page>\n");
    	}
    
    fwrite($f, "</pages>\n");
    fwrite($f, "</docasm>\n");

    fclose($f);
        
    $out = null;
    $exitCode = 0;
    $out = exec($pdfconstructor . " -f \"". $xmlfn ."\" -o \"" . $pdffn ."\"", $out, $exitCode);
    if ($exitCode != 0) 
    	{
        echo "An error occurred while building form:" . $out;
        }
	exit($exitCode);

?>