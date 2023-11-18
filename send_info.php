
<?php
require('db.php');

// Math calculations class
include('math.php');

// Encryptions class
include('encryptions.php');

// TimeInfo class
include('timeinfo.php');

// Database_operations class
include('database_operations.php');



$year = $_POST['year'];

$year_calculations = new TimeInfo();

$encryptions = new Encryptions();

$db_operations = new Database_operations();

$years = $year_calculations->calculate_30_primenumber_years($year);

$existing_years = $year_calculations->return_already_inserted_years();

// match new years with existing years
$unique_years = array_unique( array_merge($years, $existing_years) );
$new_years = array_diff( $unique_years, $existing_years);

// return only new years, not previously present
$years = $new_years;

// Christmas weekday encryption

// get christmas day of each year
$years_and_encrypted_days = $year_calculations->get_years_and_encrypted_christmas_days($years);

// insert full entries into database
foreach($years_and_encrypted_days as $year_encr_day){
    $data_separated = explode(' ', $year_encr_day);
    $year = mysqli_real_escape_string($conn, $data_separated[0]);
    $day = mysqli_real_escape_string($conn, $data_separated[1]);
  // send year + encripted day to database
    $db_operations->insert_into_table($year, $day);
}


$database_entries = $db_operations->select_from_table('year', 'day');
$database_entries = $conn->query($database_entries);


$decrypted_results_list = [];
while($db_entry = mysqli_fetch_assoc($database_entries)) {
  
    //decrypt Christmas day
    $original_christ_day = $encryptions->decrypt($db_entry['day']);

    $db_entry['day'] = $original_christ_day;
    array_push($decrypted_results_list, $db_entry);
}

//Send the array back as a JSON object
echo json_encode($decrypted_results_list);


?>
