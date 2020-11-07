<?php 

namespace Johntaa\Arabic;

class CharsetC
{
    private $_utfStr  = '';
    private $_winStr  = '';
    private $_isoStr  = '';
    private $_htmlStr = '';

    // Hold an instance of the class
    private static $_instance;
    
    /**
     * Loads initialize values (this should be private method because of singleton)
     * 
     * @param array $sets Charsets you would like to support
     */         
    public function __construct($sets = array('windows-1256', 'utf-8'))
    {
        $handle = fopen(dirname(__FILE__).'/data/charset/charset.src', 'r');
        if ($handle) {
            $this->_utfStr  = fgets($handle, 4096);
            $this->_winStr  = fgets($handle, 4096);
            $this->_isoStr  = fgets($handle, 4096);
            $this->_htmlStr = fgets($handle, 4096);
            fclose($handle);
        }

        if (in_array('windows-1256', $sets)) {
            include dirname(__FILE__).'/data/charset/_windows1256.php';
        }
        
        if (in_array('iso-8859-6', $sets)) {
            include dirname(__FILE__).'/data/charset/_iso88596.php';
        }
        
        if (in_array('utf-8', $sets)) {
            include dirname(__FILE__).'/data/charset/_utf8.php';
        }
        
        if (in_array('bug', $sets)) {
            include dirname(__FILE__).'/data/charset/_bug.php';
        }
        
        if (in_array('html', $sets)) {
            include dirname(__FILE__).'/data/charset/_html.php';
        }
    }

    /**
     * The singleton method
     * 
     * @return object Instance of this class         
     * 
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */ 
    public static function singleton() 
    {
        // if (!(self::$_instance instanceof self)) {
        if (!isset(self::$_instance)) {
            $c = __CLASS__;

            self::$_instance = new $c;
        }

        return self::$_instance;
    }

    /**
     * Prevent users to clone the instance
     *
     * @return void
     */
    private function __clone() 
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    /**
     * Get HTML entity from given position
     *      
     * @param integer $index Extract position
     *      
     * @return string HTML entity
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    protected function getHTML($index)
    {
        return trim(substr($this->_htmlStr, $index*4, 4));
    }
    
    /**
     * Get UTF character from given position
     *      
     * @param integer $index Extract position
     *      
     * @return string UTF character
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    protected function getUTF($index)
    {
        return trim(substr($this->_utfStr, $index*2, 2));
    }
    
    /**
     * Get extract position of a given UTF character
     *      
     * @param string $char UTF character
     *      
     * @return integer Extract position
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    protected function findUTF($char)
    {
        if (!$char) {
            return false;
        }
        return strpos($this->_utfStr, $char)/2;
    }
    
    /**
     * Get Windows-1256 character from given position
     *      
     * @param integer $index Extract position
     *      
     * @return string Windows-1256 character
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    protected function getWIN($index)
    {
        return substr($this->_winStr, $index, 1);
    }
    
    /**
     * Get extract position of a given Windows-1256 character
     *      
     * @param string $char Windows-1256 character
     *      
     * @return integer Extract position
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    protected function findWIN($char)
    {
        if (!$char) {
            return false;
        }
        return strpos($this->_winStr, $char);
    }
    
    /**
     * Get ISO-8859-6 character from given position
     *      
     * @param integer $index Extract position
     *      
     * @return string ISO-8859-6 character
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    protected function getISO($index)
    {
        return substr($this->_isoStr, $index, 1);
    }
    
    /**
     * Get extract position of a given ISO-8859-6 character
     *      
     * @param string $char ISO-8859-6 character
     *      
     * @return integer Extract position
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    protected function findISO($char)
    {
        if (!$char) {
            return false;
        }
        return strpos($this->_isoStr, $char);
    }
    
    /**
     * Convert Arabic string from Windows-1256 to ISO-8859-6 format
     *      
     * @param string $string Original Arabic string in Windows-1256 format
     *      
     * @return string Converted Arabic string in ISO-8859-6 format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function win2iso($string)
    {
        $chars     = preg_split('//', $string);
        $converted = null;
        
        foreach ($chars as $char) {
            $key = $this->findWIN($char);
            if (is_int($key)) {
                $converted .= $this->getISO($key);
            } else {
                $converted .= $char;
            }
        }
        return $converted;
    }
    
    /**
     * Convert Arabic string from Windows-1256 to UTF-8 format
     *      
     * @param string $string Original Arabic string in Windows-1256 format
     *      
     * @return string Converted Arabic string in Windows-1256 format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function win2utf($string)
    {
        $chars     = preg_split('//', $string);
        $converted = null;
        
        foreach ($chars as $char) {
            $key = $this->findWIN($char);

            if (is_int($key)) {
                $converted .= $this->getUTF($key);
            } else {
                $converted .= $char;
            }
        }
        return $converted;
    }

    /**
     * Convert Arabic string from Windows-1256 to HTML entities format
     *      
     * @param string $string Original Arabic string in Windows-1256 format
     *      
     * @return string Converted Arabic string in HTML entities format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function win2html($string)
    {
        $chars     = preg_split('//', $string);
        $converted = null;
        
        foreach ($chars as $char) {
            $key = $this->findWIN($char);

            if (is_int($key) && $key < 58) {
                $converted .= '&#' . $this->getHTML($key) . ';';
            } else {
                $converted .= $char;
            }
        }
        return $converted;
    }

    /**
     * Convert Arabic string from ISO-8859-6 to HTML entities format
     *      
     * @param string $string Original Arabic string in ISO-8859-6 format
     *      
     * @return string Converted Arabic string in HTML entities format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function iso2html($string)
    {
        $chars     = preg_split('//', $string);
        $converted = null;
        
        foreach ($chars as $char) {
            $key = $this->findISO($char);

            if (is_int($key) && $key < 58) {
                $converted .= '&#' . $this->getHTML($key) . ';';
            } else {
                $converted .= $char;
            }
        }
        return $converted;
    }

    /**
     * Convert Arabic string from UTF-8 to HTML entities format
     *      
     * @param string $string Original Arabic string in UTF-8 format
     *      
     * @return string Converted Arabic string in HTML entities format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function utf2html($string)
    {
        $chars     = preg_split('//', $string);
        $converted = null;
        
        $cmp = false;
        foreach ($chars as $char) {
            $ascii = ord($char);
            if (($ascii == 216 || $ascii == 217) && !$cmp) {
                $code = $char;
                $cmp  = true;
                continue;
            }
            if ($cmp) {
                $code .= $char;
                $cmp   = false;
                $key   = $this->findUTF($code);
                if (is_int($key) && $key < 58) {
                    $converted .= '&#' . $this->getHTML($key) . ';';
                }
            } else {
                $converted .= $char;
            }
        }
        return $converted;
    }
    
    /**
     * Convert Arabic string from ISO-8859-6 to Windows-1256 format
     *      
     * @param string $string Original Arabic string in ISO-8859-6 format
     *      
     * @return string Converted Arabic string in Windows-1256 format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function iso2win($string)
    {
        $chars     = preg_split('//', $string);
        $converted = null;
        
        foreach ($chars as $char) {
            $key = $this->findISO($char);
            if (is_int($key)) {
                $converted .= $this->getWIN($key);
            } else {
                $converted .= $char;
            }
        }
        return $converted;
    }
    
    /**
     * Convert Arabic string from ISO-8859-6 to UTF-8 format
     *      
     * @param string $string Original Arabic string in ISO-8859-6 format
     *      
     * @return string Converted Arabic string in UTF-8 format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function iso2utf($string)
    {
        $chars     = preg_split('//', $string);
        $converted = null;
        
        foreach ($chars as $char) {
            $key = $this->findISO($char);
            if (is_int($key)) {
                $converted .= $this->getUTF($key);
            } else {
                $converted .= $char;
            }
        }
        return $converted;
    }
    
    /**
     * Convert Arabic string from UTF-8 to Windows-1256 format
     *      
     * @param string $string Original Arabic string in UTF-8 format
     *      
     * @return string Converted Arabic string in Windows-1256 format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function utf2win($string)
    {
        $chars     = preg_split('//', $string);
        $converted = null;
        
        $cmp = false;
        foreach ($chars as $char) {
            $ascii = ord($char);
            if (($ascii == 216 || $ascii == 217) && !$cmp) {
                $code = $char;
                $cmp  = true;
                continue;
            }
            if ($cmp) {
                $code .= $char;
                $cmp   = false;
                $key   = $this->findUTF($code);
                if (is_int($key)) {
                    $converted .= $this->getWIN($key);
                }
            } else {
                $converted .= $char;
            }
        }
        return $converted;
    }
    
    /**
     * Convert Arabic string from UTF-8 to ISO-8859-6 format
     *      
     * @param string $string Original Arabic string in UTF-8 format
     *      
     * @return string Converted Arabic string in ISO-8859-6 format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function utf2iso($string)
    {
        $chars     = preg_split('//', $string);
        $converted = null;
        
        $cmp = false;
        foreach ($chars as $char) {
            $ascii = ord($char);
            if (($ascii == 216 || $ascii == 217) && !$cmp) {
                $code = $char;
                $cmp  = true;
                continue;
            }
            if ($cmp) {
                $code .= $char;
                $cmp   = false;
                $key   = $this->findUTF($code);
                if (is_int($key)) {
                    $converted .= $this->getISO($key);
                }
            } else {
                $converted .= $char;
            }
        }
        return $converted;
    }
    
    /**
     * Convert buggy Arabic imported database string to Windows-1256 format
     *      
     * @param string $string Original corrupted Arabic string, usually when export
     *                       database from MySQL < 4.1 into MySQL >= 4.1 
     *                       using phpMyAdmin tool where each Arabic UTF-8 
     *                       character translate as two ISO-8859-1 characters in 
     *                       export, then translate them into UTF-8 format in import.
     *                    
     * @return string Converted Arabic string in Windows-1256 format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function bug2win($string)
    {
        $chars     = preg_split('//', $string);
        $converted = null;
        
        $cmp = false;
        foreach ($chars as $char) {
            $ascii = ord($char);
            if (($ascii == 195 || $ascii == 194) && !$cmp) {
                $code = $char;
                $cmp  = true;
                continue;
            }
            if ($cmp) {
                $code .= $char;
                $cmp   = false;
                $key   = array_search($code, $this->bug);
                if (is_int($key)) {
                    $converted .= $this->getWIN($key);
                }
            } else {
                $converted .= $char;
            }
        }
        return $converted;
    }
    
    /**
     * Convert buggy Arabic imported database string to UTF-8 format
     *      
     * @param string $string Original corrupted Arabic string, usually when export
     *                       database from MySQL < 4.1 into MySQL >= 4.1 using 
     *                       phpMyAdmin tool where each Arabic UTF-8 character 
     *                       translate as two ISO-8859-1 characters in export, 
     *                       then translate them into UTF-8 format in import.
     *                    
     * @return string Converted Arabic string in UTF-8 format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function bug2utf($string)
    {
        $chars     = preg_split('//', $string);
        $converted = null;
        
        $cmp = false;
        foreach ($chars as $char) {
            $ascii = ord($char);
            if (($ascii == 195 || $ascii == 194) && !$cmp) {
                $code = $char;
                $cmp  = true;
                continue;
            }
            if ($cmp) {
                $code .= $char;
                $cmp   = false;
                $key   = array_search($code, $this->bug);
                if (is_int($key)) {
                    $converted .= $this->getUTF($key);
                }
            } else {
                $converted .= $char;
            }
        }
        return $converted;
    }
    
    /**
     * Convert buggy Arabic imported database string to ISO-8859-6 format
     *      
     * @param string $string Original corrupted Arabic string, usually when export
     *                       database from MySQL < 4.1 into MySQL >= 4.1 using 
     *                       phpMyAdmin tool where each Arabic UTF-8 character 
     *                       translate as two ISO-8859-1 characters in export, 
     *                       then translate them into UTF-8 format in import.
     *                    
     * @return string Converted Arabic string in ISO-8859-6 format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function bug2iso($string)
    {
        $chars     = preg_split('//', $string);
        $converted = null;
        
        $cmp = false;
        foreach ($chars as $char) {
            $ascii = ord($char);
            if (($ascii == 195 || $ascii == 194) && !$cmp) {
                $code = $char;
                $cmp  = true;
                continue;
            }
            if ($cmp) {
                $code .= $char;
                $cmp   = false;
                $key   = array_search($code, $this->bug);
                if (is_int($key)) {
                    $converted .= $this->getISO($key);
                }
            } else {
                $converted .= $char;
            }
        }
        return $converted;
    }
    
    /**
     * Convert buggy Arabic string as HTML entities to UTF-8 format
     *      
     * @param string $string Original corrupted Arabic string, usually when insert 
     *                       Arabic string as HTML entities.
     *                            
     * @return string Converted Arabic string in UTF-8 format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function html2utf($string)
    {
        $converted = preg_replace($this->html, $this->utf8, $string);
        return $converted;
    }
    
    /**
     * Convert buggy Arabic string as HTML entities to Windows-1256 format
     *                    
     * @param string $string Original corrupted Arabic string, usually when insert 
     *                       Arabic string as HTML entities.
     *                    
     * @return string Converted Arabic string in Windows-1256 format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function html2win($string)
    {
        $converted = preg_replace($this->html, $this->windows1256, $string);
        return $converted;
    }
    
    /**
     * Convert buggy Arabic string as HTML entities to ISO-8859-6 format
     *      
     * @param string $string Original corrupted Arabic string, usually when insert 
     *                       Arabic string as HTML entities.
     *                    
     * @return string Converted Arabic string in ISO-8859-6 format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public function html2iso($string)
    {
        $converted = preg_replace($this->html, $this->iso88596, $string);
        return $converted;
    }
}

