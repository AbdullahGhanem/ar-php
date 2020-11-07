<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Convert keyboard language programmatically (English - Arabic)</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style.css" media="all" />
</head>

<body>

<div class="Paragraph" dir="rtl">
<h2 dir="ltr">Example Output 1:</h2>
<?php
/**
 * Example of Convert keyboard language programmatically (English - Arabic)
 *
 * @category  I18N
 * @package   I18NArabic
 * @author    Khaled Al-Sham'aa <khaled@ar-php.org>
 * @copyright 2006-2013 Khaled Al-Sham'aa
 *
 * @license   LGPL <http://www.gnu.org/licenses/lgpl.txt>
 * @link      http://www.ar-php.org
 */

error_reporting(E_STRICT);
$time_start = microtime(true);

require '../../Arabic.php';
$Arabic = new I18NArabic('KeySwap');

$str = "Hpf lk hgkhs hglj'vtdkK Hpf hg`dk dldg,k f;gdjil Ygn
,p]hkdm hgHl,v tb drt,k ljv]]dk fdk krdqdk>";
echo "<u><i>Before:</i></u><br />$str<br /><br />";

$text = $Arabic->swapEa($str);
echo "<u><i>After:</i></u><br />$text<br /><br /><b>جبران خليل جبران</b>";
?>
</div><br />
<div class="Paragraph">
<h2>Example Code 1:</h2>
<?php
$code = <<< END
<?php
    require '../../Arabic.php';
    \$Arabic = new I18NArabic('KeySwap');

    \$str = "Hpf lk hgkhs hglj'vtdkK Hpf hg`dk dldg,k f;gdjil Ygn
    ,p]hkdm hgHl,v tb drt,k ljv]]dk fdk krdqdk>";
    echo "<u><i>Before:</i></u><br />\$str<br /><br />";
    
    \$text = \$Arabic->swapEa(\$str);
    echo "<u><i>After:</i></u><br />\$text<br /><br /><b>جبران خليل جبران</b>";
END;

highlight_string($code);

?>
</div>
<br />
<div class="Paragraph">
<h2 dir="ltr">Example Output 2:</h2>
<?php
    $str = "ِىغ هىفثممهلثىف بخخم ؤشى ةشنث فاهىلس لاهللثق ةخقث ؤخةحمثء شىي ةخقث رهخمثىفز ÷ف فشنثس ش فخعؤا خب لثىهعس شىي ش مخف خب ؤخعقشلث فخ ةخرث هى فاث خححخسهفث يهقثؤفهخىز";
    echo "<u><i>Before:</i></u><br />$str<br /><br />";
    
    $text = $Arabic->swapAe($str);
    echo "<u><i>After:</i></u><br />$text<br /><br /><b>Albert Einstein</b>";
?>

</div><br />
<div class="Paragraph">
<h2>Example Code 2:</h2>
<?php
$code = <<< END
<?php
    require '../../Arabic.php';
    \$Arabic = new I18NArabic('KeySwap');
    
    \$str = "ِىغ هىفثممهلثىف بخخم ؤشى ةشنث فاهىلس لاهللثق ةخقث ؤخةحمثء شىي ةخقث رهخمثىفز ÷ف فشنثس ش فخعؤا خب لثىهعس شىي ش مخف خب ؤخعقشلث فخ ةخرث هى فاث خححخسهفث يهقثؤفهخىز";
    
    echo "<u><i>Before:</i></u><br />\$str<br /><br />";
    
    \$text = \$Arabic->swapAe(\$str);
    echo "<u><i>After:</i></u><br />\$text<br /><br /><b>Albert Einstein</b>";
END;

highlight_string($code);

$time_end = microtime(true);
$time = $time_end - $time_start;

echo "<hr />Total execution time is $time seconds<br />\n";
echo 'Amount of memory allocated to this script is ' . memory_get_usage() . ' bytes';

$included_files = get_included_files();
echo '<h4>Names of included or required files:</h4><ul>';

foreach ($included_files as $filename) {
    echo "<li>$filename</li>";
}

echo '</ul>';
?>
<a href="../Docs/I18NArabic/_Arabic---KeySwap.php.html" target="_blank">Related Class Documentation</a>
</div>
</body>
</html>