# PHPUnit-selenium-HTMLGallery
=======================

## Notice
PHPUnit - php extension to generate a offline HTML-Gallery for selenium Screenshots.

## Features

- Create HTML offline gallery from selenium screenshot folder
- Gallery comes with a Zoom function


## Tested with

- TeamCity CI, phing & PHPUnit

## Screenshots

![ScreenShot](https://raw2.github.com/linslin/phpunit-seleniumGallery/development/art/screen1.png)
![ScreenShot](https://raw2.github.com/linslin/phpunit-seleniumGallery/development/art/screen2.png)



## Install with phing

1. Checkout https://github.com/linslin/phpunit-seleniumGallery into build/lib/
2. Modify build.xml and ad ad-hoc: (this is on way you can run this script)
```xml
<?xml version="1.0" encoding="UTF-8" ?>
<project name="MyProject" default="hello">

    <!-- ============================================  -->
    <!-- Exemple release                              -->
    <!-- ============================================  -->
    <target name="example">
    
       <!--  create report dir -->
       <mkdir dir="reports/" />
       <mkdir dir="reports/selenium" />
       <mkdir dir="reports/selenium/gallery" />
        
       <!--  generate selenium gallery by call adhoc -->
       <seleniumGallery/>
    <target> 
        
    <adhoc-task name="seleniumGallery"><![CDATA[
        class BarTask extends Task {
          function main() {
             require_once 'lib/seleniumGallery/seleniumGallery.php';
             $seleniumGallery = new seleniumGallery();
             $seleniumGallery->inputDir = 'reports/selenium/screenshots';
             $seleniumGallery->baseDir = getcwd().'/lib/seleniumGallery';
             $seleniumGallery->generate();
          }
      }
    ]]></adhoc-task>
</project> 
```

3. A HTML-Gallery will be created in build/reports/selenium/gallery/

## License
phpunit-seleniumGallery is released under MIT license.