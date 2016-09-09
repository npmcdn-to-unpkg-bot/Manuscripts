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
//	5-9 September 2016
//
//
/////////////////////////////////////////////////////////// Collect session data

	$MerdUser = session_id();
	if(empty($MerdUser)) { session_start(); }
	$SIDmerd = session_id(); 
	
/////////////////////////////////////////////////////////// Clean post and get	
	
	include("../config.php");
	include("../era.dbconnect.php");
	include("./index_functions.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	mb_internal_encoding("UTF-8");
	if (!mysqli_set_charset($mysqli_link, "utf8")) {
		echo "PROBLEM WITH CHARSET!";
		die;
	}
	
/////////////////////////////////////////////////////////// Destroy get and post arrays after use	
	
	$alphabet = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	$alphabetNumerical = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x",
		"y","z","0","1","2","3","4","5","6","7","8","9", "*");
	$searchLetter = mysql_real_escape_string($_GET["searchLetter"]);
	if(($searchLetter == "") or (!in_array($searchLetter, $alphabetNumerical))) { $searchLetter = "a"; }
	$_GET = array();
	$_POST = array();
	
/////////////////////////////////////////////////////////// Ideological keywords array	

	$ideologicalWords = array("Anti-Aristocratic Works","Anti-clerical Works","apologetic Works","Catholic Text","Christian Enlightenment Text",
		"Erotic Works","Irreligious Works","Jansenist Text","Libertine Texts","Philosophie","Pornographic Works","Protestant Texts","Satirical Works",
		"Sceptical Works","Spurious Works","Works of Religiosity");	

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Page header

?>
<!--


	Project: Manuscripts Categorisation Toolkit
	Project Team: Jason Ensor, Simon Burrows
	Project Base: Western Sydney University, Digital Humanities Research Group, School of Humanities and Communication Arts
	Project Methodology: Procedural Scripting PHP | MySQL | JQuery



	FOR ALL ENQUIRIES ABOUT CODE

	Who:	Dr Jason Ensor
	Email: 	j.ensor@westernsydney.edu.au | jasondensor@gmail.com
	Web: 	http://www.jasonensor.com
	Mobile:	0419 674 770



  	WEB FRAMEWORK

  	Bootstrap Twitter | http://getbootstrap.com/
	JQuery | http://jquery.com/download/
    Nifty Responsive Admin Template | https://wrapbootstrap.com/theme/nifty-responsive-admin-template-WB0048JF7



  	VERSION 0.1
    
  	Development Started: 15 August 2016
	Last updated: 9 September 2016




















//-->
<!DOCTYPE html>
<html lang="en">
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>   
    	<title>Manuscripts - Categorisation - Digital Humanities Research Group - Western Sydney University</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="description" content="Manuscripts - Categorisation, Western Sydney University. Development: Dr Jason Ensor" />
        <meta name="robots" content="noindex,nofollow" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta http-equiv='cache-control' content='no-cache' />
		<meta http-equiv='expires' content='0' />
		<meta http-equiv='pragma' content='no-cache' />
		<meta name="msapplication-TileColor" content="#ffffff" />
		<meta name="msapplication-TileImage" content="./icons/ms-icon-144x144.png" />
		<meta name="theme-color" content="#ffffff" />  
        <link rel="apple-touch-icon" href="./icons/apple-icon.png" />
		<link rel="apple-touch-icon" sizes="57x57" href="./icons/apple-icon-57x57.png" />
		<link rel="apple-touch-icon" sizes="60x60" href="./icons/apple-icon-60x60.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="./icons/apple-icon-72x72.png" />
		<link rel="apple-touch-icon" sizes="76x76" href="./icons/apple-icon-76x76.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="./icons/apple-icon-114x114.png" />
		<link rel="apple-touch-icon" sizes="120x120" href="./icons/apple-icon-120x120.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="./icons/apple-icon-144x144.png" />
		<link rel="apple-touch-icon" sizes="152x152" href="./icons/apple-icon-152x152.png" />
		<link rel="apple-touch-icon" sizes="180x180" href="./icons/apple-icon-180x180.png" />
		<link rel="icon" type="image/png" sizes="192x192"  href="./icons/android-icon-192x192.png" />
		<link rel="icon" type="image/png" sizes="32x32" href="./icons/favicon-32x32.png" />
		<link rel="icon" type="image/png" sizes="96x96" href="./icons/favicon-96x96.png" />
		<link rel="icon" type="image/png" sizes="16x16" href="./icons/favicon-16x16.png" />
        <link rel="icon" type="image/x-icon" href="./icons/favicon.ico" />
		<link rel="shortcut icon" type="image/x-icon" href="./icons/favicon.ico" />     
        <link rel="manifest" href="./manifest.json" />        
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=latin">
		<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="./css/nifty.min.css">
		<link rel="stylesheet" type="text/css" href="./plugins/themify-icons/themify-icons.min.css">
		<link rel="stylesheet" type="text/css" href="./css/pace.min.css">
        <link rel="stylesheet" type="text/css" href="./css/themes/type-c/theme-ocean.min.css">
        <link rel="stylesheet" type="text/css" href="./plugins/datatables/media/css/dataTables.bootstrap.css">
		<link rel="stylesheet" type="text/css" href="./plugins/datatables/extensions/Responsive/css/dataTables.responsive.css">
        <link rel="stylesheet" type="text/css" href="./plugins/ionicons/css/ionicons.min.css" >
        <link rel="stylesheet" type="text/css" href="./js/bootstrap-tagmanager/tagmanager.css">
        <link rel="stylesheet" type="text/css" href="./js/fancybox/jquery.fancybox.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="./js/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css">  
		<script language="javascript" type="text/javascript" src="./js/pace.min.js"></script>
        <script language="javascript" type="text/javascript" src="./js/jquery-2.2.4.min.js"></script>
		<script language="javascript" type="text/javascript" src="./js/bootstrap.min.js"></script>
		<script language="javascript" type="text/javascript" src="./js/nifty.min.js"></script>
        <script language="javascript" type="text/javascript" src="./plugins/datatables/media/js/jquery.dataTables.js"></script>
		<script language="javascript" type="text/javascript" src="./plugins/datatables/media/js/dataTables.bootstrap.js"></script>
		<script language="javascript" type="text/javascript" src="./plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
        <script language="javascript" type="text/javascript" src="./js/bootstrap-tagmanager/tagmanager.js"></script>
        <script language="javascript" type="text/javascript" src="./js/fancybox/jquery.fancybox.pack.js"></script> 
        <script language="javascript" type="text/javascript" src="./js/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.js"></script> 
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Manual CSS Interventions

?>         
        <style type="text/css" rel="stylesheet">
		
			.tm-font {
				font-size: 1.0em;
				font-weight: bold;
			}

			.tt-query {
				-webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
				-moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
				box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
			}
			
			.tt-hint {
				color: #999;
			}
			
			.tt-menu {
				width: 100%;
				margin: 12px 0;
				padding: 8px 0;
				background-color: #fff;
				border: 1px solid #ccc;
				border: 1px solid rgba(0, 0, 0, 0.2);
				-webkit-border-radius: 8px;
				-moz-border-radius: 8px;
				border-radius: 8px;
				-webkit-box-shadow: 0 5px 10px rgba(0,0,0,.2);
				-moz-box-shadow: 0 5px 10px rgba(0,0,0,.2);
				box-shadow: 0 5px 10px rgba(0,0,0,.2);
			}
			
			.tt-suggestion {
				padding: 3px 20px;
				font-size: 1.0em;
			}
			
			.tt-suggestion:hover {
				cursor: pointer;
				color: #fff;
				background-color: #0097cf;
			}
			
			.tt-suggestion.tt-cursor {
				color: #fff;
				background-color: #0097cf;
			}
			
			.tt-suggestion p {
				margin: 0;
			}
			
			.twitter-typeahead-jde {
				display: block!important;
			}
			
			pre {
    			white-space: pre-wrap; 
    			white-space: -moz-pre-wrap;
    			white-space: -pre-wrap;
    			white-space: -o-pre-wrap;
    			word-wrap: break-word;
				tab-size: 0;
				-moz-tab-size: 0;
    			-o-tab-size: 0;
				padding: 20px;
				font-size: 0.8em;
			}
			
			.btn-default {
				margin-bottom: 2px;
				margin-right: 2px;
				min-width: 55px;	
			}
			
		</style>
    </head>   
	<body>
		<div id="container" class="effect mainnav-sm aside-dark">
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Header

?> 
			<header id="navbar">
				<div id="navbar-container" class="boxed">
					<div class="navbar-header">
                    	<a href="./index.php" class="navbar-brand">
                        	<img src="./img/logo_trans.png" alt="WSU Logo" class="brand-icon">
                            <div class="brand-title"><span class="brand-text"> MANUSCRIPTS</span></div>
                        </a>
                	</div>
                    <div class="navbar-content clearfix">
                    <!--
                    	<ul class="nav navbar-top-links pull-left">
                    		<li class="tgl-menu-btn"><a class="mainnav-toggle" href="#"><i class="ti-view-list icon-lg"></i></a></li>
               			</ul>
                    //-->
                        <ul class="nav navbar-top-links pull-left">
                    		<li style="font-size: 1.2em; padding: 0.5em;" class="text-bold">&nbsp; &nbsp;CATEGORISATION TOOLKIT (Last Updated 9 September 2016)</li>
               			</ul>
                        <ul class="nav navbar-top-links pull-right">
							<li><a href="#" class="aside-toggle navbar-aside-icon"><i class="pci-ver-dots"></i></a></li>
						</ul>
                	</div>
            	</div>
			</header>
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Main Content

?>             
            <div class="boxed">
				<div id="content-container">
                
					<!--                
					<div id="page-title">
                    	<h1 class="page-header text-overflow">Categorisation Toolkit</h1> 
                	</div>
                    //-->
                    
              		<!-- Page Content Open -->
             		<div id="page-content">
                        <div class="row">
                        
                        	<!-- Titles Table Panel -->
                        	<div class="col-lg-6">
                            	<div id="panel-titles" class="panel panel-bordered" style="min-height:770px;">
                                	<div class="panel-heading">
                                    	<!-- 
                                        <div class="panel-control"><button id="panel-titles-refresh" class="btn"><i class="ti-reload icon-lg"></i></button></div> 
                                        //-->
                                    	<h3 class="panel-title">WORKS BY TITLE</h3>
                                	</div>
                                    <div class="panel-body" style="text-align: justify;">
                                        <p class="pad-btm">This tool sits within the <em>Mapping Print, Charting Enlightenment</em> project. To view a list of works from the database that do not have assigned keywords, please click on a letter of the alphabet below which will query the database using your selection as the first character of a work's title. Please wait for the page to finish loading, after which you can scroll through the complete list.</p>
                                        <div class="pad-btm mar-btm">
                                            <div class="btn-toolbar">
                                                <div class="btn-group">
                                                    <?php

/////////////////////////////////////////////////////////// Get Titles By First Letter
                                                        
                                                        foreach($alphabet as $alpha) {
															echo "<a href=\"./?searchLetter=".$alpha."\" target=\"_self\">";
                                                            echo "<button class=\"btn btn-default btn-active-purple";
                                                            if(($alpha == $searchLetter)) { echo " active"; }
                                                            echo "\"><strong>".strtoupper($alpha)."</strong></button>";	
															echo "</a>";
                                                        }
														
/////////////////////////////////////////////////////////// Get Titles By First Number														
														
														echo "<a href=\"./?searchLetter=0\" target=\"_self\">";
                                                        echo "<button class=\"btn btn-default btn-active-purple";
                                                        if(($searchLetter == "0")) { echo " active"; }
                                                        echo "\"><strong>0 - 9</strong></button>";	
														echo "</a>";
														
/////////////////////////////////////////////////////////// Get Titles By First Other Character														
														
                                                        echo "<a href=\"./?searchLetter=*\" target=\"_self\">";
                                                        echo "<button class=\"btn btn-default btn-active-purple";
                                                        if(($searchLetter == "*")) { echo " active"; }
                                                        echo "\"><strong>*</strong></button>";	
														echo "</a>";
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    	<table id="dt-basic" class="table table-striped table-hover" cellspacing="0" width="100%">
                                    		<thead>
                                     			<tr>
                                        			<th>ID</th>
                                         			<th>TITLE</th>
                                                    <th>VALID</th>
                                                    <th>PARIS</th>
                                                    <th>TAGS</th>
                                      			</tr>
                                  			</thead>
                                     		<tbody>
                                        	<?php
                                                
												$queryD = "SELECT * FROM manuscript_books WHERE ";
												if(($searchLetter != "0") && ($searchLetter != "*")) {
													$queryD .= "super_book_title LIKE \"$searchLetter%\" ";
												}
												if(($searchLetter == "0")) {
													$queryD .= "(";
													$queryD .= "super_book_title LIKE \"0%\" OR ";
													$queryD .= "super_book_title LIKE \"1%\" OR ";
													$queryD .= "super_book_title LIKE \"2%\" OR ";
													$queryD .= "super_book_title LIKE \"3%\" OR ";
													$queryD .= "super_book_title LIKE \"4%\" OR ";
													$queryD .= "super_book_title LIKE \"5%\" OR ";
													$queryD .= "super_book_title LIKE \"6%\" OR ";
													$queryD .= "super_book_title LIKE \"7%\" OR ";
													$queryD .= "super_book_title LIKE \"8%\" OR ";
													$queryD .= "super_book_title LIKE \"9%\"";
													$queryD .= ") ";
												}
												if(($searchLetter == "*")) {
													$queryD .= "(";
													$queryD .= "super_book_title NOT LIKE \"0%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"1%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"2%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"3%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"4%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"5%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"6%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"7%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"8%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"9%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"A%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"B%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"C%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"D%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"E%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"F%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"G%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"H%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"I%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"J%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"K%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"L%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"M%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"N%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"O%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"P%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"Q%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"R%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"S%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"T%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"U%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"V%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"W%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"X%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"Y%\" AND ";
													$queryD .= "super_book_title NOT LIKE \"Z%\"";
													$queryD .= ") ";
												}
												$queryD .= "ORDER BY super_book_title ASC";
												$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
												while($rowD = mysqli_fetch_row($mysqli_resultD)) {
													$ID = strtoupper($rowD[0]);
													
/////////////////////////////////////////////////////////// Display ID													
													
													echo "<tr>";
													echo "<td class=\"text-right\">$ID</td>";
													
/////////////////////////////////////////////////////////// Display title													
													
													echo "<td>";
													echo "<a href=\"javascript: ";
													echo "var dataE = 'ID=$ID'; ";
													echo "var doDiv = $('#titleDetail').fadeOut('fast', function(){ ";
													echo "var searchVal = $('#titleDetail').load('./data_titles.php',dataE, function(){ ";
													echo "var doDivAlso = $('#titleDetail').fadeIn('slow'); ";
													echo "}); ";
													echo "}); ";
													echo "\">";
													echo "<strong>$rowD[1]</strong>";
													echo "</a>";
													echo "</td>";
													
/////////////////////////////////////////////////////////// Display valid status													
													
													echo "<td>";
													$valid = "y";
													$queryFU = "SELECT * FROM manuscript_cat_problem WHERE super_book_code = \"$ID\" ";
													$mysqli_resultFU = mysqli_query($mysqli_link, $queryFU);
													while($rowFU = mysqli_fetch_row($mysqli_resultFU)) {
														$valid = "no";
													}
													if(($valid == "y")) {
														echo "<a href=\"javascript: var = doToggle = toggleValid('btn_V".$ID."');\">";
														echo "<button id=\"".$ID."\" class=\"btn btn-block btn-default btn-toggle\">";													
														echo "Yes";
														echo "</button>";
														echo "</a>";
													} else {
														echo "<a href=\"javascript: var = doToggle = toggleValid('btn_V".$ID."');\">";
														echo "<button id=\"".$ID."\" class=\"btn btn-block btn-pink btn-toggle\">";
														echo "No";														
														echo "</button>";
														echo "</a>";
													}
													echo "</td>";
													
/////////////////////////////////////////////////////////// Display parisian tag status													
													
													echo "<td>";
													if(($rowD[3] != "")) {
														echo "<a href=\"javascript: ";
														echo "var dataE = 'ID=$ID&action=yes'; ";
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
														echo "\">";
														echo "<button id=\"btn_P".$ID."\" class=\"btn btn-block btn-default\">Yes</button>";
														echo "</a>";
													} else {
														echo "<a href=\"javascript: ";
														echo "var dataE = 'ID=$ID&action=no'; ";
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
														echo "\">";
														echo "<button id=\"btn_P".$ID."\" class=\"btn btn-block btn-pink\">No</button>";	
														echo "</a>";
													}
													echo "</td>";

/////////////////////////////////////////////////////////// Display tags status
													
													echo "<td>";
													if(($rowD[2] != "")) {
														echo "<a href=\"javascript: ";
														echo "var dataE = 'ID=$ID&action=yes'; ";
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
														echo "\">";
														echo "<button id=\"btn_".$ID."\" class=\"btn btn-block btn-success\">Yes</button>";
														echo "</a>";
													} else {
														echo "<a href=\"javascript: ";
														echo "var dataE = 'ID=$ID&action=no'; ";
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
														echo "\">";
														echo "<button id=\"btn_".$ID."\" class=\"btn btn-block btn-pink\">No</button>";	
														echo "</a>";
													}
													echo "</td>";
													echo "</tr>\n";
												}
												
											?>
                                			</tbody>
                                 		</table>
                                    </div>
								</div>
                            </div>
                            
                            <!-- Further Detail Panel -->
                          	<div class="col-lg-3">
                            	<div id="panel-detail" class="panel panel-colorful panel-dark panel-bordered" style="min-height:770px;">
                                	<div class="panel-heading">
                                    	<!-- 
                                        <div class="panel-control"><button id="panel-detail-refresh" class="btn"><i class="ti-reload icon-lg"></i></button></div> 
                                        //-->
                                    	<h3 class="panel-title">DETAILS</h3>
                                	</div>
                                    <div id="titleDetail" class="panel-body text-light" style="text-align: left;">
										<p>When a title is selected from the database, details from one of its editions will display here. 
                                        If there is more than one edition attached to the work then the edition is selected randomly.</p>
										<p>&nbsp;</p>
										<p><img class="mar-top bord-all" src="./icons/cover_image.jpg" width="100%" border="0"></p>
                                    </div>
								</div>                                
                            </div>                           
                            
                            <!-- Tag Title Panel -->
                          	<div class="col-lg-3">
                            	<div id="panel-detail" class="panel panel-colorful bg-gray-dark panel-bordered mar-rgt" style="min-height:770px;">
                                	<div class="panel-heading">
                                    	<!-- 
                                        <div class="panel-control"><button id="panel-detail-refresh" class="btn"><i class="ti-reload icon-lg"></i></button></div> 
                                        //-->
                                    	<h3 class="panel-title">KEYWORDS</h3>
                                	</div>
                                    <div id="titleTags" class="panel-body text-dark" style="text-align: left;">
										<p>When the <em>Add Keywords</em> or <em>Edit Keywords</em> button is clicked, this panel will 
                                        load the form for assigning, adding, 
                                        deleting or modifying existing keywords that are associated with the work highlighted in the middle panel. 
                                        Please note that keywords are assigned at the <em>Work</em> level rather than at the edition or manifestation level.</p>
                                    </div>
								</div>
                            </div>                             
                        </div>
                 	</div>
                    
                    <!-- Page Content Close -->
				</div>
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Navigation

?>                 
  				<nav id="mainnav-container">
                	<div id="mainnav">
						<div id="mainnav-menu-wrap">
                        	<div class="nano">
                            	<div class="nano-content">
                                    <div id="mainnav-shortcut">
                                        <ul class="list-unstyled">
                                            <li class="col-xs-4" data-content="Assign Keywords">
                                            	<a class="shortcut-grid" href="./index.php"><i class="ti-info-alt"></i></a></li>
                                            <!-- <li class="col-xs-4" data-content="Visualise Keywords (Coming Soon)">
                                            	<a class="shortcut-grid" id="inlineModal" href="#inlineViz"><i class="ion-stats-bars"></i></a></li> //-->
                                            <li class="col-xs-4" data-content="Edit Keywords List">
                                            	<a class="shortcut-grid" id="inlineModalEditor" href="#inlineKeywordEditor"><i class="ti-tag"></i></a></li>
                                        </ul>
                                    </div>
									<!-- 
                                    <ul id="mainnav-menu" class="list-group">
										<li class="list-header">Options</li>
										<li><a href="#"><i class="ti-clip"></i><span class="menu-title">Link 1</span></a></li>
										<li><a href="#"><i class="ti-marker"></i><span class="menu-title">Link 2</span></a></li>
										<li><a href="#"><i class="ti-world"></i><span class="menu-title">Link 3</span></a></li>
                                        <li><a href="#"><i class="ti-reload"></i><span class="menu-title">Link 4</span></a></li>
                                        <li><a href="#"><i class="ti-help"></i><span class="menu-title">Link 5</span></a></li>
									</ul>
                                    //-->
                                </div>
                        	</div>
                   		</div>
					</div>
           		</nav>              
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Aside

?>                
                <aside id="aside-container">
                    <div id="aside">
                        <div class="nano">
                            <div class="nano-content">
                                <ul class="nav nav-tabs nav-justified">
                                    <li class="active"><a href="#asd-tab-1" data-toggle="tab"><i class="ion-ios-pricetag"></i></a></li>
                                    <li><a href="#asd-tab-2" data-toggle="tab"><i class="ion-ios-home"></i></a></li>
                                    <li><a href="#asd-tab-3" data-toggle="tab"><i class="ion-ios-star"></i></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="asd-tab-1">
                                        <p class="pad-all text-lg">TAGS LIST</p>
                                        <div class="pad-hor" style="text-align: left;">
                                            <div class="input-group-btn">
					                            <button data-toggle="dropdown" class="btn btn-block btn-dark dropdown-toggle" type="button">
					                                Select Letter <i class="dropdown-caret"></i>
					                            </button>
					                            <ul class="dropdown-menu" style="width: 99%;">
												<?php
													foreach($alphabet as $al) {
														echo "<li><a href=\"javascript: ";
														echo "var dataE = 'letter=".ucwords($al)."&action=find'; ";
														echo "var doDivA = $('#keywordsList').fadeOut('fast', function(){ ";
														echo "var searchValA = $('#keywordsList').load('./data_keywords_desc.php', dataE, function(){ ";
														echo "var doDivAlsoA = $('#keywordsList').fadeIn('slow'); ";
														echo "}); ";
														echo "}); ";
														echo "\" style=\"color: #000000;\">".ucwords($al)."</a></li>";	
													}
												?>
					                            </ul>
					                        </div>
                                            <?php
												echo "<div id=\"tagsSearchInput\" class=\"mar-ver text-dark\">";
												echo "<input type=\"text\" ";
    											echo "name=\"taggerSearch\" ";
    											echo "autocomplete=\"off\" ";
    											echo "id=\"taggerSearch\" ";
    											echo "placeholder=\"Start typing a tag or click to clear ...\" ";
    											echo "class=\"input-sm text-dark tm-input\" ";
    											echo "style=\"display: block; width: 100%; min-width: 100%;\" ";
    											echo "onclick=\"var cleanBar = $('#taggerSearch').typeahead('val','');\" />";
												echo "</div>";
											?>
                                            <?php
												echo "<div class=\"panel panel-bordered panel-primary mar-top\" ";
												echo "style=\"border: 0px solid 1690F3; background-color: #063D6B;\">";
    											echo "<div class=\"panel-body\">";
												echo "<div id=\"keywordsList\" class=\"text-light text-left mar-top mar-btm\" style=\"padding-bottom: 25px;\">";
												echo "To view a list of keywords from the database, please select a letter of the alphabet ";
												echo "from the above drop-down menu which will query the database using your ";
												echo "selection as the first character of a keyword or begin typing a tag in the input box above. ";
												echo "</div>";
												echo "</div>";
												echo "</div>";
											?>
                                        </div>
                                        <p>&nbsp;</p>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div class="tab-pane fade" id="asd-tab-2">
                                        <p class="pad-all text-lg">HISTORY &nbsp; <?php
                                        	echo "<a href=\"javascript: ";
											echo "var doAuditA = $('#auditHistoryPanel').fadeOut('fast', function(){ ";
											echo "var doAuditB = $('#auditHistoryPanel').load('./data_history.php', '$ref=".time()."', function(){ ";
											echo "var doAuditC = $('#auditHistoryPanel').fadeIn('slow'); ";
											echo "}); ";
											echo "}); ";
											echo "\" >";
										?><i class="ion-loop text-right"></i></a></p>
                                        <div id="auditHistoryPanel" class="pad-hor" style="text-align: left;">
											<?php
												$reload = "yes";
												include("./data_history.php");
											?>
                                        </div>
                                        <p>&nbsp;</p>
                                        <p>&nbsp;</p>
                                    </div>
                                    <div class="tab-pane fade" id="asd-tab-3">
                                    	<p class="pad-all text-lg">IDEOLOGICAL KEYWORDS</p>
                                    	<div class="pad-hor" style="text-align: left;">
                                        	<?php
                                    			echo "<div class=\"panel panel-bordered panel-primary mar-top\" ";
												echo "style=\"border: 0px solid 1690F3; background-color: #063D6B;\">";
    											echo "<div class=\"panel-body\">";
												echo "<div id=\"ideologicalList\" class=\"text-light text-left mar-top mar-btm\" style=\"padding-bottom: 25px;\">";
												echo "<p>";
                                    			foreach($ideologicalWords as $iw) {													
													$queryZ = "SELECT * FROM keywords WHERE keyword LIKE \"$iw\" ORDER BY keyword ASC";
													$mysqli_resultZ = mysqli_query($mysqli_link, $queryZ);
													echo mysqli_error($mysqli_resultZ);
													while($rowZ = mysqli_fetch_row($mysqli_resultZ)) {	
														echo "<a href=\"javascript: ";
														echo "var doThis = tagApi.tagsManager('pushTag','".ucwords($rowZ[1])."'); ";
														echo "var dataA = 'kID=".$rowZ[0]."&action=yes'; ";
														echo "var doAssA = $('#tagAssociationsList').fadeOut('fast', function(){ ";
														echo "var doAssB = $('#tagAssociationsList').load('./data_keywords_assoc.php', dataA, function(){ ";
														echo "var doAssC = $('#tagAssociationsList').fadeIn('slow'); ";
														echo "}); ";
														echo "}); ";
														echo "\" ";
														echo "style=\"color:#FFFFFF;\" ";
														echo "class=\"add-tooltip\" ";
														echo "data-toggle=\"tooltipABC\" ";
														echo "data-container=\"body\" ";
														echo "data-placement=\"left\" ";
														echo "data-original-title=\"";
														echo "<strong>".strtoupper($rowZ[1])."</strong><br /><br />$rowZ[2]";
														echo "\" ";
														echo ">";	
														echo ucwords($rowZ[1]);
														echo "</a>";
														echo "<br />";
													}
												}
												echo "</p>";
												echo "</div>";
												echo "</div>";
												echo "</div>";                                    	
											?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
			</div>
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Footer

?>         
<!--
        	<footer id="footer">
				<div class="hide-fixed pull-right pad-rgt">Last Updated: 6 September 2016</div>
				<p class="pad-lft">Western Sydney University &#0169; <?php echo date("Y",time()); ?></p>
			</footer>
            <button class="scroll-top btn"><i class="pci-chevron chevron-up"></i></button>
//-->
        </div>
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Viz Modal

?>        
        <div style="display:none"><div id="inlineViz">Nothing to see here ... yet!</div></div>
        <div style="display:none"><div id="inlineKeywordEditor" class="pad-all"></div></div>
        <div style="display:none"><div id="theDarkCloset"></div></div>
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Scripts

?> 
		<script language="javascript" type="text/javascript" src="./js/typeahead.bundle.js"></script>
		<script language="javascript" type="text/javascript" >
		
			$('[data-toggle="tooltipABC"]').tooltip({
				template: '<div class="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner" style="border: 3px solid #ffffff; color: #000000; background-color:#6ab5f1; padding:20px;"></div></div>',
				html: true,
				trigger : 'hover'
			});
		
			$(window).on('load', function() {	
			
				$( ".btn-toggle" ).click(function(event) {
  					var currentState = $(this).html();
					if(currentState == "Yes") {
						var changeState = $(this).html('No');
						var doClass = $(this).removeClass('btn-default').addClass('btn-pink');
						var changeID = $(this).attr('id');
						var dataE = "action=add&super_book_code=" + changeID;
						var searchValP = $('#theDarkCloset').load('./data_post_valid.php', dataE, function(){});
						
					} else {
						var changeState = $(this).html('Yes');
						var doClass = $(this).removeClass('btn-pink').addClass('btn-default');
						var changeID = $(this).attr('id');
						var dataE = "action=delete&super_book_code=" + changeID;
						var searchValQ = $('#theDarkCloset').load('./data_post_valid.php', dataE, function(){});
					}
				});
					
				$('#dt-basic').dataTable( {
        			"responsive": false,
					"order": [[ 1, "asc" ]],
					"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]], 
					"scrollY": "380px",
					"scrollCollapse": false,
	       			"paging": false
//        			"language": {
//	           			"paginate": {
//            				"previous": '<i class="ti-angle-left"></i>',
//              			"next": '<i class="ti-angle-right"></i>'
//	           			}
//	      			}
    			});	
				
				$("#inlineModal").fancybox({
					helpers : {
						overlay : {
							css : {
								'background' : 'rgba(38, 50, 56, 0.90)'
							}
						}
					},					
					maxWidth	: 1200,
					maxHeight	: 1000,
					fitToView	: false,
					width		: '90%',
					height		: '90%',
					autoSize	: false,
					closeClick	: true,
					openEffect	: 'fade',
					closeEffect	: 'fade',
					openSpeed	: 300,
					closeSpeed	: 100
				});		
				
				$("#inlineModalEditor").fancybox({
					helpers : {
						overlay : {
							css : {
								'background' : 'rgba(38, 50, 56, 0.90)'
							}
						}
					},		
					href		: '#inlineKeywordEditor',	
					afterLoad   : function() {
							this.content = $('#inlineKeywordEditor').load('./data_editor_keywords.php');
    				},	
					maxWidth	: 1200,
					maxHeight	: 1000,
					fitToView	: false,
					width		: '90%',
					height		: '90%',
					autoSize	: false,
					closeClick	: false,
					openEffect	: 'fade',
					closeEffect	: 'fade',
					openSpeed	: 300,
					closeSpeed	: 100
				});			
				
				var preKeywordsB = new Bloodhound({
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
 
    			preKeywordsB.initialize();
				
				$("#taggerSearch").typeahead(null, {
      				name: 'preKeywordsB',
      				displayKey: 'name',
      				source: preKeywordsB.ttAdapter()
    			}).on('typeahead:selected', function (e, d) {
        			tagApi.tagsManager("pushTag", d.name);
					var dataA = "kName="+d.name+"&action=yes";
					var doAssA = $('#tagAssociationsList').fadeOut('fast', function(){
						var doAssB = $('#tagAssociationsList').load('./data_keywords_assoc.php',dataA, function(){
							var doAssB = $('#tagAssociationsList').fadeIn('slow');
						});
					});
    			});									
							
			});
		
		</script>
    </body>
</html>
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Finish

	include("../era.dbdisconnect.php");

?> 