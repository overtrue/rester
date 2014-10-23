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

/**
 * 获取来源IP
 *
 * @return integer
 */
function get_client_ip($integer = true)
{
    $unknown = 'unknown';

    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])
        && $_SERVER['HTTP_X_FORWARDED_FOR']
        && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])
                && $_SERVER['REMOTE_ADDR']
                && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (false !== strpos($ip, ',')) {
        $ip = reset(explode(',', $ip));
    }

    return $integer ? ip2long($ip) : $id;
}

