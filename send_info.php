<?php

if($_POST['year'] > 0 && $_POST['year'] < 9999) {

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
    $new_years = $time_calculations->return_only_missing_years_from_db($year, '30');

    // calculate + encrypt day christmas day of each year, make list with (years, encrypted days)
    $years_and_encrypted_days = $time_calculations->get_years_and_encrypted_christmas_days($new_years);
    // insert full entries into database
    foreach($years_and_encrypted_days as $year_encr_day){
        $year_day_separated = explode(' ', $year_encr_day);
        $year = mysqli_real_escape_string($conn, $year_day_separated[0]);
        $day = mysqli_real_escape_string($conn, $year_day_separated[1]);
        // send year + encripted day to database
        $db_operations->insert_into_table($year, $day);
    }

    // Select full info (year, encryoted day) from database
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
}

?>
