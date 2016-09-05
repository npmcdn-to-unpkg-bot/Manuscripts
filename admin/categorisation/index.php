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
	$searchLetter = mysql_real_escape_string($_GET["searchLetter"]);
	if(($searchLetter == "") or (!in_array($searchLetter, $alphabet))) { $searchLetter = "a"; }
	$_GET = array();
	$_POST = array();	

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
	Last updated: 5 September 2016




















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
		<meta name="msapplication-TileImage" content="/ms-icon-144x144.png" />
		<meta name="theme-color" content="#ffffff" />  
        <link rel="apple-touch-icon" href="./apple-icon.png" />
		<link rel="apple-touch-icon" sizes="57x57" href="./apple-icon-57x57.png" />
		<link rel="apple-touch-icon" sizes="60x60" href="./apple-icon-60x60.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="./apple-icon-72x72.png" />
		<link rel="apple-touch-icon" sizes="76x76" href="./apple-icon-76x76.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="./apple-icon-114x114.png" />
		<link rel="apple-touch-icon" sizes="120x120" href="./apple-icon-120x120.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="./apple-icon-144x144.png" />
		<link rel="apple-touch-icon" sizes="152x152" href="./apple-icon-152x152.png" />
		<link rel="apple-touch-icon" sizes="180x180" href="./apple-icon-180x180.png" />
		<link rel="icon" type="image/png" sizes="192x192"  href="./android-icon-192x192.png" />
		<link rel="icon" type="image/png" sizes="32x32" href="./favicon-32x32.png" />
		<link rel="icon" type="image/png" sizes="96x96" href="./favicon-96x96.png" />
		<link rel="icon" type="image/png" sizes="16x16" href="./favicon-16x16.png" />
        <link rel="icon" type="image/x-icon" href="./favicon.ico" />
		<link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />     
        <link rel="manifest" href="./manifest.json" />        
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=latin">
		<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="./css/nifty.min.css">
		<link rel="stylesheet" type="text/css" href="./plugins/themify-icons/themify-icons.min.css">
		<link rel="stylesheet" type="text/css" href="./css/pace.min.css">
        <link rel="stylesheet" type="text/css" href="./css/themes/type-c/theme-ocean.min.css">
        <link rel="stylesheet" type="text/css" href="./plugins/datatables/media/css/dataTables.bootstrap.css">
		<link rel="stylesheet" type="text/css" href="./plugins/datatables/extensions/Responsive/css/dataTables.responsive.css">
        <link rel="stylesheet" type="text/css" href="./js/bootstrap-tagmanager/tagmanager.css">        
		<script language="javascript" type="text/javascript" src="./js/pace.min.js"></script>
        <script language="javascript" type="text/javascript" src="./js/jquery-2.2.4.min.js"></script>
		<script language="javascript" type="text/javascript" src="./js/bootstrap.min.js"></script>
		<script language="javascript" type="text/javascript" src="./js/nifty.min.js"></script>
        <script language="javascript" type="text/javascript" src="./plugins/datatables/media/js/jquery.dataTables.js"></script>
		<script language="javascript" type="text/javascript" src="./plugins/datatables/media/js/dataTables.bootstrap.js"></script>
		<script language="javascript" type="text/javascript" src="./plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
        <script language="javascript" type="text/javascript" src="./js/bootstrap-tagmanager/tagmanager.js"></script>
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
				color: #999
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
			
		</style>
    </head>   
	<body>
		<div id="container" class="effect mainnav-sm">
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
                    	<ul class="nav navbar-top-links pull-left">
                    		<li class="tgl-menu-btn"><a class="mainnav-toggle" href="#"><i class="ti-view-list icon-lg"></i></a></li>
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
                
					<!-- Page Title and Search Box -->                
					<div id="page-title">
                    	<h1 class="page-header text-overflow">Categorisation Toolkit</h1>
                    	<!--
                        <div class="searchbox">
                        	<div class="input-group custom-search-form">
                            	<input type="text" class="form-control" placeholder="Search..">
                            	<span class="input-group-btn">
                                	<button class="text-muted" type="button"><i class="ti-search"></i></button>
                            	</span>
                        	</div>
                    	</div> 
                        //-->  
                	</div>
                    
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
                                                        
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    	<table id="dt-basic" class="table table-striped table-hover" cellspacing="0" width="100%">
                                    		<thead>
                                     			<tr>
                                        			<th>ID</th>
                                         			<th>TITLE</th>
                                                    <th>TAGS</th>
                                      			</tr>
                                  			</thead>
                                     		<tbody>
                                        	<?php
                                                
												$queryD = "SELECT * FROM manuscript_books WHERE ";
												$queryD .= "super_book_title LIKE \"$searchLetter%\" ";
											//	$queryD .= "AND keywords = \"\" ";
												$queryD .= "ORDER BY super_book_title ASC";
												$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
												while($rowD = mysqli_fetch_row($mysqli_resultD)) {
													$ID = strtoupper($rowD[0]);
													echo "<tr>";
													echo "<td class=\"text-right\">$ID</td>";
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
													echo "<td>";
													if(($rowD[2] != "") or ($rowD[3] != "")) {
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
<p>When a title is selected from the database, details from one of its editions will display here. If there is more than one edition attached to the work then the edition is selected randomly.</p>
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
<p>When the <em>Edit Keywords</em> button is clicked, this panel will load the form for assigning, adding, deleting or modifying existing keywords that are associated with the work highlighted in the middle panel. Please note that keywords are assigned at the <em>Work</em> level rather than at the edition or manifestation level.</p>
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
                                            <li class="col-xs-4" data-content="Tag Works"><a class="shortcut-grid" href="./index.php"><i class="ti-info-alt"></i></a></li>
                                            <li class="col-xs-4" data-content="Edit Tags"><a class="shortcut-grid" href="#"><i class="ti-tag"></i></a></li>
                                            <li class="col-xs-4" data-content="Audit Work"><a class="shortcut-grid aside-toggle" href="#"><i class="ti-pin-alt"></i></a></li>
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
                                    <li class="active"><a href="#demo-asd-tab-1" data-toggle="tab"><i class="info-alt"></i></a></li>
                                    <!--
                                    <li><a href="#demo-asd-tab-2" data-toggle="tab"><i class="ti-comments"></i></a></li>
                                    <li><a href="#demo-asd-tab-3" data-toggle="tab"><i class="ti-settings"></i></a></li>
                                    //-->
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="demo-asd-tab-1">
                                        <p class="pad-all text-lg">History</p>
                                        <div class="pad-hor" style="text-align: left;">
Curabitur fringilla libero id leo interdum, at aliquam nisi fermentum. Ut ut felis ultricies, accumsan ex quis, malesuada massa. Ut viverra tempor arcu ut feugiat. Nulla commodo erat non ultricies pulvinar. Ut feugiat vestibulum fringilla. Mauris ullamcorper diam ut nisi scelerisque ultrices et vehicula lorem. Vivamus elementum eu nisi at blandit. Nam in quam malesuada nunc interdum mattis vel eu diam. Donec justo sapien, aliquam vel purus nec, viverra porta odio. Sed sollicitudin massa magna, sed pharetra orci porttitor sit amet. Ut venenatis feugiat pellentesque.
                                        </div>
                                    </div>
                                    <!--
                                    <div class="tab-pane fade" id="demo-asd-tab-2">
                                        <p class="pad-all text-lg">Second tab</p>
                                        <div class="pad-hor" style="text-align: left;">
Proin bibendum blandit urna at faucibus. Nam fringilla cursus nisl ac porta. Maecenas dictum dui turpis, et posuere arcu aliquam sit amet. Duis augue dolor, vulputate in accumsan nec, mollis ac nunc. Donec ultricies ut sapien euismod dictum. Praesent mauris purus, cursus quis fringilla et, faucibus non justo. Vestibulum arcu magna, fermentum a posuere et, gravida sit amet neque. Suspendisse potenti. Sed dignissim eu urna nec laoreet. Phasellus suscipit vel turpis sed efficitur. Curabitur eget aliquet justo. Aenean semper lorem id elementum ultricies. Nunc finibus et nibh a tempus. Cras in magna imperdiet, suscipit arcu vitae, posuere dui. Integer mollis, felis et accumsan varius, neque arcu varius neque, sit amet vestibulum elit metus nec justo. Morbi hendrerit erat a lobortis iaculis.
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="demo-asd-tab-3">
                                        <p class="pad-all text-lg">Third tab</p>
                                        <div class="pad-hor" style="text-align: left;">
Curabitur id dui a nisi aliquet ullamcorper nec eu sapien. Fusce lacinia pulvinar condimentum. Nulla facilisi. Sed iaculis erat lacus, sit amet tempor erat pharetra ultricies. Pellentesque fermentum blandit lacus, at volutpat purus volutpat et. Integer mattis magna at porta imperdiet. Integer eget nibh vulputate, vehicula nibh in, iaculis dui. Maecenas arcu justo, pharetra vitae pulvinar at, convallis sed mauris. Fusce eu imperdiet felis. Nunc euismod erat eget tortor viverra, quis dapibus diam ornare. Ut consectetur sed lectus at aliquam. Nulla facilisi. Morbi aliquam, lectus nec tristique malesuada, sapien metus ullamcorper nibh, in molestie turpis dolor rutrum dui.
                                        </div>
                                    </div>
                                    //-->
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
			</div>
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Footer

?>         
        	<footer id="footer">
				<div class="hide-fixed pull-right pad-rgt">Last Updated: 5 September 2016</div>
				<p class="pad-lft">Western Sydney University &#0169; <?php echo date("Y",time()); ?></p>
			</footer>
            <button class="scroll-top btn"><i class="pci-chevron chevron-up"></i></button>
        </div>
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Scripts

?> 
		<script language="javascript" type="text/javascript" >
		
			$(window).on('load', function() {
				
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
			
			});
		
		</script>
    </body>
</html>
<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Finish

	include("../era.dbdisconnect.php");

?> 