<?php  

namespace Johntaa\Arabic;

class CompressStr
{
    private static $_encode;
    private static $_binary;
    
    private static $_hex;
    private static $_bin;
   
    /**
     * Loads initialize values
     *
     * @ignore
     */         
    public function __construct()
    {
        self::$_encode = iconv('utf-8', 'cp1256', ' الميوتة');
        self::$_binary = '0000|0001|0010|0011|0100|0101|0110|0111|';
    
        self::$_hex = '0123456789abcdef';
        self::$_bin = '0000|0001|0010|0011|0100|0101|0110|0111|1000|';
        self::$_bin = self::$_bin . '1001|1010|1011|1100|1101|1110|1111|';
    }

    /**
     * Set required encode and binary hash of most probably character in 
     * selected language
     *      
     * @param string $lang [en, fr, gr, it, sp, ar] Language profile selected
     *      
     * @return object $this to build a fluent interface
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public static function setLang($lang) 
    {
        switch ($lang) {
        case 'en':
            self::$_encode = ' etaoins';
            break;
        case 'fr':
            self::$_encode = ' enasriu';
            break;
        case 'gr':
            self::$_encode = ' enristu';
            break;
        case 'it':
            self::$_encode = ' eiaorln';
            break;
        case 'sp':
            self::$_encode = ' eaosrin';
            break;
        default:
            self::$_encode = iconv('utf-8', 'cp1256', ' الميوتة');
        }

        self::$_binary = '0000|0001|0010|0011|0100|0101|0110|0111|';
        
        return $this;
    }
    
    /**
     * Compress the given string using the Huffman-like coding
     *      
     * @param string $str The text to compress
     *                    
     * @return binary The compressed string in binary format
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public static function compress($str) 
    {
        $str  = iconv('utf-8', 'cp1256', $str);

        $bits = self::str2bits($str);
        $hex  = self::bits2hex($bits);
        $bin  = pack('h*', $hex);

        return $bin;
    }

    /**
     * Uncompress a compressed string
     *       
     * @param binary $bin The text compressed by compress(). 
     *                    
     * @return string The original uncompressed string
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public static function decompress($bin) 
    {
        $temp  = unpack('h*', $bin);
        $bytes = $temp[1];

        $bits = self::hex2bits($bytes);
        $str  = self::bits2str($bits);
        $str  = iconv('cp1256', 'utf-8', $str);

        return $str;
    }

    /**
     * Search a compressed string for a given word
     *      
     * @param binary $bin  Compressed binary string
     * @param string $word The string you looking for
     *                    
     * @return boolean True if found and False if not found
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public static function search($bin, $word) 
    {
        $wBits = self::str2bits($word);

        $temp  = unpack('h*', $bin);
        $bytes = $temp[1];
        $bits  = self::hex2bits($bytes);

        if (strpos($bits, $wBits)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Retrieve the origenal string length
     *      
     * @param binary $bin Compressed binary string
     *      
     * @return integer Origenal string length
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public static function length($bin) 
    {
        $temp  = unpack('h*', $bin);
        $bytes = $temp[1];
        $bits  = self::hex2bits($bytes);

        $count = 0;
        $i     = 0;

        while (isset($bits[$i])) {
            $count++;
            if ($bits[$i] == 1) {
                $i += 9;
            } else {
                $i += 4;
            }
        }

        return $count;
    }

    /**
     * Convert textual string into binary string
     *      
     * @param string $str The textual string to convert
     *       
     * @return binary The binary representation of textual string
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    protected static function str2bits($str) 
    {
        $bits  = '';
        $total = strlen($str);

        $i = -1;
        while (++$i < $total) {
            $char = $str[$i];
            $pos  = strpos(self::$_encode, $char);

            if ($pos !== false) {
                $bits .= substr(self::$_binary, $pos*5, 4);
            } else {
                $int   = ord($char);
                $bits .= '1'.substr(self::$_bin, (int)($int/16)*5, 4);
                $bits .= substr(self::$_bin, ($int%16)*5, 4);
            }
        }

        // Complete nibbel
        $add   = strlen($bits) % 4;
        $bits .= str_repeat('0', $add);

        return $bits;
    }

    /**
     * Convert binary string into textual string
     *      
     * @param binary $bits The binary string to convert
     *       
     * @return string The textual representation of binary string
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    protected static function bits2str($bits) 
    {
        $str = '';
        while ($bits) {
            $flag = substr($bits, 0, 1);
            $bits = substr($bits, 1);

            if ($flag == 1) {
                $byte = substr($bits, 0, 8);
                $bits = substr($bits, 8);

                if ($bits || strlen($code) == 8) {
                    $int  = base_convert($byte, 2, 10);
                    $char = chr($int);
                    $str .= $char;
                }
            } else {
                $code = substr($bits, 0, 3);
                $bits = substr($bits, 3);

                if ($bits || strlen($code) == 3) {
                    $pos  = strpos(self::$_binary, "0$code|");
                    $str .= substr(self::$_encode, $pos/5, 1);
                }
            }
        }

        return $str;
    }

    /**
     * Convert binary string into hexadecimal string
     *      
     * @param binary $bits The binary string to convert
     *       
     * @return hexadecimal The hexadecimal representation of binary string  
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    protected static function bits2hex($bits) 
    {
        $hex   = '';
        $total = strlen($bits) / 4;

        for ($i = 0; $i < $total; $i++) {
            $nibbel = substr($bits, $i*4, 4);

            $pos  = strpos(self::$_bin, $nibbel);
            $hex .= substr(self::$_hex, $pos/5, 1);
        }

        return $hex;
    }

    /**
     * Convert hexadecimal string into binary string
     *      
     * @param hexadecimal $hex The hexadezimal string to convert
     *       
     * @return binary The binary representation of hexadecimal string
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    protected static function hex2bits($hex) 
    {
        $bits  = '';
        $total = strlen($hex);

        for ($i = 0; $i < $total; $i++) {
            $pos   = strpos(self::$_hex, $hex[$i]);
            $bits .= substr(self::$_bin, $pos*5, 4);
        }

        return $bits;
    }
}

