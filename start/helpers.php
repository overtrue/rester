<?php
/**
 * helpers.php
 * 
 * @author Carlos <anzhengchao@gmail.com>
 * @date   [2014-07-17 15:51]
 */


/**
 * 获取应用运行环境
 *
 * @return string
 */
function env($test = null) 
{
    $envs = include __DIR__ . '/env.php';
    foreach ($envs as $env => $hostnames) {
        if (in_array(gethostname(), $hostnames) || in_array('*', $hostnames)) {
            return $test ? $env == $test : $env;
        }
    }

    return $test ? 'production' === $test : 'production';
}

/**
 * 加密字符串
 *
 * @param string $input 
 *
 * @return string
 */
function encrypt($input) {
    $securekey = getSecurekey();
    return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $securekey, $input, MCRYPT_MODE_ECB);
}

/**
 * 解密字符串
 *
 * @param string $input 
 *
 * @return string
 */
function decrypt($input) {
    $securekey = getSecurekey();
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $securekey, $input, MCRYPT_MODE_ECB));
}

/**
 * 获取配置文件路径
 *
 * @return string
 */
function config_path()
{
    return path_join(APP_PATH, 'config');
}

if (!function_exists('nice_time')) {
    function niceTime($date)
    {
        if(empty($date)) {
            return "No date provided";
        }

        
        $periods     = array("秒", "分", "小时", "天", "周", "月", "年", "世纪");
        $lengths     = array("60","60","24","7","4.35","12","10");
        
        $now         = time();
        $unix_date   = is_numeric($date) ? $date : strtotime($date);
        
           // check validity of date
        if(empty($unix_date)) {    
            return "Bad date";
        }

        // is it future date or past date
        if($now > $unix_date + 60) {    
            $difference = $now - $unix_date;
            $tense      = "前";
            
        } else {
            $difference = abs($unix_date - $now);
            
            return "刚刚";
        }
        
        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }
        
        $difference = round($difference);
        
        return "{$difference}{$periods}{$tense}";
    }
}

if(!function_exists('get_client_ip')){
    
    function get_client_ip() 
    { 
        $unknown = 'unknown'; 
        
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) 

            && $_SERVER['HTTP_X_FORWARDED_FOR'] 

            && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) { 

                $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 

        } elseif(isset($_SERVER['REMOTE_ADDR']) 

                && $_SERVER['REMOTE_ADDR'] 

                && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) { 

                $ip = $_SERVER['REMOTE_ADDR']; 
            } 

        if (false !== strpos($ip, ','))
        { 
            $ip = reset(explode(',', $ip));
        } 
        return ip2long($ip); 
    }
}

if(!function_exists('get_first_charter'))
{
        function get_first_charter($str) {
            if (empty($str)) {
                return '';
            }
            $fchar = ord($str{0});
            if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($str{0});
            $s1 = iconv('UTF-8', 'gb2312', $str);
            $s2 = iconv('gb2312', 'UTF-8', $s1);
            $s = $s2 == $str ? $s1 : $str;
            $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
            if ($asc >= - 20319 && $asc <= - 20284) return 'A';
            if ($asc >= - 20283 && $asc <= - 19776) return 'B';
            if ($asc >= - 19775 && $asc <= - 19219) return 'C';
            if ($asc >= - 19218 && $asc <= - 18711) return 'D';
            if ($asc >= - 18710 && $asc <= - 18527) return 'E';
            if ($asc >= - 18526 && $asc <= - 18240) return 'F';
            if ($asc >= - 18239 && $asc <= - 17923) return 'G';
            if ($asc >= - 17922 && $asc <= - 17418) return 'H';
            if ($asc >= - 17417 && $asc <= - 16475) return 'J';
            if ($asc >= - 16474 && $asc <= - 16213) return 'K';
            if ($asc >= - 16212 && $asc <= - 15641) return 'L';
            if ($asc >= - 15640 && $asc <= - 15166) return 'M';
            if ($asc >= - 15165 && $asc <= - 14923) return 'N';
            if ($asc >= - 14922 && $asc <= - 14915) return 'O';
            if ($asc >= - 14914 && $asc <= - 14631) return 'P';
            if ($asc >= - 14630 && $asc <= - 14150) return 'Q';
            if ($asc >= - 14149 && $asc <= - 14091) return 'R';
            if ($asc >= - 14090 && $asc <= - 13319) return 'S';
            if ($asc >= - 13318 && $asc <= - 12839) return 'T';
            if ($asc >= - 12838 && $asc <= - 12557) return 'W';
            if ($asc >= - 12556 && $asc <= - 11848) return 'X';
            if ($asc >= - 11847 && $asc <= - 11056) return 'Y';
            if ($asc >= - 11055 && $asc <= - 10247) return 'Z';
            return null;
        }
}