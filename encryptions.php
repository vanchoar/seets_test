<?php
//////////////////////////

class Encryptions {
  
  public static $encryption_method = "AES-128-CBC";
  public static $key = "your_amazing_key_here";

  public function encrypt($data) { 

      $ivlen = openssl_cipher_iv_length($cipher = self::$encryption_method);
      $iv = openssl_random_pseudo_bytes($ivlen);
      $ciphertext_raw = openssl_encrypt($data, $cipher, self::$key, $options = OPENSSL_RAW_DATA, $iv);
      $hmac = hash_hmac('sha256', $ciphertext_raw, self::$key, $as_binary = true);
      $ciphertext = base64_encode($iv . $hmac . $ciphertext_raw);
      return $ciphertext;
  }

  public function decrypt($data) {

      $ivlen = openssl_cipher_iv_length($cipher = self::$encryption_method);
      $c = base64_decode($data);
      $iv = substr($c, 0, $ivlen);
      $hmac = substr($c, $ivlen, $sha2len = 32);
      $ciphertext_raw = substr($c, $ivlen + $sha2len);
      $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, self::$key, $options = OPENSSL_RAW_DATA, $iv);
      $calcmac = hash_hmac('sha256', $ciphertext_raw, self::$key, $as_binary = true);
      if (hash_equals($hmac, $calcmac))
      {
          return $original_plaintext;
      }
  }
}

?>