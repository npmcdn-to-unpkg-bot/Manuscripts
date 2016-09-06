<?php

/////////////////////////////////////////////////////////// Credits
//
//
//	Manuscripts Categorisation Toolkit
//	Digital Humanities Research Group
//  School of Humanities and Communication Arts
//  Western Sydney University
//
//	Procedural Scripting: PHP | MySQL | JQuery
//
//	FOR ALL ENQUIRIES ABOUT CODE
//
//	Who:	Dr Jason Ensor
//	Email: 	j.ensor@westernsydney.edu.au | jasondensor@gmail.com
//	Web: 	http://www.jasonensor.com
//	Mobile: 0419 674 770
//
//  DATA SOURCES
//
//	FBTEE STN Data | http://fbtee.uws.edu.au/
//
//  WEB FRAMEWORK
//
//  Bootstrap Twitter | http://getbootstrap.com/
//	JQuery | http://jquery.com/download/
//	Nifty Responsive Admin Template | https://wrapbootstrap.com/theme/nifty-responsive-admin-template-WB0048JF7
//
//  VERSION 0.1
//	6 September 2016
//
//
/////////////////////////////////////////////////////////// Clean post and get	
	
	if(($reload == "")) {
		$MerdUser = session_id();
		if(empty($MerdUser)) { session_start(); }
		$SIDmerd = session_id();
		header("Content-type: text/html;charset=UTF-8");
		mb_internal_encoding("UTF-8");
		include("../config.php");
		include("../era.dbconnect.php");
		include("./index_functions.php");
		if (!mysqli_set_charset($mysqli_link, "utf8")) {
			echo "PROBLEM WITH CHARSET!";
			die;
		}
		$_GET = array();
		$_POST = array();
	}
	
/////////////////////////////////////////////////////////// Load progress

	$queryD = "SELECT COUNT(*) FROM manuscript_books ";
	$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
	while($rowD = mysqli_fetch_row($mysqli_resultD)) {
		$fullTotal = $rowD[0];
	}
	$queryD = "SELECT COUNT(*) FROM manuscript_books WHERE keywords != \"\" ";
	$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
	while($rowD = mysqli_fetch_row($mysqli_resultD)) {
		$keywordTotal = $rowD[0];
	}
	$progress = round(($keywordTotal / $fullTotal) * 100);
	echo "<div class=\"progress progress-lg\"><div style=\"width: ".$progress."%;\" class=\"progress-bar progress-bar-success\">".$progress."%</div></div>";
	
/////////////////////////////////////////////////////////// Load audit

	$audit = array();
	$timeKeys = array();
	$actualKeys = array();
	$queryD = "SELECT DISTINCT(super_book_code), change_time, new_keywords, new_keywordIDs, MAX(ID) AS maxID ";
	$queryD .= "FROM manuscript_cat_audit GROUP BY super_book_code ORDER BY maxID DESC LIMIT 10";
	$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
	while($rowD = mysqli_fetch_row($mysqli_resultD)) {
		$audit[] = $rowD[0];	
		$timeKeys[] = $rowD[1];
		$actualKeys[] = $rowD[2];
	}
	$b = 0;
	if(($audit[0] != "")) {
		foreach($audit as $t) {
			$queryD = "SELECT * FROM manuscript_books WHERE super_book_code LIKE \"$t\" ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) {
			//	$actualKeys[$b] = preg_replace("/\|/i",", ", "$actualKeys[$b]");
			//	$actualKeys[$b] = trim($actualKeys[$b]);
			//	$actualKeys[$b] = rtrim($actualKeys[$b], ",");
				echo "<div class=\"panel panel-bordered panel-primary mar-top\" ";
				echo "style=\"border: 0px solid 1690F3; background-color: #063D6B;\">";
    			echo "<div class=\"panel-body\">";
				echo "<div id=\"keywordsList\" class=\"text-light text-left mar-top\">";
				echo "<strong>$rowD[1]</strong><br />";
				echo $timeKeys[$b];
				echo "<br /><br />";
				$aks = explode("|","$actualKeys[$b]");
				if(($aks[0] != "")) {	
					foreach($aks as $j) {
						echo "<li>";
						echo "<a href=\"javascript: var doThisZ = tagApi.tagsManager('pushTag','$j');\" ";
						echo "class=\"add-tooltip\" ";
						echo "data-toggle=\"tooltipZ\" ";
						echo "data-container=\"body\" ";
						echo "data-placement=\"left\" ";
						echo "data-original-title=\"Click to assign <strong>".strtoupper($j)."</strong> as a keyword in the left-hand panel.\" ";
						echo ">";
						echo "$j";
						echo "</a></li>";
					}
				}
				echo "<br /><a href=\"javascript: ";
				echo "var dataE = 'ID=$t&action=yes'; ";
				echo "var doDiv = $('#titleDetail').fadeOut('fast', function(){ ";
				echo "var searchVal = $('#titleDetail').load('./data_titles.php',dataE, function(){ ";
				echo "var doDivAlso = $('#titleDetail').fadeIn('slow'); ";
				echo "}); ";
				echo "}); ";
				echo "var doDivA = $('#titleTags').fadeOut('fast', function(){ ";
				echo "var searchValA = $('#titleTags').load('./data_keywords.php','', function(){ ";
				echo "var doDivAlsoA = $('#titleTags').fadeIn('slow'); ";
				echo "}); ";
				echo "}); ";
				echo "\" ";
				echo "style=\"color:#ffffcc;\" ";
				echo "><strong>REVIEW RECORD</strong></a>";
				echo "</div>";
				echo "</div>";
				echo "</div>";
				$b++;
			}
		}
	}
	
/////////////////////////////////////////////////////////// Finish

	include("../era.dbdisconnect.php");

?>
			<script language="javascript" type="text/javascript" >
			
				$('[data-toggle="tooltipZ"]').tooltip({
					template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="border: 3px solid #ffffff; color: #000000; background-color:#6ab5f1; padding:20px;"></div></div>',
					html: true,
					trigger : 'hover'
				});
				
			</script>
