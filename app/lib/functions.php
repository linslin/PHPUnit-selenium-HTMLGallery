<?php 
/**
 * selenium gallery - functions lib
 *
 * @category phpUnit-selenium-Gallery
 * @package seleniumGallery
 * @author Nils Gajsek <nils.gajsek@glanzkinder.com>
 * @copyright 2013-2014 Nils Gajsek
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 0.1
 * @link https://github.com/linslin
 * 
 */

function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 