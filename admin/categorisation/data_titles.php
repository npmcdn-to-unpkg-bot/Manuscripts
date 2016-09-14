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
//  15-18 August 2016
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
	$ID = $_GET['ID'];
	$action = $_GET['action'];

/////////////////////////////////////////////////////////// Data routine

	if(!empty($ID)) {
		$book_code = "";
		$edition_status = "";
		$full_book_title = "";
		$languages = "";
		$stated_publishers = "";
		$actual_publishers = "";
		$stated_places = "";
		$actual_places = "";
		$stated_years = "";
		$actual_years = "";
		$research_notes = "";
		$illegality = "";
		$authorIDs = array();
		$authors = "";
		$work = "";
		$found = "";
		$queryD = "SELECT * FROM manuscript_books WHERE super_book_code LIKE \"$ID\"";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) {
			$work = $rowD[1];
			$illegality = $rowD[4];
		}
		if(($work != "")) { 
			echo "<p><strong>Work</strong></p>"; 
			echo "<ul>"; 
			echo "<li>$work</li>"; 
			echo "<li>Book Code: $ID</li>";
			echo "</ul>"; 
			echo "<p>&nbsp;</p>";
		}
		$queryD = "SELECT * FROM manuscript_books_editions WHERE super_book_code LIKE \"$ID\" ORDER BY RAND() LIMIT 1";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) {
			$book_code = $rowD[0];
			$edition_status = $rowD[2];
			$edition_type = $rowD[3];
			$full_book_title = $rowD[4];
			$languages = $rowD[8];
			$stated_publishers = $rowD[9];
			$actual_publishers = $rowD[10];
			$stated_places = $rowD[11];
			$actual_places = $rowD[12];
			$stated_years = $rowD[13];
			$actual_years = $rowD[14];
			$research_notes = $rowD[22];
		}
		if(($book_code != "")) {
			$queryE = "SELECT author_code FROM manuscript_books_authors WHERE book_code = \"$book_code\" ORDER BY author_type ASC";
			$mysqli_resultE = mysqli_query($mysqli_link, $queryE);
			while($rowE = mysqli_fetch_row($mysqli_resultE)) {
				$authorIDs[] = $rowE[0];
			}
			if(($authorIDs[0] != "")) {
				foreach($authorIDs as $a) {
					$queryF = "SELECT author_name FROM manuscript_authors WHERE author_code = \"$a\"";	
					$mysqli_resultF = mysqli_query($mysqli_link, $queryF);
					while($rowF = mysqli_fetch_row($mysqli_resultF)) {
						$authors .= "<li>$rowF[0]</li>";
					}
				}
			}
		}
		if(($full_book_title != "")) { 
			echo "<p><strong>Example Edition</strong></p>"; 
			echo "<ul>"; 
			echo "<li>$full_book_title</li>";
			echo "<li>Edition Code: ".strtoupper($book_code)."</li>";
			if(($illegality != "")) { echo "<li>Illegal Status: $illegality</li>"; }
			echo "</ul>"; 
			echo "<p>&nbsp;</p>";
		}
		if(($authorIDs[0] != "")) { 
			echo "<p><strong>Author(s)</strong></p>"; 
			echo "<ul>$authors</ul>"; 
			echo "<p>&nbsp;</p>";
		}	
		echo "<p><strong>Publication</strong></p>";
		echo "<ul>";
		if(($edition_status != "")) { echo "<li>Edition Status: $edition_status</li>"; $found = "y"; }
		if(($edition_type != "")) { echo "<li>Edition Type: $edition_type</li>"; $found = "y"; }
		if(($languages != "")) { echo "<li>Languages: $languages</li>"; $found = "y"; }
		if(($stated_publishers != "")) { echo "<li>Stated Publisher: $stated_publishers</li>"; $found = "y"; }
		if(($actual_publishers != "")) { echo "<li>Actual Publisher: $actual_publishers</li>"; $found = "y"; }
		if(($stated_places != "")) { echo "<li>Stated Place: $stated_places</li>"; $found = "y"; }
		if(($actual_places != "")) { echo "<li>Actual Place: $actual_places</li>"; $found = "y"; }
		if(($stated_years != "")) { echo "<li>Stated Year: $stated_years</li>"; $found = "y"; }
		if(($actual_years != "")) { echo "<li>Actual Year: $actual_years</li>"; $found = "y"; }
		if(($research_notes != "")) { echo "<li>Research Notes: $research_notes</li>"; $found = "y"; }
		if(($found != "y")) {
			echo "<li>No edition details exist</li>";
		}
		echo "</ul>";
		echo "<p>&nbsp;</p>";
		echo "<p>";
		echo "<a href=\"javascript: ";
		echo "var dataE = 'ID=$ID&action=keywords'; ";
		echo "var doDiv = $('#titleTags').fadeOut('fast', function(){ ";
		echo "var searchVal = $('#titleTags').load('./data_keywords.php',dataE, function(){ ";
		echo "var doDivAlso = $('#titleTags').fadeIn('slow'); ";
		echo "}); ";
		echo "}); ";
		echo "\">";
		if(($action == "yes")) {
			echo "<button class=\"btn btn-block btn-success\">Edit Keywords</button>";
		} else {
			echo "<button class=\"btn btn-block btn-danger\">Add Keywords</button>";
		}
		echo "</a></p>";
		
/////////////////////////////////////////////////////////// Search queries
		
		if(($work != "")) {
			echo "<p>";
			echo "<a href=\"https://www.worldcat.org/search?qt=worldcat_org_bks&q=".$work."&fq=dt%3Abks\" target=\"_querySearch\">";
			echo "<button class=\"btn btn-block btn-primary\">SEARCH WORLDCAT</button>";
			echo "</a></p>";
			echo "<p>";
			echo "<a href=\"https://www.google.com/search?tbm=bks&q=".$work."\" target=\"_querySearch\">";
			echo "<button class=\"btn btn-block btn-info\">SEARCH GOOGLE BOOKS</button>";
			echo "</a></p>";
			echo "<p>";
			echo "<a target=\"_querySearch\" href=\"http://kvk.bibliothek.kit.edu/hylib-bin/kvk/nph-kvk2.cgi?";
			echo "maske=kvk-redesign&lang=en&title=KIT-Bibliothek%3A+Karlsruher+";
			echo "Virtueller+Katalog+KVK+%3A+Ergebnisanzeige&head=%2F%2Fkvk.bibliothek.kit.edu%2Fasset%2Fhtml%2Fhead.html";
			echo "&header=%2F%2Fkvk.bibliothek.kit.edu%2Fasset%2Fhtml%2Fheader.html&spacer=%2F%2Fkvk.bibliothek.kit.edu%2Fasset%2Fhtml%2Fspacer.html&";
			echo "footer=%2F%2Fkvk.bibliothek.kit.edu%2Fasset%2Fhtml%2Ffooter.html&css=none&input-charset=utf-8&ALL=".$work."&TI=&AU=&CI=&ST=";
			echo "&PY=&SB=&SS=&PU=&kataloge=SWB&kataloge=BVB&kataloge=NRW&kataloge=HEBIS&kataloge=HEBIS_RETRO&kataloge=KOBV_SOLR";
			echo "&kataloge=GBV&kataloge=DDB&kataloge=STABI_BERLIN&SCHWEIZ=&kataloge=SWISSBIB&kataloge=HELVETICAT";
			echo "&kataloge=BASEL&kataloge=ETH&kataloge=VKCH_RERO&kataloge=VERBUND_BELGIEN&kataloge=BNF_PARIS";
			echo "&kataloge=COPAC&kataloge=NB_NIEDERLANDE&ref=direct&client-js=yes&inhibit_redirect=1\">";
			echo "<button class=\"btn btn-block btn-mint\">SEARCH KARLSRUHER KATALOG</button>";
			echo "</a></p>";
		}
		
/////////////////////////////////////////////////////////// End		
			
	} else {
        echo "<p>No data was provided. ";
		echo "Please reload the webpage and see if that resolves the problem. ";
		echo "If this issue continues, please contact the developer.</p>";
    }
	
/////////////////////////////////////////////////////////// Finish

	include("../era.dbdisconnect.php");

?>