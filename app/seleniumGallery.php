<?php 
/**
 * selenium gallery generator
 *
 * @category  phpUnit-selenium-Gallery
 * @package   seleniumGallery
 * @author    Nils Gajsek <nils.gajsek@glanzkinder.com>
 * @copyright 2013-2014 Nils Gajsek
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version   0.1
 * @link      https://github.com/linslin
 * 
 */
require 'lib/functions.php';
require 'lib/phpThumb/phpthumb.class.php';

class seleniumGallery
{
    
    // ################## class vars ##################
    
    /**
     * Search file extension in selenium screenshot dir
     * @var string
     */
    private $_fileExtension = 'png';
    
    /**
     * Vendor JS directory
     * @var string
     */
    private $_vendorJSdir = 'js/';
    
    /**
     * Input directory. Directory to search selenium screenshots
     * @var string
     */
    public $inputDir = '';
    
    /**
     * Input directory. Directory to search selenium screenshots
     * @var string
     */
    public $baseDir = '';
    
    /**
     * Report directory. Directory to create report
     * @var string
     */
    public $reportDir = 'reports/selenium/gallery';
    
    /**
     * Default template path
     * @var string
     */
    private $_template = 'views/default.php';
    
    /**
     * Image file array
     * @var array
     */
    private $_images = array();

    
    
    // ################## class methods ##################
    
    /**
     * Public class constructor
     */
    public function __construct()
    {
    }
    
    
    /**
     * Generate generate
     */
    public function generate()
    {
        // try collect images
        $this->collectImage();
        
        // try make gallery
        if (!empty($this->_images)) {
            
            $viewTemplate = file_get_contents($this->baseDir.'/views/part-header.html');
            $bodyTemplate = file_get_contents($this->baseDir.'/views/part-body.html');
            $imagelistTemplate = file_get_contents($this->baseDir.'/views/part-imagelist.html');
            $footerTemplate = file_get_contents($this->baseDir.'/views/part-footer.html');
            $items = '';
            $activItem = '';
            $first = true;
            
            //load view template
            foreach ($this->_images as $image) {
                
                $image = str_replace("%", "%25", $image);
               
                if($first){
                    $first = false;
                    $activItem = '<a href="./image/'.$image.'"  style="margin-bottom:10px;" class="jqzoom" rel="gal1"  title="'.$image.'" ><img src="./image/thumb_'.$image.'"  title="'.$image.'"  style="border: 4px solid #666;"></a>';
                    $items .= '<li><a class="zoomThumbActive" href="javascript:void(0);" rel="{gallery: \'gal1\', smallimage: \'./image/thumb_'.$image.'\', largeimage: \'./image/'.$image.'\'}"><img src="./image/thumb_'.$image.'"></a></li>';
                } else {
                    $items .= '<li><a href="javascript:void(0);" rel="{gallery: \'gal1\', smallimage: \'./image/thumb_'.$image.'\',largeimage: \'./image/'.$image.'\'}"><img src="./image/thumb_'.$image.'"></a></li>'; 
                }
            }
            
            //create template and copy files
            file_put_contents($this->reportDir.'/gallery.html', $viewTemplate.$bodyTemplate.$activItem.$imagelistTemplate.$items.$footerTemplate);
            recurse_copy($this->baseDir.'/'.$this->_vendorJSdir, $this->reportDir.'/js');
            recurse_copy($this->inputDir, $this->reportDir.'/image');
            
            //creat thumbs
            $this->createThumbs();
        } else {
            echo "\nInfo: seleniumGallery no images found in: ".getcwd().'/'.$this->inputDir.'/'."\n";
        }
    }
    
    
    private function createThumbs()
    {
        if ($handle = opendir(getcwd().'/'.$this->inputDir.'/')) {
        
            while (false !== ($file = readdir($handle))) {
                if(pathinfo($file, PATHINFO_EXTENSION) === $this->_fileExtension){

                    //make image dir writeable for thumb create
                    chmod(getcwd().'/'.$this->reportDir.'/image', 0775);
                    
                    $phpThumb = new phpThumb();
                    $phpThumb->setSourceData(file_get_contents(getcwd().'/'.$this->inputDir.'/'.$file));
                    $phpThumb->setParameter('w', 400);
                    $phpThumb->GenerateThumbnail();
                    $phpThumb->RenderToFile(getcwd().'/'.$this->reportDir.'/image/thumb_'.$file);
                    $phpThumb->purgeTempFiles();
                }
            }
            closedir($handle);
        }
    }
    
    
    /**
     * Readout all png files from input dir
     */
    private function collectImage()
    {
        
        if ($handle = opendir($this->inputDir)) {
        
            /* Das ist der korrekte Weg, ein Verzeichnis zu durchlaufen. */
            while (false !== ($file = readdir($handle))) {
               if(pathinfo($file, PATHINFO_EXTENSION) === $this->_fileExtension){
                   $this->_images[] = $file;
               }
            }
            closedir($handle);
        }
    }
}
?>