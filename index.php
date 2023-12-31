<html>
<head>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<title>Year & Christmas calculator</title>
</head>

<body>
    <div class="col-sm-6 col-sm-offset-3">
        <form action="" method="POST" id="year-form">
        <div class="form-group">

            <label for="year">
                <h3>Write year:</h3>
                <input  class="form-control" id="year" type="text" maxlength="4" placeholder="Insert year"  pattern="\d*" />
            </label>
            <input  class="btn btn-success" type="submit"/>        
        </div>

        </form>

        <table class="table" id="table">
            <thead>
                <th>YEAR</th>
                <th>CHRISTMAS DAY <span class="small">(*December 25)<span></th>
            </thead>
            <tbody id="table_info">
            </tbody>
        </table>

    </div>
</body>

<script>
$(document).ready(function () {
  $("#year-form").submit(function (event) {
    var formData = {
      year: $("#year").val(),
    };

    $.ajax({
      type: "POST",
      url: "send_info.php",
      data: formData,
      dataType: "json",
      encode: true,
    }).done(function (data) {
        console.log(data);
        $('#table_info').empty();
        $.each(data, function(k, v) {
            $("#table_info").append('<tr> <td>' + v.year + '</td> <td>'+ v.day +' </td> </tr>');
        });
    });

    event.preventDefault();
  });
});
</script>
</html>
<div>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
class Encryptions {

  public string $text;
// public $encryption_method = "AES-128-CBC";
// public $key = "your_amazing_key_here";

  public function __construct(string $text)
    {
        $this->text = $text;
    }

  public function encrypt($data): string {
      //$encryption_method = "AES-128-CBC";
      //$key = "your_amazing_key_here";
      return $this->text;
      //$ivlen = openssl_cipher_iv_length($cipher = $encryption_method);
      //$iv = openssl_random_pseudo_bytes($ivlen);
      //$ciphertext_raw = openssl_encrypt($data, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
      //$hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
      //$ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
      //return $ciphertext;
  }
}

$encryptions = new Encryptions('asd');
echo $encryptions->encrypt('');
?>