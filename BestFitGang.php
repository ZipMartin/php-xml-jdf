<?php

	$pdfconstructor = '/Applications/Apago/pdfconstructor/pdfconstructor -license /Applications/Apago/pdfconstructor/license.psl -fontMap /Applications/Apago/pdfconstructor/pdfconstructor.fontmap';

	$pdfName = dirname($argv[1])."/".basename($argv[1]);
	$jobName = basename($argv[1]);
	$bleedWidth = floatval($argv[2] / 72);
	$bleedHeight = floatval($argv[3] / 72);
	$trimWidth = floatval($argv[4] / 72);
	$trimHeight = floatval($argv[5] / 72);
	$sheetSize = $argv[6];
//	$sheetSize = "12 x 18";
	$duplex = $argv[7];
//	$duplex = "No";
	$quantity = $argv[8];
//	$quantity = 250;
	$numPages = $argv[9];
	$sheetXY = preg_split("/ x /", $sheetSize);
	$sheetX = $sheetXY[0];
	$sheetY = $sheetXY[1];
	$mediaWidth = floatval($sheetX) - .5;
	$mediaHeight = floatval($sheetY) - .75;
	
	if (floatval($trimWidth) == 4.25 && floatval($trimHeight) == 11)
		{
		$mediaWidth = floatval($sheetX) - .375;
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
	$stepCount = $columns * $rows;
	if ($stepCount / $numPages < 1)
		{
		$stepRep = 1;
		} else {
			$stepRep = $stepCount / $numPages;
		}
	
	if ($duplex == "No")
		{
		$numSheets = ceil($numPages / $stepCount);
		$sides = 1;
		} else {
			$numSheets = ceil($numPages / 2 / $stepCount);
			$sides = 2;
		}
	
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
	
	$pages = $numPages / $sides;
	$positions = $columns * $rows;
	$step = ceil($positions / $pages);
	$runlist = array();
	
	$currPage = 0;
	$rl = 0;
	
	if ($step == 1)
		{
		for ($ou = 0; $ou < $numPages; $ou++)
			{
			$runlist[$rl] = $ou;
			$rl++;
			}
		}
	
	if ($sides != 2 && $step > 1)
		{
		while ($rl < $positions * $numSheets)
			{
			for ($s = 0; $s < $step; $s++)
				{
				$runlist[$rl] = $currPage;
				$rl++;
				}
			$currPage++;
			}
		} else {
			while ($rl < $positions * $numSheets)
				{
				for ($s = 0; $s < $step; $s++)
					{
					$runlist[$rl] = $currPage * 2;
					$rl++;
					}
				$currPage++;
				}
			for ($d = 0; $d < $positions * $numSheets; $d++)
				{
				$runlist[$rl] = $runlist[$d] + 1;
				$rl++;	
				}
		}
	
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
    
	$sheetCounter = 0;
	$rl = 0;
	while ($sheetCounter < $numSheets)
	{    
    
    fwrite($f, "<page>\n");
    fwrite($f, "<boxes>\n");
    fwrite($f, "<MediaBox width='".($sheetX * 72)."' height='".($sheetY * 72)."' x='0' y='0' />\n");
    fwrite($f, "</boxes>\n");
    fwrite($f, "<elements>\n");
    fwrite($f, "<richtext frame='24 72 576 84' rotation='90' style='vertical-align:middle'>\n");
    fwrite($f, "<paragraph style='text-align:left;font-family:Arial;font-size:8;fill:[0 0 0 100];line-height:110%'>\n");
    fwrite($f, "<chunk>"."Job Name: ".$jobName." Sheet: ".$sheetSize." Trim: ".$trimWidth." x ".$trimHeight." Qty: ".$quantity." Flat: ".($sheetCounter + 1)." of ".$numSheets."</chunk>\n");
    fwrite($f, "</paragraph>\n");
    fwrite($f, "</richtext>\n");
    	
//	$sheetCounter = 0;
//	$rl = 0;
//	while ($sheetCounter < $numSheets)
//	{
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
			if ($rl < count($runlist))
				{	
				fwrite($f, "<document href='#OneUpCard' x='".$xVal."' y='".$yVal."' rotation='".$rotation."' page='".$runlist[$rl]."'/>\n");
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
		fwrite($f, "<MediaBox width='".($sheetX * 72)."' height='".($sheetY * 72)."' x='0' y='0' />\n");
		fwrite($f, "</boxes>\n");
		fwrite($f, "<elements>\n");
		fwrite($f, "<richtext frame='24 72 576 84' rotation='90' style='vertical-align:middle'>\n");
		fwrite($f, "<paragraph style='text-align:left;font-family:Arial;font-size:8;fill:[0 0 0 100];line-height:110%'>\n");
		fwrite($f, "<chunk>"."Job Name: ".$jobName." Sheet: ".$sheetSize." Trim: ".$trimWidth." x ".$trimHeight." Qty: ".$quantity." Flat: ".($sheetCounter + 1)." of ".$numSheets."</chunk>\n");
		fwrite($f, "</paragraph>\n");
		fwrite($f, "</richtext>\n");
			
		if ($step != 1)
			{
			$rl = $stepCount;
			}
//		for ($c = 0; $c < $columns; $c++)
		for ($c = $columns -1; $c >= 0; $c--)
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
				if ($rl < count($runlist))
					{
					fwrite($f, "<document href='#OneUpCard' x='".$xVal."' y='".$yVal."' rotation='".$rotation * $rotFactor."' page='".$runlist[$rl]."'/>\n");
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
    	$sheetCounter++;
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
    