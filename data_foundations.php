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
/////////////////////////////////////////////////////////// Vars

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
	$alias = $_GET["alias"];
	$filter = $_GET["filter"];
	$action = $_GET["action"];
	$FIELD = $_GET["FIELD"];
	$TABLE = $_GET["TABLE"];
	$FIELD_SEARCH = $_GET["FIELD_SEARCH"];
	$searchText = $_GET["searchText"];
	$orderBy = $_GET["orderBy"];
	$ID = $_GET["ID"];
	$debug = "n";
	$page_total = $_GET["page_total"];
	$time = time();
	$dateStamp = date("Y-m-d H:i:s",$time);
	
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
	
	if(($action == "DELETE") && ($ID != "") && ($TABLE != "")) {
		$queryD = "DELETE FROM $TABLE WHERE $FIELD = \"$ID\" ";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
	}

/////////////////////////////////////////////////////////// Pagination

	$query_pag_num = "SELECT COUNT(*) AS count FROM $TABLE";
	if(($searchText == "") && ($FIELD != "") && ($filter != "") && ($action != "SEARCH")) {
		$query_pag_num .= " WHERE $FIELD LIKE \"$filter%\" ";
	}
	if(($searchText != "") && ($FIELD_SEARCH != "") && ($action == "SEARCH")) {
		$query_pag_num .= "WHERE $FIELD_SEARCH LIKE \"%$searchText%\" ";
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

/////////////////////////////////////////////////////////// Display records

?>
						<div class="page-header">
                			<h3><?php echo $alias; ?></h3>
              			</div>
                        <p>
                       		<ul class="pagination" style="float:right;">
<?php

	ob_start();

///////////////////////////////// FOR ENABLING THE FIRST BUTTON

	if ($first_btn && $cur_page > 1) {
		echo "<li>";
		echo "<a href=\"javascript: var dataE = 'orderBy=".$orderBy."&FIELD_SEARCH=".$FIELD_SEARCH."&searchText=".$searchText."&FIELD=".$FIELD."&TABLE=".$TABLE."&alias=".$alias."&filter=".$filter."&page=1'; var enameVal = $('#manuscriptRecords').load('./data_foundations.php',dataE);\">";
		echo "First</a></li>";
	} else if ($first_btn) {
    	echo "<li><a href=\"#\" style=\"pointer-events: none; cursor: default; color:#888888; background-color: #efefef;\">First</a></li>";
	}

///////////////////////////////// FOR ENABLING THE PREVIOUS BUTTON

	if ($previous_btn && $cur_page > 1) {
    	$pre = $cur_page - 1;
		echo "<li>";
		echo "<a href=\"javascript: var dataE = 'orderBy=".$orderBy."&FIELD_SEARCH=".$FIELD_SEARCH."&searchText=".$searchText."&FIELD=".$FIELD."&TABLE=".$TABLE."&alias=".$alias."&filter=".$filter."&page=".$pre."'; var enameVal = $('#manuscriptRecords').load('./data_foundations.php',dataE);\">";
		echo "&laquo;</a></li>";
	} else if ($previous_btn) {
    	echo "<li><a href=\"#\" style=\"pointer-events: none; cursor: default; color:#888888; background-color: #efefef;\">&laquo;</a></li>";
	}
	
///////////////////////////////// ALL BUTTON

	echo "<li>";
	echo "<a href=\"javascript: var dataE = 'orderBy=".$orderBy."&FIELD_SEARCH=".$FIELD_SEARCH."&searchText=".$searchText."&FIELD=".$FIELD."&TABLE=".$TABLE."&alias=".$alias."&filter=".$filter."&page=1&page_total=".$count."'; var enameVal = $('#manuscriptRecords').load('./data_foundations.php',dataE);\">";
	echo "ALL</a></li>";

///////////////////////////////// LOOP THROUGH PAGES

	for ($i = $start_loop; $i <= $end_loop; $i++) {
		if ($cur_page == $i) {
        	echo "<li class=\"active\">";
			echo "<a href=\"javascript: var dataE = 'orderBy=".$orderBy."&FIELD_SEARCH=".$FIELD_SEARCH."&searchText=".$searchText."&FIELD=".$FIELD."&TABLE=".$TABLE."&alias=".$alias."&filter=".$filter."&page={$i}'; var enameVal = $('#manuscriptRecords').load('./data_foundations.php',dataE);\">";
			echo "{$i}</a></li>";
    	} else {
        	echo "<li>";
			echo "<a href=\"javascript: var dataE = 'orderBy=".$orderBy."&FIELD_SEARCH=".$FIELD_SEARCH."&searchText=".$searchText."&FIELD=".$FIELD."&TABLE=".$TABLE."&alias=".$alias."&filter=".$filter."&page={$i}'; var enameVal = $('#manuscriptRecords').load('./data_foundations.php',dataE);\">";
			echo "{$i}</a></li>";
		}
	}

///////////////////////////////// TO ENABLE THE NEXT BUTTON

	if ($next_btn && $cur_page < $no_of_paginations) {
    	$nex = $cur_page + 1;
    	echo "<li>";
		echo "<a href=\"javascript: var dataE = 'orderBy=".$orderBy."&FIELD_SEARCH=".$FIELD_SEARCH."&searchText=".$searchText."&FIELD=".$FIELD."&TABLE=".$TABLE."&alias=".$alias."&filter=".$filter."&page=".$nex."'; var enameVal = $('#manuscriptRecords').load('./data_foundations.php',dataE);\">";
		echo "&raquo;</a></li>";
	} else if ($next_btn) {
    	echo "<li><a href=\"#\" style=\"pointer-events: none; cursor: default; color:#888888; background-color: #efefef;\">&raquo;</a></li>";
	}

///////////////////////////////// TO ENABLE THE END BUTTON

	if ($last_btn && $cur_page < $no_of_paginations) {
		$las = $no_of_paginations;
    	echo "<li>";
		echo "<a href=\"javascript: var dataE = 'orderBy=".$orderBy."&FIELD_SEARCH=".$FIELD_SEARCH."&searchText=".$searchText."&FIELD=".$FIELD."&TABLE=".$TABLE."&alias=".$alias."&filter=".$filter."&page=".$las."'; var enameVal = $('#manuscriptRecords').load('./data_foundations.php',dataE);\">";
		echo "Last</a></li>";
	} else if ($last_btn) {
    	echo "<li><a href=\"#\" style=\"pointer-events: none; cursor: default; color:#888888; background-color: #efefef;\">Last</a></li>";
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Start manuscript_books_editions routine

	$nav = ob_get_clean();
	echo $nav;

	if(($TABLE == "manuscript_books_editions")) {

?>
		</ul><br />
		Page <strong><?php echo $cur_page; ?></strong> of <strong><?php echo $no_of_paginations."</strong> &nbsp; [".$count." total records]"; ?>
		<table id="manuscriptTable" class="table table-striped table-bordered" cellspacing="0" width="100%" style="width:100%;">
			<thead>
				<tr>
					<th>Author(s)</th>
            		<th>Title</th>
            		<th>Publisher</th>
					<th>Place</th>
                    <th>Year</th>
        		</tr>
    		</thead>
			<tbody>
<?php

/////////////////////////////////////////////////////////// Start data loop

		$queryD = "SELECT * FROM $TABLE ";
		if(($filter != "") && ($action != "SEARCH")) {
			$queryD .= "WHERE $FIELD LIKE \"$filter%\" ";
		}
		if(($searchText != "") && ($action == "SEARCH")) {
			$queryD .= "WHERE $FIELD_SEARCH LIKE \"%$searchText%\" ";
		}
		if(($orderBy == "")) {
			$queryD .= "ORDER BY $FIELD DESC LIMIT $start, $per_page";
		} else {
			$queryD .= "ORDER BY $orderBy DESC, $FIELD DESC LIMIT $start, $per_page";
		}
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) {
					
			$aname = "";		
			$authors = array();
			$author_names = array();
			$title = "$rowD[4]";
			$publisher = "$rowD[9]";
			$place = "$rowD[11]";
			$year = "$rowD[13]";
							
			$queryE = "SELECT author_code FROM manuscript_books_authors WHERE book_code = \"$rowD[0]\" ORDER BY author_code ASC ";
			$mysqli_resultE = mysqli_query($mysqli_link, $queryE);
			while($rowE = mysqli_fetch_row($mysqli_resultE)) {
				$authors[] = $rowE[0];
			}
			
			foreach($authors as $a) {
				$queryE = "SELECT author_name FROM manuscript_authors WHERE author_code = \"$a\" ";
				$mysqli_resultE = mysqli_query($mysqli_link, $queryE);
				while($rowE = mysqli_fetch_row($mysqli_resultE)) {
					$author_names[] = $rowE[0];
				}
			}
			
			$d = 0;
			$c = count($author_names);
			foreach($author_names as $n) {
				if(($d<$c)) {
					$aname .= $n."; ";
				} else {
					$aname .= $n.".";
				}
			}
							
/////////////////////////////////////////// Records							
							
			$t++;
			$newsbk = "";
			echo "<tr>\n";
			echo "<td $styleColor width=\"15%\">$aname</td>";
			echo "<td $styleColor width=\"50%\">";
			echo "<p style=\"text-align:justify;\">$title</p>";
			
/////////////////////////////////////////// Supplementary Data Start		
			
			echo "<p style=\"font-size: 0.8em; color: #777777;\">EDITION CODE</p>";
			echo "<p style=\"font-size: 0.8em; color: #777777; text-indent:25px;\">".$rowD[0]."</p>";
			echo "<p style=\"font-size: 0.8em; color: #777777;\">SUPER BOOK CODE</p>";
			echo "<p style=\"font-size: 0.8em; color: #777777; text-indent:25px;\">".$rowD[1];
			if(preg_match("/z/i",$rowD[1])) { 
				echo " : <strong><span style=\"color: #EE0000;\">NEW</span></strong>"; 
				$newsbk = "y"; 
			}
			echo "</p>";
			echo "<p style=\"font-size: 0.8em; color: #777777;\">SUPER BOOK TITLE</p>";
			$queryF = "SELECT * FROM manuscript_books WHERE super_book_code = \"".$rowD[1]."\" ORDER BY super_book_title ASC";
			$mysqli_resultF = mysqli_query($mysqli_link, $queryF);
			while($rowF = mysqli_fetch_row($mysqli_resultF)) {
			echo "<p style=\"font-size: 0.8em; color: #777777; margin-left:25px; margin-right:25px;\">";
				echo $rowF[1];
				echo "</p>";
			}
			echo "<p style=\"font-size: 0.8em; color: #777777;\">LINKED EVENT / SALES RECORDS</p>";
			echo "<p style=\"font-size: 0.8em; color: #777777; margin-left:25px; margin-right:25px;\">";
			$queryF = "SELECT * FROM manuscript_events WHERE ID_EditionName = \"".$rowD[0]."\" ORDER BY ID ASC";
			$mysqli_resultF = mysqli_query($mysqli_link, $queryF);
			while($rowF = mysqli_fetch_row($mysqli_resultF)) {
				echo $rowF[0]." (event); ";
			}
			$queryF = "SELECT * FROM manuscript_events_sales WHERE ID_EditionName = \"".$rowD[0]."\" ORDER BY ID ASC";
			$mysqli_resultF = mysqli_query($mysqli_link, $queryF);
			while($rowF = mysqli_fetch_row($mysqli_resultF)) {
				echo $rowF[0]." (sale); ";
			}
			echo "</p>";
			
/////////////////////////////////////////// Supplementary Data End			
			
			echo "</td>";
			echo "<td $styleColor width=\"15%\">$publisher</td>";
			echo "<td $styleColor width=\"15%\"><p style=\"text-align:left;\">$place</p></td>";
			echo "<td $styleColor width=\"5%\"nowrap ><p style=\"text-align:left;\">$year</p></td>";
		//	echo "<td $styleColor nowrap><a href=\"javascript: var dataE = ";
		//	echo "'action=DELETE&ID=$rowD[0]&alias=$alias&filter=$filter&page=$cur_page&orderBy=$orderBy
		//  &FIELD_SEARCH=$FIELD_SEARCH&searchText=$searchText&FIELD=$FIELD&TABLE=$TABLE'; ";
		//	echo "var r = confirm('Are you sure you want to delete this record with ID $rowD[0]?'); ";
		//	echo "if (r == true) { var doThis = $('#manuscriptRecords').load('./data_foundations.php',dataE); }\">";
		//	echo "<i class=\"glyphicon glyphicon-trash\"></i></a></td>";
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// End manuscript_books_editions routine

	}

/////////////////////////////////////////////////////////// Finish

	include("./admin/era.dbdisconnect.php");

?>