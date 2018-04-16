<?php

	$pdfconstructor = '/Applications/Apago/pdfconstructor/pdfconstructor -license /Applications/Apago/pdfconstructor/license.psl -fontMap /Applications/Apago/pdfconstructor/pdfconstructor.fontmap';

	$pdfName = dirname($argv[1])."/".basename($argv[1]);
	$jobName = basename($argv[1]);
	$bleedWidth = floatval($argv[2] / 72);
	$bleedHeight = floatval($argv[3] / 72);
	$trimWidth = floatval($argv[4] / 72);
	$trimHeight = floatval($argv[5] / 72);
//	$pageCount = intval($argv[6]);
	$sheetSize = $argv[6];
//	$sheetSize = "12 x 18";
	$duplex = $argv[7];
	$quantity = $argv[8];
	$numNames = $argv[9];
	$sheetXY = preg_split("/ x /", $sheetSize);
	$sheetX = $sheetXY[0];
	$sheetY = $sheetXY[1];
	$mediaWidth = floatval($sheetX) - .5;
	$mediaHeight = floatval($sheetY) - .75;
	
/*	$numNamesFind = preg_split("/names/", $jobName);
	$numNamesSplit = preg_split("/_/", $numNamesFind[0]);
	$numNames = intval($numNamesSplit[1]);
	if ($pageCount / $numNames == 1)
		{
			$duplex = "No";
		} else {
			$duplex = "Yes";
		}
*/
	$runListA = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	$runListAd = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
	$runListB = array(0,0,0,0,1,1,1,1,0,0,0,0,1,1,1,1,0,0,0,0,1,1,1,1);
	$runListBd = array(0,0,0,0,2,2,2,2,0,0,0,0,2,2,2,2,0,0,0,0,2,2,2,2,1,1,1,1,3,3,3,3,1,1,1,1,3,3,3,3,1,1,1,1,3,3,3,3);
	$runListC = array(0,0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,2,2,2,2,2,2,2,2);
	$runListCd = array(0,0,0,0,0,0,0,0,2,2,2,2,2,2,2,2,4,4,4,4,4,4,4,4,5,5,5,5,5,5,5,5,3,3,3,3,3,3,3,3,1,1,1,1,1,1,1,1);
	$runListD = array(0,0,1,1,2,2,3,3,0,0,1,1,2,2,3,3,0,0,1,1,2,2,3,3);
	$runListDd = array(0,0,2,2,4,4,6,6,0,0,2,2,4,4,6,6,0,0,2,2,4,4,6,6,1,1,3,3,5,5,7,7,1,1,3,3,5,5,7,7,1,1,3,3,5,5,7,7);
	$runListE = array(0,0,0,0,3,3,3,3,1,1,1,1,4,4,4,4,2,2,2,2,5,5,5,5);
	$runListEd = array(0,0,0,0,6,6,6,6,2,2,2,2,8,8,8,8,4,4,4,4,10,10,10,10,5,5,5,5,11,11,11,11,3,3,3,3,9,9,9,9,1,1,1,1,7,7,7,7);
	$runListF = array(0,1,2,3,4,5,6,7,0,1,2,3,4,5,6,7,0,1,2,3,4,5,6,7);
	$runListFd = array(0,2,4,6,8,10,12,14,0,2,4,6,8,10,12,14,0,2,4,6,8,10,12,14,1,3,5,7,9,11,13,15,1,3,5,7,9,11,13,15,1,3,5,7,9,11,13,15);
	$runListG = array(0,0,3,3,6,6,9,9,1,1,4,4,7,7,10,10,2,2,5,5,8,8,11,11);
	$runListGd = array(0,0,6,6,12,12,18,18,2,2,8,8,14,14,20,20,4,4,10,10,16,16,22,22,5,5,11,11,17,17,23,23,3,3,9,9,15,15,21,21,1,1,7,7,13,13,19,19);
	$runListH = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23);
	$runListHd = array(0,2,4,6,8,10,12,14,16,18,20,22,24,26,28,30,32,34,36,38,40,42,44,46,33,35,37,39,41,43,45,47,17,19,21,23,25,27,29,31,1,3,5,7,9,11,13,15);
	
	if ($duplex == "No")
		{
			switch ($numNames)
			{
				case 1:
					$runList = $runListA;
					break;
				case 2:
					$runList = $runListB;
					break;
				case 3:
					$runList = $runListC;
					break;
				case 4:
					$runList = $runListD;
					break;
				case 6:
					$runList = $runListE;
					break;
				case 8:
					$runList = $runListF;
					break;
				case 12:
					$runList = $runListG;
					break;
				case 24:
					$runList = $runListH;
					break;						
			}
			} else {
				switch ($numNames)
				{
					case 1:
						$runList = $runListAd;
						break;
					case 2:
						$runList = $runListBd;
						break;
					case 3:
						$runList = $runListCd;
						break;
					case 4:
						$runList = $runListDd;
						break;
					case 6:
						$runList = $runListEd;
						break;
					case 8:
						$runList = $runListFd;
						break;
					case 12:
						$runList = $runListGd;
						break;
					case 24:
						$runList = $runListHd;
						break;
				}
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
	fwrite($f, "<input id='OneUpCard' href=\"".$pdfName."\"/>\n");				
    fwrite($f, "<object id='hcrop' width='12' height='12'>\n");
    fwrite($f, "<line x1='0' y1='0' x2='9' y2='0' style='stroke:[0 0 0 100]; stroke-width:.5'/>\n");
    fwrite($f, "</object>\n");
    fwrite($f, "<object id='vcrop' width='12' height='12'>\n");
    fwrite($f, "<line x1='0' y1='0' x2='0' y2='9' style='stroke:[0 0 0 100]; stroke-width:.5'/>\n");
    fwrite($f, "</object>\n");
    fwrite($f, "<object id='hcropb' width='8.64' height='8.64'>\n");
    fwrite($f, "<line x1='0' y1='0' x2='8.64' y2='0' style='stroke:[0 0 0 100]; stroke-width:.5'/>\n");
    fwrite($f, "<line x1='0' y1='8.64' x2='8.64' y2='8.64' style='stroke:[0 0 0 100]; stroke-width:.5'/>\n");
    fwrite($f, "</object>\n");
    fwrite($f, "<object id='vcropb' width='8.64' height='8.64'>\n");
	fwrite($f, "<line x1='0' y1='0' x2='0' y2='8.64' style='stroke:[0 0 0 100]; stroke-width:.5'/>\n");
	fwrite($f, "<line x1='8.64' y1='0' x2='8.64' y2='8.64' style='stroke:[0 0 0 100]; stroke-width:.5'/>\n");
    fwrite($f, "</object>\n");        
    fwrite($f, "</resources>\n");  
    fwrite($f, "<pages>\n");
    
    fwrite($f, "<page>\n");
    fwrite($f, "<boxes>\n");
    fwrite($f, "<MediaBox width='864' height='1296' x='0' y='0' />\n");
    fwrite($f, "</boxes>\n");
    fwrite($f, "<elements>\n");
    fwrite($f, "<richtext frame='24 72 576 84' rotation='90' style='vertical-align:middle'>\n");
    fwrite($f, "<paragraph style='text-align:left;font-family:Arial;font-size:8;fill:[0 0 0 100];line-height:110%'>\n");
    fwrite($f, "<chunk>"."Job Name: ".$jobName." Sheet: ".$sheetSize." Trim: ".$trimWidth." x ".$trimHeight." Qty: ".$quantity."</chunk>\n");
    fwrite($f, "</paragraph>\n");
    fwrite($f, "</richtext>\n");
    	
	$rl = 0;
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
			if ($rl < 24)
				{	
				fwrite($f, "<document href='#OneUpCard' x='".$xVal."' y='".$yVal."' rotation='".$rotation."' page='".$runList[$rl]."'/>\n");
				$rl++;
				}
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
    
    if ($duplex == "Yes")
    	{
    	$rotFactor = 3;
    	fwrite($f, "<page>\n");
		fwrite($f, "<boxes>\n");
		fwrite($f, "<MediaBox width='864' height='1296' x='0' y='0' />\n");
		fwrite($f, "</boxes>\n");
		fwrite($f, "<elements>\n");
		fwrite($f, "<richtext frame='24 72 576 84' rotation='90' style='vertical-align:middle'>\n");
		fwrite($f, "<paragraph style='text-align:left;font-family:Arial;font-size:8;fill:[0 0 0 100];line-height:110%'>\n");
		fwrite($f, "<chunk>"."Job Name: ".$jobName." Sheet: ".$sheetSize." Trim: ".$trimWidth." x ".$trimHeight." Qty: ".$quantity."</chunk>\n");
		fwrite($f, "</paragraph>\n");
		fwrite($f, "</richtext>\n");
			
		$rl = 24;
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
				if ($rl < 48)
					{
					fwrite($f, "<document href='#OneUpCard' x='".$xVal."' y='".$yVal."' rotation='".$rotation * $rotFactor."' page='".$runList[$rl]."'/>\n");
					$rl++;
					}
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
    