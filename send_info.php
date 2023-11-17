
<?php
require('db.php');
  
// calculate previous 30 primenumber years
class YearCalculations {
  // function if is prime number
  private function isPrimeNumber($year){
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


  // function calculate until 30 years
  public function calculate_years($year){
    $years = [];
  
    for( $i=0; $i<$year; $i++){
      if ($sum_years < 30) {
        if($this->isPrimeNumber($year)){
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
}

$year = $_POST['year'];

$year_calc = new YearCalculations();

$years = $year_calc->calculate_years($year);

///////////////////////////////////////



// check if year already present in database
class ExistingYearsCalculate {
  // select all existing years
  public function inserted_years(){
    include('db.php');
    $sql = "SELECT year FROM test_table";
    $res = $conn->query($sql);
  
    $years_already_present = [];
    while($y = mysqli_fetch_assoc($res)){
        array_push($years_already_present, $y['year']);
    };
  
    return $years_already_present;
  } 

}

$existingYearsCalc = new ExistingYearsCalculate();

$existing_years = $existingYearsCalc->inserted_years();

  // match new years with existing years
$unique_years = array_unique( array_merge($years, $existing_years) );
$new_years = array_diff( $unique_years, $existing_years);

  // return only new years, not previously present
$years = $new_years;
////////////////////////////////////////////////  


  // Christmas weekday encryption

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

$encryptions = new Encryptions();

$full_info = [];
// get christmas day of each year
foreach($years as $y){
  $date=date_create($y."-12-25");
  $christ_day = date_format($date,"l");

  $cipher_christ_day = $encryptions->encrypt($christ_day);

  $christmass_year = $y.' '. $cipher_christ_day;
  array_push($full_info, $christmass_year);
}

// encript day for each year

foreach($full_info as $data1){
  $data_separated = explode(' ', $data1);
  $year = mysqli_real_escape_string($conn, $data_separated[0]);
  $day = mysqli_real_escape_string($conn, $data_separated[1]);

  $sql = "INSERT INTO test_table (year,day) VALUES ('".$year."', '".$day."')";

  $conn->query($sql);
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
