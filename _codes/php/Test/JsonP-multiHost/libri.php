<?php 
 
$books = array(); 
 
$books[] = array("id" => "b1", "title" => "title1-".$_GET['host'], "author" => "author1-".$_GET['host']); 
$books[] = array("id" => "b1", "title" => "title2-".$_GET['host'], "author" => "author2-".$_GET['host']); 
$books[] = array("id" => "b1", "title" => "title3-".$_GET['host'], "author" => "author3-".$_GET['host']); 
$books[] = array("id" => "b1", "title" => "title4-".$_GET['host'], "author" => "author4-".$_GET['host']); 
$books[] = array("id" => "b1", "title" => "title5-".$_GET['host'], "author" => "author5-".$_GET['host']); 
 
echo $_GET['cb'] ."(".json_encode($books).");"; 
 
