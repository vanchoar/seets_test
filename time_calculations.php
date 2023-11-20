<?php

class Time_calculations { 
    // function calculate until certain ($quantity) number of years
      public function calculate_previous_quantity_primenumber_years($year, $quantity){
            $years = [];
    
            $math = new Math();
    
            for( $i=0; $i<$year; $i++){
              if ($sum_years < $quantity) {
                if($math->isPrimeNumber($year)){
                  array_push($years, $year);
                };
    
                $year = $year - 1;
                $sum_years = count($years);
              }else{
                break;
              }
            }
            // return $quantity number of primenumber years array
            return $years;
      }

      public function return_only_missing_years_from_db($year, $quantity){

            // Calculate 30 previous Prime number years
            $years = $this->calculate_previous_quantity_primenumber_years($year, $quantity);

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
            return $new_years;
      }

    
      // get christmas day of each year from $years list
      public function get_years_and_encrypted_christmas_days($years){    
        $years_and_encrypted_days = [];
        $encryptions = new Encryptions();

            foreach($years as $y){
                $date=date_create($y."-12-25");
                $christ_day = date_format($date,"l");
    
                // encrypt christmass weekday 
                $cipher_christ_day = $encryptions->encrypt($christ_day);
    
                $christmass_year = $y.' '. $cipher_christ_day;
                array_push($years_and_encrypted_days, $christmass_year);
            }

        return $years_and_encrypted_days;
    }

}

?>