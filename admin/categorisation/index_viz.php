<!doctype html>
<html>
<head>
<title>Keywords Visualisation</title>
	<link rel="stylesheet" type="text/css" href="./js/visjs/dist/vis.css" />
	<script language="javascript" type="text/javascript" src="./data_viz_nodes.js"></script>
	<script language="javascript" type="text/javascript" src="./js/visjs/dist/vis.js"></script> 
	<style type="text/css">
		#mynetwork {
			width: 100%;
			height: 1000px;
			border: 0px solid lightgray;
		}
	</style>
</head>
<body>
<div id="mynetwork"></div>
<script language="javascript" type="text/javascript">

	var container = document.getElementById('mynetwork');
	var data = {
		nodes: nodes,
		edges: edges
	};
	var options = {
		dragNodes: false,
		enabled: false	
	};
	var network = new vis.Network(container, data, options);
	
</script>
</body>
</html>