<?php  namespace Johntaa\Arabic;
 
class KeySwap
{
    // First 12 chars replaced by 1 Byte in Arabic keyboard 
    // while rest replaced by 2 Bytes UTF8
    private static $_swapEn = '{}DFL:"ZCV<>`qwertyuiop[]asdfghjkl;\'zxcvnm,./~QWERYIOPASHJKXN?M';
    private static $_swapAr = '<>][/:"~}{,.ذضصثقفغعهخحجدشسيبلاتنمكطئءؤرىةوزظًٌَُّإ÷×؛ٍِأـ،ْآ؟’';
    
    private static $_swapFr       = '²azertyuiop^$qsdfghjklmù*<wxcvn,;:!²1234567890°+AZERYIOP¨£QSDFHJKLM%µ<WXCVN?./§';
    private static $_swapArAzerty = '>ضصثقفغعهخحجدشسيبلاتنمكطذ\\ئءؤرىةوزظ>&é"\'(-è_çà)=ضصثقغهخحجدشسيباتنمكطذ\\ئءؤرىةوزظ';  

    private $_transliteration = array();
    
    /**
     * Loads initialize values
     *
     * @ignore
     */         
    public function __construct()
    {
        $xml = simplexml_load_file(dirname(__FILE__).'/data/charset/arabizi.xml');
        
        foreach ($xml->transliteration->item as $item) {
            $index = $item['id'];
            $this->_transliteration["$index"] = (string)$item;
        } 
    }
    
    /**
     * Make conversion to swap that odd Arabic text by original English sentence 
     * you meant when you type on your keyboard (if keyboard language was  
     * incorrect)
     *          
     * @param string $text Odd Arabic string
     *                    
     * @return string Normal English string
     * @author Khaled Al-Sham'aa
     */
    public static function swapAe($text)
    {
        $output = '';
        
        $text = stripslashes($text);

        $text = str_replace('لا', 'b', $text);
        $text = str_replace('لآ', 'B', $text);
        $text = str_replace('لأ', 'G', $text);
        $text = str_replace('لإ', 'T', $text);
        $text = str_replace('‘', 'U', $text);
        
        $max = strlen($text);
        
        for ($i=0; $i<$max; $i++) {

            $pos = strpos(self::$_swapAr, $text[$i]);

            if ($pos === false) {
                $output .= $text[$i];
            } else {
                $pos2 = strpos(self::$_swapAr, $text[$i].$text[$i+1]);
                if ($pos2 !== false) {
                    $pos = $pos2;
                    $i++;
                }

                if ($pos < 12) {
                    $adjPos = $pos;
                } else {
                    $adjPos = ($pos - 12)/2 + 12;
                }

                $output .= substr(self::$_swapEn, $adjPos, 1);
            }

        }
        
        return $output;
    }
    
    /**
     * Make conversion to swap that odd English text by original Arabic sentence 
     * you meant when you type on your keyboard (if keyboard language was  
     * incorrect)
     *           
     * @param string $text Odd English string
     *                    
     * @return string Normal Arabic string
     * @author Khaled Al-Sham'aa
     */
    public static function swapEa($text)
    {
        $output = '';
        
        $text = stripslashes($text);
        
        $text = str_replace('b', 'لا', $text);
        $text = str_replace('B', 'لآ', $text);
        $text = str_replace('G', 'لأ', $text);
        $text = str_replace('T', 'لإ', $text);
        $text = str_replace('U', '‘', $text);
        
        $max = strlen($text);
        
        for ($i=0; $i<$max; $i++) {
            $pos = strpos(self::$_swapEn, $text[$i]);
            if ($pos === false) {
                $output .= $text[$i];
            } else {
                if ($pos < 12) {
                    $adjPos = $pos;
                    $len    = 1;
                } else {
                    $adjPos = ($pos - 12)*2 + 12;
                    $len    = 2; 
                }
                if ($adjPos == 112) {
                    $len = 3;
                }
                $output .= substr(self::$_swapAr, $adjPos, $len);
            }
        }
        
        return $output;
    }
}
