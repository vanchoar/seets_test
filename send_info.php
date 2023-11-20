<?php
require('db.php');

// Math calculations class
include('math.php');

// Encryptions class
include('encryptions.php');

// TimeInfo class
include('time_calculations.php');

// Database_operations class
include('database_operations.php');



$time_calculations = new Time_calculations();

$encryptions = new Encryptions();

$db_operations = new Database_operations();

$year = $_POST['year'];

// Calculate 30 previous Prime number years
// Get only new years, not previously present
// calculate + encrypt day christmas day of each year, make list with (years, encrypted days)
// insert full entries into database
$time_calculations->only_add_missing_years($year);

// Select full info (year, day) from database
$database_entries = $db_operations->select_from_table('year', 'day');
$database_entries = $conn->query($database_entries);


$decrypted_results_list = [];
while($db_entry = mysqli_fetch_assoc($database_entries)) {
  
    //decrypt Christmas day and return to list
    $original_christ_day = $encryptions->decrypt($db_entry['day']);

    $db_entry['day'] = $original_christ_day;
    array_push($decrypted_results_list, $db_entry);
}

//Send the array back as a JSON object
echo json_encode($decrypted_results_list);


?>
