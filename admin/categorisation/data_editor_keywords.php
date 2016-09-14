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
//	9 September 2016
//	14 September 2016
//
//
/////////////////////////////////////////////////////////// Collect session data

	$MerdUser = session_id();
	if(empty($MerdUser)) { session_start(); }
	$SIDmerd = session_id(); 
	
/////////////////////////////////////////////////////////// Clean post and get	
	
	mb_internal_encoding("UTF-8");
	include("../config.php");
	include("../era.dbconnect.php");
	include("./index_functions.php");
	if (!mysqli_set_charset($mysqli_link, "utf8")) {
		echo "PROBLEM WITH CHARSET!";
		die;
	}
	$action = $_GET["action"];
	$kID = $_GET["kID"];
	$kName = preg_replace("/\"/i","'",$_GET["inputkName"]);
	$kDescription = preg_replace("/\"/i","'",$_GET["inputkDescription"]);
	$alphabet = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	$searchLetter = mysql_real_escape_string($_GET["searchLetter"]);
	if(($searchLetter == "") or (!in_array($searchLetter, $alphabet))) { $searchLetter = "a"; }
	$msg = "";
	$_GET = array();
	$_POST = array();
	
/////////////////////////////////////////////////////////// Data routine

	if(($action == "ADD") && ($kName !="") && ($kDescription != "")) {
		if(($kUUID == "")) { 
			$kUUID = guidv4(); 
		}
		$queryD = "INSERT INTO keywords VALUES (\"$kUUID\", \"$kName\", \"$kDescription\", \"\")";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		$searchLetter = $kName[0];
		$msg = "<span class=\"text-success text-bold\">New keyword and description added. Please <a href=\"./index.php\">RELOAD</a> toolkit.</span>";
	}
	if(($action == "DELETE") && ($kID !="")) {
		$queryD = "DELETE FROM keywords WHERE keyword_code = \"$kID\"";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		$searchLetter = $kName[0];
		$msg = "<span class=\"text-success text-bold\">Keyword and description have been deleted. Please <a href=\"./index.php\">RELOAD</a> toolkit.</span>";
	}
	if(($action == "ADD") && (($kName == "") or ($kDescription == ""))) {
		$msg = "<span class=\"text-danger text-bold\">No action taken: incomplete details were submitted!</span>";
	}
	
/////////////////////////////////////////////////////////// Table start	

?>
	<div><h4>Edit / Add Keywords</h4></div>
	<div class="panel pad-all bg-gray" style="border: 1px solid #555555;">
		<div class="btn-group">
<?php

/////////////////////////////////////////////////////////// Get Keywords By First Letter
                                                        
	foreach($alphabet as $alpha) {
		echo "<a href=\"javascript: ";
		echo "var dataA = 'searchLetter=".$alpha."'; ";
		echo "var doAssA = $('#inlineKeywordEditor').fadeOut('fast', function(){ ";
		echo "var doAssB = $('#inlineKeywordEditor').load('./data_editor_keywords.php', dataA, function(){";
		echo "var doAssC = $('#inlineKeywordEditor').fadeIn('slow'); ";
		echo "}); ";
		echo "}); ";	
		echo "\" >";
        echo "<button class=\"btn btn-default btn-active-purple";
        if(($alpha == $searchLetter)) { echo " active"; }
        echo "\"><strong>".strtoupper($alpha)."</strong></button>";	
		echo "</a>";
   	}

?>
		</div>
	</div>
<?php

/////////////////////////////////////////////////////////// Add new keyword form

?>    
    <div class="panel pad-all bg-gray" style="border: 1px solid #555555;">
		<div class="row">
    		<div class="col-md-2 control-label text-bold text-right" style="padding-top:7px;">Name</div>
			<div class="col-md-9"><input type="text" id="inputkName" class="form-control" placeholder="Enter your keyword name ..."></div>
            <div class="col-md-1 text-right pad-ver">&nbsp;</div>
		</div>
		<div class="row">
			<div class="col-md-2 control-label text-bold text-right" style="padding-top:7px;">Description</div>
 			<div class="col-md-9"><textarea id="inputkDescription" rows="2" class="form-control" placeholder="Your description here ..."></textarea></div>
            <div class="col-md-1 text-right pad-ver">&nbsp;</div>
		</div>
		<div class="row">
			<div class="col-md-2 text-right pad-ver">&nbsp;</div>
			<div class="col-md-4 text-right"><button id="inputkAdd" class="btn btn-block btn-success mar-top">Add New Keyword</button></div>
            <div class="col-md-5 text-center" style="padding-top: 25px;"><?php echo $msg; ?></div>
            <div class="col-md-1 text-right pad-ver">&nbsp;</div>
		</div>
    </div>
<?php

/////////////////////////////////////////////////////////// Start table

?>    
	<table id="dt-basic" class="table table-striped table-hover table-bordered" cellspacing="0" width="100%" style="border: 0px solid #dddddd;">
		<thead>
			<tr>
        		<th class="pad-all text-light bg-dark text-right" style="font-size: 1.2em; padding: 15px;">#</th>
				<th class="pad-all text-light bg-dark" style="font-size: 1.2em; padding: 15px;">NAME</th>
				<th class="pad-all text-light bg-dark" style="font-size: 1.2em; padding: 15px;">DESCRIPTION</th>
			</tr>
		</thead>
		<tbody>
<?php                                            

/////////////////////////////////////////////////////////// Data routine

	$w = 1;
	$queryD = "SELECT * FROM keywords ";
	$queryD .= "WHERE keyword LIKE \"$searchLetter%\" ";
	$queryD .= "ORDER BY keyword ASC";
	$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
	while($rowD = mysqli_fetch_row($mysqli_resultD)) {
		if(($p == 0)) { $color = "text-mint"; $p = 1; } else { $color = "text-purple"; $p = 0; }
		echo "<tr>";
		echo "<td width=\"5%\" nowrap class=\"pad-all text-muted text-right\" style=\"font-size: 1.0em; padding: 15px;\">$w</td>";
		echo "<td class=\"pad-all text-main text-bold\" width=\"20%\" style=\"font-size: 1.0em; padding: 15px;\">";
		echo "<a href=\"#\" id=\"kName\" ";
		echo "class=\"kName\" data-type=\"textarea\" data-pk=\"$rowD[0]\" data-url=\"./data_post_keywords.php\" data-title=\"Edit Keyword Name\" ";
		echo "style=\"text-decoration: none; border: none;\">";
		echo preg_replace("/\"/i","'","$rowD[1]");
		echo "</a>";
		echo "</td>";
		echo "<td class=\"pad-all text-semibold\" style=\"font-size: 1.0em; text-align: justify; padding: 15px;\">";
		echo "<a href=\"#\" id=\"kDescription\" ";
		echo "class=\"kDesc\" data-type=\"textarea\" data-pk=\"$rowD[0]\" data-url=\"./data_post_keywords.php\" data-title=\"Edit Keyword Description\" ";
		echo "style=\"text-decoration: none; border: none;\">";	
		echo preg_replace("/\"/i","'","$rowD[2]");
		echo "</a>";
		echo "</td>";
		echo "</tr>\n\n";
		$w++;
	}

/////////////////////////////////////////////////////////// Table finish
	
?>
		</tbody>
	</table>
    <p>&nbsp;</p>
    <script language="javascript" type="text/javascript" >
	
		$(document).ready(function() {
			
    		$.fn.editable.defaults.mode = 'popup';
			$.fn.editable.defaults.placement = 'right';
			$.fn.editable.defaults.ajaxOptions = { 
				type: "GET"
			};
			
			$("#inputkAdd").click(function(){
				var inputkName = $('#inputkName').val();
				var inputkDescription = $('#inputkDescription').val();
				var action = "ADD";
				var dataA = "inputkName=" + inputkName + "&inputkDescription=" + inputkDescription + "&action=" + action;			
				var doAssA = $('#inlineKeywordEditor').fadeOut('fast', function(){
					var doAssB = $('#inlineKeywordEditor').load('./data_editor_keywords.php', dataA, function(){ 
						var doAssB = $('#inlineKeywordEditor').fadeIn('slow');
					});
				});
			});
			
			$('.kName').editable({
				placement : 'right',
				type : 'textarea',
				rows : 2,
				params: function(params) {
					var data = {};
    				data['ID'] = params.pk;
    				data[params.name] = params.value;
    				return data;
				}
			});
			
			$('.kDesc').editable({
				placement : 'top',
				type : 'textarea',
				rows : 8,
				params: function(params) {
					var data = {};
    				data['ID'] = params.pk;
    				data[params.name] = params.value;
    				return data;
				}
			});
			
		});
	
	</script>
<?php    	

/////////////////////////////////////////////////////////// Finish

	include("../era.dbdisconnect.php");

?>