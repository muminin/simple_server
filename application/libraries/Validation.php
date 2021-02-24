<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
/**
 * Additional validations for URL testing.
 *
 * @package      Module Creator
 * @subpackage  ThirdParty
 * @category    Libraries
 * @author  Brian Antonelli <brian.antonelli@autotrader.com>
 * @created 11/19/2010
 */
 
class Validation{
                      
    /**
     * Validate URL format
     *
     * @access  public
     * @param   string
     * @return  string
     */
    function valid_url_format($str){
        if (empty($str))
        {
            return FALSE;
        }
        elseif (preg_match('/^(?:([^:]*)\:)?\/\/(.+)$/', $str, $matches))
        {
            //var_dump($matches);
            if (empty($matches[2]))
            {
                return FALSE;
            }
            elseif ( ! in_array(strtolower($matches[1]), array('http', 'https'), TRUE))
            {
                return FALSE;
            }

            $str = $matches[2];
        }

        // PHP 7 accepts IPv6 addresses within square brackets as hostnames,
        // but it appears that the PR that came in with https://bugs.php.net/bug.php?id=68039
        // was never merged into a PHP 5 branch ... https://3v4l.org/8PsSN
        if (preg_match('/^\[([^\]]+)\]/', $str, $matches) && ! is_php('7') && filter_var($matches[1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== FALSE)
        {
            $str = 'ipv6.host'.substr($str, strlen($matches[1]) + 2);
        }
        //var_dump($str);
        return (filter_var('http://'.$str, FILTER_VALIDATE_URL) !== FALSE);
    }       
 
    // --------------------------------------------------------------------
     
 
    /**
     * Validates that a URL is accessible. Also takes ports into consideration. 
     * Note: If you see "php_network_getaddresses: getaddrinfo failed: nodename nor servname provided, or not known" 
     *          then you are having DNS resolution issues and need to fix Apache
     *
     * @access  public
     * @param   string
     * @return  string
     */
    function url_exists($url){
        //cek valid url
        if (!$this->valid_url_format($url)) {
            return FALSE;
        }

        $url_data = parse_url($url); // scheme, host, port, path, query
        if (!isset($url_data['host'])) {
            $url = 'http://'.$url;
        }
        
        $url_data = parse_url($url);
       //    var_dump($url_data);
        if(!fsockopen($url_data['host'], isset($url_data['port']) ? $url_data['port'] : 80)){
            $this->set_message('url_exists', 'The URL you entered is not accessible.');
            return FALSE;
        }               
         
        return $url;
    }  
}