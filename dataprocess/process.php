<?php
	require 'Classes/PHPExcel.php';
	
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "shipdb";
	
	$conn = new mysqli($servername, $username, $password, $dbname);

/*	$url = 'https://rimf.ffa.int/rimf/public/license.php';
	$data = file_get_contents($url);
	
	$ss = strstr($data, '<tbody>');
	$ee = strstr ( $data , '</tbody>' );

	$data = substr ( $data , strlen($data)-strlen($ss)+7 , strlen($ss)-strlen($ee)-7 );*/
//	echo $data;
//	echo "</table>";


	$num = 0;
	$file = fopen("#01 RFV_database_export.csv","r");
	$line = fgetcsv($file);	// omit header
	while(! feof($file))
	{
		$line = fgetcsv($file);
		if (is_array($line)) 
		{
			$c=0;
			foreach ($line as $key => $value)
				$data[$num][$c++] = $value;
			$num++;
		}
	}
	fclose($file);

	if ($num != 0)
	{
//	
		reset($data);
		$c = 1;
		$sqlH = "INSERT INTO `rawdata` (`shipID`, `Name`, `flag`, `flagNo`, `IRCS`, `IMONO`, `portReg`, `owner`, `Source`, `Type`, `length`, `tonnage`, `byear`) VALUES ";
		foreach  ($data as $row)
		{
			$Sval = "(\"". $row[2].$row[44]."" . "\",\"". $row[2] ."\",\"". $row[3] . "\",\"". $row[4] . "\",\"". $row[13] . "\",\"". $row[44] . "\",\"". $row[7] . "\",\"". $row[8] . "\",\"1\",\"". $row[17] . "\",\"". $row[20]. "\",\"". $row[27]. "\",\"". $row[16] ."\")";
			$sqlH = $sqlH.$Sval.",";
		
			if ($c % 2 == 0)
			{				
				$sqlH = trim($sqlH,",");
				$sqlH = $sqlH.";";
		//		echo $sqlH;
				
				if ($conn->query($sqlH) === TRUE)
					echo "";
					else {
						echo "Error DB insert : " . $conn->error. " at ".$c."<br/>";
						echo $sqlH;
					}
				$sqlH = "INSERT INTO `rawdata` (`shipID`, `Name`, `flag`, `flagNo`, `IRCS`, `IMONO`, `portReg`, `owner`, `Source`, `Type`, `length`, `tonnage`, `byear`) VALUES ";
			}
			$c++;
		}
		if ($num % 2 != 0)
		{
			$sqlH = trim($sqlH,",");
			$sqlH = $sqlH.";";
			if ($conn->query($sqlH) === TRUE)
				echo " ";
			else
				echo "Error DB insert : " . $conn->error;
		}
//	
		reset($data);
		$c = 1;
		$sqlH = "INSERT INTO `license` (`shipID`, `authType`, `Issue`, `authNo`, `Date`, `Due`, `area`, `method`, `species`) VALUES ";
		foreach  ($data as $row)
		{
			$Sval = "(\"". $row[2].$row[44]."" . "\",\"". $row[37] . "\",\"WCPFC\",\"". $row[38] . "\",\"". $row[41] . "\",\"". $row[42] . "\",\"". $row[39] . "\",\"". $row[19] . "\",\"". $row[40] ."\")";
			$sqlH = $sqlH.$Sval.",";
		
			if ($c % 256 == 0)
			{				
				$sqlH = trim($sqlH,",");
				$sqlH = $sqlH.";";
		//		echo $sqlH;
				
				if ($conn->query($sqlH) === TRUE)
					echo "New record created successfully<br/>";
					else {
						echo "Error DB insert : " . $conn->error. " at ".$c."<br/>";
						echo $sqlH;
					}
				$sqlH = "INSERT INTO `license` (`shipID`,`authType`, `Issue`, `authNo`, `Date`, `Due`, `area`, `method`, `species`) VALUES ";
			}
			$c++;
		}
		if ($num % 256 != 0)
		{
			$sqlH = trim($sqlH,",");
			$sqlH = $sqlH.";";
			if ($conn->query($sqlH) === TRUE)
				echo "New record created successfully<br/>";
			else
				echo "Error DB insert : " . $conn->error;
		}
	}
	$id = 1;
	$sqlH = "UPDATE `dbsource` SET `record` = '".$num."', `Udate` = '".date('Y-m-d H:i:s')."' WHERE `dbsource`.`id` = ".$id.";";
	$conn->query($sqlH);
	
	$num = 0;

		$reader= PHPExcel_IOFactory::createReaderForFile("#25 IOTC_positive_list_2016-04-21.xls");
		$reader->setReadDataOnly(true);
		$excel= $reader->load("#25 IOTC_positive_list_2016-04-21.xls");
		$sheet=$excel->getActiveSheet();
					
		$Brow = 2;
		while ($sheet->getCellByColumnAndRow(0,$Brow)->getValue())
		{
			for ($c=0; $c<=25; $c++)
			{
				$data[$num][$c] = $sheet->getCellByColumnAndRow($c,$Brow)->getValue();
				if ($c == 19 || $c == 20)
					$data[$num][$c] = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($data[$num][$c])); 
			}
			$num++;
			$Brow++;
		}
		$excel->disconnectWorksheets();

	if ($num != 0)
	{
//	
		reset($data);
		$c = 1;
		$sqlH = "INSERT INTO `rawdata` (`shipID`, `Name`, `flag`, `IMONO`, `portReg`, `owner`, `Source`, `Type`, `length`, `tonnage`) VALUES ";
		foreach  ($data as $row)
		{
			$Sval = "(\"". $row[4].$row[23]."" . "\",\"". $row[4] ."\",\"". $row[0] . "\",\"". $row[23] . "\",\"". $row[24] . "\",\"". $row[15] . "\",\"25\",\"". $row[6] . "\",\"". $row[12]. "\",\"". $row[14] ."\")";
			$sqlH = $sqlH.$Sval.",";
		
			if ($c % 2 == 0)
			{				
				$sqlH = trim($sqlH,",");
				$sqlH = $sqlH.";";
		//		echo $sqlH;
				
				if ($conn->query($sqlH) === TRUE)
					echo "";
					else {
						echo "Error DB insert : " . $conn->error. " at ".$c."<br/>";
						echo $sqlH;
					}
				$sqlH = "INSERT INTO `rawdata` (`shipID`, `Name`, `flag`, `IMONO`, `portReg`, `owner`, `Source`, `Type`, `length`, `tonnage`) VALUES ";
			}
			$c++;
		}
		if ($num % 2 != 0)
		{
			$sqlH = trim($sqlH,",");
			$sqlH = $sqlH.";";
			if ($conn->query($sqlH) === TRUE)
				echo " ";
			else
				echo "Error DB insert : " . $conn->error;
		}
//	
		reset($data);
		$c = 1;
		$sqlH = "INSERT INTO `license` (`shipID`, `Issue`, `authNo`, `Date`, `Due`, `method`) VALUES ";
		foreach  ($data as $row)
		{
			$Sval = "(\"". $row[4].$row[23].""  . "\",\"IOTC\",\"". $row[5] . "\",\"". $row[19] . "\",\"". $row[20] . "\",\"". $row[2] ."\")";
			$sqlH = $sqlH.$Sval.",";
		
			if ($c % 256 == 0)
			{				
				$sqlH = trim($sqlH,",");
				$sqlH = $sqlH.";";
		//		echo $sqlH;
				
				if ($conn->query($sqlH) === TRUE)
					echo "New record created successfully<br/>";
					else {
						echo "Error DB insert : " . $conn->error. " at ".$c."<br/>";
						echo $sqlH;
					}
				$sqlH = "INSERT INTO `license` (`shipID`, `Issue`, `authNo`, `Date`, `Due`, `method`) VALUES ";
			}
			$c++;
		}
		if ($num % 256 != 0)
		{
			$sqlH = trim($sqlH,",");
			$sqlH = $sqlH.";";
			if ($conn->query($sqlH) === TRUE)
				echo "New record created successfully<br/>";
			else
				echo "Error DB insert : " . $conn->error;
		}
	}
	$id = 25;
	$sqlH = "UPDATE `dbsource` SET `record` = '".$num."', `Udate` = '".date('Y-m-d H:i:s')."' WHERE `dbsource`.`id` = ".$id.";";
	$conn->query($sqlH);
	
	
	$num = 0;
	$file = fopen("#28 authorised_vessel_record.csv","r");
	$line = fgetcsv($file);	// omit header
	while(! feof($file))
	{
		$line = fgetcsv($file);
		if (is_array($line)) 
		{
			$c=0;
			foreach ($line as $key => $value)
				$data[$num][$c++] = $value;
			$num++;
		}
	}
	fclose($file);

	if ($num != 0)
	{
	
//	
		reset($data);
		$c = 1;
		$sqlH = "INSERT INTO `rawdata` (`shipID`, `Name`, `flag`, `flagNo`, `IRCS`, `IMONO`, `owner`, `Source`, `Type`, `length`, `tonnage`) VALUES ";
		foreach  ($data as $row)
		{
			$Sval = "(\"". $row[2].$row[7]."" . "\",\"". $row[2] ."\",\"". $row[3] . "\",\"". $row[6] . "\",\"". $row[5] . "\",\"". $row[7] . "\",\"". $row[13] . "\",\"28\",\"". $row[9]. "\",\"". $row[11]. "\",\"". $row[8] ."\")";
			$sqlH = $sqlH.$Sval.",";
		
			if ($c % 2 == 0)
			{				
				$sqlH = trim($sqlH,",");
				$sqlH = $sqlH.";";
		//		echo $sqlH;
				
				if ($conn->query($sqlH) === TRUE)
					echo "";
					else {
						echo "Error DB insert : " . $conn->error. " at ".$c."<br/>";
						echo $sqlH;
					}
				$sqlH = "INSERT INTO `rawdata` (`shipID`, `Name`, `flag`, `flagNo`, `IMONO`, `owner`, `Source`, `Type`, `length`, `tonnage`) VALUES ";
			}
			$c++;
		}
		if ($num % 2 != 0)
		{
			$sqlH = trim($sqlH,",");
			$sqlH = $sqlH.";";
			if ($conn->query($sqlH) === TRUE)
				echo " ";
			else
				echo "Error DB insert : " . $conn->error;
		}
//		
		reset($data);
		$c = 1;
		$sqlH = "INSERT INTO `license` (`authType`, `Issue`, `authNo`, `Date`, `Due`, `area`, `method`, `species`) VALUES ";
		foreach  ($data as $row)
		{
			$Sval = "(\"". "" . "\",\"CCSBT\",\"". $row[0] . "\",\"". $row[17] . "\",\"". $row[18] . "\",\"". "" . "\",\"". $row[10] . "\",\"". "" ."\")";
			$sqlH = $sqlH.$Sval.",";
		
			if ($c % 256 == 0)
			{				
				$sqlH = trim($sqlH,",");
				$sqlH = $sqlH.";";
		//		echo $sqlH;
				
				if ($conn->query($sqlH) === TRUE)
					echo "New record created successfully<br/>";
					else {
						echo "Error DB insert : " . $conn->error. " at ".$c."<br/>";
						echo $sqlH;
					}
				$sqlH = "INSERT INTO `license` (`authType`, `Issue`, `authNo`, `Date`, `Due`, `area`, `method`, `species`) VALUES ";
			}
			$c++;
		}
		if ($num % 256 != 0)
		{
			$sqlH = trim($sqlH,",");
			$sqlH = $sqlH.";";
			if ($conn->query($sqlH) === TRUE)
				echo "New record created successfully<br/>";
			else
				echo "Error DB insert : " . $conn->error;
		}
	}	
	$id = 28;
	$sqlH = "UPDATE `dbsource` SET `record` = '".$num."', `Udate` = '".date('Y-m-d H:i:s')."' WHERE `dbsource`.`id` = ".$id.";";
	$conn->query($sqlH);
	
	
		$reader= PHPExcel_IOFactory::createReaderForFile("#23 FULL Black List from Greenpeace Web Site.xls");
		$reader->setReadDataOnly(true);
		$excel= $reader->load("#23 FULL Black List from Greenpeace Web Site.xls");
		$sheet=$excel->getActiveSheet();
			
		$num=0;
		$Brow = 1;
		while ($sheet->getCellByColumnAndRow($Brow,4)->getValue())
		{
			$data[$num][0] = $sheet->getCellByColumnAndRow($Brow,4)->getValue();
			$data[$num][1] = $sheet->getCellByColumnAndRow($Brow,6)->getValue();
			$data[$num][2] = $sheet->getCellByColumnAndRow($Brow,10)->getValue();
			$data[$num][3] = $sheet->getCellByColumnAndRow($Brow,14)->getValue();
			$data[$num][4] = $sheet->getCellByColumnAndRow($Brow,16)->getValue();
			$data[$num][5] = $sheet->getCellByColumnAndRow($Brow,18)->getValue();
			$data[$num][6] = $sheet->getCellByColumnAndRow($Brow,73)->getValue();
			$data[$num][7] = $sheet->getCellByColumnAndRow($Brow,74)->getValue();
			$data[$num][8] = $sheet->getCellByColumnAndRow($Brow,75)->getValue();
	/*		
		3-name
		5-flag
		9-IRCS
		13-IMO
		15-Type
		17-length
		72-issueby
		73-date
		74-note
	*/
			$num++;
			$Brow++;
		}
		$excel->disconnectWorksheets();
	$id = 23;
	$sqlH = "UPDATE `dbsource` SET `record` = '".$num."', `Udate` = '".date('Y-m-d H:i:s')."' WHERE `dbsource`.`id` = ".$id.";";
	$conn->query($sqlH);
	for ($c=0; $c<$num; $c++)
	{
			$sqlH = "SELECT * FROM `rawdata` WHERE `Name` LIKE '".$$data[$c][0]."' OR `IMONO` LIKE '".$data[$c][3]."';";
			$result = $conn->query($sqlH);
			
			$r = $result->fetch_assoc();
			
			if ($r == NULL)
			{
				$sqlH = "INSERT INTO `rawdata` (`shipID`, `Name`, `flag`, `IRCS`, `IMONO`, `Source`, `Type`, `length`) VALUES (\"".$data[$c][0].$data[$c][3]."\", \"".$data[$c][0]."\", \"".$data[$c][1]."\", \"".$data[$c][2]."\", \"".$data[$c][3]."\", \"23\", \"".$data[$c][4]."\", \"".$data[$c][5]."\");";
				$conn->query($sqlH);
				echo $sqlH;
			}
					
			$sqlH = "INSERT INTO `blacklist` (`ShipID`, `Issue`, `Date`, `Note`) VALUES (\"".$data[$c][0].$data[$c][3]."\", \"".$data[$c][6]."\", \"".$data[$c][7]."\", \"".$data[$c][8]."\");";
			$conn->query($sqlH);	
	
	}


?>