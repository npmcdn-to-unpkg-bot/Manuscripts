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
//	5-10 September 2016
//	12-14 September 2016
//
//
/////////////////////////////////////////////////////////// Collect session data

	$MerdUser = session_id();
	if(empty($MerdUser)) { session_start(); }
	$SIDmerd = session_id(); 
	$defText = "<p>When the <em>Add Keywords</em> or <em>Edit Keywords</em> button is clicked, this panel will load the form for assigning, adding, deleting or modifying existing keywords that are associated with the work highlighted in the middle panel. Please note that keywords are assigned at the <em>Work</em> level rather than at the edition or manifestation level.</p>";
	
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
	$rParisian = $_GET['rParisian'];
	$fuzzyValue = $_GET['fuzzyValue'];
	$fuzzyComment = $_GET['fuzzyComment'];
	$doneFuzzy = "";
	$_GET = array();
	$_POST = array();
	
/////////////////////////////////////////////////////////// If save then record fuzzy value and comment

	if(($ID != "") && ($action == "save")) {
		if(($fuzzyValue != "")) {
			$queryD = "DELETE FROM manuscript_cat_fuzzy WHERE super_book_code LIKE \"$ID\"; ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			$queryD = "INSERT INTO manuscript_cat_fuzzy VALUES (\"0\", \"$ID\", \"$fuzzyValue\", \"$fuzzyComment\"); ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			$doneFuzzy = "y";
		}
	}
	
/////////////////////////////////////////////////////////// Get existing fuzzy value and comment

	if(($ID != "")) {
		$queryD = "SELECT * FROM manuscript_cat_fuzzy WHERE super_book_code LIKE \"$ID\"; ";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) {
			$fuzzyValue = $rowD[2];
			$fuzzyComment = $rowD[3];
		}
	}

/////////////////////////////////////////////////////////// If save then get keyword IDs and run save + audit routines

	if(($ID != "") && ($action == "save")) {

/////////////////////////////// Set vars
		
		$old_keywordIDs = "";
		$old_keywords = "";
		$new_keywordIDs = "";
		$new_keywords = $rKeywords;
		$rIDs = array();
		$frIDs = "";

/////////////////////////////// Subroutine for standard keywords record and audit

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
		$queryD = "UPDATE manuscript_books SET keywords = \"$frIDs\", parisian_keyword = \"$rParisian\" WHERE super_book_code LIKE \"$ID\"";
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
	
/////////////////////////////// Subroutine for Parisian keyword audit	
			
		if(($rParisian != "")) {
			$queryD = "DELETE FROM manuscript_cat_audit WHERE super_book_code = \"LAST_PARISIAN_KEYWORD\"";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			
			$queryD = "SELECT * FROM parisian_keywords WHERE parisian_keyword_code LIKE \"$rParisian\"";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) {
				$rParisianName = $rowD[1];
			}
			if(($rParisian != "") && ($rParisianName != "")) {
				$theTime = date("Y-m-d H:i:s",time());
				$queryD = "INSERT INTO manuscript_cat_audit VALUES ";
				$queryD .= "(0, \"LAST_PARISIAN_KEYWORD\", \"$theTime\", \"$rParisianName\", \"$rParisian\", \"\", \"\", \"admin\")";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			}
		}
	}

/////////////////////////////////////////////////////////// Data routine for existing keywords

	if(!empty($ID)) {
		$found = "";
		$keywordIDs = array();
		$keywordsP = "";
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
			if(($keywordsP != "")) {
				$queryD = "SELECT * FROM parisian_keywords WHERE parisian_keyword_code LIKE \"$keywordsP\"";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
				while($rowD = mysqli_fetch_row($mysqli_resultD)) {
					$paris_keyword = ucwords($rowD[1]);
				}
			}
		}
	}
			
/////////////////////////////////////////////////////////// Div for alert		
	
	if(!empty($ID)) {
		if(($found == "y")) {
			if(($action == "save") && ($save == "y")) {
				echo "<div id=\"alertKeywords\" class=\"alert alert-success alert-dismissible\">";
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
			echo "<div class=\"mar-btm\">";
			echo "<input type=\"hidden\" name=\"titleTaggerList\" id=\"titleTaggerList\" >";
    		echo "<input type=\"text\" ";
    		echo "name=\"titleTagger\" ";
    		echo "autocomplete=\"off\" ";
    		echo "id=\"titleTagger\" ";
    		echo "placeholder=\"Start typing a tag or click to clear ...\" ";
    		echo "class=\"title-tagger input-sm text-dark tm-input\" ";
    		echo "style=\"display: block; width: 100%; min-width: 100%;\" ";
    		echo "onclick=\"var cleanBar = $('#titleTagger').typeahead('val','');\" /></div>\n";
			
/////////////////////////////////////////////////////////// Select for Parisian tags

			$sel = "";
			echo "<div class=\"mar-btm\">";
			echo "<select data-placeholder=\"Choose a Parisian Keyword ...\" id=\"rParisian\" name=\"rParisian\">";
			if(($paris_keyword == "") && ($keywordsP == "")) {
				echo "<option value=\"\" selected disabled>Choose a Parisian Keyword ...</option>";
			} else {
				echo "<option value=\"$keywordsP\" selected>$paris_keyword</option>";
				echo "<option value=\"\" disabled>&nbsp;</option>";
			}
			$queryD = "SELECT * FROM parisian_keywords WHERE ancestor1 IS NULL ORDER BY parisian_keyword ASC";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) {
				echo "<option value=\"$rowD[0]\" style=\"background-color: #555555; color: #ffffff; border-bottom: 1px solid #ffffff;\">$rowD[1]</option>";
				$queryP = "SELECT * FROM parisian_keywords ";
				$queryP .= "WHERE ancestor1 = \"$rowD[0]\" ";
				$queryP .= "OR ancestor2 = \"$rowD[0]\" ";
				$queryP .= "OR ancestor3 = \"$rowD[0]\" ";
				$queryP .= "ORDER BY parisian_keyword ASC";
				$mysqli_resultP = mysqli_query($mysqli_link, $queryP);
				while($rowP = mysqli_fetch_row($mysqli_resultP)) {
					echo "<option value=\"$rowP[0]\" style=\"background-color: #cfcfcf; border-bottom: 1px solid #ffffff;\">$rowP[1]</option>";
					$queryP2 = "SELECT * FROM parisian_keywords ";
					$queryP2 .= "WHERE ancestor1 = \"$rowP[0]\" ";
					$queryP2 .= "OR ancestor2 = \"$rowP[0]\" ";
					$queryP2 .= "OR ancestor3 = \"$rowP[0]\" ";
					$queryP2 .= "ORDER BY parisian_keyword ASC";
					$mysqli_resultP2 = mysqli_query($mysqli_link, $queryP2);
					while($rowP2 = mysqli_fetch_row($mysqli_resultP2)) {
						echo "<option value=\"$rowP2[0]\" style=\"background-color: #efefef; border-bottom: 1px solid #ffffff;\">$rowP2[1]</option>";
					}
				}
			}
			echo "</select></div>\n";	
			
/////////////////////////////////////////////////////////// Select for fuzzy value

			$fuzzyArray = array("No information exists on how this work was classified.", "Classified on basis of title alone.", "Classified on basis of subject categorisations in library or other catalogues.", "Classified after accounts in bibliographic sources, antiquarian bookseller's catalogue descriptions, or other secondary source accounts.", "Classified after inspection of a copy of the work.", "Classified after thorough reading knowledge of work.");
			$sel = "";
			echo "<div class=\"mar-btm\">";
			echo "<select data-placeholder=\"Choose a fuzzy value ...\" id=\"fuzzyValue\" name=\"fuzzyValue\">";
			if(($fuzzyValue == "")) {
				echo "<option value=\"\" selected disabled>Choose a fuzzy value ...</option>";
			} else {
				echo "<option value=\"$fuzzyValue\" selected>".$fuzzyValue.". ".$fuzzyArray[$fuzzyValue]."</option>";
				echo "<option value=\"\" disabled>&nbsp;</option>";
			}
			echo "<option value=\"5\" style=\"background-color: #cfcfcf; border-bottom: 1px solid #ffffff;\">";
			echo "5. Classified after thorough reading knowledge of work.";
			echo "</option>";
			echo "<option value=\"4\" style=\"background-color: #cfcfcf; border-bottom: 1px solid #ffffff;\">";
			echo "4. Classified after inspection of a copy of the work.";
			echo "</option>";
			echo "<option value=\"3\" style=\"background-color: #cfcfcf; border-bottom: 1px solid #ffffff;\">";
			echo "3. Classified after accounts in bibliographic sources, antiquarian bookseller's catalogue descriptions, or other secondary source accounts.";
			echo "</option>";
			echo "<option value=\"2\" style=\"background-color: #cfcfcf; border-bottom: 1px solid #ffffff;\">";
			echo "2. Classified on basis of subject categorisations in library or other catalogues.";
			echo "</option>";
			echo "<option value=\"1\" style=\"background-color: #cfcfcf; border-bottom: 1px solid #ffffff;\">";
			echo "1. Classified on basis of title alone.";
			echo "</option>";
			echo "<option value=\"0\" style=\"background-color: #cfcfcf; border-bottom: 1px solid #ffffff;\">";
			echo "0. No information exists on how this work was classified.";
			echo "</option>";
			echo "</select></div>\n";
			
/////////////////////////////////////////////////////////// Textarea for fuzzy comment

			echo "<div>";
			echo "<textarea placeholder=\"Please justify your fuzzy value above ...\" id=\"fuzzyComment\" name=\"fuzzyComment\" rows=\"1\" ";
			echo "style=\"width:100%; padding: 5px; outline: none;\">";
			echo $fuzzyComment;
			echo "</textarea>";
			echo "</div>\n";		

/////////////////////////////////////////////////////////// Submit button	
			
			echo "<a href=\"javascript: ";	
			echo "var rKey = $('#titleTaggerList').val(); ";
			echo "var rParis = $('#rParisian').val(); ";
			echo "var fuzzyValue = $('#fuzzyValue').val(); ";
			echo "var fuzzyComment = $('#fuzzyComment').val(); ";
			echo "var dataE = '";	
			echo "ID=$ID";
			echo "&action=save";	
			echo "&rParisian='+rParis+'";
			echo "&fuzzyValue='+fuzzyValue+'";
			echo "&fuzzyComment='+fuzzyComment+'";
			echo "&rKeywords='+rKey";
			echo "; ";
			echo "var doDivA = $('#titleTags').fadeOut('fast', function(){ ";
			echo "var searchValA = $('#titleTags').load('./data_keywords.php',dataE, function(){ ";
			echo "var doDivAlsoA = $('#titleTags').fadeIn('slow'); ";
			echo "}); ";
			echo "}); ";
			echo "\">";		
			echo "<button class=\"btn btn-block btn-purple mar-top\">Save Keyword Assignment(s)</button>";
			echo "</a>";
    		echo "</div>";
			
/////////////////////////////////////////////////////////// Div for last update if exists

			if(($ID != "") && ($showUpdateTime != "")) {
				$lastTime = "";
				$queryD = "SELECT * FROM manuscript_cat_audit WHERE super_book_code LIKE \"$ID\" ORDER BY ID DESC LIMIT 1";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
				while($rowD = mysqli_fetch_row($mysqli_resultD)) {
					$lastTime = $rowD[2];	
				}
				if(($lastTime != "")) {
					echo "<div id=\"tagsManagerTimeStamp\" class=\"mar-top text-sm text-right\">";
					echo "<em><strong>Last Updated: </strong>$lastTime</em>";
					echo "</div>";	
				}
			}
			
/////////////////////////////////////////////////////////// Div for commonly associated tags if pinged

			echo "<div id=\"tagAssociationsContainer\">";
			echo "<div id=\"tagAssociations\" class=\"pad-top mar-top\">";
			echo "<h4>RANKED ASSOCIATED TAGS</h4>";
			echo "<hr />";
			echo "</div>";
			
//////////////////////////// Gather associated tags			
			
			echo "<div class=\"panel panel-bordered-primary bg-gray\">";
    		echo "<div class=\"panel-body\">";
			echo "<div id=\"tagAssociationsList\">";
			echo "To show associated keywords, please slide out the right-hand panel and select a tag.";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			
//////////////////////////// Close container div			
			
			echo "</div>";
			
/////////////////////////////////////////////////////////// Div for recent categorisation tags

			echo "<div id=\"tagsManagerRecent\" class=\"pad-top mar-top\">";
			echo "<h4>RECENTLY USED TAGS</h4>";
			echo "<hr />";
			echo "</div>";	
			
//////////////////////////// Gather prior parisian tag

			$frenchCode = "";
			$frehcnName = "";
			$queryD = "SELECT * FROM manuscript_cat_audit WHERE super_book_code LIKE \"%PARISIAN%\" ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) {
				$frenchCode = $rowD[4];
				$frenchName = $rowD[3];
			}

//////////////////////////// Gather prior standard tags
			
			$o = 1;
			$priorTags = "";
			$priorTagsA = array();
			$priorTagsB = array();
			$queryD = "SELECT DISTINCT(new_keywords) FROM manuscript_cat_audit ";
			$queryD .= "WHERE new_keywords != \"\" AND super_book_code NOT LIKE \"%PARISIAN%\" ";
			$queryD .= "ORDER BY ID DESC LIMIT 1";
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

//////////////////////////// Show prior French tag
						
    		echo "<div class=\"panel panel-bordered-mint bg-gray\">";
    		echo "<div class=\"panel-body\">";
			echo "<div id=\"tagsManagerSaved\">";
			if(($frenchCode != "") && ($frenchName != "")) {
				echo "<li>";
				echo "<a href=\"javascript: ";
				echo "var selectF = $('#rParisian').val('".$frenchCode."').change(); ";
				echo "var selectU = $('#rParisian').trigger('chosen:updated'); ";
				echo "\" ";
				echo "style=\"color:#000055;\" ";
				echo "class=\"add-tooltip\" ";
				echo "data-toggle=\"tooltip\" ";
				echo "data-container=\"body\" ";
				echo "data-placement=\"left\" ";
				echo "data-original-title=\"";
				echo "PARISIAN KEYWORD";
				echo "\" ";
				echo ">$frenchName</a> (Parisian Keyword)</li>";
			}
			
//////////////////////////// Sort and show prior Standard tags			
			
			sort($priorTags);
			foreach($priorTags as $p) {
				$descp = "";
				$dText = "";
				$kID = "";
				$queryD = "SELECT * FROM keywords WHERE keyword LIKE \"$p\"";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
				while($rowD = mysqli_fetch_row($mysqli_resultD)) {
					$dText = "<strong>".strtoupper($p)."</strong><br /><br />$rowD[2]";
					$kID = $rowD[0];
					$descp = "y";
				}
				if(($descp != "y")) {
					$dText = "NO DESCRIPTION AVAILABLE";	
				}
				echo "<li>";
				echo "<a href=\"javascript: ";
				echo "var doThis = tagApi.tagsManager('pushTag','$p'); ";
//				echo "var dataA = 'kID=$kID&action=yes'; ";
//				echo "var doAssA = $('#tagAssociationsList').fadeOut('fast', function(){ ";
//				echo "var doAssB = $('#tagAssociationsList').load('./data_keywords_assoc.php',dataA, function(){ ";
//				echo "var doAssB = $('#tagAssociationsList').fadeIn('slow'); ";
//				echo "}); ";
//				echo "}); ";
				echo "\" ";
				echo "style=\"color:#000055;\" ";
				echo "class=\"add-tooltip\" ";
				echo "data-toggle=\"tooltip\" ";
				echo "data-container=\"body\" ";
				echo "data-placement=\"left\" ";
				echo "data-original-title=\"";
				echo $dText;
				echo "\" ";
				echo ">$p</a></li>";
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
			<script language="javascript" type="text/javascript" >
			
				$('[data-toggle="tooltip"]').tooltip({
					template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="border: 3px solid #ffffff; color: #000000; background-color:#6ab5f1; padding:20px;"></div></div>',
					html: true,
					trigger : 'hover'
				});
				
				$("#rParisian").chosen({
					width: "100%",
					allow_single_deselect: "true",
					max_selected_options: "1",
					placeholder_text_single: "Choose a Parisian Keyword ..."
				});
				
				$("#fuzzyValue").chosen({
					width: "100%",
					allow_single_deselect: "true",
					max_selected_options: "1",
					placeholder_text_single: "Choose a fuzzy value ..."
				});

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
					if(($rKeywords != "")) {
						echo "var doClass = $('#btn_".$ID."').removeClass('btn-pink').addClass('btn-success');\n\n";
					} else {
						echo "var doClass = $('#btn_".$ID."').removeClass('btn-success').addClass('btn-pink');\n\n";
					}
					echo "var doAuditA = $('#auditHistoryPanel').fadeOut('fast', function(){ ";
					echo "var doAuditB = $('#auditHistoryPanel').load('./data_history.php','', function(){ ";
					echo "var doAuditC = $('#auditHistoryPanel').fadeIn('slow'); ";
					echo "}); ";
					echo "}); ";
				}
				
				if(($action == "save")) {
					echo "var doAlert = $('#alertKeywords').delay(5000).fadeOut();\n\n";	
				}
				if(($doneFuzzy == "y")) {
					echo "var doClassB = $('#btn_P".$ID."').removeClass('btn-pink').addClass('btn-default');\n\n";
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