<?php

// calculate previous 30 primenumber years
class Calculations { 

    public function isPrimeNumber($number){
      $count=0;  
      for ( $i=1; $i <= $number; $i++)  
      {  
          if (($number % $i)==0)  
          {  
              $count++;  
          }  
      }  
    
      if ($count < 3)  
      {  
          return true;  
      } else {
          return false; 
      }
    }
  }

?>