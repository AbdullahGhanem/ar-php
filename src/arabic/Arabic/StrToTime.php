<?php 

namespace Johntaa\Arabic;

class StrToTime
{
    private static $_hj = array();

    private static $_strtotimeSearch  = array();
    private static $_strtotimeReplace = array();
    
    /**
     * Loads initialize values
     *
     * @ignore
     */         
    public function __construct()
    {
        $xml = simplexml_load_file(dirname(__FILE__).'/data/ArStrToTime.xml');
    
        foreach ($xml->xpath("//str_replace[@function='strtotime']/pair") as $pair) {
            array_push(self::$_strtotimeSearch, (string)$pair->search);
            array_push(self::$_strtotimeReplace, (string)$pair->replace);
        } 

        foreach ($xml->hj_month->month as $month) {
            array_push(self::$_hj, (string)$month);
        } 
    }
    
    /**
     * This method will parse about any Arabic textual datetime description into 
     * a Unix timestamp
     *          
     * @param string  $text The string to parse, according to the GNU » 
     *                      Date Input Formats syntax (in Arabic).
     * @param integer $now  The timestamp used to calculate the 
     *                      returned value.       
     *                    
     * @return Integer Returns a timestamp on success, FALSE otherwise
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public static function strtotime($text, $now)
    {
        $int = 0;

        for ($i=0; $i<12; $i++) {
            if (strpos($text, self::$_hj[$i]) > 0) {
                preg_match('/.*(\d{1,2}).*(\d{4}).*/', $text, $matches);

                include dirname(__FILE__).'/Mktime.php';
                $temp = new Mktime();
                $fix  = $temp->mktimeCorrection($i+1, $matches[2]); 
                $int  = $temp->mktime(0, 0, 0, $i+1, $matches[1], $matches[2], $fix);
                $temp = null;

                break;
            }
        }

        if ($int == 0) {
            $patterns     = array();
            $replacements = array();
  
            array_push($patterns, '/َ|ً|ُ|ٌ|ِ|ٍ|ْ|ّ/');
            array_push($replacements, '');
  
            array_push($patterns, '/\s*ال(\S{3,})\s+ال(\S{3,})/');
            array_push($replacements, ' \\2 \\1');
  
            array_push($patterns, '/\s*ال(\S{3,})/');
            array_push($replacements, ' \\1');
  
            $text = preg_replace($patterns, $replacements, $text);
            $text = str_replace(
                self::$_strtotimeSearch, 
                self::$_strtotimeReplace, 
                $text
            );
  
            $pattern = '[ابتثجحخدذرزسشصضطظعغفقكلمنهوي]';
            $text    = preg_replace("/$pattern/", '', $text);

            $int = strtotime($text, $now);
        }
        
        return $int;
    }
}
