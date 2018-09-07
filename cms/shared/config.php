<?php

/*
  // Done load sylesheet images
  $content = file_get_contents('http://www.zol.co.zw/images/logo.jpg');
  file_put_contents('../images/logo.jpg',$content);
 */

class CMSConf {

    // Database Settings
    const DB_HOST = 'localhost';
    const DB_USER = 'root'/*'mapsqa'*/;
    const DB_PASS = 'root'/*'kasiooi12j3#'*/;
    const DB_NAME = 'zol_map_qa'/*'zol_map'*/;
    
    //other settings
    const MAP_ROOT = 'https://localhost/cmap';
}
