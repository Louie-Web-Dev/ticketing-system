<?php

require_once("database.php");

$ticket_no = $_POST["ticket_no"];
$name_ = $_POST["name_"];
$department = $_POST["department"];
$date_ = $_POST["date_"];
$catgry = $_POST["catgry"];
$selected = $_POST["selected"];
$desc_ = mysqli_real_escape_string($conn, $_POST["desc_"]);
$status = "pending";
$concern_type = $_POST['concern_type'];


$sql = "INSERT INTO concerns(ticket_no, name, department, con_date, category, sub_cat, description, status, concern_type) 
	 					   VALUES('" . $ticket_no . "','" . $name_ . "','" . $department . "','" . $date_ . "','" . $catgry . "','" . $selected . "','" . $desc_ . "','" . $status . "', '" . $concern_type . "')";

$retval = mysqli_query($conn, $sql);
