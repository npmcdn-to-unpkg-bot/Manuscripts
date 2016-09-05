<?php

/////////////////////////////////////////////////////////// Credits
//
//
//	FBTEE Manuscripts Ingestion Toolkit
//	Digital Humanities Research Group
//  School of Humanities and Communication Arts
//  University of Western Sydney
//
//	Procedural Scripting: PHP | MySQL | JQuery
//
//	FOR ALL ENQUIRIES ABOUT CODE
//
//	Who:	Dr Jason Ensor
//	Email: 	j.ensor@uws.edu.au | jasondensor@gmail.com
//	Mobile: 0419 674 770
//
//  DATA SOURCES
//
//	FBTEE STN Data | http://fbtee.uws.edu.au/
//
//  WEB FRAMEWORK
//
//  Bootstrap Twitter v3.1.1 | http://getbootstrap.com/
//  Font Awesome v4.0.3 | http://fortawesome.github.io/Font-Awesome/
//  Google Fonts API | http://fonts.googleapis.com
//  Modernizr v.2.6.2 | http://modernizr.com/
//  JQuery v.1.11.0 | http://jquery.com/download/
//	JQuery JQPlot v.1.0.8 | http://www.jqplot.com/
//	JQuery UI v.10.4 | https://jqueryui.com/
//
//  VERSION 0.1
//  21 August 2014
//
//
/////////////////////////////////////////////////////////// Clean post and get

	session_start();
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	header("Content-type: text/html;charset=UTF-8");
//	header("Content-type: text/html;charset=ISO-8859-1");
	mb_internal_encoding("UTF-8");
	if (!mysqli_set_charset($mysqli_link, "utf8")) {
		echo "PROBLEM WITH CHARSET!";
		die;
	}
	$query = $_GET["q"];
	$action = $_GET["action"];
	$event = $_GET["event"];
	$table = $_GET["table"];
	$alias = $_GET["alias"];
	
/////////////////////////////////////////////////////////// On if page type is to be Auction rather than Catalogue Work.

	$switch = "y";
	
/////////////////////////////////////////////////////////// Table manuscript_books_editions

	if(($table == "manuscript_books_editions")) {
	
		$recs = array();
		$queryD = "SELECT book_code, ";
		$queryD .= "full_book_title, "; 
		$queryD .= "edition_status, ";
		$queryD .= "actual_publishers, ";
		$queryD .= "actual_publication_places, ";
		$queryD .= "actual_publication_years, ";
		$queryD .= "number_of_volumes, ";
		$queryD .= "edition, ";
		$queryD .= "stated_publishers, ";
		$queryD .= "stated_publication_places, ";
		$queryD .= "stated_publication_years, ";
		$queryD .= "book_code ";
		$queryD .= "FROM manuscript_books_editions WHERE super_book_code = '$query' AND super_book_code != '' ORDER BY stated_publication_years DESC, full_book_title ASC"; 
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
		
			if(($rowD[1] != "") && ($rowD[1] != " ")) { $rowD[1] = "$rowD[1]"; }
			if(($rowD[6] != "") && ($rowD[6] != " ")) { $rowD[6] = "$rowD[6] [vols]"; }
			if(($rowD[7] != "") && ($rowD[7] != " ")) { $rowD[7] = "$rowD[7] [ed]"; }
			if(($rowD[8] != "") && ($rowD[8] != " ")) { $rowD[8] = "$rowD[8] [pub]"; }
			if(($rowD[9] != "") && ($rowD[9] != " ")) { $rowD[9] = "$rowD[9] [place]"; }
			if(($rowD[10] != "") && ($rowD[10] != " ")) { $rowD[10] = "$rowD[10] [year]"; }
			
			$record = "";
			if(($rowD[10] != "") && ($rowD[10] != " ")) { $record .= "$rowD[10]. "; }
			if(($rowD[8] != "") && ($rowD[8] != " ")) { $record .= "$rowD[8]. "; }
			if(($rowD[9] != "") && ($rowD[9] != " ")) { $record .= "$rowD[9]. "; }
			if(($rowD[7] != "") && ($rowD[7] != " ")) { $record .= "$rowD[7]. "; }
			if(($rowD[6] != "") && ($rowD[6] != " ")) { $record .= "$rowD[6]. "; }
			if(($rowD[1] != "") && ($rowD[1] != " ")) { $record .= "$rowD[1]"; }
			
			$author = "";
			$queryX = "SELECT a.author_name FROM manuscript_authors a, manuscript_books_authors b WHERE a.author_code = b.author_code AND b.book_code = \"$rowD[11]\" ";
			$mysqli_resultX = mysqli_query($mysqli_link, $queryX);
			while($rowX = mysqli_fetch_row($mysqli_resultX)) { 
				$author = $rowX[0];
			}
			
			if(($author != "")) {
				$record = $author." [author]. ".$record;
			}
			
			$recs[] = "<option value=\"$rowD[0]\" style=\"text-align:justify; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; white-space: pre-wrap; word-wrap: break-word;\">$record</option>";
			
		}
		
/////////////////////////////////////////////////////////// Display records and linked javascript
		
		$c = count($recs);
		if(($c > 0)) {	
		
?>
	<select class="selectpicker form-control" id="inputEditionName" name="inputEditionName" title="Please Select Edition ..." data-live-search="true" style="z-index: 2000;">
<?php

			foreach($recs as $r) {
				echo $r;	
			}
		
?>
	</select>
<?php

/////////////////////////////////////////////////////////////////// Switcher Catalogue

				if(($switch == "")) {

?>
    <script language="javascript" type="text/javascript" >
		
		$(document).ready(function(e) {
			
			var doG = $('#inputEditionName').selectpicker();
			
			var vax = 0;			
			var archiveSel = $("#inputArchive").val() + ". ";
			if (archiveSel == ". ") { archiveSel = ""; }
			var inspectorOne = $("#inputAgentA option:selected").text() + ". ";
			if (inspectorOne == ". ") { inspectorOne = ""; } else { vax++; }
			var inspectorTwo = $("#inputAgentB option:selected").text() + ". ";
			if (inspectorTwo == ". ") { inspectorTwo = ""; } else { vax++; }
			var inspectorThree = $("#inputAgentC option:selected").text() + ". ";
			if (inspectorThree == ". ") { inspectorThree = ""; }
			var placeValue = $("#inputPlaceName option:selected").text() + ". ";
			if (placeValue == ". ") { placeValue = ""; } else { vax++; }
			var dealerValue = $("#inputDealerName option:selected").text() + ". ";
			if (dealerValue == ". ") { dealerValue = ""; } else { vax++; }
			var editionValue = $("#inputEditionName option:selected").text() + ". ";
			if (editionValue == ". ") { editionValue = ""; } else { vax++; }
			var citationValue = $('#citationValue').html("<h4>Manuscript Details</h4><p>MS " + archiveSel + inspectorOne + inspectorTwo + inspectorThree + placeValue + dealerValue + editionValue + "</p");
			
			if (vax == 4) {
				var disableValue = $('#addEventButton').prop('disabled', false);
			}
			
			var doRunThis = $("#inputEditionName").change(function () {
							
				var vax = 0;			
				var archiveSel = $("#inputArchive").val() + ". ";
				if (archiveSel == ". ") { archiveSel = ""; }
				var inspectorOne = $("#inputAgentA option:selected").text() + ". ";
				if (inspectorOne == ". ") { inspectorOne = ""; } else { vax++; }
				var inspectorTwo = $("#inputAgentB option:selected").text() + ". ";
				if (inspectorTwo == ". ") { inspectorTwo = ""; } else { vax++; }
				var inspectorThree = $("#inputAgentC option:selected").text() + ". ";
				if (inspectorThree == ". ") { inspectorThree = ""; }
				var placeValue = $("#inputPlaceName option:selected").text() + ". ";
				if (placeValue == ". ") { placeValue = ""; } else { vax++; }
				var dealerValue = $("#inputDealerName option:selected").text() + ". ";
				if (dealerValue == ". ") { dealerValue = ""; } else { vax++; }
				var editionValue = $("#inputEditionName option:selected").text() + ". ";
				if (editionValue == ". ") { editionValue = ""; } else { vax++; }
				var citationValue = $('#citationValue').html("<h4>Manuscript Details</h4><p>MS " + archiveSel + inspectorOne + inspectorTwo + inspectorThree + placeValue + dealerValue + editionValue + "</p");
				
				if (vax == 4) {
					var disableValue = $('#addEventButton').prop('disabled', false);
				}
				
			});			
			
		});
		
    </script>
<?php

				} else {

/////////////////////////////////////////////////////////////////// Switcher Auction

?>
    <script language="javascript" type="text/javascript" >
		
		$(document).ready(function(e) {
			
			var doG = $('#inputEditionName').selectpicker();
			
			var vax = 0;			
			var archiveSel = $("#inputArchive").val() + ". ";
			if (archiveSel == ". ") { archiveSel = ""; } else { vax++; }
			var inputSale = $("#inputSale option:selected").text() + ". ";
			if (inputSale == ". ") { inputSale = ""; } else { inputSale = "<strong>" + inputSale + "</strong>"; vax++; }
			var inputSaleReason = $("input[name=inputSaleReason]:checked").attr("data-scenario") + ". ";
			if (inputSaleReason == ". ") { inputSaleReason = ""; } else { inputSaleReason = "<em>" + inputSaleReason + "</em>"; vax++; }
			var inputAgents = $("#inputAgents").val();
			var dealerValue = "Purchaser: " + $("#inputDealerName option:selected").text() + ". ";
			if (dealerValue == "Purchaser: . ") { dealerValue = ""; } else { dealerValue = "<strong>" + dealerValue + "</strong>"; vax++; }
			var editionValue = $("#inputEditionName option:selected").text() + ". ";
			if (editionValue == ". ") { editionValue = ""; } else { editionValue = "<em>" + editionValue + "</em>"; vax++; }
			var citationValue = $('#citationValue').html("<h4>Manuscript Details</h4><p>MS " + archiveSel + inputSale + inputSaleReason + inputAgents + dealerValue + editionValue + "</p>");
			if (vax > 3) { var disableValue = $('#addEventButton').prop('disabled', false); }
			
			var doRunThis = $("#inputEditionName").change(function () {
				var vax = 0;			
				var archiveSel = $("#inputArchive").val() + ". ";
				if (archiveSel == ". ") { archiveSel = ""; } else { vax++; }
				var inputSale = $("#inputSale option:selected").text() + ". ";
				if (inputSale == ". ") { inputSale = ""; } else { inputSale = "<strong>" + inputSale + "</strong>"; vax++; }
				var inputSaleReason = $("input[name=inputSaleReason]:checked").attr("data-scenario") + ". ";
				if (inputSaleReason == ". ") { inputSaleReason = ""; } else { inputSaleReason = "<em>" + inputSaleReason + "</em>"; vax++; }
				var inputAgents = $("#inputAgents").val();
				var dealerValue = "Purchaser: " + $("#inputDealerName option:selected").text() + ". ";
				if (dealerValue == "Purchaser: . ") { dealerValue = ""; } else { dealerValue = "<strong>" + dealerValue + "</strong>"; vax++; }
				var editionValue = $("#inputEditionName option:selected").text() + ". ";
				if (editionValue == ". ") { editionValue = ""; } else { editionValue = "<em>" + editionValue + "</em>"; vax++; }
				var citationValue = $('#citationValue').html("<h4>Manuscript Details</h4><p>MS " + archiveSel + inputSale + inputSaleReason + inputAgents + dealerValue + editionValue + "</p>");
				if (vax > 3) { var disableValue = $('#addEventButton').prop('disabled', false); }	
			});			
			
		});
		
    </script>
<?php

				}

/////////////////////////////////////////////////////////////////// Switcher End
	
		} else {
			echo "<input type=\"text\" class=\"selectpicker form-control\" id=\"inputEditionName\" name=\"inputEditionName\" placeholder=\"No Editions Available\" disabled>";		
		}
	}

/////////////////////////////////////////////////////////// Table manuscript_agents_inspectors

	if(($table == "manuscript_agents_inspectors")) {
		
		$recs = array();
		$queryD = "SELECT * FROM manuscript_agents_inspectors WHERE msNumber = \"$query\" OR msNumber = \"\" ORDER BY Agent_Name ASC"; 
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$recs[] = "<option value=\"$rowD[1]\" style=\"text-align:justify; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; white-space: pre-wrap; word-wrap: break-word;\">$rowD[2] ($rowD[8])</option>";
		}
		$c = count($recs);
		if(($c > 0)) {	
		
?>
	<select class="selectpicker form-control" id="inputAgent<?php echo $alias; ?>" title="Please Select Inspector or Adjoint <?php echo $alias; ?>" data-live-search="true" style="z-index: 999;">
		<option data-hidden="true"></option>
<?php

		foreach($recs as $r) {
			echo $r;	
		}
		
?>
	</select>
    <script language="javascript" type="text/javascript" >
		
		$(document).ready(function(e) {
		
			var do<?php echo $alias; ?> = $('#inputAgent<?php echo $alias; ?>').selectpicker();
			
			var doRunThis = $("#inputAgent<?php echo $alias; ?>").change(function () {
							
				var vax = 0;			
				var archiveSel = $("#inputArchive").val() + ". ";
				if (archiveSel == ". ") { archiveSel = ""; }
				var inspectorOne = $("#inputAgentA option:selected").text() + ". ";
				if (inspectorOne == ". ") { inspectorOne = ""; } else { vax++; }
				var inspectorTwo = $("#inputAgentB option:selected").text() + ". ";
				if (inspectorTwo == ". ") { inspectorTwo = ""; } else { vax++; }
				var inspectorThree = $("#inputAgentC option:selected").text() + ". ";
				if (inspectorThree == ". ") { inspectorThree = ""; }
				var placeValue = $("#inputPlaceName option:selected").text() + ". ";
				if (placeValue == ". ") { placeValue = ""; } else { vax++; }
				var dealerValue = $("#inputDealerName option:selected").text() + ". ";
				if (dealerValue == ". ") { dealerValue = ""; } else { vax++; }
				var editionValue = $("#inputEditionName option:selected").text() + ". ";
				if (editionValue == ". ") { editionValue = ""; } else { vax++; }
				var citationValue = $('#citationValue').html("<h4>Manuscript Details</h4><p>MS " + archiveSel + inspectorOne + inspectorTwo + inspectorThree + placeValue + dealerValue + editionValue + "</p");
				
				if (vax == 4) {
					var disableValue = $('#addEventButton').prop('disabled', false);
				}
				
			});
			
		});
		
    </script> 
<?php

		} else {
			$ac = "1";
			if(($alias == "B")) { $ac = "2"; }
			echo "<input type=\"text\" class=\"form-control\" id=\"inputAuditor".$ac."\" placeholder=\"Inspector or Adjoint ".$alias."\" disabled>";	
		}
	}
	
/////////////////////////////////////////////////////////// Table manuscript_places

	if(($table == "manuscript_places")) {

		$recs = array();
		$queryD = "SELECT * FROM manuscript_places WHERE msNumber = \"$query\" ORDER BY Place_Name ASC"; 
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$recs[] = "<option value=\"$rowD[2]\" style=\"text-align:justify; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; white-space: pre-wrap; word-wrap: break-word;\">$rowD[3]</option>";
		}
		$c = count($recs);
		if(($c > 0)) {
				
?>
	<select class="selectpicker form-control" id="inputPlaceName" title="Please Select a Place" data-live-search="true" style="z-index: 999;">
		<option data-hidden="true"></option>
<?php

		foreach($recs as $r) {
			echo $r;	
		}
		
?>   
		<option disabled>&nbsp;</option>
	</select>
    <script language="javascript" type="text/javascript" >
		
		$(document).ready(function(e) {
		
			var doG = $('#inputPlaceName').selectpicker();
			var qoD = $("#inputPlaceName").change(function () {
			
				var placeSel = this.value;
				var dataP = 'table=manuscript_dealers&q=' + placeSel;
				var goE = $('#inputDealer').load("./data_agents.php",dataP);
				
				var vax = 0;			
				var archiveSel = $("#inputArchive").val() + ". ";
				if (archiveSel == ". ") { archiveSel = ""; }
				var inspectorOne = $("#inputAgentA option:selected").text() + ". ";
				if (inspectorOne == ". ") { inspectorOne = ""; } else { vax++; }
				var inspectorTwo = $("#inputAgentB option:selected").text() + ". ";
				if (inspectorTwo == ". ") { inspectorTwo = ""; } else { vax++; }
				var inspectorThree = $("#inputAgentC option:selected").text() + ". ";
				if (inspectorThree == ". ") { inspectorThree = ""; }
				var placeValue = $("#inputPlaceName option:selected").text() + ". ";
				if (placeValue == ". ") { placeValue = ""; } else { vax++; }
				var dealerValue = $("#inputDealerName option:selected").text() + ". ";
				if (dealerValue == ". ") { dealerValue = ""; } else { vax++; }
				var editionValue = $("#inputEditionName option:selected").text() + ". ";
				if (editionValue == ". ") { editionValue = ""; } else { vax++; }
				var citationValue = $('#citationValue').html("<h4>Manuscript Details</h4><p>MS " + archiveSel + inspectorOne + inspectorTwo + inspectorThree + placeValue + dealerValue + editionValue + "</p>");
				
				if (vax == 4) {
					var disableValue = $('#addEventButton').prop('disabled', false);
				}
				
			});
		});
		
    </script>
<?php
	
		} else {
			echo "<input type=\"text\" class=\"form-control\" id=\"inputPlaceName\" placeholder=\"Location of Inspection\" disabled>";	
		}
	}
	
/////////////////////////////////////////////////////////// Table manuscript_dealers

	if(($table == "manuscript_dealers")) {

		$recs = array();
		$queryD = "SELECT * FROM manuscript_dealers WHERE Place_Code = \"$query\" ORDER BY Dealer_Name ASC"; 
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$recs[] = "<option value=\"$rowD[1]\" style=\"text-align:justify; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; white-space: pre-wrap; word-wrap: break-word;\">$rowD[2] ($rowD[5])</option>";
		}
		$c = count($recs);
		if(($c > 0)) {
			
?>
	<select class="selectpicker form-control" id="inputDealerName" title="Please Select a Dealer" data-live-search="true" style="z-index: 999;">
		<option data-hidden="true"></option>
<?php

		foreach($recs as $r) {
			echo $r;	
		}
			
?>   
		<option disabled>&nbsp;</option>
	</select>
    <script language="javascript" type="text/javascript" >
		
		$(document).ready(function(e) {
		
			var doG = $('#inputDealerName').selectpicker();
			
			var doRunThis = $("#inputDealerName").change(function () {
							
				var vax = 0;			
				var archiveSel = $("#inputArchive").val() + ". ";
				if (archiveSel == ". ") { archiveSel = ""; }
				var inspectorOne = $("#inputAgentA option:selected").text() + ". ";
				if (inspectorOne == ". ") { inspectorOne = ""; } else { vax++; }
				var inspectorTwo = $("#inputAgentB option:selected").text() + ". ";
				if (inspectorTwo == ". ") { inspectorTwo = ""; } else { vax++; }
				var inspectorThree = $("#inputAgentC option:selected").text() + ". ";
				if (inspectorThree == ". ") { inspectorThree = ""; }
				var placeValue = $("#inputPlaceName option:selected").text() + ". ";
				if (placeValue == ". ") { placeValue = ""; } else { vax++; }
				var dealerValue = $("#inputDealerName option:selected").text() + ". ";
				if (dealerValue == ". ") { dealerValue = ""; } else { vax++; }
				var editionValue = $("#inputEditionName option:selected").text() + ". ";
				if (editionValue == ". ") { editionValue = ""; } else { vax++; }
				var citationValue = $('#citationValue').html("<h4>Manuscript Details</h4><p>MS " + archiveSel + inspectorOne + inspectorTwo + inspectorThree + placeValue + dealerValue + editionValue + "</p>");
				
				if (vax == 4) {
					var disableValue = $('#addEventButton').prop('disabled', false);
				}
				
			});
			
		});
		
    </script>
<?php	

		} else {
			echo "<input type=\"text\" class=\"form-control\" id=\"inputDealerName\" placeholder=\"Bookseller\" disabled>";	
		}
	}

/////////////////////////////////////////////////////////// Finish

	include("./admin/era.dbdisconnect.php");

?>