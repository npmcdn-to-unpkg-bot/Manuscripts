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
//	5 September 2016
//
//
/////////////////////////////////////////////////////////// Collect session data

	$MerdUser = session_id();
	if(empty($MerdUser)) { session_start(); }
	$SIDmerd = session_id(); 
	$defText = "<p>When the <em>Edit Keywords</em> button is clicked, this panel will load the form for assigning, adding, deleting or modifying existing keywords that are associated with the work highlighted in the middle panel. Please note that keywords are assigned at the <em>Work</em> level rather than at the edition or manifestation level.</p>";
	
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
	$rKeywords = $_GET['rKeywords'];
	$_GET = array();
	$_POST = array();

/////////////////////////////////////////////////////////// If save then get keyword IDs and run save + audit routines

	if(($ID != "") && ($action == "save")) {
		$old_keywordIDs = "";
		$old_keywords = "";
		$new_keywordIDs = "";
		$new_keywords = $rKeywords;
		$rIDs = array();
		$frIDs = "";
		if(($rKeywords != "")) {
			if(preg_match("/\|/i","$rKeywords")) {
				$rKeys = explode("|","$rKeywords");
				foreach($rKeys as $ky) {
					$queryD = "SELECT * FROM keywords WHERE keyword LIKE \"$ky\"";
					$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
					while($rowD = mysqli_fetch_row($mysqli_resultD)) {
						$rIDs[] = $rowD[0];
						$frIDs .= $rowD[0].",";
					}
				}
				$frIDs = rtrim($frIDs, ",");
			} else {
				$queryD = "SELECT * FROM keywords WHERE keyword LIKE \"$rKeywords\"";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
				while($rowD = mysqli_fetch_row($mysqli_resultD)) {
					$rIDs[] = $rowD[0];
					$frIDs .= $rowD[0];
				}
			}
			$queryD = "SELECT * FROM manuscript_books WHERE super_book_code LIKE \"$ID\"";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) {
				$old_keywordIDs = $rowD[2];
			}
			if((preg_match("/,/i","$old_keywordIDs"))) {
				$prep_old_keywordIDs = explode(",",$old_keywordIDs);
			} else {
				$prep_old_keywordIDs = array();
				$prep_old_keywordIDs[] = $old_keywordIDs;	
			}
			if(($prep_old_keywordIDs[0] != "")) {
				foreach($prep_old_keywordIDs as $p) {
					$queryD = "SELECT * FROM keywords WHERE keyword_code LIKE \"$p\"";
					$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
					while($rowD = mysqli_fetch_row($mysqli_resultD)) {
						$old_keywords .= ucwords($rowD[1]).",";
					}
				}
				$old_keywords = rtrim($old_keywords, ",");
			}		
			$new_keywordIDs = $frIDs;
			$queryD = "UPDATE manuscript_books SET keywords = \"$frIDs\" WHERE super_book_code LIKE \"$ID\"";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			if(($mysqli_resultD > 0)) {
				$save = "y";
				$theTime = date("Y-m-d H:i:s",time());
				$queryD = "INSERT INTO manuscript_cat_audit VALUES ";
				$queryD .= "(0, \"$ID\", \"$theTime\", \"$new_keywords\", \"$new_keywordIDs\", \"$old_keywords\", \"$old_keywordIDs\", \"admin\")";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);	
			} else {
				$save = "n";
			}
		} else {
			$save = "n";
		}
	}

/////////////////////////////////////////////////////////// Data routine for existing keywords

	if(!empty($ID)) {
		$found = "";
		$keywordIDs = array();
		$queryD = "SELECT * FROM manuscript_books WHERE super_book_code LIKE \"$ID\"";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) {
			$keywords = $rowD[2];
			$keywordsP = $rowD[3];
			$found = "y";
		}
		if((preg_match("/,/i","$keywords"))) {
			$keywordIDs = explode(",",$keywords);
		} else {
			$keywordIDs[] = $keywords;	
		}
		if(($keywordsP != "")) {
			$keywordIDs[] = 	$keywordsP;
		}
		if(($found == "y")) {
			$prefill = array();
			foreach($keywordIDs as $k) {
				$queryD = "SELECT * FROM keywords WHERE keyword_code LIKE \"$k\"";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
				while($rowD = mysqli_fetch_row($mysqli_resultD)) {
					$prefill[] = ucwords($rowD[1]);
				}
			}
		}
	}
			
/////////////////////////////////////////////////////////// Div for alert		
	
	if(!empty($ID)) {
		if(($found == "y")) {
			if(($action == "save") && ($save == "y")) {
				echo "<div id=\"alertKeywords\" class=\"alert alert-primary alert-dismissible\">";
				echo "<button class=\"close\" data-dismiss=\"alert\"><i class=\"pci-cross pci-circle\"></i></button>";
				echo "<strong>Keyword assignments saved.</strong>";
				echo "</div>";
			}
			if(($action == "save") && ($save != "y")) {
				echo "<div id=\"alertKeywords\" class=\"alert alert-pink alert-dismissible\">";
				echo "<button class=\"close\" data-dismiss=\"alert\"><i class=\"pci-cross pci-circle\"></i></button>";
				echo "<strong>There was a problem: please note.</strong>";
				echo "</div>";
			}
	
/////////////////////////////////////////////////////////// Div for keywords currently on file		
					
			echo "<div class=\"panel panel-bordered-dark bg-gray\">";
    		echo "<div class=\"panel-body\">";
    		echo "<div id=\"tagsManagerDisplay\">";
    		echo "</div>";
    		echo "</div>";
    		echo "</div>";	
			
/////////////////////////////////////////////////////////// Div for input functions			
			
    		echo "<div id=\"tagsManagerInput\">";
			echo "<input type=\"hidden\" name=\"titleTaggerList\" id=\"titleTaggerList\" >";
    		echo "<input type=\"text\" ";
    		echo "name=\"titleTagger\" ";
    		echo "autocomplete=\"on\" ";
    		echo "id=\"titleTagger\" ";
    		echo "placeholder=\"Start typing a tag or click to clear ...\" ";
    		echo "class=\"title-tagger input-sm text-dark tm-input\" ";
    		echo "style=\"display: block; width: 100%; min-width: 100%;\" ";
    		echo "onclick=\"var cleanBar = $('#titleTagger').val('');\" />";
			echo "<a href=\"javascript: ";	
			echo "var rKey = $('#titleTaggerList').val(); ";
			echo "var dataE = '";	
			echo "ID=$ID";
			echo "&action=save";	
			echo "&rKeywords='+rKey";
			echo "; ";
			echo "var doDivA = $('#titleTags').fadeOut('fast', function(){ ";
			echo "var searchValA = $('#titleTags').load('./data_keywords.php',dataE, function(){ ";
			echo "var doDivAlsoA = $('#titleTags').fadeIn('slow'); ";
			echo "}); ";
			echo "}); ";
			echo "\">";
			echo "<button class=\"btn btn-block btn-purple mar-top\">Save Keyword Assignment</button>";
			echo "</a>";
    		echo "</div>";
			
/////////////////////////////////////////////////////////// Div for recent categorisation tags

			echo "<div id=\"tagsManagerRecent\" class=\"pad-top mar-top\">";
			echo "<h4>RECENT TAGS</h4>";
			echo "<hr />";
			echo "</div>";

//////////////////////////// Gather prior tags (just an example presently!)
			
			$o = 1;
			$priorTags = "";
			$priorTagsA = array();
			$priorTagsB = array();
			$queryD = "SELECT DISTINCT(new_keywords) FROM manuscript_cat_audit LIMIT 2";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) {
				if(($o == 1)) {
					if((preg_match("/\|/i","$rowD[0]"))) {
						$priorTagsA = explode("|","$rowD[0]");
					} else {
						$priorTagsA[] = $rowD[0];
					}
				}
				if(($o == 2)) {
					if((preg_match("/\|/i","$rowD[0]"))) {
						$priorTagsB = explode("|","$rowD[0]");
					} else {
						$priorTagsB[] = $rowD[0];
					}
				}
				$o++;
			}
			$priorTags = array_merge($priorTagsA,$priorTagsB);
			$priorTags = array_unique($priorTags);

//////////////////////////// Sort and show prior tags
						
    		echo "<div class=\"panel panel-bordered-info bg-gray\">";
    		echo "<div class=\"panel-body\">";
			echo "<div id=\"tagsManagerSaved\">";
			sort($priorTags);
			foreach($priorTags as $p) {
				echo "<li><a href=\"javascript: var doThis = tagApi.tagsManager('pushTag','$p');\" style=\"color:#000055;\">$p</a></li>";
			}
    		echo "</div>";
			echo "</div>";
			echo "</div>";
			
/////////////////////////////////////////////////////////// Debug
//
//			echo "<div id=\"tagsManagerRecent\" class=\"pad-top mar-top\">";
//			echo "<h4>DEBUG</h4>";
//			echo "<hr />";
//			echo "<pre>";
//			print_r($_GET);
//			print_r($_POST);
//			print_r($rIDs);
//			echo "</pre>";
//			echo "</div>";			
//
/////////////////////////////////////////////////////////// Page Scripts
			
?>
			<script language="javascript" type="text/javascript" src="./js/typeahead.bundle.js"></script>
			<script language="javascript" type="text/javascript" >

				var preKeywords = new Bloodhound({
      				datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
      				queryTokenizer: Bloodhound.tokenizers.whitespace,
      				limit: 10,
      				prefetch: {
        				url: './data_prefetch.php?r=<?php echo time(); ?>',
        				filter: function (list) {
          					return $.map(list, function (preKeyword) {
            					return { name: preKeyword };
          					});
        				}
      				}
    			});
 
    			preKeywords.initialize();

				var tagApi = $(".title-tagger").tagsManager({
    				prefilled: [<?php
					
					$c = count($prefill);
					if(($c == 0)) { echo ""; }
					if(($c == 1)) { echo "\"".$prefill[0]."\""; }
					if(($c > 1)) {
						$d = ($c - 1);
						for($w=0;$w<$c;$w++) {
							if(($w == $d)) {
								echo "\"".$prefill[$w]."\"";	
							} else {
								echo "\"".$prefill[$w]."\", ";	
							}
						}
					}
					
					?>],
					tagsContainer: '#tagsManagerDisplay', 
					deleteTagsOnBackspace: false,
					onlyTagList: true, 
					delimiters: [9, 13, 124],
					output: '#titleTaggerList',
					tagClass: 'tm-tag'
  				});

				$(".title-tagger").typeahead(null, {
      				name: 'preKeywords',
      				displayKey: 'name',
      				source: preKeywords.ttAdapter()
    			}).on('typeahead:selected', function (e, d) {
        			tagApi.tagsManager("pushTag", d.name);
    			});		
				
<?php

				if(($action == "save") && ($save == "y")) {
					echo "var doButton = $('#btn_".$ID."').html('Yes');\n\n";
					echo "var doClass = $('#btn_".$ID."').removeClass('btn-pink').addClass('btn-success');\n\n";
				}
				
				if(($action == "save")) {
					echo "var doAlert = $('#alertKeywords').delay(3000).fadeOut();\n\n";	
				}

?>								
		
			</script>
<?php			
	
/////////////////////////////////////////////////////////// End routine-specific scripts	
			
		}
	} else {
		if(($action != "")) {
        	echo "<p>No data was provided. ";
			echo "Please reload the webpage and see if that resolves the problem. ";
			echo "If this issue continues, please contact the developer.</p>";
		} else {
			echo $defText;
 		}
    }
	
/////////////////////////////////////////////////////////// Finish

	include("../era.dbdisconnect.php");

?>