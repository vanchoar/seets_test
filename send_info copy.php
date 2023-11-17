
<?php
require('db.php');
  
// calculate previous 30 primenumber years
class Calculations { 

  public function isPrimeNumber($year){
    $count=0;  
    for ( $i=1; $i <= $year; $i++)  
    {  
        if (($year % $i)==0)  
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

//////////////////////////

class Encryptions {
  public $encryption_method = "AES-128-CBC";
  public $key = "your_amazing_key_here";

  public function encrypt($data) { 

      $ivlen = openssl_cipher_iv_length($cipher = $this->encryption_method);
      $iv = openssl_random_pseudo_bytes($ivlen);
      $ciphertext_raw = openssl_encrypt($data, $cipher, $this->key, $options = OPENSSL_RAW_DATA, $iv);
      $hmac = hash_hmac('sha256', $ciphertext_raw, $this->key, $as_binary = true);
      $ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
      return $ciphertext;
  }

  public function decrypt($data) {

      $ivlen = openssl_cipher_iv_length($cipher = $this->encryption_method);
      $c = base64_decode($data);
      $iv = substr($c, 0, $ivlen);
      $hmac = substr($c, $ivlen, $sha2len = 32);
      $ciphertext_raw = substr($c, $ivlen + $sha2len);
      $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $this->key, $options = OPENSSL_RAW_DATA, $iv);
      $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->key, $as_binary = true);
      if (hash_equals($hmac, $calcmac))
      {
          return $original_plaintext;
      }
  }
}

///////////////////////////////////////

class TimeInfo { 
// function calculate until 30 years
    public function calculate_30_primenumber_years($year){
        $years = [];

        $calculate = new Calculations();

        for( $i=0; $i<$year; $i++){
          if ($sum_years < 30) {
            if($calculate->isPrimeNumber($year)){
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
    public function already_inserted_years(){
        include('db.php');
        $sql = "SELECT year FROM test_table";
        $res = $conn->query($sql);
      
        $years_already_present = [];
        while($y = mysqli_fetch_assoc($res)){
            array_push($years_already_present, $y['year']);
        };
      
        return $years_already_present;
    } 

  // get christmas day of each year
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

class Database_operations {

    public function __construct()
    {
      // require('db.php');
    }

    public function insert_into_table($year, $day){
        require('db.php');
        $sql = "INSERT INTO test_table (year,day) VALUES ('".$year."', '".$day."')";
        return $conn->query($sql);
    }
}








$year = $_POST['year'];

$year_calculations = new TimeInfo();

$encryptions = new Encryptions();

$db_operations = new Database_operations();


$years = $year_calculations->calculate_30_primenumber_years($year);

$existing_years = $year_calculations->already_inserted_years();

  // match new years with existing years
$unique_years = array_unique( array_merge($years, $existing_years) );
$new_years = array_diff( $unique_years, $existing_years);

// return only new years, not previously present
$years = $new_years;
////////////////////////////////////////////////  


// Christmas weekday encryption

// get christmas day of each year
$years_and_encrypted_days = $year_calculations->get_years_and_encrypted_christmas_days($years);

// insert full entries into database
foreach($years_and_encrypted_days as $year_encr_day){
    $data_separated = explode(' ', $year_encr_day);
    $year = mysqli_real_escape_string($conn, $data_separated[0]);
    $day = mysqli_real_escape_string($conn, $data_separated[1]);

    $db_operations->insert_into_table($year, $day);
}

// send year + encripted day to database
$sql2 = "SELECT year, day FROM test_table";
$res = $conn->query($sql2);


$rows = [];
while($r = mysqli_fetch_assoc($res)) {
  
    //decrypt Christmas day
    $original_christ_day = $encryptions->decrypt($r['day']);

    $r['day'] = $original_christ_day;
    array_push($rows, $r);

}

//Send the array back as a JSON object
echo json_encode($rows);


?>
