<?php
/**
 * Created by PhpStorm.
 * Users: Daniel Eaton, Rebecca, Greg, Ana
 * Date: 10/29/2016
 * Time: 9:34 PM
 */


$file = file_get_contents('zip_code_database.csv');
$csv = str_getcsv($file);
for($x = 0; $x < (count($csv) - 3); $x += 3){
	if(substr($csv[$x],0,2) == "NM"){
		echo('Zip Code: ');
		print_r(substr($csv[$x-3],2) . "<br />");
		print_r("City: ". $csv[$x-2]."<br/>");
		print_r("Additional Areas Covered: " . $csv[$x-1] . "<br/>");
	}
}

