<?php
    class AbstractAuthenticator
    {
        public $user_levels = array(0 => 'user', 1 => 'admin', 2 => 'webmaster');
        
        public function meetsPermission($level, $min_level)
        {
            $key = -1;
            for($i = 0;$i < count($this->user_levels);++$i)
            {
                if($min_level == $this->user_levels[$i])
                {
                    $key = $i;
                    break;
                }
            }
            if($key < 0)
                return false;
            if(in_array($level, array_slice($this->user_levels, $key)))
                return true;
            return false;
        }
        
        public function isLoggedIn()
        {
            if(isset($_SESSION['logged_in']))
                return $_SESSION['logged_in'];
            return false;
        }
        
        public function authenticate($password, $stored_password, $salt = null)
        {
            $hashed_password = generatePasswordHash($password, $salt);

            //test if the hashed test password is the same as the stored password
            if($hashed_password == $stored_password)
                return true; //user authenticated
            return false; //incorrect password
        }
        
        public function generatePasswordHash($password, $salt = null)
        {
            $test_password = $password;
            if($salt != null)
                $test_password .= $salt;
    
            //hash the test password
            return hash('sha256', $test_password);
        }
        
        public function generateSalt()
        {
            $length = 256;
            $use_strong = true;
            $rand = openssl_random_pseudo_bytes($length, $use_strong);
            return hash('sha256', $rand);
        }
        
        public function doAuthentication()
        {
            //perform any authentication routines needed for the app
        }
        
        public function setLoggedIn($boolean = true)
        {
            $_SESSION['logged_in'] = $boolean;
        }
        
        public function getUserLevel()
        {
            return '';
        }
    }
?>
