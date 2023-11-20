<?php

class Time_calculations { 
    // function calculate until 30 years
        private function calculate_30_primenumber_years($year){
            $years = [];
    
            $math = new Math();
    
            for( $i=0; $i<$year; $i++){
              if ($sum_years < 30) {
                if($math->isPrimeNumber($year)){
                  array_push($years, $year);
                };
    
                $year = $year - 1;
                $sum_years = count($years);
              }else{
                break;
              }
            }
            // return 30 primenumber years array
            return $years;
        }
    
      // get christmas day of each year from $years list
        private function get_years_and_encrypted_christmas_days($years){    
            $years_and_encrypted_days = [];
            $encryptions = new Encryptions();
    
                foreach($years as $y){
                    $date=date_create($y."-12-25");
                    $christ_day = date_format($date,"l");
        
                    $cipher_christ_day = $encryptions->encrypt($christ_day);
        
                    $christmass_year = $y.' '. $cipher_christ_day;
                    array_push($years_and_encrypted_days, $christmass_year);
                }
    
            return $years_and_encrypted_days;
        }

        public function only_add_missing_years($year){

            // Calculate 30 previous Prime number years
            $years = $this->calculate_30_primenumber_years($year);

            // Select all years already present in database (if there are any)
            require('db.php');
            $db_operations = new Database_operations();
            $database_entries = $db_operations->select_from_table('year', '');  
            $res = $conn->query($database_entries);

            $existing_years = [];
            // format already existing years in database in a list
            while($y = mysqli_fetch_assoc($res)){
                array_push($existing_years, $y['year']);
            };

            // match new years with existing years
            $unique_years = array_unique( array_merge($years, $existing_years) );
            $new_years = array_diff( $unique_years, $existing_years);
            // return only new years, not previously present
            $years = $new_years;

            // calculate + encrypt day christmas day of each year, make list with (years, encrypted days)
            $years_and_encrypted_days = $this->get_years_and_encrypted_christmas_days($years);

            // insert full entries into database
            foreach($years_and_encrypted_days as $year_encr_day){
                $year_day_separated = explode(' ', $year_encr_day);
                $year = mysqli_real_escape_string($conn, $year_day_separated[0]);
                $day = mysqli_real_escape_string($conn, $year_day_separated[1]);
              // send year + encripted day to database
                $db_operations->insert_into_table($year, $day);
            }

        }
}

?>