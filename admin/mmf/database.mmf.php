<?php

/////////////////////////////////////////////////////////// Credits
//
//
//	MMF Ingestion Hack
//  School of Humanities and Communication Arts
//  Western Sydney University
//
//	Procedural Scripting: PHP | MySQL
//
//	FOR ALL ENQUIRIES ABOUT CODE
//
//	Who:	Dr Jason Ensor
//	Email: 	j.ensor@westernsydney.edu.au | jasondensor@gmail.com
//	Mobile: +61 419 674 770
//	Web: 	http://www.jasonensor.com
//
//  VERSION 0.1
//  20-21 June 2016
//
//
/////////////////////////////////////////////////////////// Vars

	session_start();
	header("Content-type: text/html;charset=UTF-8");
	mb_internal_encoding("UTF-8");
	include("../config.php");
	include("./mmf.dbconnect.php");
	$readTextFile = "y";
	$fieldNames = array("ID", "Identifier", "Author", "Translator", "Translation", "Title", 
		"Publication_Details", "Location", "References_Contemporary", "References_Post_18C", "Comments", 
		"References_BUR", "Comments_BUR", "Original_Title", "Comments_Translation", "Description", "Princeps_Entry", 
		"First_Text", "Re_Editions", "Final_Comments", "Edition_Identifier", "Edition_Short_Title", 
		"Edition_Long_Title", "Edition_Collection_Title", "Edition_Publication_Details", "Edition_Location", 
		"Edition_Comments");
	if (!mysqli_set_charset($mysqli_link, "utf8")) {
		echo "PROBLEM WITH CHARSET!";
		die;
	}	
	
/////////////////////////////////////////////////////////// Database Mining Refreshment Routines	
	
	$x = 0;
	$sql = "DROP TABLE IF EXISTS `mmf_raw_data`;";
	$mysqli_result = mysqli_query($mysqli_link, $sql);
	$sql = "CREATE TABLE `mmf_raw_data` ( ";
	$sql .= "`ID` INT(25) NOT NULL AUTO_INCREMENT, ";
	$z = (count($fieldNames) - 1);
	foreach($fieldNames as $fN) {
		if(($x > 0)) {
			if(($x != $z)) {
				$sql .= "`$fN` TEXT NOT NULL, ";
			} else {
				$sql .= "`$fN` TEXT NOT NULL, ";
			}
		}
		$x++;	
	}
	$sql .= "PRIMARY KEY (`ID`) ";
	$sql .= ") ";
	$sql .= "ENGINE=InnoDB ";
	$sql .= "DEFAULT CHARSET=utf8; ";
	$mysqli_result = mysqli_query($mysqli_link, $sql);
	echo "<p>Raw Database Refreshed ...</p>";

/////////////////////////////////////////////////////////// Import MMF Text Flat File

	if(($readTextFile == "y")) {
//		echo "<p>";
		$rawFile = "";
		$rawRecords = "";
		$rawFile = file_get_contents('./data/sample.txt');
		$rawFile = trim(preg_replace('/\s+/', ' ', $rawFile));
		$rawRecords = explode("%Start: ", $rawFile);
		foreach($rawRecords as $R) {
			if(($R != "")) {
				$R = trim(preg_replace('/\s+/', ' ', $R));
				$R = str_replace('$ %<', "%<", "$R");
				$R = str_replace('>:$ ', ">", "$R");
				$R = str_replace('>:$', ">", "$R");
				$R = str_replace('>:', ">", "$R");
				$R = str_replace('>$', ">", "$R");
				$R = str_replace('%<', "*PHP-BR*<", "$R");
				$R = str_replace('$ %End:', "", "$R");
				$R = str_replace('$ %Incipit:', "", "$R");
				$R = str_replace('%Incipit:', "", "$R");
				$R = str_replace(' $', "", "$R");
				$R = str_replace('$', "", "$R");
				$R = str_replace('%End:', "", "$R");
				$R = trim(preg_replace('/\s+/', ' ', $R));
				$r_cols = explode('*PHP-BR*', $R);
				$fields = array();
				$x = 0;
				
/////////////////////////////////////////////////////////// Preliminary Text Cleaning Rules Start				
				
				foreach($r_cols as $C) {
					$C = trim($C);
					$r_tabs = explode(">", $C);
					$r_tabs[0] = str_replace("<", "", "$r_tabs[0]");
					$Cr = "<".$r_tabs[0].">";
					$C = str_replace("$Cr", "", "$C");
					$C = str_replace("E%", "É", "$C");
					$C = str_replace("{", "é", "$C");
					$C = str_replace("}", "è", "$C");
					$C = str_replace("e^", "ê", "$C");
					$C = str_replace("a^", "â", "$C");
					$C = str_replace("i^", "î", "$C");
					$C = str_replace("o^", "ô", "$C");
					$C = str_replace("o~", "ö", "$C");
					$C = str_replace("u~", "ü", "$C");
					$C = str_replace("a~", "ä", "$C");
					$C = str_replace("\\", "ç", "$C");
					$C = str_replace("@", "à", "$C");
					$C = str_replace("`", "'", "$C");
					$C = str_replace("\"", "'", "$C");
					$C = str_replace(". .", "..", "$C");
					$C = str_replace("Autres éditions:", "", "$C");
					$C = str_replace("Autre édition:", "", "$C");
					$C = ltrim($C, " ,");
					$C = ltrim($C, "'");
					$C = rtrim($C, "'");
					$C = trim($C);
					$C = ucfirst($C);
					if(($C != "") && ($r_tabs[0] > 0)) {
//						echo "<strong>";
						$e = $r_tabs[0];
						$e = ltrim($e, '0');
//						echo strtoupper($fieldNames["$e"]);
						$temp = $fieldNames["$e"];
						$fields["$temp"] = "$C";
//						echo "</strong> $C<br />";
					}	
				}
//				echo "<br />";
//				echo "* * *";
//				echo "<br /><br />";
//				
/////////////////////////////////////////////////////////// Insert MySQL Record				
				
				$x = 0;
				$sql = "INSERT INTO mmf_raw_data VALUES ( ";
				$sql .= "0, ";
				foreach($fieldNames as $fN) {
					if(($x > 0)) {
						if(($x != $z)) {
							$sql .= "\"".ucfirst(trim($fields["$fN"]))."\", ";
						} else {
							$sql .= "\"".ucfirst(trim($fields["$fN"]))."\" ";
						}
					}
					$x++;	
				}
				$sql .= ");";
				$d++;
				$mysqli_result = mysqli_query($mysqli_link, $sql);
			}
		}
		
/////////////////////////////////////////////////////////// Import MMF Text Flat File	
//		
//		echo "</p>";
		echo "<p>$d Raw Records Created.</p>";
	}
	
/////////////////////////////////////////////////////////// Clone Raw Database for Creating Relational Tables

	$x = 0;	
	$sql = "DROP TABLE IF EXISTS `mmf_revised_data`;";
	$mysqli_result = mysqli_query($mysqli_link, $sql);
	$sql = "CREATE TABLE `mmf_revised_data` ( ";
	$sql .= "`ID` INT(25) NOT NULL AUTO_INCREMENT, ";
	$z = (count($fieldNames) - 1);
	foreach($fieldNames as $fN) {
		if(($x > 0)) {
			if(($x != $z)) {
				$sql .= "`$fN` TEXT NOT NULL, ";
			} else {
				$sql .= "`$fN` TEXT NOT NULL, ";
			}
		}
		$x++;	
	}
	$sql .= "PRIMARY KEY (`ID`) ";
	$sql .= ") ";
	$sql .= "ENGINE=InnoDB ";
	$sql .= "DEFAULT CHARSET=utf8; ";
	$mysqli_result = mysqli_query($mysqli_link, $sql);
	echo "<p>New Database Created ...</p>";	
	
/////////////////////////////////////////////////////////// Clone Raw Data

	$titleDone = "";
	$sql = "SELECT * FROM mmf_raw_data ORDER BY ID ASC";
	$mysqli_result = mysqli_query($mysqli_link, $sql);
	while($row = mysqli_fetch_assoc($mysqli_result)) { 
		$sqlc = "INSERT INTO mmf_revised_data VALUES ( 0, ";
		$x = 0;
		foreach($fieldNames as $fN) {
			if(($x > 0)) {
				if(($x != $z)) {	
					
///////////////////////////////// Explode Title into Short and Long					
					
					if(($fN == "Title")) {
						if(($titleDone != "y")) {
							$sqlb = "ALTER TABLE `mmf_revised_data` ";
							$sqlb .= "ADD `Title_Long` TEXT NOT NULL AFTER `Title`;";
							$mysqli_resultb = mysqli_query($mysqli_link, $sqlb);
							$sqlb = "ALTER TABLE `mmf_revised_data` ";
							$sqlb .= "CHANGE `Title` `Title_Short` ";
							$sqlb .= "TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
							$mysqli_resultb = mysqli_query($mysqli_link, $sqlb);
							$titleDone = "y";
						}
						$temp = $row["$fN"];
						if(preg_match("/Z1/", "$temp")) {
							$temps = explode("Z1","$temp");
							$temps[1] = ltrim($temps[1], " ,");
							$sqlc .= "\"$temps[0]\", \"".ucfirst($temps[1])."\", ";
						} else {
							$sqlc .= "\"$temp\", \"\", ";
						}
					} 

///////////////////////////////// Explode Book Author into Author_Surname and Author_Other_Names

					if(($fN == "Author")) {
						if(($authorDone != "y")) {
							$sqlb = "ALTER TABLE `mmf_revised_data` ";
							$sqlb .= "ADD `Author_Other_Names` TEXT NOT NULL AFTER `Author`;";
							$mysqli_resultb = mysqli_query($mysqli_link, $sqlb);
							$sqlb = "ALTER TABLE `mmf_revised_data` ";
							$sqlb .= "CHANGE `Author` `Author_Surname` ";
							$sqlb .= "TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
							$mysqli_resultb = mysqli_query($mysqli_link, $sqlb);
							$authorDone = "y";
						}
						$temp = $row["$fN"];
						if(preg_match("/,/", "$temp")) {
							$temps = explode(",","$temp");
							$cT = count($temps);
							if(($cT > 1)) {
								$cTT = 0;
								$oNames = "";
								foreach($temps as $tp) {
									if(($cTT != 0)) {
										$oNames .= $tp." ";	
									}
									$cTT++;
								}
								$temps[0] = ucfirst(strtolower($temps[0]));
								$sqlc .= "\"".trim($temps[0])."\", \"".trim($oNames)."\", ";
							} else {
								$sqlc .= "\"".trim($temp)."\", \"\", ";
							}							
						} else {
							$sqlc .= "\"".trim($temp)."\", \"\", ";
						}	
					}
					
///////////////////////////////// Explode Translator Author into Translator_Surname and Translator_Other_Names

					if(($fN == "Translator")) {
						if(($transDone != "y")) {
							$sqlb = "ALTER TABLE `mmf_revised_data` ";
							$sqlb .= "ADD `Translator_Other_Names` TEXT NOT NULL AFTER `Translator`;";
							$mysqli_resultb = mysqli_query($mysqli_link, $sqlb);
							$sqlb = "ALTER TABLE `mmf_revised_data` ";
							$sqlb .= "CHANGE `Translator` `Translator_Surname` ";
							$sqlb .= "TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;";
							$mysqli_resultb = mysqli_query($mysqli_link, $sqlb);
							$transDone = "y";
						}
						$temp = $row["$fN"];
						if(preg_match("/\s/", "$temp")) {
							$cTT = 0;
							$temps = explode(" ","$temp");
							$cT = (count($temps) - 1);
							if(($cT > 0)) {
								$fNames = "";
								foreach($temps as $tp) {
									if(($cTT < $cT)) {
										$fNames .= "$tp ";
									}
									$cTT++;
								}
								$temps[$cT] = ucfirst(strtolower($temps[$cT]));
								$sqlc .= "\"".trim($temps[$cT])."\", \"".trim($fNames)."\", ";
							} else {
								$sqlc .= "\"\", \"\", ";
							}
						} else {
							$sqlc .= "\"".trim($temp)."\", \"\", ";
						}	
					}
					
///////////////////////////////// Explode Identifier into Identifier and Publication Date

					if(($fN == "Identifier")) {
						if(($idDone != "y")) {
							$sqlb = "ALTER TABLE `mmf_revised_data` ";
							$sqlb .= "ADD `Publication_Date` TEXT NOT NULL AFTER `Identifier`;";
							$mysqli_resultb = mysqli_query($mysqli_link, $sqlb);
							$idDone = "y";
						}
						$temp = $row["$fN"];
						if(preg_match("/./", "$temp")) {
							$temps = explode(".","$temp");
							$sqlc .= "\"$temp\", \"".trim($temps[0])."\", ";
						} else {
							$sqlc .= "\"$temp\", \"\", ";
						}
					}
					
///////////////////////////////// All Other Fields					
					
					if(($fN != "Title") && ($fN != "Identifier") && ($fN != "Author") && ($fN != "Translator")) {
						$sqlc .= "\"".$row["$fN"]."\", ";
					}
				} else {
					$sqlc .= "\"".$row["$fN"]."\" ";
				}
			}
			$x++;
		}
		
///////////////////////////////// Close SQL Statement and Parse	
		
		$sqlc .= ");";
		$mysqli_resultc = mysqli_query($mysqli_link, $sqlc);
		$j++;
	}
	echo "<p>$j Cleaned Records Created.</p>";
	
/////////////////////////////////////////////////////////// Finish

	include("./mmf.dbdisconnect.php");

?>