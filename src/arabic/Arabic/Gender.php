<?php  namespace Johntaa\Arabic;

class Gender
{
    /**
     * Loads initialize values
     *
     * @ignore
     */         
    public function __construct()
    {
    }

    /**
     * Check if Arabic word is feminine
     *          
     * @param string $str Arabic word you would like to check if it is 
     *                    feminine
     *                    
     * @return boolean Return true if input Arabic word is feminine
     * @author Khaled Al-Sham'aa <khaled@ar-php.org>
     */
    public static function isFemale($str)
    {
        $female = false;
        
        $words = explode(' ', $str);
        $str   = $words[0];

        $str = str_replace(array('أ','إ','آ'), 'ا', $str);

        $last       = mb_substr($str, -1, 1, 'UTF-8');
        $beforeLast = mb_substr($str, -2, 1, 'UTF-8');

        if ($last == 'ة' || $last == 'ه' || $last == 'ى' || $last == 'ا' 
            || ($last == 'ء' && $beforeLast == 'ا')
        ) {

            $female = true;
        } elseif (preg_match("/^[اإ].{2}ا.$/u", $str) 
            || preg_match("/^[إا].ت.ا.+$/u", $str)
        ) {
            // الأسماء على وزن إفتعال و إفعال
            $female = true;
        } else {
            // List of the most common irregular Arabic female names
            $names = file(dirname(__FILE__).'/data/female.txt');
            $names = array_map('trim', $names);

            if (array_search($str, $names) > 0) {
                $female = true;
            }
        }

        return $female;
    }
}
