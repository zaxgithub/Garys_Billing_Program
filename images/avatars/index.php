<?php
require_once(realpath(dirname("../../../"))."\\includes\\constants.php");
require_once(SITE_PATH."\\functions\\utility.functions.php");


if ($handle = opendir(realpath(dirname(__FILE__)))) {
    while (false !== ($file = readdir($handle))) {
			if (preg_match("/(\.png$)/i", $file) || preg_match("/(\.jpg$)/i", $file) || preg_match("/(\.gif$)/i", $file)){
				echo $file."<br />\r\n";
				echo "<img src=\"".getAvatarSrc($file)."\" width=\"120\" height=\"120\" alt=\"".$file."\" border=\"0\" />";
			}
    }
    closedir($handle);
}
?>
