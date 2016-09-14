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
//	5-7 September 2016
//	14 September 2016
//
//
/////////////////////////////////////////////////////////// Collect session data

	$MerdUser = session_id();
	if(empty($MerdUser)) { session_start(); }
	$SIDmerd = session_id(); 
	
/////////////////////////////////////////////////////////// Clean post and get	
	
	header("Content-type: text/html;charset=UTF-8");
	mb_internal_encoding("UTF-8");
	include("../config.php");
	include("../era.dbconnect.php");
	include("./index_functions.php");
	if (!mysqli_set_charset($mysqli_link, "utf8")) {
		echo "PROBLEM WITH CHARSET!";
		die;
	}
	$letter = $_GET['letter'];
	$action = $_GET['action'];
	$_GET = array();
	$_POST = array();
	
/////////////////////////////////////////////////////////// Get keywords

	echo "<p><strong>$letter</strong></p>";
	echo "<p>";
	$queryD = "SELECT * FROM keywords WHERE keyword LIKE \"$letter%\" ORDER BY keyword ASC";
	$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
	while($rowD = mysqli_fetch_row($mysqli_resultD)) {	
		echo "<a href=\"javascript: ";
		echo "var doThis = tagApi.tagsManager('pushTag','".ucwords($rowD[1])."'); ";
		echo "var dataA = 'kID=".$rowD[0]."&action=yes'; ";
		echo "var doAssA = $('#tagAssociationsList').fadeOut('fast', function(){ ";
		echo "var doAssB = $('#tagAssociationsList').load('./data_keywords_assoc.php',dataA, function(){ ";
		echo "var doAssB = $('#tagAssociationsList').fadeIn('slow'); ";
		echo "}); ";
		echo "}); ";
	//	echo "var fixTip = $(this).on('click', function () { $(this).tooltip('remove'); }); ";
		echo "\" ";
		echo "style=\"color:#FFFFFF;\" ";
		echo "class=\"add-tooltip\" ";
		echo "data-toggle=\"tooltipBG\" ";
		echo "data-container=\"body\" ";
		echo "data-placement=\"left\" ";
		echo "data-original-title=\"";
		echo "<strong>".strtoupper($rowD[1])."</strong><br /><br />$rowD[2]";
		echo "\" ";
		echo ">";	
		echo ucwords($rowD[1]);
		echo "</a>";
		echo "<br />";
	}
	echo "</p>";
?>
	<script language="javascript" type="text/javascript" >
	
		$('[data-toggle="tooltipBG"]').tooltip({
			template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="border: 3px solid #000000; color: #000000; background-color:#FFFFFF; padding:20px;"></div></div>',
			html: true,
			trigger : 'hover'
		});
	
	</script>
<?php
	
/////////////////////////////////////////////////////////// Finish

	include("../era.dbdisconnect.php");

?>