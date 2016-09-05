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
	include("./config.php");
	include("./era.dbconnect.php");
	$doAddSuperBooks = "n";
	$doAddAuthorNames = "n";
	$doAddSuperBooksTitle = "n";	
	$doA = "n";
	$doB = "n";
	$doBA = "n";
	$doBE = "n";
	$correctTitles = "y";
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Database update November 2014 start

/////////////////////////////////////////////////////////// Fix superbook titles

	if(($correctTitles == "y")) {
		$update = "0";
		$queryD = "SELECT * FROM table_50 ORDER BY super_book_code ASC";
		if (!mysqli_set_charset($mysqli_link, "utf8")) {
			echo "PROBLEM WITH CHARSET!";
			die;
		}
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$code = $rowD[0];
			$title = $rowD[1];
			if(($code != "") && ($title != "")) {
				$queryX = "UPDATE manuscript_books SET super_book_title = \"$title\" WHERE super_book_code = \"$code\" ";
//				if(($code == "spbk0010045")) {
//					echo mb_detect_encoding($title)."<br />";
//					echo $queryX."<br />";
//					foreach(mb_list_encodings() as $chr){ 
//       				echo mb_convert_encoding($queryX, 'ASCII', $chr)." : ".$chr."<br>";    
//					}
//				}
				$mysqli_resultX = mysqli_query($mysqli_link, $queryX);
				$update++;
			}
		}
		echo "Done: $update records updated";
	}

/////////////////////////////////////////////////////////// Update authors table

	if(($doA == "y")) {
		$add = 0;
		$update = 0;
		$queryD = "SELECT * FROM manuscripts_add_authors ORDER BY author_code ASC";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			if(($rowD[0] != "")) {
				$found = "n";
				$queryX = "SELECT * FROM manuscript_authors WHERE author_code = \"$rowD[0]\" ";
				$mysqli_resultX = mysqli_query($mysqli_link, $queryX);
				while($rowX = mysqli_fetch_row($mysqli_resultX)) {
					$found = "y";	
				}
				if(($found == "y")) {
					$queryX = "UPDATE manuscript_authors SET author_name = \"$rowD[1]\" WHERE author_code = \"$rowD[0]\" ";
					$mysqli_resultX = mysqli_query($mysqli_link, $queryX);
					$update++;
				} else {
					$queryX = "INSERT INTO manuscript_authors VALUES (\"$rowD[0]\",\"$rowD[1]\");";	
					$mysqli_resultX = mysqli_query($mysqli_link, $queryX);
					$add++;
				}
			}
		}
		echo "Done: $update | $add | manuscript_authors table";
	}
	
/////////////////////////////////////////////////////////// Update books table

	if(($doB == "y")) {
		$update = 0;
		$delete = 0;
		$queryD = "SELECT * FROM manuscripts_add_books ORDER BY super_book_code ASC";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			if(($rowD[0] != "")) {
				$queryX = "UPDATE manuscript_books SET super_book_title = \"$rowD[1]\", Illegality = \"$rowD[4]\" WHERE super_book_code = \"$rowD[0]\" ";
				$mysqli_resultX = mysqli_query($mysqli_link, $queryX);
				$update++;
			}
		}
		$queryD = "SELECT manuscript_books.super_book_code FROM manuscript_books WHERE manuscript_books.super_book_code NOT IN ";
		$queryD .= "(SELECT manuscripts_add_books.super_book_code FROM manuscripts_add_books) ";
		$queryD .= "AND manuscript_books.super_book_code LIKE \"%spbk0010%\" AND manuscript_books.super_book_code NOT LIKE \"zspbk00%\" ORDER BY manuscript_books.super_book_code ASC";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) {
			if(($rowD[0] != "")) {
				$queryX = "DELETE FROM manuscript_books WHERE super_book_code = \"$rowD[0]\" ";
				$mysqli_resultX = mysqli_query($mysqli_link, $queryX);
				$delete++;
			}
		}
		echo "Done: $update | $delete | manuscript_add_books table";
	}

/////////////////////////////////////////////////////////// Update editions table

	if(($doBE == "y")) {
		$update = 0;
		$queryD = "SELECT * FROM manuscripts_add_books_editions ORDER BY book_code ASC";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			if(($rowD[0] != "")) {
				$queryX = "UPDATE manuscript_books_editions SET ";
				if(($rowD[2] != "")){ $queryX .= "edition_status = \"$rowD[2]\", "; }
				if(($rowD[3] != "")){ $queryX .= "edition_type = \"$rowD[3]\", "; }
				if(($rowD[4] != "")){ $queryX .= "full_book_title = \"$rowD[4]\", "; }
				if(($rowD[5] != "")){ $queryX .= "short_book_titles = \"$rowD[5]\", "; }
				if(($rowD[6] != "")){ $queryX .= "translated_title = \"$rowD[6]\", "; }
				if(($rowD[7] != "")){ $queryX .= "translated_language = \"$rowD[7]\", "; }
				if(($rowD[8] != "")){ $queryX .= "languages = \"$rowD[8]\", "; }
				if(($rowD[9] != "")){ $queryX .= "stated_publishers = \"$rowD[9]\", "; }
				if(($rowD[10] != "")){ $queryX .= "actual_publishers = \"$rowD[10]\", "; }
				if(($rowD[11] != "")){ $queryX .= "stated_publication_places = \"$rowD[11]\", "; }
				if(($rowD[12] != "")){ $queryX .= "actual_publication_places = \"$rowD[12]\", "; }
				if(($rowD[13] != "")){ $queryX .= "stated_publication_years = \"$rowD[13]\", "; }
				if(($rowD[14] != "")){ $queryX .= "actual_publication_years = \"$rowD[14]\", "; }
				if(($rowD[15] != "")){ $queryX .= "pages = \"$rowD[15]\", "; }
				if(($rowD[16] != "")){ $queryX .= "quick_pages = \"$rowD[16]\", "; }
				if(($rowD[17] != "")){ $queryX .= "number_of_volumes = \"$rowD[17]\", "; }
				if(($rowD[18] != "")){ $queryX .= "section = \"$rowD[18]\", "; }
				if(($rowD[19] != "")){ $queryX .= "edition = \"$rowD[19]\", "; }
				if(($rowD[20] != "")){ $queryX .= "book_sheets = \"$rowD[20]\", "; }
				if(($rowD[21] != "")){ $queryX .= "notes = \"$rowD[21]\", "; }
				if(($rowD[22] != "")){ $queryX .= "research_notes = \"$rowD[22]\", "; }
				$queryX .= "super_book_code = \"$rowD[1]\" ";
				$queryX .= "WHERE book_code = \"$rowD[0]\" ";
				$mysqli_resultX = mysqli_query($mysqli_link, $queryX);
				$update++;
			}
		}
		echo "Done: $update | manuscript_books_editions table";
	}

/////////////////////////////////////////////////////////// Update books to authors table

	if(($doBA == "y")) {
		$add = 0;
		$update = 0;
		$queryD = "SELECT * FROM manuscripts_add_books_authors ORDER BY book_code ASC";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			if(($rowD[0] != "")) {
				$found = "n";
				$queryX = "SELECT * FROM manuscript_books_authors WHERE book_code = \"$rowD[0]\" AND author_code = \"$rowD[1]\" ";
				$mysqli_resultX = mysqli_query($mysqli_link, $queryX);
				while($rowX = mysqli_fetch_row($mysqli_resultX)) {
					$found = "y";	
				}
				if(($found == "y")) {
					$rowD[2] = strtolower($rowD[2]);
					$queryX = "UPDATE manuscript_books_authors SET author_type = \"$rowD[2]\" WHERE book_code = \"$rowD[0]\" AND author_code = \"$rowD[1]\" ";
					$mysqli_resultX = mysqli_query($mysqli_link, $queryX);
					$update++;
				} else {
					$rowD[2] = strtolower($rowD[2]);
					$queryX = "INSERT INTO manuscript_books_authors VALUES (\"$rowD[0]\",\"$rowD[1]\",\"$rowD[2]\",\"0\");";	
					$mysqli_resultX = mysqli_query($mysqli_link, $queryX);
					$add++;
				}
			}
		}
		echo "Done: $update | $add | manuscript_books_authors table";
	}
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Database update November 2014 finish
	
/////////////////////////////////////////////////////////// MAKE SURE manuscripts_books_authors HAS BEEN CLONED AS manuscript_authors_search
	
/////////////////////////////////////////////////////////// Add superbooks

	$x = 0;
	$y = 0;
	if(($doAddSuperBooks == "y")) {
		echo "<br />Add superbooks to manuscript_authors_search ... ";
		$queryD = "SELECT * FROM manuscript_authors_search WHERE book_code != \"\" ORDER BY book_code ASC";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$x++;
			$queryX = "SELECT super_book_code FROM manuscript_books_editions WHERE book_code = \"$rowD[0]\" AND super_book_code != \"\" ";
			$mysqli_resultX = mysqli_query($mysqli_link, $queryX);
			while($rowX = mysqli_fetch_row($mysqli_resultX)) {
				$y++;
				$queryA = "UPDATE manuscript_authors_search SET super_book_code = \"$rowX[0]\" WHERE book_code = \"$rowD[0]\"";
				$mysqli_resultA = mysqli_query($mysqli_link, $queryA);
			}
		}
		echo "$x records found ... $y records updated";
	}

/////////////////////////////////////////////////////////// Add author names
	
	$x = 0;
	$y = 0;
	if(($doAddAuthorNames == "y")) {
		echo "<br />Add authors to manuscript_authors_search ... ";
		$queryD = "SELECT * FROM manuscript_authors_search WHERE book_code != \"\" ORDER BY book_code ASC";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$x++;
			$queryX = "SELECT author_name FROM manuscript_authors WHERE author_code = \"$rowD[1]\" AND author_name != \"\" ";
			$mysqli_resultX = mysqli_query($mysqli_link, $queryX);
			while($rowX = mysqli_fetch_row($mysqli_resultX)) {
				$y++;
				$queryA = "UPDATE manuscript_authors_search SET author_name = \"$rowX[0]\" WHERE author_code = \"$rowD[1]\"";
				$mysqli_resultA = mysqli_query($mysqli_link, $queryA);
			}
		}
		echo "$x records found ... $y records updated";
	}

/////////////////////////////////////////////////////////// Add superbooks ttle

	$x = 0;
	$y = 0;
	if(($doAddSuperBooksTitle == "y")) {
		echo "<br />Add superbooks title to manuscript_authors_search ... ";
		$queryD = "SELECT * FROM manuscript_authors_search WHERE book_code != \"\" ORDER BY book_code ASC";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) { 
			$x++;
			$queryX = "SELECT super_book_title FROM manuscript_books WHERE super_book_code = \"$rowD[4]\" AND super_book_title != \"\" ";
			$mysqli_resultX = mysqli_query($mysqli_link, $queryX);
			while($rowX = mysqli_fetch_row($mysqli_resultX)) {
				$y++;
				$queryA = "UPDATE manuscript_authors_search SET super_book_title = \"$rowX[0]\" WHERE super_book_code = \"$rowD[4]\"";
				$mysqli_resultA = mysqli_query($mysqli_link, $queryA);
			}
		}
		echo "$x records found ... $y records updated";
	}	
	
/////////////////////////////////////////////////////////// Finish

	include("./era.dbdisconnect.php");

?>