<?php
/**
 * Created by notepad.exe
 * Name: Waldemar Fraer
 * Date: 28.09.2018
 * Time: 13:58
 */

  class LoginHelper {

    /* fallback falls jemand diese funktion nicht hat - anfang */
    /* quelle: http://php.net/manual/de/function.random-int.php */
    //if (!function_exists('random_int')) {
      function random_int($min, $max) {
        if (!function_exists('mcrypt_create_iv')) {
          //trigger_error('mcrypt must be loaded for random_int to work', E_USER_WARNING);
          return null;
        }

        if (!is_int($min) || !is_int($max)) {
          //trigger_error('$min and $max must be integer values', E_USER_NOTICE);
          $min = (int)$min;
          $max = (int)$max;
        }

        if ($min > $max) {
          //trigger_error('$max can\'t be lesser than $min', E_USER_WARNING);
          return null;
        }

        $range = $counter = $max - $min;
        $bits = 1;

        while ($counter >>= 1) {
          ++$bits;
        }

        $bytes = (int)max(ceil($bits/8), 1);
        $bitmask = pow(2, $bits) - 1;

        if ($bitmask >= PHP_INT_MAX) {
          $bitmask = PHP_INT_MAX;
        }

      do {
        $result = hexdec(
                    bin2hex(
                      mcrypt_create_iv($bytes, MCRYPT_DEV_URANDOM)
                    )
                  ) & $bitmask;
      } while ($result > $range);
        return $result + $min;
      }
    //}
    /* fallback falls jemand diese funktion nicht hat - ende */

    /* shopware passwort funktionen - anfang */
    function getBytes($length) {
      if ($length <= 0) {
        return false;
      }
      return random_bytes($length);
    }

    function getInteger($min, $max) {
      if ($min > $max) {
        echo "fail...";
      }
      return self::random_int($min, $max);
    }

    function getString($length, $charlist = null) {
      // charlist is empty or not provided
      if (empty($charlist)) {
        $numBytes = ceil($length * 0.75);
        $bytes = self::getBytes($numBytes);
        return mb_substr(rtrim(base64_encode($bytes), '='), 0, $length, '8bit');
      }
      $listLen = mb_strlen($charlist, '8bit');
      if ($listLen == 1) {
        return str_repeat($charlist, $length);
      }
      $result = '';
      for ($i = 0; $i < $length; ++$i) {
        $pos = self::getInteger(0, $listLen - 1);
        $result .= $charlist[$pos];
      }
      return $result;
    }

    function getAlphanumericString($length) {
      $charlist = implode(range('a', 'z')) . implode(range('A', 'Z')) . implode(range(0, 9));
      return self::getString($length, $charlist);
    }

    function getSalt() {
      return self::getAlphanumericString(22);
    }

    function generateInternal($password, $salt, $iterations) {
      $hash = '';
      for ($i = 0; $i <= $iterations; ++$i) {
        $hash = hash('sha256', $hash . $password . $salt);
      }
      return $iterations . ':' . $salt . ':' . $hash;
    }
    /* shopware passwort funktionen - ende */

    public static function get_userdata_by_username($username = NULL) {
      if ($username != NULL) {
        $q = "SELECT id, roleID, username, password, encoder, apiKey, localeID, sessionID, lastlogin, name, email, active, failedlogins, lockeduntil, extended_editor, disabled_cache
                FROM s_core_auth
               WHERE username = ?
               LIMIT 1";
        return Db::fetch($q, [$username]);
      } else {
        return false;
      }
    }

    public static function check_login($username = NULL, $password = NULL) {
      if ($username != NULL && $password != NULL) {
        // hole userdaten aus shopware tabelle s_core_auth
        $userdata = self::get_userdata_by_username($username);
        if ($userdata !== false) {
          // zwischenspeichern des gespeicherten passwort-hashes aus shopware
          $hash     = $userdata['password'];
          // prüfe verschlüsselungsmethode
          if ($userdata['encoder'] == "md5") {
            // md5 logik
            if ($hash == MD5($password)) {
              // success
              //echo "okay ... md5 ... passwort passt";
              // set logged_in status
              self::set_logged_in_status($username);
              return true;
            } else {
              // fail
              //echo "fail ... md5 ... passwort stimmt nicht überein";
              return false;
            }
          } elseif ($userdata['encoder'] == "bcrypt") {
            // bcrypt logik
            if (password_verify($password, $hash)) {
              // success
              //echo "okay ... bcrypt ... passwort passt";
              // set logged_in status
              self::set_logged_in_status($username);
              return true;
            } else {
              // fail
              //echo "fail ... bcrypt ... passwort stimmt nicht überein";
              return false;
            }
          } elseif ($userdata['encoder'] == "sha256") {
            // sh256 logik
            $salt       = self::getSalt();
            // erstelle charlist wie shopware
            $charlist   = implode(range('a', 'z')) . implode(range('A', 'Z')) . implode(range(0, 9));
            // teile den hash in iterations und den salt
            list($iterations, $salt) = explode(':', $hash);
            // erstelle vergleichs-hash
            $verifyHash = self::generateInternal($password, $salt, $iterations);
            // prüfe die hashes
            if (hash_equals($hash, $verifyHash)) {
              // success
              //echo "okay ... sha256 ... passwort passt";
              // set logged_in status
              self::set_logged_in_status($username);
              return true;
            } else {
              // fail
              //echo "fail ... sha256 ... passwort stimmt nicht überein";
              return false;
            }
          } else {
            // uncatched encoder type
            return false;
          }
        } else {
          return "Username nicht gefunden...";
        }
      } else {
        return "Logindaten nicht übergeben...";
      }
    }

    public static function set_logged_in_status($username = NULL, $name = NULL) {
      if ($username != NULL) {
        $_SESSION['PSCHelper']['username'] = $username;
      }
    }

    public static function set_logged_out_status() {
      // lösche die variablen aus der session
      unset($_SESSION['PSCHelper']['username']);
    }

    public static function check_logged_in() {
      if (isset($_SESSION['PSCHelper']['username']) && $_SESSION['PSCHelper']['username'] != NULL) {
        return true;
      } else {
        return false;
      }
    }

  }

?>