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
//  21 October 2015
//
//
/////////////////////////////////////////////////////////// Clean post and get

	session_start();
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	header("Content-type: text/html;charset=UTF-8");
	mb_internal_encoding("UTF-8");
	if (!mysqli_set_charset($mysqli_link, "utf8")) {
		echo "PROBLEM WITH CHARSET!";
		die;
	}
	
/////////////////////////////////////////////////////////// Vars	
	
	$colors = array();
	$colors[] = "rgba(223, 83, 83, .5)";
	$colors[] = "rgba(119, 152, 191, .5)";
	$colors[] = "rgba(64, 128, 0, .5)";
	$colors[] = "rgba(128, 0, 255, .5)";
	$colors[] = "rgba(0, 128, 128, .5)";
	sort($colors);
	
/////////////////////////////////////////////////////////// Write document header
	
?>
<!DOCTYPE html>
<html lang="en">
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<title>Visualise Stock Sales (Sales Scatterplot)</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Visualise Text">
		<meta name="robots" content="noindex,nofollow">
		<meta http-equiv='cache-control' content='no-cache'>
		<meta http-equiv='expires' content='0'>
		<meta http-equiv='pragma' content='no-cache'>
        <script language="javascript" type="text/javascript" src="./js/jquery-2.1.1.js"></script>
    	<script language="javascript" type="text/javascript" src="./js/highcharts/js/highcharts.js"></script>
        <style type="text/css">
		
			body { 
  				font: 11px helvetica neue, helvetica, arial, sans-serif;
			}
			
			#selected {
				z-index:2000;
				position: absolute;
				background-color: #ffffff;
				color: #000000;
				border-radius: 5px;
				border: 3px solid #222222;
				padding: 10px 10px 10px 10px;
				bottom: 60px;
				right: 60px;
				height: 95px;
				width: 400px;
				opacity: 0.7;
				filter: alpha(opacity=70);
			}

			#selected h1 {
				font-size: 20px;
				margin: 0px;
				line-height: 20px;
				font-weight: bold;
				white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis;
			}

			#selected p {
				font-size: 12px;
				margin: 0px;
				line-height: 30px;
				font-weight: bold;
				white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis;
			}
			
			#viz {
				width: 99%; 
				height: 99%; 
				margin: 0 auto;
			}

		</style>
        <link rel="apple-touch-icon" href="./touch-icon-iphone.png" />
		<link rel="apple-touch-icon" sizes="76x76" href="./touch-icon-ipad.png" />
		<link rel="apple-touch-icon" sizes="120x120" href="./touch-icon-iphone-retina.png" />
		<link rel="apple-touch-icon" sizes="152x152" href="./touch-icon-ipad-retina.png" />
		<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png" />
		<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png" />
		<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png" />
		<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png" />
		<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png" />
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png" />
		<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png" />
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png" />
		<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png" />
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png" />
        <link rel="icon" type="image/x-icon" href="./favicon.ico" />
		<link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />     
        <link rel="manifest" href="./manifest.json" />
        <meta name="msapplication-TileColor" content="#ffffff" />
		<meta name="msapplication-TileImage" content="/ms-icon-144x144.png" />
		<meta name="theme-color" content="#ffffff" />
	</head>
	<body style="overflow-x: hidden;">
	<!--[if lt IE 7]>
    	<p class="browsehappy">You are using an <strong>outdated</strong> browser. 
    	Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
    <div id="selected" style="height:auto;padding:30px;">
		<p style="text-align:center;"><img src="./western_sydney_logo.png" alt="" width="50%"></p>
		<p style="line-height:30px;">
    		<h3 style="color:#800000;line-height:1.3em;text-align:center;">STOCK SALES<br />Sales Scatterplot</h3>
        	Professor Simon Burrows<br />School of Humanities &amp; Communication Arts<br />Western Sydney University<br /><br />
    	</p>
	</div>
    <div id="viz"></div>
    <script language="javascript" type="text/javascript">

		$(function () {
    		$('#viz').highcharts({
        		chart: {
            		type: 'scatter',
            		zoomType: 'xy'
        		},
				credits: {
					enabled: false	
				},
        		title: {
            		text: 'Years vs. Volume of Copies'
        		},
        		xAxis: {
            		title: {
                		enabled: true,
                		text: 'Copies'
            		},
					tickInterval: 200,
					minorTickInterval: 100,
            		startOnTick: true,
            		endOnTick: true,
            		showLastLabel: true
        		},
        		yAxis: {
					tickInterval: 1,
					minorTickInterval: 1,
            		title: {
                		text: 'Years'
            		}
        		},
        		legend: {
            		layout: 'vertical',
           			align: 'left',
            		verticalAlign: 'top',
            		x: 100,
            		y: 100,
            		floating: true,
            		backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF',
            		borderWidth: 1
        		},
        		plotOptions: {
            		scatter: {
                		marker: {
                    		radius: 5,
                    		states: {
                        		hover: {
                            		enabled: true,
                            		lineColor: 'rgb(100,100,100)'
                        		}
                    		}
                		},
                		states: {
                    		hover: {
                        		marker: {
                            		enabled: false
                        		}
                    		}
                		},
                		tooltip: {
                    		headerFormat: '<b>{series.name}</b><br>',
                    		pointFormat: '{point.y:.0f}, {point.x} copies'
                		}
            		}
        		},
        		series: [
					{
           				name: 'Bankruptcy',
           	 			color: '<?php echo $colors[3]; ?>',
						marker: {
                			symbol: 'circle'
            			},
           	 			data: [
<?php

/////////////////////////////////////////////////////////// Bankruptcy Data

	$sample_data = array();
	$myQueryCheck = "SELECT EventCopies, EventDate ";
	$myQueryCheck .= "FROM manuscript_events_sales ";
	$myQueryCheck .= "WHERE EventType = \"Stock Sale\" ";
	$myQueryCheck .= "AND EventSaleReasonText = \"Bankruptcy\" ORDER BY EventDate ASC";
	$mysqli = new mysqli("$localhost", "$username", "$password", "$database");
	$result = $mysqli->query("$myQueryCheck");
	while ($row = mysqli_fetch_row($result)){
		$row[0] = round("$row[0]",0);
		$starts = explode("-",$row[1]);
		$sample_data[] = "[".$row[0].",".$starts[0]."]";
	}
	
/////////////////////////////////////////////////////////// Organise Data and Display

	$sample_data = array_unique($sample_data);
	sort($sample_data);
	$cData = count($sample_data);
	$i = 0;	
	foreach($sample_data as $sd) {
		$i++;
		echo $sd;
		if(($i != $cData)) {
			echo ",";
		} else {
			echo "\n";	
		}
	}

?>			
						]
        			}, {
            			name: 'Deceased Estate',
            			color: '<?php echo $colors[5]; ?>',
						marker: {
                			symbol: 'circle'
            			},
            			data: [
<?php

/////////////////////////////////////////////////////////// Deceased Estate Data

	$sample_data = array();
	$myQueryCheck = "SELECT EventCopies, EventDate ";
	$myQueryCheck .= "FROM manuscript_events_sales ";
	$myQueryCheck .= "WHERE EventType = \"Stock Sale\" ";
	$myQueryCheck .= "AND EventSaleReasonText = \"Deceased Estate\" ORDER BY EventDate ASC";
	$mysqli = new mysqli("$localhost", "$username", "$password", "$database");
	$result = $mysqli->query("$myQueryCheck");
	while ($row = mysqli_fetch_row($result)){
		$row[0] = round("$row[0]",0);
		$starts = explode("-",$row[1]);
		$sample_data[] = "[".$row[0].",".$starts[0]."]";
	}
	
/////////////////////////////////////////////////////////// Organise Data and Display

	$sample_data = array_unique($sample_data);
	sort($sample_data);
	$cData = count($sample_data);
	$i = 0;	
	foreach($sample_data as $sd) {
		$i++;
		echo $sd;
		if(($i != $cData)) {
			echo ",";
		} else {
			echo "\n";	
		}
	}

?>				
						]
        			}, {
            			name: 'On Behalf of King',
            			color: '<?php echo $colors[4]; ?>',
						marker: {
                			symbol: 'circle'
            			},
            			data: [
<?php

/////////////////////////////////////////////////////////// On Behalf of King Data

	$sample_data = array();
	$myQueryCheck = "SELECT EventCopies, EventDate ";
	$myQueryCheck .= "FROM manuscript_events_sales ";
	$myQueryCheck .= "WHERE EventType = \"Stock Sale\" ";
	$myQueryCheck .= "AND EventSaleReasonText = \"On Behalf of King\" ORDER BY EventDate ASC";
	$mysqli = new mysqli("$localhost", "$username", "$password", "$database");
	$result = $mysqli->query("$myQueryCheck");
	while ($row = mysqli_fetch_row($result)){
		$row[0] = round("$row[0]",0);
		$starts = explode("-",$row[1]);
		$sample_data[] = "[".$row[0].",".$starts[0]."]";
	}
	
/////////////////////////////////////////////////////////// Organise Data and Display

	$sample_data = array_unique($sample_data);
	sort($sample_data);
	$cData = count($sample_data);
	$i = 0;	
	foreach($sample_data as $sd) {
		$i++;
		echo $sd;
		if(($i != $cData)) {
			echo ",";
		} else {
			echo "\n";	
		}
	}

?>				
						]
        			}
				]
    		});
		});

	</script>
</body>
</html>
<?php

/////////////////////////////////////////////////////////// Finish

	include("./admin/era.dbdisconnect.php");

?>