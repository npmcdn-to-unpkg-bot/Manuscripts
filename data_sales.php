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
//  14 July 2015
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
	$queries = explode("|",$query);
	$query = $queries[0];
	$msNumber = $queries[1];
	$action = $_GET["action"];
	$event = $_GET["event"];
	$table = $_GET["table"];
	$alias = $_GET["alias"];
	
/////////////////////////////////////////////////////////// Table manuscript_sales_events msNumber

	if(($alias == "msNumber")) {
		$queryD = "SELECT msNumber FROM manuscript_sales_events WHERE salesNumber = \"$query\" AND msNumber != \"\" "; 
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			echo "<input type=\"hidden\" class=\"form-control\" id=\"inputArchive\" name=\"inputArchive\" value=\"$rowD[0]\">";
			echo "<input type=\"text\" class=\"form-control\" id=\"inputArchiveDisplay\" name=\"inputArchiveDisplay\" value=\""."MS "."$rowD[0]\" readonly>\n";
		}
	}

/////////////////////////////////////////////////////////// Table manuscript_sales_events ID_Purchaser

	if(($alias == "ID_Purchasers")) {
		
		$queryD = "SELECT ID_Purchasers, code, ID_Agent FROM manuscript_sales_events WHERE salesNumber = \"$query\" AND ID_Purchasers != \"\" ";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) {
			$purchaser = $rowD[0];
			$event_code = $rowD[1];	
			$agent = $rowD[2];
		}
		if(preg_match("/,/",$purchaser)) {
			$purchasers = explode(",",$purchaser);
		} else {
			$purchasers = array();
			$purchasers[] = $purchaser;
		}
		echo "<select class=\"selectpicker form-control\" id=\"inputDealerName\" name=\"inputDealerName\" title=\"Please Select Purchaser ...\" data-live-search=\"true\" style=\"z-index: 2000;\">";
		echo "<option data-hidden=\"true\"></option>";
		$dealers = array();
		foreach($purchasers as $p) {
			$queryD = "SELECT * FROM manuscript_dealers WHERE Client_Code = \"$p\" ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) {
				$dealers[] = "<option id=\"$rowD[2]\" value=\"$rowD[1]\" style=\"text-align:justify; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; white-space: pre-wrap; word-wrap: break-word;\">$rowD[2]</option>\n";
			}
		}
		sort($dealers);
		foreach($dealers as $d) {
			echo $d;	
		}
		echo "<option disabled>&nbsp;</option>";
		echo "</select>\n";
		
/////////////////////////////////////////////////////////// Get list of attendees		
		
		if(preg_match("/,/",$agent)) { 
			$agents = explode(",",$agent);
		} else {
			$agents = array();
			$agents[] = $agent;	
		}
		$agentlist = "";
		foreach($agents as $a) {
			if(preg_match("/\(/",$a)) {
				$as = explode(" ",$a);
				$term = "Other";
				$termTemp = $as[0];
				$termType = $as[1];
				$queryD = "SELECT Dealer_Name FROM manuscript_dealers WHERE Client_Code = \"$termTemp\" ";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
				while($rowD = mysqli_fetch_row($mysqli_resultD)) {
					$term = $rowD[0];
				}
				$agentlist .= $term." ".$termType.". ";
			} else {
				$term = "Other";
				$termType = "(Unknown Status)";
				$agentlist .= $term." ".$termType.". ";
			}
		}
		if(($agentlist == "")) {
			$term = "Other";
			$termType = "(Unknown Status)";
			$agentlist .= $term." ".$termType.". ";
		}
		$agentlist = "Attendees: ".$agentlist;
		echo "<input type=\"hidden\" class=\"form-control\" id=\"inputAgentsRaw\" name=\"inputAgents\" value=\"$agent\">";
		echo "<input type=\"hidden\" class=\"form-control\" id=\"inputAgents\" name=\"inputAgents\" value=\"$agentlist\">";

/////////////////////////////////////////////////////////// Run scripts
		
?>
    		<script language="javascript" type="text/javascript" >
		
				$(document).ready(function(e) {
			
					var doG = $('#inputDealerName').selectpicker();
    				var $radios = $('input:radio[name=inputSaleReason]');
        			$radios.filter('[value=<?php echo $event_code; ?>]').prop('checked', true);

					var vax = 0;			
					var archiveSel = "<?php echo $msNumber; ?>" + ". ";
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
					
					var doRunThis = $("#inputDealerName").change(function () {
						var vax = 0;			
						var archiveSel = "<?php echo $msNumber; ?>" + ". ";
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

/////////////////////////////////////////////////////////// Finish

	include("./admin/era.dbdisconnect.php");

?>