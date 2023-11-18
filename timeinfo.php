<?php

class TimeInfo { 
    // function calculate until 30 years
        public function calculate_30_primenumber_years($year){
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
    
      // function to select all existing years form database
        public function return_already_inserted_years(){
            include('db.php');
            // include('database_operations.php');

            $db_operations = new Database_operations();
            $database_entries = $db_operations->select_from_table('year', '');            

            $res = $conn->query($database_entries);
          
            $years_already_present = [];
            
            // format years in list
            while($y = mysqli_fetch_assoc($res)){
                array_push($years_already_present, $y['year']);
            };
          
            return $years_already_present;
        } 
    
      // get christmas day of each year from $years list
        public function get_years_and_encrypted_christmas_days($years){    
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
}

?>