<!DOCTYPE html>
<!--	Author: Anthony Storti
		Date:	12/1/19
		Purpose:Show all films grouped by category with count save to file and display onscreen with table
-->
<html>
<head>
	<title>Final Part 1</title>
	<link rel ="stylesheet" type="text/css" href="sample.css">
</head>
<body>
	<?php

       $mysqli = new mysqli("localhost", "root", "Dovetail1", "sakila");
        if($mysqli->connect_error) {
          exit('Error connecting to database'); //Should be a message a typical user could understand in production
        }
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli->set_charset("utf8mb4");
        //The ? below is a placeholder
        $stmt = $mysqli->prepare("SELECT CONCAT(name, ':',COUNT(title), ':',GROUP_CONCAT(title ORDER BY c.category_id)) AS films
				FROM film f, film_category fc, category c
				WHERE f.film_id = fc.film_id AND
	  		fc.category_id = c.category_id
				GROUP BY name");

        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows === 0) exit('No rows');
				$i = 0;
				$outFile = fopen("results.txt","w");
				print("<h1>Table Films and Categories</h1>");
				print("<table border='1'>");
				print("<tr><th>Category</th><th>Count</th><th>Films</th></tr>");
				while($row = $result->fetch_assoc())
			{
				$tableD = explode(":",$row['films']);
				fputs($outFile, $row['films'].";"."\n");
				print ("<tr><td>".$tableD[0]."</td><td>".$tableD[1]."</td><td>".$tableD[2]."</td></tr>");
			}
			fclose($outFile);
			print("</table>");
$stmt->close();
$mysqli->close();
?>
</body>
</html>
