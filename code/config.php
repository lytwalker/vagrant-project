<?php

/*
  // Done load sylesheet images
  $content = file_get_contents('http://www.zol.co.zw/images/logo.jpg');
  file_put_contents('../images/logo.jpg',$content);
 */

class Conf {

    // Database Settings
    const DB_HOST = 'localhost';
    const DB_USER = 'zol_website';
    const DB_PASS = '8f7d2wftwsGddXer';
    const DB_NAME = 'zol_website_prod';
    // Hash key-values
    const HASH_ALGORYTHM = 'sha256';
    const HASH_METHOD = 'AES-256-CBC';
    const HASH_KEY = '(Z@L)_k3y1234567';
    const HASH_IV = '(Z@L)_1v12345678';


    /*     * *********************************************************
     * ZOL Payments API Settings    */     
    const API_SESSION_URL = 'http://payments.zol.co.zw/WsApi/WsSession.asmx?WSDL'; //localhost:21826
    const API_PRISM_URL = 'http://payments.zol.co.zw/WsApi/WsVersion3.asmx?WSDL';
    const SITE_BASE_URL = 'https://www.zol.co.zw/index.php?option=com_onlinesignup';
    
    /*     * *********************************************************
     * EcoCash Settings             */   
    const FINISH_URL = 'https://www.zol.co.zw/index.php?option=com_onlinesignup&task=oneapiecocashfinish';
    const UPDATE_URL = 'https://www.zol.co.zw/index.php?option=com_onlinesignup&task=oneapiecocashupdate';
    const API_ECOCASH_URL = 'http://payments.zol.co.zw/WsApi/WsVersion3.asmx?WSDL';
    const API_LOCAL_URL = 'https://www.zol.co.zw/index.php?option=com_onlinesignup&task=oneapiecocashinvoice';
    const API_USER = 'oneapi@zol.co.zw';
    const API_PASS = '#Z014p1?%';
    const API_TOKEN = '%0n34p1';
    
    /*     * *********************************************************
     * Paynow Settings              */
    const API_ZOL_URL = 'http://payments.zol.co.zw/WsApi/WsVersion3.asmx?WSDL';
    const PN_LOCAL_URL = 'https://www.zol.co.zw/';
    const PN_API_USER = 'paynow@zol.co.zw';
    const PN_API_PASS = '#PN14p1@%';
    const PN_API_TOKEN = '@P4yn0w!';

    /*     * ************************
     *  e-Sales Settings     */
    const PD_API_TOKEN = "e7c9aeb1296cdbe9c5ac5bbc258a7c7e39ebb711";
    const PD_STAGE_ID = 85;
    const PAY_ZOLID = "1234";
    
    /*     * ************************
     *  Mail Settings     */
    Const MAIL_FROM = "sales@zol.co.zw";
    const MAIL_PROTOCOL = "smtp";
    const MAIL_HOST = "smtp.mandrillapp.com";
    const MAIL_USER = "licenses@teamzol.co.zw";
    const MAIL_PWD = "Iq8q8doK1By5U3VjC7KU0w";
    const MAIL_CONFIRM_LINK = "https://www.zol.co.zw/index.php?option=com_onlinesignup&task=email&ref=";
}
