<?php
   	$mysqli_link = mysqli_connect("$localhost", "$username", "$password") or die ("<p><b>$localhost</b> could not connect to the database using <b>$username</b> and <b>$password</b>!");
   	mysqli_select_db($mysqli_link, "$database") or die ("<p>Could not select the database!");
?>