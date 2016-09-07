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

/////////////////////////////////////////////////////////// Page header

?>
<!--


	Project: French Book Trade in Enlightenment Europe, web version 2.0 (2013-2016)
	Project Chief Investigator: Professor Simon Burrows
	Project Base: School of Humanities and Communication Arts, Western Sydney University
	Project Methodology: Procedural Scripting PHP | MySQL | JQuery



	FOR ALL ENQUIRIES ABOUT CODE

	Who:	Dr Jason Ensor
	Email: 	j.ensor@westernsydney.edu.au | jasondensor@gmail.com
	Web: 	http://www.jasonensor.com
	Mobile:	0419 674 770



  	WEB FRAMEWORK

  	Bootstrap Twitter v3.1.1 | http://getbootstrap.com/
  	Font Awesome v4.0.3 | http://fortawesome.github.io/Font-Awesome/
  	Google Fonts API | http://fonts.googleapis.com
  	Modernizr v.2.6.2 | http://modernizr.com/
  	JQuery v.1.11.0 | http://jquery.com/download/
  	JQuery JQPlot v.1.0.8 | http://www.jqplot.com/
  	JQuery UI v.10.4 | https://jqueryui.com/





























//-->
<!DOCTYPE html>
<html lang="en">
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
    	<title>Manuscripts - Digital Humanities Research Group - Western Sydney University</title>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">      
        <meta name="description" content="Manuscripts, Western Sydney University. Project Chief Investigators: Simon Burrows and Jason Ensor. Development: Dr Jason Ensor" />
        <meta name="robots" content="noindex,nofollow" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta http-equiv='cache-control' content='no-cache'>
		<meta http-equiv='expires' content='0'>
		<meta http-equiv='pragma' content='no-cache'>      
		<meta name="msapplication-TileColor" content="#ffffff" />
		<meta name="msapplication-TileImage" content="./icons/ms-icon-144x144.png" />        
        <meta name="theme-color" content="#ffffff" />         
		<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700&subset=latin,cyrillic-ext,latin-ext,cyrillic' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="./js/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="./js/jquery-ui/themes/base/jquery.ui.all.css">
        <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="./css/bootstrap-theme.min.css">
        <link rel="stylesheet" type="text/css" href="./css/bootstrap-select.css">
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
        <script language="javascript" type="text/javascript" src="./js/jquery-2.1.1.js"></script>
        <script language="javascript" type="text/javascript" src="./js/bootstrap.min.js"></script>
        <!--[if lt IE 9]>
			<script language="javascript" type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
        <link rel="stylesheet" type="text/css" href="./js/bootstrap3-editable/css/bootstrap-editable.css">  
        <script language="javascript" type="text/javascript" src="./js/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
		<link rel="stylesheet" type="text/css" href="./css/datepicker.css">  
        <style type="text/css">
		
			body {
				padding-top: 50px;
				overflow: hidden;
			}
			
			#wrapper {
				min-height: 100%;
				height: 100%;
				width: 100%;
				position: absolute;
				top: 0px;
				left: 0;
				display: inline-block;
			}
			
			#main-wrapper {
				height: 100%;
				min-height: 100%;
				padding: 50px 0 0px 0;
				margin: 0 auto -46px;
			}
			
			#main {
				position: relative;
				height: 100%;
				overflow-y: auto;
				padding: 0 20px 40px 20px;
				text-align: justify;
			}
			
			#sidebar-wrapper {
				height: 100%;
				padding: 50px 0 0px 0;
				position: fixed;
				border-right: 1px solid gray;
			}
			
			#sidebar {
				position: relative;
				height: 100%;
				overflow-y: scroll;
				padding: 0 20px 40px 20px;
				text-align: justify;
			}
			
			#sidebar .list-group-item {
				border-radius: 0;
				border-left: 0;
				border-right: 0;
				border-top: 0;
			}
			
			#footer {
				height: 1.7em;
				background-color: #495374;
				color: #ffffff;
				padding: 3px;
				bottom: 0px;
				position: absolute;
				width: 100%;
				text-align: center;
				font-size: 1.0em;
			}
			
			@media (min-width: 992px) {
				#main-wrapper {
					float:right;
				}
			}
			
			@media (max-width: 992px) {
				body {
					padding-top: 0px;
				}
			}
			
			@media (max-width: 992px) {
				#wrapper {
				   height: 100%;
					overflow-y: auto;
				}
			}
			
			@media (max-width: 992px) {
				#main-wrapper {
					height: 100%;
					padding-top: 0px;
				}
			}
			
			@media (max-width: 992px) {
				#main {
					overflow: hidden;
					height: auto;
					padding-top: 0px;
				}
			}
			
			@media (max-width: 992px) {
				#sidebar {
					overflow: hidden;
					height: auto;
				}
			}
			
			@media (max-width: 992px) {
				#sidebar-wrapper {
					overflow: hidden;
					position: static;
					height: auto;
					border-right:0;
					margin-bottom: 0px;
				}
			}
			
			@media (max-width: 992px) {
				#footer {
					display: none;
				}
			}
			
			.bs-callout{padding:20px;margin:20px 0;border:1px solid #eee;border-left-width:5px;border-radius:3px}
			.bs-callout h4{margin-top:0;margin-bottom:5px}
			.bs-callout p:last-child{margin-bottom:0}
			.bs-callout code{border-radius:3px}
			.bs-callout+.bs-callout{margin-top:-5px}
			.bs-callout-danger{border-left-color:#d9534f}
			.bs-callout-danger h4{color:#d9534f}
			.bs-callout-warning{border-left-color:#f0ad4e}
			.bs-callout-warning h4{color:#f0ad4e}
			.bs-callout-info{border-left-color:#5bc0de}
			.bs-callout-info h4{color:#5bc0de}
			
			.ui-autocomplete {
				max-height: 360px;
				overflow-y: auto;
				/* prevent horizontal scrollbar */
				overflow-x: hidden;
			}
			
			* html .ui-autocomplete {
				height: 360px;
			}
			
		</style>
    </head>
    <body style="overflow-x: hidden;">
	<!--[if lt IE 7]>
    	<p class="browsehappy">You are using an <strong>outdated</strong> browser. 
    	Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Navigation header bar

?>  
			<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
				<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="./index.php" style="color:#569669;"><em>Manuscripts</em></a>
					</div>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
        					<li class="dropdown">
          						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Menu <span class="caret"></span></a>
          						<ul class="dropdown-menu" role="menu">
                                	<li><a href="./index.php">Home</a></li>
                                    <li><a href="./admin/categorisation/" target="_CATEGORISATION">Categorisation</a></li>
            						<li><a href="http://fbtee.uws.edu.au/" target="_FBTEE">FBTEE</a></li>
            						<li><a href="http://fbtee.uws.edu.au/stn/interface/" target="_STN">STN</a></li>
          						</ul>
        					</li>
                            
                            <li class="dropdown">
                            	<a href="#" class="dropdown-toggle" data-toggle="dropdown">View Records <span class="caret"></span></a>
          						<ul class="dropdown-menu" role="menu">
                                	<li><a href="javascript: var dataE = 'alias=All%20Sales%20Records&filter='; var enameVal = $('#manuscriptRecords').load('./data_records_sales.php',dataE);">Show All Sales Records</a></li>
                                    <li><a href="javascript: var dataE = 'alias=Stock%20Sales%20Records&filter=Stock%20Sale'; var enameVal = $('#manuscriptRecords').load('./data_records_sales.php',dataE);">Show All Stock Sales</a></li>
                                    <li><a href="javascript: var dataE = 'alias=Sales%20of%20Privilege%20Records&filter=Sale%20of%20Privilege'; var enameVal = $('#manuscriptRecords').load('./data_records_sales.php',dataE);">Show All Sales of Privilege</a></li>
                                    <li><a href="javascript: var dataE = 'alias=All%20Records&filter='; var enameVal = $('#manuscriptRecords').load('./data_records.php',dataE);">Show All Catalogue Records</a></li>
            						<li><a href="javascript: var dataE = 'alias=Stamped%20Records&filter=Stamped'; var enameVal = $('#manuscriptRecords').load('./data_records.php',dataE);">Show All Stamped Books</a></li>
                                    <li><a href="javascript: var dataE = 'alias=Confiscations&filter=Confiscation'; var enameVal = $('#manuscriptRecords').load('./data_records.php',dataE);">Show All Confiscations</a></li>
                                    <li><a href="javascript: var dataE = 'alias=Print%20Permissions&filter=Print%20Permission'; var enameVal = $('#manuscriptRecords').load('./data_records.php',dataE);">Show All Print Permissions</a></li>
									<li><a href="javascript: var dataE = 'alias=All%20New%20Editions&FIELD=book_code&filter=nbk&TABLE=manuscript_books_editions'; var enameVal = $('#manuscriptRecords').load('./data_foundations.php',dataE);">Show All New Editions</a></li>
                                    <li><a href="javascript: var dataE = 'alias=All%20New%20Superbooks&FIELD=super_book_code&filter=zspbk&TABLE=manuscript_books'; var enameVal = $('#manuscriptRecords').load('./data_foundations_superbooks.php',dataE);">Show All New Superbooks</a></li>
                                </ul>
                            </li>
                            
                            <li class="dropdown">
                            	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Visualise Events <span class="caret"></span></a>
          						<ul class="dropdown-menu" role="menu">
									<li><a href="./data_visualise_nodes_editions_links.php" target="_viz_editionsLinks">Show Only Linked Editions</a></li>
                                    <li><a href="./data_visualise_nodes_superbooks_links.php" target="_viz_booksLinks">Show Only Linked SuperBooks</a></li>
                                    <li><a href="./data_visualise_nodes_editions.php" target="_viz_editions">Show All Editions</a></li>
                                    <li><a href="./data_visualise_nodes_superbooks.php" target="_viz_books">Show All SuperBooks</a></li>
                                </ul>
                            </li>
                            
                             <li class="dropdown">
                            	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Visualise Sales <span class="caret"></span></a>
          						<ul class="dropdown-menu" role="menu">
									<li><a href="./data_visualise_nodes_stock_sales_sellers.php" target="_viz_stockSalesBuyersSellers">Show Buyers and Sellers</a></li>
                                    <li><a href="./data_visualise_nodes_stock_sales_superbooks_buyers.php" target="_viz_stockSalesBuyersSuperbooks">Show Buyers and Superbooks</a></li>
                                    <li><a href="./data_visualise_nodes_stock_sales_superbooks_sellers.php" target="_viz_stockSalesSellersSuperbooks">Show Sellers and Superbooks</a></li>
                                    <li><a href="./data_visualise_nodes_stock_sales_linked_superbooks.php" target="_viz_stockSalesSuperbooksLinks">Show Only Linked SuperBooks</a></li>
                                    <li><a href="./data_visualise_nodes_stock_sales_linked_editions.php" target="_viz_stockSalesEditionsLinks">Show Only Linked Editions</a></li>
                                    <li><a href="./data_visualise_nodes_stock_sales_superbooks.php" target="_viz_stockSalesSuperbooks">Show Only SuperBooks</a></li>
                                    <li><a href="./data_visualise_nodes_stock_sales_scatterplot_sellers.php" target="_viz_stockSalesScatterSellers">Show Sellers Scatterplot</a></li>
                                    <li><a href="./data_visualise_nodes_stock_sales_scatterplot_buyers.php" target="_viz_stockSalesScatterBuyers">Show Buyers Scatterplot</a></li>
                                    <li><a href="./data_visualise_nodes_stock_sales_highcharts_sales.php" target="_viz_stockSalesScatterBuyers">Show Sales Scatterplot</a></li>
                                </ul>
                            </li>
                            
      					</ul>
      					<ul class="nav navbar-nav navbar-right">
                            <form class="navbar-form navbar-left" role="search">
<!--                            
								<label class="radio-inline">
									<input type="radio" name="inputSearchRecords" id="inputSearchRecords" value="AUDITOR" style="width: 1.2em; height:1.2em;">
										Auditor | Sale
								</label>
                                &nbsp;
								<label class="radio-inline">
									<input type="radio" name="inputSearchRecords" id="inputSearchRecords" value="PLACE" style="width: 1.2em; height:1.2em;">
										Place
								</label>
                                &nbsp;
								<label class="radio-inline">
									<input type="radio" name="inputSearchRecords" id="inputSearchRecords" value="DEALER" style="width: 1.2em; height:1.2em;">
										Dealer | Purchaser
								</label>
                                &nbsp;
                                <label class="radio-inline">
									<input type="radio" name="inputSearchRecords" id="inputSearchRecords" value="EDITION" style="width: 1.2em; height:1.2em;" checked>
										Edition
								</label>
                                &nbsp;
								&nbsp;
//-->
								<label class="radio-inline">
									<input type="radio" name="inputSearchRecords" id="inputSearchRecordsA" value="SALE" style="width: 1.2em; height:1.2em;">
										Sales
								</label>
                                &nbsp;
								<label class="radio-inline">
									<input type="radio" name="inputSearchRecords" id="inputSearchRecordsB" value="STAMP" style="width: 1.2em; height:1.2em;" checked>
										Stampings
								</label>
                                &nbsp;
								&nbsp;
								<div class="input-group">
                                    <div class="input-group-btn">
            							<button class="btn btn-default" disabled><i class="glyphicon glyphicon-search"></i></button>
          							</div>
          							<input type="text" class="form-control" placeholder="Search Events ..." name="searchText" id="searchText" onclick="var emptyThis = $('#searchText').val('');" >
        						</div>
        						<button id="searchRecordEvents" name="searchRecordEvents" type="submit" class="btn btn-default">Find Record</button>
      						</form>
      					</ul>
    				</div>
  				</div>
			</nav>
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Forms

?>    
			<div id="wrapper">
  				<div id="sidebar-wrapper" class="col-md-6">
            		<div id="sidebar">
                		<div class="page-header">
                			<h3>Document Events</h3>
              			</div>
                        <p>
							<form class="form-horizontal" role="form">
<?php

/////////////////////////////////////////////////////////////////// Switcher Catalogue

								if(($switch == "")) {

?>
                            	<div class="form-group">
									<div class="col-sm-2"><label for="inputArchive" class="col-sm-2 control-label">Source</label></div>
									<div class="col-sm-4">
                                    	<select class="selectpicker form-control" id="inputArchive" title="Please Select an Archive" data-live-search="true">
                                        	<option data-hidden="true"></option>
											<?php
												$archives = array();                                    
 												$queryD = "SELECT DISTINCT(archiveName) FROM manuscript_archive_ref WHERE archiveName != \"\" AND archiveName IS NOT NULL ORDER BY archiveName ASC"; 
												$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
												while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
													$archives[] = $rowD[0];
												}
												$cA = count($archives);
												for($x=0;$x<$cA;$x++) {
													$tmp = $archives[$x];
												//	echo "<optgroup label=\"$tmp\" style=\"text-align:justify; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; white-space: pre-wrap; word-wrap: break-word;\">";
													$queryD = "SELECT * FROM manuscript_archive_ref WHERE archiveName = \"$tmp\" ORDER BY msNumber ASC"; 
													$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
													while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
														$a = $rowD[4];
														$b = $rowD[3];
														$c = $rowD[2];
														$d = $rowD[1];
														echo "<option value=\"$a\" style=\"text-align:justify; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; white-space: pre-wrap; word-wrap: break-word;\">";
														echo "$c $a | $b, $d</option>";
													}
												//	echo "</optgroup>";	
												}		
											?>
                                            <option disabled>&nbsp;</option>                                           
                                        </select>
									</div>
									<div class="col-sm-2"><label for="inputPlaceName" class="col-sm-2 control-label">Place</label></div>
									<div class="col-sm-4" id="inputPlace">
										<input type="text" class="form-control" id="inputPlaceName" placeholder="Location of Activity" disabled>
									</div>      
								</div>
								<div class="form-group">
									<div class="col-sm-2"><label for="inputAuditor1" class="col-sm-2 control-label">Auditor</label></div>
									<div class="col-sm-4" id="inputAuditorA">
										<input type="text" class="form-control" id="inputAuditor1" placeholder="Inspector / Adjoint A" disabled>
									</div>
									<div class="col-sm-2"><label for="inputAuditor2" class="col-sm-2 control-label">Auditor</label></div>
									<div class="col-sm-4" id="inputAuditorB">
										<input type="text" class="form-control" id="inputAuditor2" placeholder="Inspector / Adjoint B" disabled>
									</div>
								</div>
                                <div class="form-group">
									<div class="col-sm-2"><label for="inputAuditor3" class="col-sm-2 control-label">Auditor</label></div>
									<div class="col-sm-4" id="inputAuditorC">
										<input type="text" class="form-control" id="inputAuditor3" placeholder="Inspector / Adjoint C [Optional]" disabled>
									</div>
									<div class="col-sm-2"><label for="inputDealerName" class="col-sm-2 control-label">Dealer</label></div>
									<div class="col-sm-4" id="inputDealer">
										<input type="text" class="form-control" id="inputDealerName" placeholder="Bookseller" disabled>
									</div>
								</div>
<?php

								} else {

/////////////////////////////////////////////////////////////////// Switcher Auction

?>
                            	<div class="form-group">
                                    <div class="col-sm-2"><label for="inputSale" class="col-sm-2 control-label">Sale</label></div>
									<div class="col-sm-4" id="inputSaleHeader">
										<select class="selectpicker form-control" id="inputSale" title="Please Select Sale Name" data-live-search="true">
                                        	<option data-hidden="true"></option>
											<?php

												$queryD = "SELECT manuscript_sales_events.Client_Code, manuscript_dealers.Dealer_Name, manuscript_sales_events.salesNumber, manuscript_dealers.Place, manuscript_dealers.Profession, manuscript_sales_events.msNumber ";
												$queryD .= "FROM manuscript_sales_events, manuscript_dealers ";
												$queryD .= "WHERE manuscript_dealers.Client_Code = manuscript_sales_events.Client_Code ";
												$queryD .= "ORDER BY manuscript_dealers.Dealer_Name ASC"; 
												$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
												while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
													echo "<option value=\"$rowD[2]|$rowD[5]\" style=\"text-align:justify; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; white-space: pre-wrap; word-wrap: break-word;\">";
													echo "$rowD[1] ($rowD[3])</option>";
												}
	
											?>
                                            <option disabled>&nbsp;</option>                                          
                                        </select>
									</div> 
									<div class="col-sm-2"><label for="inputArchive" class="col-sm-2 control-label">Source</label></div>
									<div class="col-sm-4" id="inputArchiveHolder">
                                    	<input type="text" class="form-control" id="inputArchive" placeholder="Selected Archive" disabled>
									</div>    
								</div>
                                <div class="form-group">
									<div class="col-sm-2"><label for="inputPlaceName" class="col-sm-2 control-label">Place</label></div>
									<div class="col-sm-4" id="inputPlace">
										<input type="hidden" class="form-control" id="inputPlaceName" name="inputPlaceName" value="pl306">
                                        <input type="text" class="form-control" id="inputPlaceNameHolder" name="inputPlaceNameHolder" readonly value="Paris">
									</div> 
									<div class="col-sm-2"><label for="inputDealerName" class="col-sm-2 control-label">Purchaser</label></div>
									<div class="col-sm-4" id="inputDealer">
										<input type="text" class="form-control" id="inputDealerName" placeholder="Agent or Bookseller" disabled>
									</div>
								</div>
<?php

								}

/////////////////////////////////////////////////////////////////// Switcher End

?>     
								<div class="form-group">
									<div class="col-sm-2"><label for="event_location" class="col-sm-2 control-label">Location</label></div>
									<div class="col-sm-10" id="inputLocation">
                                    	<div class="col-md-3">
											<label class="radio-inline">
												<input type="radio" name="event_location" id="event_location" value="ON PREMISES" style="width: 1.2em; height:0.9em;">
												<strong><span style="color:#7b6ac3;">On Premises</span></strong>
											</label>
                                        </div>
                                        <div class="col-md-3">
                                        	<label class="radio-inline">
												<input type="radio" name="event_location" id="event_location" value="ELSEWHERE" style="width: 1.2em; height:0.9em;">
												<strong><span style="color:#258fb2;">Elsewhere</span></strong>
											</label>
                                        </div>
                                        <div class="col-md-4">
                                        	<label class="radio-inline">
												<input type="radio" name="event_location" id="event_location" value="LOCAL CHAMBRE SYNDICALE" style="width: 1.2em; height:0.9em;" checked>
												<strong><span style="color:#cf6d2d;">Local Chambre Syndicale</span></strong>
											</label>
                                        </div>
									</div>
								</div>                               
							</form>
                        </p>
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Manuscript Details

?>
						<div class="bs-callout bs-callout-danger">
							<div class="citationValue" id="citationValue" style="text-align:justify; font-size: 1.0em;">
								<h4>Manuscript Details</h4>
							</div>
						</div>
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Event Details

?>
						<p>
							<form class="form-horizontal" role="form">
<?php

/////////////////////////////////////////////////////////////////// Switcher Auction

								if(($switch == "y")) {

?>                       
								<div class="form-group">
									<div class="col-sm-2"><label for="inputSaleReason" class="col-sm-2 control-label">Reason</label></div>
									<div class="col-sm-10" id="inputSaleOptions">
                                    <?php
										$j = 0;
										$colors = array("#000000","#7b6ac3","#258fb2","#cf6d2d","#28af26");
										$queryD = "SELECT * FROM manuscript_sales ORDER BY ID ASC"; 
										$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
										while($rowD = mysqli_fetch_row($mysqli_resultD)) {
											$j++;
											if(($j == 3)) { $checked = "checked"; } else { $checked = ""; }
											?>
                                            <div class="col-md-3">
                                            	<label class="radio-inline">
  													<input type="radio" name="inputSaleReason" id="inputSale<?php echo $j; ?>" value="<?php echo $rowD[0]; ?>" data-scenario="<?php echo $rowD[1]; ?>" style="width: 1.2em; height:0.9em;" <?php echo $checked; ?> > 
													<strong><span style="color:<?php echo $colors[$j]; ?>"><?php echo $rowD[1]; ?></span></strong>
												</label>
                                            </div>
                                            <?php
										}
									?>
									</div>
								</div>
<?php

								}

/////////////////////////////////////////////////////////////////// Switcher End

?>
								<div class="form-group">
									<div class="col-sm-2"><label for="inputEvent" class="col-sm-2 control-label">Event</label></div>
									<div class="col-sm-10" id="inputEventOptions">
                                    <?php
										$j = 0;
										$colors = array("#000000","#7b6ac3","#258fb2","#cf6d2d","#28af26");
										$queryD = "SELECT * FROM manuscript_stocktake ORDER BY ID ASC"; 
										$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
										while($rowD = mysqli_fetch_row($mysqli_resultD)) {
											$j++;
											if(($switch == "")) {
												if(($j == 1)) { $checked = "checked"; } else { $checked = ""; }
											} else {
												if(($j == 3)) { $checked = "checked"; } else { $checked = ""; }
											}
											?>
                                            <div class="col-md-3">
                                            	<label class="radio-inline">
  													<input type="radio" name="inputEvent" id="inputEvent<?php echo $j; ?>" value="<?php echo $rowD[1]; ?>" style="width: 1.2em; height:0.9em;" <?php echo $checked; ?> > 
													<strong><span style="color:<?php echo $colors[$j]; ?>"><?php echo $rowD[1]; ?></span></strong>
												</label>
                                            </div>
                                            <?php
										}
									?>
									</div>
								</div>	
								<div class="form-group">
									<div class="col-sm-2"><label for="manuscript_folio" class="col-sm-2 control-label">Reference</label></div>
									<div class="col-md-3">
										<div class="input-group">
											<input type="text" class="form-control" value="" id="manuscript_folio" name="manuscript_folio" placeholder="Folio / Page" onclick="var emptyThis = $('#manuscript_folio').val('');" >
											<span class="input-group-addon"><i class="glyphicon glyphicon-folder-open" data-toggle="tooltip" data-placement="top" title="Folio or Page Number"></i></span>
										</div>
									</div>
									<div class="col-md-4">
										<div class="input-group">
											<input type="text" class="form-control" value="" id="manuscript_article" name="manuscript_article" placeholder="Article Number" onclick="var emptyThis = $('#manuscript_article').val('');" >
											<span class="input-group-addon"><i class="glyphicon glyphicon-book" data-toggle="tooltip" data-placement="top" title="Article Number"></i></span>
										</div>
									</div>
									<div class="col-md-3">
										<div class="input-group">
											<input type="text" class="form-control" value="1777-01-01" id="event_date" name="event_date" data-date-viewmode="years">
											<span class="input-group-addon"><i class="glyphicon glyphicon-calendar" data-toggle="tooltip" data-placement="top" title="Date of Event"></i></span>
										</div>
									</div>												
								</div>					
								<div class="form-group">
									<div class="col-sm-2"><label for="inputSuperBookTitle" class="col-sm-2 control-label">SuperBook</label></div>
									<div class="col-sm-6" id="inputSuperBook">
										<input type="text" class="selectpicker form-control" id="inputSuperBookTitle" name="inputSuperBookTitle" placeholder="Search via Title or Author ..." autofocus 
                                        onclick="var burrows = $('#inputSuperBookTitle').val(''); var sburrows = $('#inputSuperBookTitleHidden').val(''); var dataZ = 'table=manuscript_books_editions&alias=&q='; var enameZ = $('#inputEdition').load('./data_agents.php',dataZ);" value="">
                                        <input type="hidden" id="inputSuperBookTitleHidden" name="inputSuperBookTitleHidden" value="">
									</div>
                                    <div class="col-sm-1"><a href='javascript: var URLvalue = $("#inputSuperBookTitle").val(); var goURL = window.open("https://www.worldcat.org/search?q=ti%3A" + URLvalue + "&qt=advanced", "_worldcat","");' 
                                    target="_worldcat" style="text-decoration:none;">Worldcat</a>
                                    <br /><a href='javascript: var URLvalue = $("#inputSuperBookTitle").val(); var goURL = window.open("https://www.google.com.au/search?q=" + URLvalue + "&btnG=Search+Books&tbm=bks&tbo=1&gws_rd=ssl", "_googlebooks","");' 
                                    target="_googlebooks" style="text-decoration:none;">Google</a></div>
									<div class="col-sm-3" id="inputSelectSearch">
										<label class="radio-inline">
											<input type="radio" name="inputSearch" id="inputSearch" value="TITLE" style="width: 1.2em; height:1.2em;" checked>
											<strong><span style="color:#88a825;">Title</span></strong>
										</label>
										<label class="radio-inline">
											<input type="radio" name="inputSearch" id="inputSearch" value="AUTHOR" style="width: 1.2em; height:1.2em;">
											<strong><span style="color:#268faf;">Author</span></strong>
										</label>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-2"><label for="inputEditionName" class="col-sm-2 control-label">Edition</label></div>
									<div class="col-sm-7" id="inputEdition">
										<input type="text" class="selectpicker form-control" id="inputEditionName" name="inputEditionName" placeholder="Select Edition ..." disabled>
									</div>
									<div class="col-sm-3">
										<button type="button" class="btn btn-info" id="addEditionButton" style="width:100%;">Add New</button>
									</div>
								</div>
                                <div class="form-group">
									<div class="col-sm-2"><label for="manuscript_notes" class="col-sm-2 control-label">Note&nbsp;A</label></div>
									<div class="col-sm-10" id="manuscript_notes_div">
										<input type="text" class="selectpicker form-control" id="manuscript_notes" name="manuscript_notes" placeholder="Edition Notes ... (e.g., Ambiguities about the selected edition, etc.)" onclick="var emptyThis = $('#manuscript_notes').val('');" >
									</div>
								</div>
                                <div class="form-group">
									<div class="col-sm-2"><label for="manuscript_othernotes" class="col-sm-2 control-label">Note&nbsp;B</label></div>
									<div class="col-sm-10" id="manuscript_othernotes_div">
										<input type="text" class="selectpicker form-control" id="manuscript_othernotes" name="manuscript_othernotes" 
                                        	placeholder="Event Notes ... (e.g., Precise volumes traded, Why elsewhere selected as location, etc.)" onclick="var emptyThis = $('#manuscript_othernotes').val('');" >
									</div>
								</div>										
                                <div class="form-group">
									<div class="col-sm-2"><label for="manuscript_morenotes" class="col-sm-2 control-label">Note&nbsp;C</label></div>
									<div class="col-sm-10" id="manuscript_morenotes_div">
										<input type="text" class="selectpicker form-control" id="manuscript_morenotes" name="manuscript_morenotes" 
                                        	placeholder="Sales Notes ... (e.g., Beneficiaries of sale, etc.)" onclick="var emptyThis = $('#manuscript_morenotes').val('');" >
									</div>
								</div>	
								<div class="form-group">
									<div class="col-sm-2"><label for="manuscript_page_stamped" class="col-sm-2 control-label">Detail</label></div>
                       		 		<!--
                                    <div class="col-md-2">
										<div class="input-group">
											<input type="text" class="form-control" value="" id="manuscript_page_stamped" name="manuscript_page_stamped" placeholder="Page" onclick="var emptyThis = $('#manuscript_page_stamped').val('');" >
											<span class="input-group-addon"><i class="glyphicon glyphicon-file" data-toggle="tooltip" data-placement="top" title="Page Stamped"></i></span>
										</div>
									</div>
                                    //-->
                 			       	<div class="col-md-2">
										<div class="input-group">
											<input type="text" class="form-control" value="" id="manuscript_copies" name="manuscript_copies" placeholder="Number of ..." onclick="var emptyThis = $('#manuscript_copies').val('');" >
										</div>
                      		      </div>
                         		   <div class="col-md-3">
										<div class="input-group" style="width:100%;">
                         		          	<select class="selectpicker form-control" id="manuscript_copies_type" title="Please Select Kind" data-live-search="true">
                              			       	<option data-hidden="true"></option>
 												<?php 
													$queryD = "SELECT * FROM manuscript_sales_numbers ORDER BY event ASC";  
													$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
													while($rowD = mysqli_fetch_row($mysqli_resultD)) {
														if(($rowD[2] == "copies")) { $selectedSales = "selected"; } else { $selectedSales = ""; }
														echo "<option value=\"$rowD[2]\" $selectedSales>$rowD[1]</option>";
													}
													echo "</optgroup>";								
												?>                                            
                              			  	</select>
										</div>                                   
                            		</div>
                            		<div class="col-md-2">
										<div class="input-group">
                                   			<input type="text" class="form-control" value="" id="manuscript_lotprice" name="manuscript_lotprice" placeholder="Lot Price" onclick="var emptyThis = $('#manuscript_lotprice').val('');" >
											<span class="input-group-addon"><i class="glyphicon glyphicon-euro" data-toggle="tooltip" data-placement="top" title="Lot Price"></i></span>
										</div>                                   
                            		</div>                   
                        			<div class="col-md-3">
										<div class="input-group">
											<input type="text" class="form-control" value="" id="manuscript_volnotes" name="manuscript_volnotes" placeholder="Note" onclick="var emptyThis = $('#manuscript_volnotes').val('');" >
											<span class="input-group-addon"><i class="glyphicon glyphicon-th-list" data-toggle="tooltip" data-placement="top" title="Volumes Note"></i></span>
										</div>
									</div>								
								</div>
                        	</form>
						</p>
                        <div class="form-group row">
                        	<div class="col-md-6" style="float:right;">
								<button type="button" class="btn btn-danger" id="addEventButton" disabled style="width:100%;">Create Record</button>	
							</div>
                        </div>
            		</div>
        		</div>
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Content

?>
        		<div id="main-wrapper" class="col-md-6 pull-right" >
            		<div id="main">
<?php

	$text = "<p>The French Book Trade in Enlightenment Europe (FBTEE) project uses database technology to map the trade of the Société Typographique de Neuchâtel (STN), a celebrated Swiss publishing house that operated between 1769 and 1794. As the STN sold the works of other publishers alongside its own editions, their archives can be considered a representative source for studying the history of the book trade and dissemination of ideas in the late Enlightenment.</p><p>Using state of the art database, web interface and GIS technology, the project provides a user-friendly resource for use by scholars, teachers and students of French literature and history, book history, the Enlightenment and bibliography more generally. <strong>This toolkit works best on Google Chrome.</strong> Please <a href=\"https://www.google.com/chrome/browser/\" target=\"_chrome\">click here</a> to download and install.</p>";
//	$text = utf8_decode($text);

////////////////////////////////////////////////////////// Default page

	if(($action == "") && ($query == "")) {

?>
						<div id="manuscriptRecords">
              				<div class="page-header">
                				<h3>FBTEE</h3>
              				</div>
<?php 

		echo $text;
		$doMap = "y";
		if(($doMap != "y")) {
			echo "<p>&nbsp;<br /><img src=\"./images/manuscripts.png\" border=\"0\" width=\"100%\" class=\"img-responsive img-rounded\"></p>";
		} else {
			echo "<p>&nbsp;<br /><div style=\"";
			echo "padding:8px; margin:0 0 2em; ";
			echo "border-radius: 8px; -moz-border-radius: 8px; -webkit-border-radius: 8px; -khtml-border-radius: 8px; ";
			echo "background:#838fb2; \">";
			echo "<div id=\"map_canvas\" style=\"width: 100%; height:460px;";
			echo "border-radius: 8px; -moz-border-radius: 8px; -webkit-border-radius: 8px; -khtml-border-radius: 8px; \"></div>";
			echo "</div>";
			echo "<p><strong>RUNNING TOTALS</strong></p>";
			
/////////////////////////////////////////////////////////// Get places from database

			echo "<div id=\"show_results_places_total\" style=\"-webkit-column-width: 250px; -moz-column-width: 250px; column-width: 250px; padding-bottom: 5px; color: #005000;\">";
			$places = array();
			$p = 0;
			$pcode = "";
			$places_table = array();
			$actualTotal = "0";
			$queryD = "SELECT * FROM manuscript_places ORDER BY Place_Name ASC ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) {
				$places[$p]['name'] = $rowD[3];
				$places[$p]['lon'] = $rowD[4];
				$places[$p]['lat'] = $rowD[5];
				$pcode = $rowD[2];
				$places[$p]['total'] = "0";
				$queryF = "SELECT ID_PlaceName, SUM(EventCopies) FROM manuscript_events WHERE ID_PlaceName = \"$pcode\" GROUP BY ID_PlaceName";
				$mysqli_resultF = mysqli_query($mysqli_link, $queryF);
				while($rowF = mysqli_fetch_row($mysqli_resultF)) {
					$actualTotal = ($actualTotal + $rowF[1]);
					$rowF[1] = number_format($rowF[1]);
					$places[$p]['total'] = $rowF[1];
					if(($rowF[1] > 0)) {
						$places_table[] = "<table width=\"90%\"><tr><td width=\"50%\" valign=\"top\">$rowD[3]</td><td width=\"50%\" style=\"text-align:right;\" valign=\"top\">$rowF[1]</td></tr></table>";
					}
				}
				$p++;				
			}			
			$cp = count($places_table);	
			if(($cp > 0)) {
				foreach($places_table as $pt) {
					echo $pt;
				}
			}
			echo "</div>";
			$actualTotal = number_format($actualTotal);
			echo "<p><strong>Full Number of Book Copies : $actualTotal</strong></p>";
		}
		echo "</div>";
	}

////////////////////////////////////////////////////////// All records

	if(($action == "list_all")) {

?>
                        <div id="manuscriptRecords">
                        <div class="page-header">
                			<h3><?php echo $alias; ?></h3>
              			</div>
                        <p>
                       		<ul class="pagination" style="float:right;">
  								<li><a href="#">&laquo;</a></li>
  								<li class="active"><a href="#">1</a></li>
  								<li><a href="#">2</a></li>
  								<li><a href="#">3</a></li>
  								<li><a href="#">4</a></li>
  								<li><a href="#">5</a></li>
  								<li><a href="#">&raquo;</a></li>
							</ul>
							<table id="manuscriptTable" class="table table-striped table-bordered" cellspacing="0" width="100%" style="width:100%;">
    							<thead>
        							<tr>
             							<th>#</th>
            							<th>MS</th>
            							<th>Agent(s)</th>
										<th>Place</th>
            							<th>Dealer</th>
            							<th>Title</th>
                                        <th>Date</th>
            							<th>&nbsp;</th> 
        							</tr>
    							</thead>
 <!--
    							<tfoot>
        							<tr>
            							<th>#</th>
            							<th>MS</th>
            							<th>Agent(s)</th>
										<th>Place</th>
            							<th>Dealer</th>
            							<th>Book</th>
                                        <th>Date</th>
            							<th>&nbsp;</th> 
        							</tr>
    							</tfoot>
//-->
    							<tbody>
        							<tr>
            							<td><a href="#" class="dataEdit" data-type="text" data-name="field1" data-url="./?">--</a></td>
            							<td><a href="#" class="dataEdit" data-type="text" data-name="field2" data-url="./?">--</a></td>
            							<td><a href="#" class="dataEdit" data-type="text" data-name="field3" data-url="./?">--</a></td>
            							<td><a href="#" class="dataEdit" data-type="text" data-name="field4" data-url="./?">--</a></td>
            							<td><a href="#" class="dataEdit" data-type="text" data-name="field5" data-url="./?">--</a></td>
            							<td><a href="#" class="dataEdit" data-type="text" data-name="field6" data-url="./?">--</a></td>
                                        <td><a href="#" class="dataEdit" data-type="text" data-name="field6" data-url="./?">--</a></td>
                                        <td><a href="#" class="dataEdit" data-type="text" data-name="field6" data-url="./?">--</a></td>
        							</tr>
    							</tbody>
							</table>
                        </p>
                   		</div>
<?php	

	}

?>
            	</div>
        	</div>
		</div>
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Footer

?>
   			<div id="footer">
      			<div class="container">
        			<p>Manuscripts Data Toolkit | Ensor, J. &amp; Burrows, S. | School of Humanities and Communication Arts | Western Sydney University | Last Update: 16 August 2016</p>
      			</div>
    		</div>
<?php

/////////////////////////////////////////////////////////// Configuration JS

?>        
	<script language="javascript" type="text/javascript" src="./js/modernizr.min.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jquery-ui/ui/jquery.ui.core.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jquery-ui/ui/jquery.ui.widget.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jquery-ui/ui/jquery.ui.position.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jquery-ui/ui/jquery.ui.menu.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jquery-ui/ui/jquery.ui.autocomplete.js"></script>
    <script language="javascript" type="text/javascript" src="./js/spin.min.js"></script>
    <script language="javascript" type="text/javascript" src="./js/bootstrap-select.js"></script>
	<script language="javascript" type="text/javascript" src="./js/bootstrap-datepicker.js"></script>
    <script language="javascript" type="text/javascript" >
		
		
		$(document).ready(function(e) {
			
			var goA = $('.selectpicker').selectpicker();
			
			var goB = $('[data-toggle="tooltip"]').tooltip({'placement': 'top'});

<?php

/////////////////////////////////////////////////////////////////// Switcher Catalogue

			if(($switch == "")) {

?>
			
			var goD = $("#inputArchive").change(function () {
				var archiveSel = this.value;
				var dataA = 'table=manuscript_agents_inspectors&alias=A&q=' + archiveSel;
				var dataB = 'table=manuscript_agents_inspectors&alias=B&q=' + archiveSel;
				var dataC = 'table=manuscript_agents_inspectors&alias=C&q=' + archiveSel;
				var dataD = 'table=manuscript_places&q=' + archiveSel;
				var goE = $('#inputAuditorA').load("./data_agents.php",dataA);
				var goF = $('#inputAuditorB').load("./data_agents.php",dataB);
				var goG = $('#inputAuditorC').load("./data_agents.php",dataC);
				var goH = $('#inputPlace').load("./data_agents.php",dataD);
				var citationValue = $('#citationValue').html("<h4>Manuscript Details</h4><p>MS " + archiveSel + ".</p>");
				var disableValue = $('#addEventButton').prop('disabled', true);
    		});
<?php

/////////////////////////////////////////////////////////////////// Switcher Auction

			} else {

?>			
			var goN = $("#inputSale").change(function () {
				var saleSel = this.value;
				var dataA = 'table=manuscript_sales_events&alias=msNumber&q=' + saleSel;
				var dataB = 'table=manuscript_sales_events&alias=ID_Purchasers&q=' + saleSel;
				var goF = $('#inputDealer').load("./data_sales.php",dataB);
				var goE = $('#inputArchiveHolder').load("./data_sales.php",dataA);
			});

<?php

			}

/////////////////////////////////////////////////////////////////// Switcher End

?>

			$('#event_date').datepicker({
    			format: "yyyy-mm-dd",
    			startView: 0
			});

<?php

/////////////////////////////////////////////////////////////////// Switcher Catalogue

			if(($switch == "")) {

?>

			$("#searchRecordEvents").click(function(){
				var searchText = $("#searchText").val();
            	dataE = "searchText=" + searchText + "&alias=All Records" + "&action=SEARCH";
				if ($('#inputSearchRecordsA').is(":checked")) {
					var searchVal = $('#manuscriptRecords').load("./data_records_sales.php",dataE);
				} else {
					var searchVal = $('#manuscriptRecords').load("./data_records.php",dataE);
				}
				return false;
			});
			
			$("#searchText").keypress(function(e) {
    			if(e.keyCode == 13) {
        			var searchText = $("#searchText").val();
            		dataE = "searchText=" + searchText + "&alias=All Records" + "&action=SEARCH";
					if ($('#inputSearchRecordsA').is(":checked")) {
						var searchVal = $('#manuscriptRecords').load("./data_records_sales.php",dataE);
					} else {
						var searchVal = $('#manuscriptRecords').load("./data_records.php",dataE);
					}
					return false;
    			}
			});
			
			$("#addEditionButton").click(function(){
			
				var inputSuperBookTitle = $("#inputSuperBookTitleHidden").val();
				var inputEditionName = $("select[id=inputEditionName]").val();
				dataE = "inputSuperBookTitle=" + inputSuperBookTitle + "&inputEditionName=" + inputEditionName + "&action=ADD";
				var enameVal = $('#manuscriptRecords').load('./data_editions.php',dataE);
				return false;
				
			});

			$("#addEventButton").click(function(){
            
            	var archiveSel = encodeURIComponent($("select[id=inputArchive]").val());
                var audA = encodeURIComponent($("select[id=inputAgentA]").val());
                var audB = encodeURIComponent($("select[id=inputAgentB]").val());
                var audC = encodeURIComponent($("select[id=inputAgentC]").val());
                var placeValue = encodeURIComponent($("select[id=inputPlaceName]").val());
                var inputDealer = encodeURIComponent($("select[id=inputDealerName]").val());
                var inputSuperBookTitle = encodeURIComponent($("#inputSuperBookTitleHidden").val());
                var inputEditionName = encodeURIComponent($("select[id=inputEditionName]").val());
				var inputEvent = encodeURIComponent($("input[name=inputEvent]:checked").val());
				var manuscript_copies = encodeURIComponent($("#manuscript_copies").val());
				var manuscript_folio = encodeURIComponent($("#manuscript_folio").val());
				var manuscript_notes = encodeURIComponent($("#manuscript_notes").val());
				var manuscript_page_stamped = encodeURIComponent($("#manuscript_page_stamped").val());
				var manuscript_article = encodeURIComponent($("#manuscript_article").val());
				var event_date = encodeURIComponent($("#event_date").val());
				var event_notes = encodeURIComponent($("#manuscript_othernotes").val());
				var event_vols = encodeURIComponent($("#manuscript_volnotes").val());
				var event_location = encodeURIComponent($("input[name=event_location]:checked").val());
                var citationValue = encodeURIComponent($('#citationValue').text());
				
                citationValue = citationValue.replace(/(<p>|<\/p>)/g, "");
                citationValue = citationValue.replace(/(<h4>|<\/h4>)/g, "");
                citationValue = citationValue.replace(/(Manuscript Details)/g, "");
                
            	dataE = "inputArchive=" + archiveSel + 
						"&inputAgentA=" + audA + 
						"&inputAgentB=" + audB + 
						"&inputAgentC=" + audC + 
						"&inputPlaceName=" + placeValue + 
						"&inputDealerName=" + inputDealer + 
						"&inputSuperBookTitle=" + inputSuperBookTitle + 
						"&inputEditionName=" + inputEditionName + 
						"&inputEvent=" + inputEvent + 
						"&manuscript_folio=" + manuscript_folio + 
						"&event_date=" + event_date + 
						"&manuscript_copies=" + manuscript_copies +
						"&manuscript_page_stamped=" + manuscript_page_stamped + 
						"&manuscript_notes=" + manuscript_notes + 
						"&manuscript_article=" + manuscript_article + 
						"&event_notes=" + event_notes + 
						"&event_vols=" + event_vols + 
						"&citationValue=" + citationValue + 
						"&event_location=" + event_location +
						"&alias=All Records" + 
						"&action=ADD";
					
            	var enameVal = $('#manuscriptRecords').load("./data_records.php",dataE);
				return false;
                
            });

<?php

/////////////////////////////////////////////////////////////////// Switcher Auction

			} else {

?>		

			$("#searchRecordEvents").click(function(){
				var searchText = $("#searchText").val();
            	dataE = "searchText=" + searchText + "&alias=All Records" + "&action=SEARCH";
				if ($('#inputSearchRecordsA').is(":checked")) {
					var searchVal = $('#manuscriptRecords').load("./data_records_sales.php",dataE);
				} else {
					var searchVal = $('#manuscriptRecords').load("./data_records.php",dataE);
				}
				return false;
			});
			
			$("#searchText").keypress(function(e) {
    			if(e.keyCode == 13) {
        			var searchText = $("#searchText").val();
            		dataE = "searchText=" + searchText + "&alias=All Records" + "&action=SEARCH";
					if ($('#inputSearchRecordsA').is(":checked")) {
						var searchVal = $('#manuscriptRecords').load("./data_records_sales.php",dataE);
					} else {
						var searchVal = $('#manuscriptRecords').load("./data_records.php",dataE);
					}
					return false;
    			}
			});
			
			$("#addEditionButton").click(function(){
				var inputSuperBookTitle = $("#inputSuperBookTitleHidden").val();
				var inputEditionName = $("select[id=inputEditionName]").val();
				dataE = "inputSuperBookTitle=" + inputSuperBookTitle + "&inputEditionName=" + inputEditionName + "&action=ADD";
				var enameVal = $('#manuscriptRecords').load('./data_editions.php',dataE);
				return false;
				
			});	

			$("#addEventButton").click(function(){
            
				var inputSale = encodeURIComponent($("select[id=inputSale]").val());
            	var archiveSel = encodeURIComponent($("#inputArchive").val());
                var placeValue = encodeURIComponent($("#inputPlaceName").val());
                var inputDealer = encodeURIComponent($("select[id=inputDealerName]").val());
				var inputAgents = encodeURIComponent($("#inputAgents").val());
				var inputAgentsRaw = encodeURIComponent($("#inputAgentsRaw").val());
				var event_location = encodeURIComponent($("input[name=event_location]:checked").val());
				var inputSaleReason = encodeURIComponent($("input[name=inputSaleReason]:checked").val());
				var inputSaleReasonText = encodeURIComponent($("input[name=inputSaleReason]:checked").attr("data-scenario"));
				var inputEvent = encodeURIComponent($("input[name=inputEvent]:checked").val());
				var manuscript_folio = encodeURIComponent($("#manuscript_folio").val());
				var manuscript_article = encodeURIComponent($("#manuscript_article").val());
				var event_date = encodeURIComponent($("#event_date").val());
                var inputSuperBookTitle = encodeURIComponent($("#inputSuperBookTitleHidden").val());
                var inputEditionName = encodeURIComponent($("select[id=inputEditionName]").val());
				var manuscript_notes = encodeURIComponent($("#manuscript_notes").val());
				var manuscript_othernotes = encodeURIComponent($("#manuscript_othernotes").val());
				var manuscript_morenotes = encodeURIComponent($("#manuscript_morenotes").val());
				var manuscript_copies = encodeURIComponent($("#manuscript_copies").val());
				var manuscript_copies_type = encodeURIComponent($("#manuscript_copies_type").val());
				var manuscript_lotprice = encodeURIComponent($("#manuscript_lotprice").val());
				var manuscript_volnotes = encodeURIComponent($("#manuscript_volnotes").val());
                var citationValue = encodeURIComponent($('#citationValue').text());			
				
                citationValue = citationValue.replace(/(<p>|<\/p>)/g, "");
                citationValue = citationValue.replace(/(<h4>|<\/h4>)/g, "");
				citationValue = citationValue.replace(/(<em>|<\/em>)/g, "");
				citationValue = citationValue.replace(/(<strong>|<\/strong>)/g, "");
                citationValue = citationValue.replace(/(Manuscript Details)/g, "");
                
            	dataE = "inputSale=" + inputSale + 
						"&inputArchive=" + archiveSel + 
						"&inputPlaceName=" + placeValue + 
						"&inputDealerName=" + inputDealer + 
						"&inputAgents=" + inputAgents + 
						"&inputAgentsRaw=" + inputAgentsRaw + 
						"&event_location=" + event_location +
						"&inputSaleReason=" + inputSaleReason + 
						"&inputSaleReasonText=" + inputSaleReasonText + 
						"&inputEvent=" + inputEvent + 
						"&manuscript_folio=" + manuscript_folio + 
						"&manuscript_article=" + manuscript_article + 
						"&event_date=" + event_date + 
						"&inputSuperBookTitle=" + inputSuperBookTitle + 
						"&inputEditionName=" + inputEditionName + 
						"&manuscript_notes=" + manuscript_notes + 
						"&manuscript_othernotes=" + manuscript_othernotes + 
						"&manuscript_morenotes=" + manuscript_morenotes + 
						"&manuscript_copies=" + manuscript_copies +
						"&manuscript_copies_type=" + manuscript_copies_type +
						"&manuscript_lotprice=" + manuscript_lotprice + 
						"&manuscript_lotprice=" + manuscript_lotprice + 
						"&manuscript_volnotes=" + manuscript_volnotes + 
						"&citationValue=" + citationValue + 
						"&alias=All Sales Records" + 
						"&action=ADD";
					
            	var enameVal = $('#manuscriptRecords').load("./data_records_sales.php",dataE);
				return false;
                
            });

<?php

			}

/////////////////////////////////////////////////////////////////// Switcher End

?>
		});
				
		$(function () {	
			$("#inputSuperBookTitle").autocomplete({
				source: function(request, response){
					var doVariation = $("input[name='inputSearch']:checked").val();
					$.ajax({
               			url: "./data_titles.php",
						dataType: "json",
                 		data: {
							term : request.term	,
							variation : doVariation
						},
                 		success: function (data) {
                  			response(data);
                 		}
            		});
  				},
				minLength: 4,
				delay: 600, 
				maxCacheLength: 4, 
				appendTo: ".ui-widget",
				select: function(event, ui) {
        			if(ui.item){
						var valink = ui.item.label;
						var lablink = ui.item.value;
						valink = valink.replace(/(<u>|<\/u>)/g, "");
						var superBook = $('#inputSuperBookTitle').val(valink);
						var superBookHidden = $('#inputSuperBookTitleHidden').val(lablink);
						var disableEName = $('#inputEditionName').prop('disabled', false);
						var eName = $('#inputEditionName').focus();
						var dataE = 'table=manuscript_books_editions&alias=&q=' + lablink;
						var enameVal = $('#inputEdition').load("./data_agents.php",dataE);
						return false;
        			}
    			}
			});
		});
		
		$.extend($.ui.autocomplete.prototype.options, {
			open: function(event, ui) {
				$(this).autocomplete("widget").css({
            		"width": ($("#inputEditionName").width() + "px")
        		});
    		}
		});
		
    </script>
	<script language="javascript" type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
	<script language="javascript" type="text/javascript" src="./js/jquery-ui-map/ui/jquery.ui.map.js"></script>
<?php

/////////////////////////////////////////////////////////// Get places from database
//
//	$places = array();
//	$p = 0;
//	$pcode = "";
//	$places_table = array();
//	$queryD = "SELECT * FROM manuscript_places ORDER BY ID ASC ";
//	$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
//	while($rowD = mysqli_fetch_row($mysqli_resultD)) {
//		$places[$p]['name'] = $rowD[3];
//		$places[$p]['lon'] = $rowD[4];
//		$places[$p]['lat'] = $rowD[5];
//		$pcode = $rowD[2];
//		$places[$p]['total'] = "0";
//		$queryF = "SELECT ID_PlaceName, SUM(EventCopies) FROM manuscript_events WHERE ID_PlaceName = \"$pcode\" GROUP BY ID_PlaceName";
//		$mysqli_resultF = mysqli_query($mysqli_link, $queryF);
//		while($rowF = mysqli_fetch_row($mysqli_resultF)) {
//			$places[$p]['total'] = $rowF[1];
//			if(($rowF[1] > 0)) {
//				$places_table[] = "<strong>$rowD[3]</strong> $rowF[1]";
//			}
//		}
//		$p++;				
//	}
//
/////////////////////////////////////////////////////////// Display places

?>	
	<script language="javascript" type="text/javascript" >
	
	$(document).ready(function(e) {
		$('#map_canvas').gmap({'center': '<?php echo $places[0]['lat']; ?>,<?php echo $places[0]['lon']; ?>', 'zoom': 6, 'disableDefaultUI':true, 'callback': function() {
			var self = this;			
			self.addMarker({
				'position': this.get('map').getCenter(),
				'icon' : 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
			}).click(function() {
				self.openInfoWindow({ 'content': '<?php echo $places[0]['name']; ?>&nbsp;(<?php echo $places[0]['total']; ?>)' }, this);
			});		
		}});
		
<?php		
	
	for($a=0;$a<$p;$a++) {
	
?>	
		$('#map_canvas').gmap('addMarker', { 
			'position': new google.maps.LatLng(<?php echo $places[$a]['lat']; ?>, <?php echo $places[$a]['lon']; ?>)<?php
			if(($places[$a]['total'] > 0)) { ?>,
			'icon' : 'http://maps.google.com/mapfiles/ms/icons/pink-dot.png'
			<?php } else {?>,
			'icon' : 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
			<?php } ?>
		}).click(function() {
			$('#map_canvas').gmap('openInfoWindow', { content : '<?php echo $places[$a]['name']; ?>&nbsp;(<?php echo $places[$a]['total']; ?>)' }, this);
		});
<?php

	}

?>		
	});
		
    </script>
<?php

	include("./admin/era.dbdisconnect.php");

/////////////////////////////////////////////////////////// Close page

?> 
    </body>
</html>