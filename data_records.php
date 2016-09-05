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
/////////////////////////////////////////////////////////// Server Vars

	session_start();
	header("Content-type: text/html;charset=UTF-8");
//	header("Content-type: text/html;charset=ISO-8859-1");
	mb_internal_encoding("UTF-8");
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");	
	if (!mysqli_set_charset($mysqli_link, "utf8")) {
		echo "PROBLEM WITH CHARSET!";
		die;
	}
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////// Sale Form Data	
	
	$inputSale = $_GET["inputSale"];								// Sale Form Data
	$inputArchive = $_GET["inputArchive"];
	$inputPlaceName = $_GET["inputPlaceName"];
	$inputDealerName = $_GET["inputDealerName"];
	$inputAgents = $_GET["inputAgents"];							// Sale Form Data
	$inputAgentsRaw = $_GET["inputAgentsRaw"];						// Sale Form Data
	$event_location = $_GET["event_location"];
	$inputSaleReason = $_GET["inputSaleReason"];					// Sale Form Data
	$inputEvent = $_GET["inputEvent"];
	$manuscript_folio = $_GET["manuscript_folio"];
	$manuscript_article = $_GET["manuscript_article"];
	$event_date = $_GET["event_date"];
	$inputSuperBookTitle = $_GET["inputSuperBookTitle"];
	$inputEditionName = $_GET["inputEditionName"];	
	$manuscript_notes = $_GET["manuscript_notes"];
	$manuscript_othernotes = $_GET["manuscript_othernotes"];		// Sale Form Data
	$manuscript_morenotes = $_GET["manuscript_morenotes"];			// Sale Form Data
	$manuscript_page_stamped = $_GET["manuscript_page_stamped"];	
	$manuscript_copies = $_GET["manuscript_copies"];
	$manuscript_copies_type = $_GET["manuscript_copies_type"];		// Sale Form Data
	$manuscript_lotprice = $_GET["manuscript_lotprice"];			// Sale Form Data
	$manuscript_volnotes = $_GET["manuscript_volnotes"];			// Sale Form Data

///////////////////////////////////////////////////////////////////////////////////////////////////////////////// Catalogue Form Data
	
	$inputAgentA = $_GET["inputAgentA"];							// NOT USED IN SALE FORM DATA
	$inputAgentB = $_GET["inputAgentB"];							// NOT USED IN SALE FORM DATA
	$inputAgentC = $_GET["inputAgentC"];							// NOT USED IN SALE FORM DATA
	$event_notes = $_GET["event_notes"];							// NOT USED IN SALE FORM DATA
	$event_vols = $_GET["event_vols"];								// NOT USED IN SALE FORM DATA

///////////////////////////////////////////////////////////////////////////////////////////////////////////////// Records Navigation Vars
	
	$searchText = $_GET["searchText"];
	$alias = $_GET["alias"];
	$citationValue = $_GET["citationValue"];
	$action = $_GET["action"];
	$ID = $_GET["ID"];
	$filter = $_GET["filter"];
	$page_total = $_GET["page_total"];
	$debug = "n";
	$time = time();
	$dateStamp = date("Y-m-d H:i:s",$time);
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////// Search manipulations

	$doSearchTexts = "";
	$searchTexts = explode(" ",$searchText);
	if(count($searchTexts) > 1) {
		$j = 0;
		$z = count($searchTexts);
		$doSearchTexts = " WHERE ("; 
		foreach($searchTexts as $sts) {
			$j++;
			if(($j != $z)) {
				$doSearchTexts .= "EventCitation LIKE \"%$sts%\" AND ";
			} else {
				$doSearchTexts .= "EventCitation LIKE \"%$sts%\"";
			}
		}
		$doSearchTexts .= ") ";
	}
	
/////////////////////////////////////////////////////////// Get user details	
	
	function get_client_ip() {
    $ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
    	    $ipaddress = getenv('HTTP_CLIENT_IP');
    	else if(getenv('HTTP_X_FORWARDED_FOR'))
    	    $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    	else if(getenv('HTTP_X_FORWARDED'))
    	    $ipaddress = getenv('HTTP_X_FORWARDED');
    	else if(getenv('HTTP_FORWARDED_FOR'))
    	    $ipaddress = getenv('HTTP_FORWARDED_FOR');
    	else if(getenv('HTTP_FORWARDED'))
    	   $ipaddress = getenv('HTTP_FORWARDED');
    	else if(getenv('REMOTE_ADDR'))
    	    $ipaddress = getenv('REMOTE_ADDR');
    	else
    	    $ipaddress = 'UNKNOWN';
    	return $ipaddress;
	}	
	$browser = $_SERVER['HTTP_USER_AGENT'];
	$user = get_client_ip();
	
/////////////////////////////////////////////////////////// Pagination vars	
	
	if((!$_GET['page'])) {
		$page = 1;
	} else {	
		$page = $_GET['page'];
	}
	$cur_page = $page;
	$page -= 1;
	if(($page_total == "") or ($page_total == "0")) {
		$per_page = 30;
	} else {
		$per_page = $page_total;
	}
	$previous_btn = true;
	$next_btn = true;
	$first_btn = true;
	$last_btn = true;
	$start = $page * $per_page;

/////////////////////////////////////////////////////////// Data debug
	
	if(($debug == "y")) {
		foreach($_GET as $key => $value) {
			echo "$key : $value <br ><br >";
		}	
	}	
	
/////////////////////////////////////////////////////////// Data routines

	if(($inputSuperBookTitle != "") && ($action == "ADD")) {
		$lastID = "";
		$queryD = "INSERT INTO manuscript_events VALUES (0, \"$inputArchive\", \"$inputAgentA\", \"$inputAgentB\", ";
		$queryD .= "\"$inputAgentC\", \"$inputPlaceName\", \"$inputDealerName\", \"$inputSuperBookTitle\", \"$inputEditionName\", \"$inputEvent\", ";
		$queryD .= "\"$manuscript_copies\", \"$manuscript_folio\", \"$event_date\", \"$citationValue\", \"$manuscript_page_stamped\", \"$manuscript_notes\", ";
		$queryD .= "\"$manuscript_article\", \"$event_notes\", \"$event_vols\", \"$event_location\", \"$dateStamp\", \"$user|$browser\")";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);	
		$queryE = "SELECT LAST_INSERT_ID()";
		$mysqli_resultE = mysqli_query($mysqli_link, $queryE);
		while($rowE = mysqli_fetch_row($mysqli_resultE)) {
			$lastID = $rowE[0];
		}
	}
	
	if(($action == "DELETE") && ($ID != "")) {
		$queryD = "DELETE FROM manuscript_events WHERE ID = \"$ID\" ";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
	}

/////////////////////////////////////////////////////////// Pagination

	$query_pag_num = "SELECT COUNT(*) AS count FROM manuscript_events";
	if(($filter != "") && ($searchText == "")) {
		$query_pag_num .= " WHERE EventType = \"$filter\" ";
	}
	if(($searchText != "")) {
		if(($doSearchTexts == "")) {
			$query_pag_num .= " WHERE EventCitation LIKE \"%$searchText%\" ";
		} else {
			$query_pag_num .= $doSearchTexts;
		}
	}
	$result_pag_num = mysqli_query($mysqli_link, $query_pag_num);
	$rowPage = mysqli_fetch_array($result_pag_num);
	$count = $rowPage['count'];
	$no_of_paginations = ceil($count / $per_page);
	
	if ($cur_page >= 7) {
    	$start_loop = $cur_page - 3;
    	if ($no_of_paginations > $cur_page + 3) {
        	$end_loop = $cur_page + 3;
    	} else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
        	$start_loop = $no_of_paginations - 6;
        	$end_loop = $no_of_paginations;
    	} else {
        	$end_loop = $no_of_paginations;
    	}
	} else {
    	$start_loop = 1;
    	if ($no_of_paginations > 7) {
       		$end_loop = 7;
    	} else {
        	$end_loop = $no_of_paginations;
		}
	}

/////////////////////////////////////////////////////////// Display recods

?>
						<div class="page-header">
                			<h3><?php echo $alias; echo " (Stampings)"; if(($searchText)) { echo " : ".$searchText; } ?></h3>
              			</div>
                        <p>
                       		<ul class="pagination" style="float:right;">
<?php

	ob_start();

///////////////////////////////// FOR ENABLING THE FIRST BUTTON

	if ($first_btn && $cur_page > 1) {
		echo "<li>";
		echo "<a href=\"javascript: ";
		echo "var dataE = 'alias=".$alias."&filter=".$filter."&page=1&searchText=".$searchText."&action=".$action."'; ";
		echo "var enameVal = $('#manuscriptRecords').load('./data_records.php',dataE);\">";
		echo "First</a></li>";
	} else if ($first_btn) {
    	echo "<li><a href=\"#\" style=\"pointer-events: none; cursor: default; color:#888888; background-color: #efefef;\">First</a></li>";
	}

///////////////////////////////// FOR ENABLING THE PREVIOUS BUTTON

	if ($previous_btn && $cur_page > 1) {
    	$pre = $cur_page - 1;
		echo "<li>";
		echo "<a href=\"javascript: ";
		echo "var dataE = 'alias=".$alias."&filter=".$filter."&page=".$pre."&searchText=".$searchText."&action=".$action."'; ";
		echo "var enameVal = $('#manuscriptRecords').load('./data_records.php',dataE);\">";
		echo "&laquo;</a></li>";
	} else if ($previous_btn) {
    	echo "<li><a href=\"#\" style=\"pointer-events: none; cursor: default; color:#888888; background-color: #efefef;\">&laquo;</a></li>";
	}

///////////////////////////////// ALL BUTTON

	echo "<li>";
	echo "<a href=\"javascript: ";
	echo "var dataE = 'orderBy=".$orderBy."&FIELD_SEARCH=".$FIELD_SEARCH."&searchText=".$searchText."&FIELD=".$FIELD."&TABLE=".$TABLE."&alias=".$alias."&filter=".$filter."&page=1&page_total=".$count."'; ";
	echo "var enameVal = $('#manuscriptRecords').load('./data_records.php',dataE);\">";
	echo "ALL</a></li>";

///////////////////////////////// LOPP THROUGH PAGES

	for ($i = $start_loop; $i <= $end_loop; $i++) {
		if ($cur_page == $i) {
        	echo "<li class=\"active\">";
			echo "<a href=\"javascript: ";
			echo "var dataE = 'alias=".$alias."&searchText=".$searchText."&action=".$action."&filter=".$filter."&page={$i}'; ";
			echo "var enameVal = $('#manuscriptRecords').load('./data_records.php',dataE);\">";
			echo "{$i}</a></li>";
    	} else {
        	echo "<li>";
			echo "<a href=\"javascript: ";
			echo "var dataE = 'alias=".$alias."&searchText=".$searchText."&action=".$action."&filter=".$filter."&page={$i}'; ";
			echo "var enameVal = $('#manuscriptRecords').load('./data_records.php',dataE);\">";
			echo "{$i}</a></li>";
		}
	}

///////////////////////////////// TO ENABLE THE NEXT BUTTON

	if ($next_btn && $cur_page < $no_of_paginations) {
    	$nex = $cur_page + 1;
    	echo "<li>";
		echo "<a href=\"javascript: ";
		echo "var dataE = 'alias=".$alias."&filter=".$filter."&page=".$nex."&searchText=".$searchText."&action=".$action."'; ";
		echo "var enameVal = $('#manuscriptRecords').load('./data_records.php',dataE);\">";
		echo "&raquo;</a></li>";
	} else if ($next_btn) {
    	echo "<li><a href=\"#\" style=\"pointer-events: none; cursor: default; color:#888888; background-color: #efefef;\">&raquo;</a></li>";
	}

///////////////////////////////// TO ENABLE THE END BUTTON

	if ($last_btn && $cur_page < $no_of_paginations) {
		$las = $no_of_paginations;
    	echo "<li>";
		echo "<a href=\"javascript: ";
		echo "var dataE = 'alias=".$alias."&filter=".$filter."&page=".$las."&searchText=".$searchText."&action=".$action."'; ";
		echo "var enameVal = $('#manuscriptRecords').load('./data_records.php',dataE);\">";
		echo "Last</a></li>";
	} else if ($last_btn) {
    	echo "<li><a href=\"#\" style=\"pointer-events: none; cursor: default; color:#888888; background-color: #efefef;\">Last</a></li>";
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// End navigation matrix creation

	$nav = ob_get_clean();
	echo $nav;

?>
							</ul><br />
							Page <b><?php echo $cur_page; ?></b> of <b><?php echo $no_of_paginations; ?></b>
							<table id="manuscriptTable" class="table table-striped table-bordered" cellspacing="0" width="100%" style="width:100%;">
    							<thead>
        							<tr>
                                    	<th>#</th>
            							<th>MS</th>
            							<th>Agent(s)</th>
										<th>Place</th>
            							<th>Dealer</th>
            							<th>Title</th>
										<th>Copies</th>
                                        <th>Date</th>
            							<th>?</th> 
        							</tr>
    							</thead>
                                <tbody>
<?php

/////////////////////////////////////////////////////////// Start data loop

						$queryD = "SELECT * FROM manuscript_events ";
						if(($filter != "") && ($action != "SEARCH")) {
							$queryD .= "WHERE EventType = \"$filter\" ";
						}
						if(($searchText != "") && ($action == "SEARCH")) {
							if(($doSearchTexts == "")) {
								$queryD .= "WHERE EventCitation LIKE \"%$searchText%\" ";
							} else {
								$queryD .= $doSearchTexts;
							}
						}
						if(($orderBy == "")) {
							$queryD .= "ORDER BY ID DESC LIMIT $start, $per_page";
						} else {
							$queryD .= "ORDER BY $orderBy DESC, ID DESC LIMIT $start, $per_page";
						}						
						$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
						while($rowD = mysqli_fetch_row($mysqli_resultD)) {
							
							$place = "";
							$dealer = "";
							$event = "";
							$book = "";
							$agents = "";
							$citationFull = stripslashes($rowD[13]);
							
							$queryE = "SELECT Place_Name FROM manuscript_places WHERE Place_Code = \"$rowD[5]\" LIMIT 1";
							$mysqli_resultE = mysqli_query($mysqli_link, $queryE);
							while($rowE = mysqli_fetch_row($mysqli_resultE)) {
								$place = $rowE[0];
							}
							
							$queryE = "SELECT Dealer_Name FROM manuscript_dealers WHERE Client_Code = \"$rowD[6]\" LIMIT 1";
							$mysqli_resultE = mysqli_query($mysqli_link, $queryE);
							while($rowE = mysqli_fetch_row($mysqli_resultE)) {
								$dealer = $rowE[0];
							}
							
							$queryE = "SELECT super_book_title FROM manuscript_books WHERE super_book_code = \"$rowD[7]\" LIMIT 1";
							$mysqli_resultE = mysqli_query($mysqli_link, $queryE);
							while($rowE = mysqli_fetch_row($mysqli_resultE)) {
								$book = $rowE[0];
							}
							
							$queryE = "SELECT Agent_Name FROM manuscript_agents_inspectors WHERE Client_Code = \"$rowD[2]\" LIMIT 1";
							$mysqli_resultE = mysqli_query($mysqli_link, $queryE);
							while($rowE = mysqli_fetch_row($mysqli_resultE)) {
								$agents = $rowE[0];
							}
							
							if(($rowD[3] != "")) {
								$queryE = "SELECT Agent_Name FROM manuscript_agents_inspectors WHERE Client_Code = \"$rowD[3]\" LIMIT 1";
								$mysqli_resultE = mysqli_query($mysqli_link, $queryE);
								while($rowE = mysqli_fetch_row($mysqli_resultE)) {
									$agents .= "; ".$rowE[0];
								}
							}
							
							if(($rowD[4] != "")) {
								$queryE = "SELECT Agent_Name FROM manuscript_agents_inspectors WHERE Client_Code = \"$rowD[4]\" LIMIT 1";
								$mysqli_resultE = mysqli_query($mysqli_link, $queryE);
								while($rowE = mysqli_fetch_row($mysqli_resultE)) {
									$agents .= "; ".$rowE[0];
								}
							}
							
							if(($rowD[9] == "Stamped")) { $event = "<strong><span style=\"color: #258fb2;\">S</span></strong>"; }
							if(($rowD[9] == "Confiscation")) { $event = "<strong><span style=\"color: #cf6d2d;\">C</span></strong>"; }
							if(($rowD[9] == "Print Permission")) { $event = "<strong><span style=\"color: #28af26;\">P</span></strong>"; }
							if(($lastID == $rowD[0])) { $styleColor = "class=\"success\""; } else { $styleColor = ""; } 
							
/////////////////////////////////////////// Main record							
							
							$t++;
							echo "<tr>\n";
							echo "<td $styleColor >$event</td>";
							echo "<td $styleColor nowrap>$rowD[1]</td>";
							echo "<td $styleColor width=\"25%\"><p style=\"text-align:left;\">$agents</p></td>";
							echo "<td $styleColor >$place</td>";
							echo "<td $styleColor ><p style=\"text-align:left;\">$dealer</p></td>";
							echo "<td $styleColor ><p style=\"text-align:left;\">$book</p></td>";
							
							echo "<td $styleColor nowrap style=\"text-align: right;\">";
							echo "<a href=\"#\" style=\"color: #888888;\" ";
							echo "id=\"updateEventCopies\" ";
							echo "class=\"dataEdit\" ";
							echo "data-type=\"text\" ";
							echo "data-value=\"$rowD[10]\" ";
							echo "data-pk=\"$rowD[0]\" ";
							echo "data-url=\"./data_ajax.php\" ";
							echo "data-title=\"Enter Copies\" >";
							echo "$rowD[10]";
							echo "</a>";
							echo "</td>";
							
							echo "<td $styleColor nowrap>";
							echo "<a href=\"#\" style=\"color: #888888;\" ";
							echo "id=\"updateEventDate\" ";
							echo "class=\"dataEdit\" ";
							echo "data-type=\"text\" ";
							echo "data-value=\"$rowD[12]\" ";
							echo "data-pk=\"$rowD[0]\" ";
							echo "data-url=\"./data_ajax.php\" ";
							echo "data-title=\"Select Date of Event\" >";
							echo "$rowD[12]";
							echo "</a>";
							echo "</td>";
							
							echo "<td $styleColor ><a href=\"javascript: var dataE = ";
							echo "'action=DELETE&ID=$rowD[0]&alias=$alias&filter=$filter&page=$cur_page'; ";
							echo "var r = confirm('Are you sure you want to delete the event record with ID $rowD[0]?'); ";
							echo "if (r == true) { var doThis = $('#manuscriptRecords').load('./data_records.php',dataE); }\">";
							echo "<i class=\"glyphicon glyphicon-trash\"></i></a></td>";
							echo "</tr>\n";
							
/////////////////////////////////////////// Supplementary record data	

							$dateEntered = $rowD[20];
							$eFolio = $rowD[11];
							$eArticle = $rowD[16];
							$eNote = $rowD[15];
							$eOther = $rowD[17];
							$ePageStamped = $rowD[14];
							$eVolsNote = $rowD[18];
							
							echo "<tr>\n";
							echo "<td colspan=\"3\" style=\"font-size:0.9em; color: #569669; text-align:right;\">";
							echo "<span style=\"color: #9b5972;\">Event Record ID $rowD[0]</span>";
							echo "<br />$dateEntered";
							echo "<br />";
							if(($eFolio != "")) { 
								echo "Folio $eFolio "; 
							}
							if(($eArticle != "")) { 
								echo "Article $eArticle "; 
							}
							echo "</td>";
							echo "<td colspan=\"6\" style=\"font-size:0.9em; color: #569669;\">$citationFull ";
							if(($eNote != "")) { 
								echo "Notes: $eNote $eOther $eVolsNote"; 
							}
							echo "<br />&nbsp;</td>";
							echo "</tr>\n";						
							
						}

/////////////////////////////////////////////////////////// End data loop						
						
?>
    							</tbody>
							</table>
                         </p>
                         <ul class="pagination" style="float:right;"><?php echo $nav; ?></ul>
						<p>&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;</p>
						<script language="javascript" type="text/javascript" >

							$(document).ready(function(e) {
								$.fn.editable.defaults.mode = 'popover';
								var goC = $('.dataEdit').editable({
									placement: 'left'
								});
							});
		
						</script>
<?php

//	echo "Debug : GET ARRAY<br /><br />";
//	foreach($_GET as $key => $value) {
//    	echo "$key : $value<br />";
//	}

/////////////////////////////////////////////////////////// Finish

	include("./admin/era.dbdisconnect.php");

?>