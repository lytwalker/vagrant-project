<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once "config.php";

ini_set("soap.wsdl_cache_enabled", 0);

/**
 * Description of ZOLPaymentUtils
 * This is a wrapper class for ZOLPaymentAPI webservice. 
 * It centralises calls to the web service such that all other classes can make
 * webservice calls via this class.
 * @author tnderere
 */
class ZOLPaymentsUtils {

    //hashing features
    private $hash_algo = Conf::HASH_ALGORYTHM;
    private $hast_method = Conf::HASH_METHOD;
    private $hast_key = Conf::HASH_KEY;
    private $hast_iv = Conf::HASH_IV;

    // Regular expression features
    const REGEX_ZOLID = '\d{2,}';
    const REGEX_AMOUNT = '^[+-]?[1-9]([0-9]{1,2})?(?:,?[0-9]{3})*(?:\.[0-9]{2})?$';
    const REGEX_ECOCASHNO = '\+?0?(263)?\s*7\s*[7,8](\s*[\d]){7}';
    const REGEX_MOBILENO = '\+?0?(263)?\s*7\s*[0-9](\s*[\d]){7}';
    const REGEX_EMAIL = '[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}';
    const REGEX_PWD = '[A-Za-z0-9._%+-,;:!@#$%&*]{6,}';
    const REGEX_PWDSTRONG = '(?=^.{4,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$';

    // Login output
    private $ws_ssn;
    public $token;
    // Properties set upon Each Request
    public $Status;

    function __construct() {
        $this->Status = '';
    }

    public function __destruct() {
        
    }

    // Session Functions
    function Login($api_user = Conf::API_USER, $api_pwd = Conf::API_PASS, $api_token = Conf::API_TOKEN, $api_timeout = 120000) {
        try {
            $this->ws_ssn = new SoapClient(Conf::API_SESSION_URL);
            $xml = new SimpleXMLElement(
                    $this->ws_ssn->Login(
                            array(
                                'username' => $api_user,
                                'password' => $api_pwd,
                                'apitoken' => $api_token,
                                'idle_timeout' => $api_timeout
                            )
                    )->LoginResult
            );
            $this->Status = $xml->Status;
            $this->token = $xml->token;
            return $this->Status;
        } catch (Exception $e) {
            return 'ERR ' . $e->getMessage();
        }
    }

    function Logout($api_user = Conf::API_USER) {
        try {
            if (!isset($this->ws_ssn)) {
                $this->ws_ssn = new SoapClient(Conf::API_SESSION_URL);
            }
            $xml = new SimpleXMLElement(
                    $this->ws_ssn->Logout(
                            array(
                                'username' => $api_user,
                                'token' => $this->token
                            )
                    )->LogoutResult
            );
            $this->token = $xml->token;
            $this->Status = $xml->Status;
            return $this->Status;
        } catch (Exception $e) {
            return 'ERR ' . $e->getMessage();
        }
    }

    // Utility Functions
    public function HashEncode($string) {
        $output = false;

        // hash
        $key = hash($this->hash_algo, $this->hast_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash($this->hash_algo, $this->hast_iv), 0, 16);

        $output = openssl_encrypt($string, $this->hast_method, $key, 0, $iv);
        $output = base64_encode($output);
        return $output;
    }

    public function HashDecode($string) {
        $output = false;
        // hash
        $key = hash($this->hash_algo, $this->hast_key);
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash($this->hash_algo, $this->hast_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($string), $this->hast_method, $key, 0, $iv);
        return $output;
    }

    public function GetRef($prefix = "ZOL") {
        $res = '';
        try {
            $t = explode(" ", microtime());
            $res = $prefix . date("ymdHis", $t[1]) . substr((string) $t[0], 2, 3);
        } catch (Exception $e) {
            $res = $prefix . date("ymdHis", $t[1]);
        }
        return $res . rand(10, 99);
    }

    public function DisplayMSISDN($msisdn) {
        if (strlen($msisdn) >= 9)
            try {
                $msisdn = str_replace(" ", "", str_replace("+", "", str_replace("-", "", str_replace("/", "", trim($msisdn)))));
                $arr1 = str_split('0' . substr($msisdn, strlen($msisdn) - 9), 3);
                return "$arr1[0] $arr1[1] $arr1[2]$arr1[3]";
            } catch (Exception $e) {
                return $msisdn;
            }
    }

    // EchoCash Payment Functions
    public function FormatMSISDN($msisdn) {
        if (strlen($msisdn) > 9) /* 776 251 511 */
            try {
                $ws = new SoapClient(Conf::API_ECOCASH_URL);
                $msisdn = $ws->EcocashFormatMSISDN(
                                array('msisdn' => $msisdn)
                        )->EcocashFormatMSISDNResult;
            } catch (Exception $e) {
                $msisdn = str_replace(" ", "", str_replace("+", "", str_replace("-", "", str_replace("/", "", trim($msisdn)))));
                try {
                    $msisdn = substr($msisdn, strlen($msisdn) - 9);
                } catch (Exception $e) {
                    
                }
            }
        return $msisdn;
    }

    public function ListServices($technology_plan) {
        try {
            $ws = new SoapClient(Conf::API_ECOCASH_URL);
            $fres = $ws->ListServicesForOrder(array(
                        'token' => $this->token,
                        'technology' => $technology_plan
                    ))->ListServicesForOrderResult;
            return json_decode($fres);
        } catch (Exception $e) {
            // Build Error Response
            return '{ "data":null }';
        }
    }

    public function PostServiceOrderValidate($custkey, $servicename, $servicelogin) {
        try {
            $ws = new SoapClient(Conf::API_ECOCASH_URL);
            $fres = $ws->ValidateServiceOrder(array(
                        'token' => $this->token,
                        'custkey' => $custkey,
                        'servicename' => $servicename,
                        'servicelogin' => $servicelogin
                    ))->ValidateServiceOrderResult;
        } catch (Exception $e) {
            // Build Error Response
            $fres = '{"CustomerKey":"' . $custkey . '",'
                    . '"transactionOperationStatus":"UNREACHABLE PostServiceOrderValidate line ' . $e->getLine() . '"'
                    . '}';
        }
        return json_decode($fres);
    }

    public function LookupStatus($msisdn, $transid) {
        try {
            $ws = new SoapClient(Conf::API_ECOCASH_URL);
            $fres = $ws->EcocashLookupStatus(
                            array(
                                'token' => $this->token,
                                'msisdn' => $msisdn,
                                'transid' => $transid
                            )
                    )->EcocashLookupStatusResult;
        } catch (Exception $e) {
            // Build Error Response
            $fres = '{"endUserId":"' . $msisdn . '",'
                    . '"clientCorrelator":"' . $transid . '",'
                    . '"transactionOperationStatus":"UNREACHABLE Payments API Status line ' . $e->getLine() . '"'
                    . '}';
        }
        return json_decode($fres);
    }

    public function PostCharge($msisdn, $transid, $amount, $currency, $custkey, $description, $updateurl) {
        return $this->PostRequest('Charge', $msisdn, $transid, $amount, $currency, $custkey, $description, $updateurl);
    }

    public function PostRefund($msisdn, $transid, $amount, $currency, $custkey, $description, $updateurl) {
        return $this->PostRequest('Refund', $msisdn, $transid, $amount, $currency, $custkey, $description, $updateurl);
    }

    public function PostRequest($type, $msisdn, $transid, $amount, $currency, $custkey, $description, $updateurl) {
        try {
            $ws = new SoapClient(Conf::API_ECOCASH_URL);
            $arr = array(
                'token' => $this->token,
                'msisdn' => $msisdn,
                'transid' => $transid,
                'amount' => $amount,
                'currency' => $currency,
                'custkey' => $custkey,
                'description' => rawurlencode($description),
                'updateurl' => $updateurl
            );
            if (strtolower($type) == 'refund') {
                $fres = $ws->EcocashPostRefund($arr)->EcocashPostRefundResult;
            } else {
                $fres = $ws->EcocashPostCharge($arr)->EcocashPostChargeResult;
            }
        } catch (Exception $e) {
            // Build Error Response
            $fres = '{"endUserId":"' . $msisdn . '",'
                    . '"clientCorrelator":"' . $transid . '",'
                    . '"CustomerKey":"' . $custkey . '",'
                    . '"transactionOperationStatus":"UNREACHABLE Payments API Post ' . $type . ' line ' . $e->getLine() . '"'
                    . '}';
        }
        return json_decode($fres);
    }

    public function PostServiceOrder($msisdn, $transid, $amount, $currency, $custkey, $description, $servicename, $servicelogin, $updateurl, $servicepwd = "") {
        try {
            $ws = new SoapClient(Conf::API_ECOCASH_URL);
            $fres = $ws->EcocashPostServiceOrder(
                            array(
                                'token' => $this->token,
                                'msisdn' => $msisdn,
                                'transid' => $transid,
                                'amount' => $amount,
                                'currency' => $currency,
                                'custkey' => $custkey,
                                'description' => rawurlencode($description),
                                'servicename' => $servicename,
                                'servicelogin' => $servicelogin,
                                'servicepwd' => $servicepwd,
                                'updateurl' => $updateurl
                            )
                    )->EcocashPostServiceOrderResult;
        } catch (Exception $e) {
            // Build Error Response
            $fres = '{"endUserId":"' . $msisdn . '",'
                    . '"clientCorrelator":"' . $transid . '",'
                    . '"CustomerKey":"' . $custkey . '",'
                    . '"transactionOperationStatus":"UNREACHABLE PostServiceOrder line ' . $e->getLine() . '"'
                    . '}';
        }
        return json_decode($fres);
    }

    // Paynow Payment Functions
    public function PaynowPollRequest($transid, $pollurl) {
        try {
            $ws = new SoapClient(Conf::API_ZOL_URL);
            $fres = $ws->PaynowPollRequest(
                            array(
                                'token' => $this->token,
                                'transid' => $transid
                            )
                    )->PaynowPollRequestResult;
        } catch (Exception $e) {
            // Build Error Response
            $fres = '{"status":"Error",'
                    . '"reference":"' . $transid . '",'
                    . '"statusmsg":"UNREACHABLE Payments API Status line ' . $e->getLine() . '",'
                    . '"browserurl":"",'
                    . '"pollurl":"",'
                    . '"hash":"",'
                    . '"error":"' . $e->getMessage() . '",'
                    . '"IsPaidup":false,'
                    . '"IsError":true'
                    . '}';
        }
        return json_decode($fres);
    }

    public function PaynowMessageRequest($msisdn, $transid, $amount, $currency, $custkey, $description, $resulturl, $returnurl, $updateurl, $authemail = "") {
        try {
            $ws = new SoapClient(Conf::API_ZOL_URL);
            $arr = array(
                'token' => $this->token,
                'msisdn' => $msisdn,
                'transid' => $transid,
                'amount' => $amount,
                'currency' => $currency,
                'custkey' => $custkey,
                'description' => rawurlencode($description),
                'resulturl' => $resulturl,
                'returnurl' => $returnurl,
                'updateurl' => $updateurl,
                'authemail' => $authemail
            );
            $fres = $ws->PaynowMessageRequest($arr)->PaynowMessageRequestResult;
        } catch (Exception $e) {
            // Build Error Response
            $fres = '{"status":"Error",'
                    . '"reference":"' . $transid . '",'
                    . '"CustomerKey":"' . $custkey . '",'
                    . '"statusmsg":"UNREACHABLE Payments API line ' . $e->getLine() . '",'
                    . '"browserurl":"",'
                    . '"pollurl":"",'
                    . '"hash":"",'
                    . '"error":"' . $e->getMessage() . ' Line ' . $e->getLine() . ' ' . $e->getTraceAsString() . '",'
                    . '"IsPaidup":false,'
                    . '"IsError":true'
                    . '}';
            echo $e;
        }
        return json_decode($fres);
    }

    public function PaynowServiceOrderRequest($msisdn, $transid, $amount, $currency, $custkey, $description, $servicename, $servicelogin, $resulturl, $returnurl, $updateurl, $servicepwd = "", $authemail = "") {
        try {
            $ws = new SoapClient(Conf::API_ZOL_URL);
            $arr = array(
                'token' => $this->token,
                'msisdn' => $msisdn,
                'transid' => $transid,
                'amount' => $amount,
                'currency' => $currency,
                'custkey' => $custkey,
                'description' => rawurlencode($description),
                'servicename' => $servicename,
                'servicelogin' => $servicelogin,
                'servicepwd' => $servicepwd,
                'resulturl' => $resulturl,
                'returnurl' => $returnurl,
                'updateurl' => $updateurl,
                'authemail' => $authemail
            );
            $fres = $ws->PaynowServiceOrderRequest($arr)->PaynowServiceOrderRequestResult;
        } catch (Exception $e) {
            // Build Error Response
            $fres = '{"status":"Error",'
                    . '"reference":"' . $transid . '",'
                    . '"CustomerKey":"' . $custkey . '",'
                    . '"statusmsg":"UNREACHABLE PaynowServiceOrderRequest line ' . $e->getLine() . '",'
                    . '"browserurl":"",'
                    . '"pollurl":"",'
                    . '"hash":"",'
                    . '"error":"' . $e->getMessage() . '",'
                    . '"IsPaidup":false,'
                    . '"IsError":true'
                    . '}';
        }
        return json_decode($fres);
    }

    public function ListServicesForTechnology($technology) {
        $fres = "";
        try {
            $ws = new SoapClient(Conf::API_ZOL_URL);
            $arr = array(
                'token' => $this->token,
                'technology' => $technology
            );
            $fres = $ws->ListServicesForTechnology($arr)->ListServicesForTechnologyResult;
        } catch (Exception $e) {
            $json = '{'
                    . '"Status":"Error",'
                    . '"Code":"' . $e->getCode() . '",'
                    . '"Description":"' . $e->getMessage() . '"'
                    . '}';
        }
        return json_decode($fres);
    }

    public function ListPipeDriveProducts($technology) {
        $fres = "";
        try {
            $technology = strtolower($technology);
            $filterid = 145;
            if ($technology == 'fiber' || $technology == 'fibre')
                $filterid = 146;
            else if ($technology == 'wimax')
                $filterid = 147;
            else if ($technology == 'vsat')
                $filterid = 148;
            $options = array('http' => array('header' => "Content-type: application/json\r\n", 'method' => "GET"));
            $url = "https://api.pipedrive.com/v1/products?start=0&sort=code,019f0eeb0035e3b7ff0c96e696ccce7c5769bb01&filter_id=$filterid&api_token=" . Conf::PD_API_TOKEN;
            $context = stream_context_create($options);
            $fres = (file_get_contents($url, false, $context));
        } catch (Exception $e) {
            $json = '{'
                    . '"Status":"Error",'
                    . '"Code":"' . $e->getCode() . '",'
                    . '"Description":"' . $e->getMessage() . '"'
                    . '}';
        }
        return json_decode($fres);
    }

    public function AddPipeDriveDeal($address, $lat, $lng, $code, $zone, $tech, $svcn, $svcd, $amt, $cur, $nam, $mob, $eml, $personid, $dealid, $ref, $det, $promo_prize="", $promo_code="") {
        global $deal_prod_id;
        try {
            // Post Person Data
            $options = array(
                'http' => array(
                    'header' => "Content-type: application/json\r\n", 'method' => ($personid == "0" ? "POST" : "PUT"),
                    'content' => '{'
                    . '"name":"' . $nam . '", '
                    . '"e017a3fba070f9cb0ef8deec87abf4ca4833ccac":"' . $address . '", '
                    . '"email":"' . $eml . '", '
                    . '"phone":"' . $mob . '" '
                    . '}',
                ),
            );
            $url = "https://api.pipedrive.com/v1/persons" . ($personid == "0" ? "" : "/$personid") . "?api_token=" . Conf::PD_API_TOKEN;
            $context = stream_context_create($options);
            $pd_resp = file_get_contents($url, false, $context);
            if ($personid == "0") {
                /* only do this for new persons */
                if (!isset($pd_resp) || strlen($pd_resp) < 3) {
                    throw new Exception('Failed to create contact person');
                }
                $json = json_decode($pd_resp);
                if ($personid == "0" AND isset($json->{"data"})) {
                    if (isset($json->{"data"}->{"id"})) {
                        $personid = $json->{"data"}->{"id"};
                    }
                }
            }
            // Post Deal Data
            $options = array(
                'http' => array(
                    'header' => "Content-type: application/json\r\n", 'method' => ($dealid == "0" ? "POST" : "PUT"),
                    'content' => '{'
                    . '"title":"' . $address . '", '
                    . '"stage_id":85, ' /* "' . Conf::PD_STAGE_ID . '" */
                    . '"person_id":"' . $personid . '", '
                    . '"2247ed13c03ae32ce2017fa921001f528744b54d":"' . $address . '", '
                    . '"37aaaa7fe84b2441391cdee678b5520e84ce4be4":"' . $lat . ',' . $lng . '", '
                    . '"b15bcb12a61d945a14be6c38fb61cccc5beb73ae":"' . $ref . '", '
					. '"ec08198dc69abfe8947cfbb35f927d659edd7c2a":"' . $promo_prize . '", '
					. '"b1d9c51513c85bf7d5be1d2dc6059098ae0c0f6f":"' . $promo_code . '", '
                    . (strlen($code) > 1 ? '"d79370a6c8659a8cb1fa1b55465f40fd2e6c9ada":"' . $code . '", ' : "")
                    . (strlen($zone) > 1 ? '"878a1f0212b0d6cae37702b4bee21d80e854f113":"' . $zone . '", ' : "")
                    . (strlen($tech) > 1 ? '"eb84df499ae5e3807d648e8c550465be10a04b6d":"' . $tech . '", ' : "")
                    . (strlen($det) > 1 ? '"eb1e223ecb711ae3c2731b8b2cd83ea76197695b":"' . $det . '", ' : "")
                    . '"fd6af2c0f516b07fa7c6772ce1cb808aae0a06c5":"New Customer" '
                    . '}',
                ),
            );
            $url = "https://api.pipedrive.com/v1/deals" . ($dealid == "0" ? "" : "/$dealid") . "?api_token=" . Conf::PD_API_TOKEN;
            $context = stream_context_create($options);
            $pd_resp = file_get_contents($url, false, $context);
            if (!isset($pd_resp) || strlen($pd_resp) < 3) {
                throw new Exception('Failed to create online sale order');
            }
            $json = json_decode($pd_resp);
            if ($dealid == "0" AND isset($json) AND isset($json->{"data"})) {
                if (isset($json->{"data"}->{"id"})) {
                    $dealid = $json->{"data"}->{"id"};
                    // Delete Product Attachement
                    if ($deal_prod_id > 0) {
                        $options = array(
                            'http' => array(
                                'header' => "Content-type: application/json\r\n", 'method' => "DELETE",
                                'content' => '{'
                                //. '"id":"' . $dealid . '", '
                                . '"product_attachment_id":"' . $deal_prod_id . '" '
                                . '}',
                            ),
                        );
                        $url = "https://api.pipedrive.com/v1/deals/$dealid/products?api_token=" . Conf::PD_API_TOKEN;
                        $context = stream_context_create($options);
                        file_get_contents($url, false, $context);
                    }
                    // Attach New Product
                    $options = array(
                        'http' => array(
                            'header' => "Content-type: application/json\r\n", 'method' => "POST",
                            'content' => '{'
                            //. '"id":"' . $dealid . '", '
                            . '"product_id":"' . $svcn . '", '
                            . '"item_price":"' . $amt . '", '
                            . '"quantity": 1 '
                            . '}',
                        ),
                    );
                    $url = "https://api.pipedrive.com/v1/deals/$dealid/products?api_token=" . Conf::PD_API_TOKEN;
                    $context = stream_context_create($options);
                    $pd_resp = file_get_contents($url, false, $context);
                    if (!isset($pd_resp) || strlen($pd_resp) < 3) {
                        throw new Exception('Failed to create contact person');
                    } else {
                        $json_add = json_decode($pd_resp);
                        if (isset($json_add) and isset($json_add->{'data'}) and isset($json_add->{'data'}->{'product_attachment_id'})) {
                            $deal_prod_id = $json_add->{'data'}->{'product_attachment_id'};
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $json = json_decode('{'
                    . '"success": "false", '
                    . '"error": "' . $e->getMessage() . '" '
                    . '}');
        }
        return $json;
    }

    public function EditDealStage($dealid, $stageid) {
        return $this->EditDealData($dealid, '{ "stage_id":"' . $stageid . '" }');
    }

    public function EditDealData($dealid, $json_str) {
        try {
            // Post Deal Data
            $options = array(
                'http' => array(
                    'header' => "Content-type: application/json\r\n", 'method' => "PUT",
                    'content' => $json_str,
                ),
            );
            $url = "https://api.pipedrive.com/v1/deals/$dealid?api_token=" . Conf::PD_API_TOKEN;
            $context = stream_context_create($options);
            $json = json_decode(file_get_contents($url, false, $context));
        } catch (Exception $e) {
            $json = json_decode('{'
                    . '"success":false, '
                    . '"error":"' . $e->getMessage() . '", '
                    . '"data": null, '
                    . '}');
        }
        return $json;
    }

    public function ShortenUrl($long_url) {
        return $long_url;

        try {
            // Request for a tiny-url for use in place of the long url
            $context = stream_context_create(
                    array(
                        'http' => array(
                            'header' => 'Content-Type: application/x-www-form-urlencoded\r\n',
                            'method' => 'GET',
                            'content' => 'url = ' . urlencode($long_url)
                        )
                    )
            );
            $xml_url = simplexml_load_string(
                    file_get_contents(
                            'https://go.zol.co.zw/yourls-api.php?signature=6447566928&action=shorturl&url=' . urlencode($long_url), false, $context
                    )
            );
            return $xml_url->shorturl;
        } catch (Exception $e) {
            
        }
        return $long_url;
    }

    public function EmailConfirmation($nam, $eml, $ref, $address, $mob, $svcd, $paystatus, $message, $folder = 'esales', $subject = "Thank you for ordering with ZOL", $template = "template_confirm.html") {
        try {
            // Prepare headers
            $from = "ZOL Sales <sales@teamzol.co.zw>";
            $shortUrl = Conf::MAIL_CONFIRM_LINK;
            $shortUrl = str_replace("esales", "#FOLDER#", $shortUrl); /* for backward compatibility */
            $shortUrl = str_replace("#FOLDER#", $folder, $shortUrl);
            $shortUrl = $shortUrl . $this->HashEncode($ref) . "&action=confirm";
			
			$template = JPATH_ROOT."/components/com_onlinesignup/utils/template_confirm.html";
            return $this->EmailGeneral($from, $nam, $eml, $subject, $ref, $mob, $message, $shortUrl, $paystatus, $address, $svcd, $template);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function EmailGeneral($from, $nam, $eml, $subject, $ref, $mob, $message, $shortUrl, $status = "", $address = "", $desc = "", $template = "template_mail.html") {
        try {
            // Prepare headers
            $to = "$nam <$eml>";
            $shortUrl = $this->ShortenUrl($shortUrl);
			$template = JPATH_ROOT."/components/com_onlinesignup/utils/template_confirm.html";
            // Prepare Body
            if (strlen($template) > 3) {
                $body = file_get_contents($template);
            } else {
                $body = $message;
            }
            $body = str_replace('#MESSAGE#', $message, $body);
            $body = str_replace('#NAME#', $nam, $body);
            $body = str_replace('#EMAIL#', $eml, $body);
            $body = str_replace('#REF#', $ref, $body);
            $body = str_replace('#ADDRESS#', $address, $body);
            $body = str_replace('#MOB#', $mob, $body);
            $body = str_replace('#SVCD#', $desc, $body);
            $body = str_replace('#DESC#', $desc, $body);
            $body = str_replace('#PAYSTATUS#', $status, $body);
            $body = str_replace('#STATUS#', $status, $body);
            $body = str_replace('#LINK#', $shortUrl, $body);

            return $this->EmailContent($from, $to, $subject, $body);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function EmailContent($from, $to, $subject, $body, $attachment = '') {
        $mail = new PHPMailer(true); //defaults to using php "mail()"; the true param means it will throw exceptions on errors, which we need to catch
        try {
            $mail->IsSMTP();
            $mail->SetFrom($from);
            $mail->AddReplyTo($from);
            $mail->AddAddress($to);
            $mail->Subject = $subject;
            $mail->AltBody = ''; // leave it blank so that $mail->MsgHTML will create an alternate automatically
            $mail->MsgHTML($body);
            if (strlen($attachment) > 2) {
                $mail->AddAttachment($attachment);      // attachment
                //$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
            }
            $mail->Send();
            return "";
        } catch (phpmailerException $e) {
            return $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            return $e->getMessage(); //Boring error messages from anything else!
        }
    }

    public function CreateCustomer($msisdn, $fnam, $snam, $eml, $url = Conf::API_ZOL_URL) {
        try {
            $ws = new SoapClient($url);
            $xmlstr = $ws->CreateCustomer(
                            array(
                                'token' => $this->token,
                                'firstname' => $fnam,
                                'surname' => $snam,
                                'msisdn' => $msisdn,
                                'email' => $eml
                            )
                    )->CreateCustomerResult;
            //echo $xmlstr;
            $xml = new SimpleXMLElement(
                    $xmlstr
            );
        } catch (Exception $e) {
            // Build Error Response
            $xml = new SimpleXMLElement(
                    '<root>'
                    . '<CustomerKey>0</CustomerKey>'
                    . '<Status>UNREACHABLE CreateCustomer line ' . $e->getLine() . '</Status>'
                    . '</root>'
            );
        }
        return $xml;
    }

    public function CreateCustomerValidate($name, $msisdn, $email, $url = Conf::API_ZOL_URL) {
        try {
            $ws = new SoapClient($url);
            $arr = array(
                'token' => $this->token,
                'name' => $name,
                'msisdn' => $msisdn,
                'email' => $email
            );
            $custResp = $ws->ValidateCreateCustomer($arr)->ValidateCreateCustomerResult;
        } catch (Exception $e) {
            // Build Error Response
            $custResp = new SimpleXMLElement(
                    '<CustomerResponse>'
                    . '<CustomerKey>0</CustomerKey>'
                    . '<Status>UNREACHABLE CreateCustomerValidate line ' . $e->getLine() . ' ' . $e->getMessage() . '</Status>'
                    . '</CustomerResponse>'
            );
        }
        return $custResp;
    }

    public function MakeServiceOrder($custkey, $description, $transid, $servicename, $servicelogin, $servicepwd, $url = Conf::API_ZOL_URL) {
        try {
            $ws = new SoapClient($url);
            $arr = array(
                'token' => $this->token,
                'custkey' => $custkey,
                'amount' => 0, // So that no monetory transaction is posted to billing system
                'currency' => "USD",
                'description' => $description,
                'transid' => $transid,
                'servicename' => $servicename,
                'servicelogin' => $servicelogin,
                'servicepwd' => $servicepwd
            );
            $xmlstr = $ws->MakeServiceOrder($arr)->MakeServiceOrderResult;
            //echo $xmlstr;
            $xml = new SimpleXMLElement(
                    $xmlstr
            );
        } catch (Exception $e) {
            // Build Error Response
            $xml = new SimpleXMLElement(
                    '<root>'
                    . '<ServiceKey>0</ServiceKey>'
                    . '<Status>UNREACHABLE MakeServiceOrder line ' . $e->getLine() . '</Status>'
                    . '</root>'
            );
        }
        return $xml;
    }

    public function SendSMS($refno, $sender, $msisdn, $message, $gw = 2, $method = "GET") {
        try {
            // Initialise
            $status = "<root><status>OK</status><body>OK</body></root>";
            $msisdn = str_replace(" ", "", str_replace("+", "", str_replace("-", "", str_replace("/", "", trim($msisdn)))));
            if (strlen($msisdn) >= 9 AND substr($msisdn, 0, 2) == "07") {
                $msisdn = "263" . substr($msisdn, strlen($msisdn) - 9);
            }
            // Do value substitution
            $url = ($gw == 3 ? Conf::API_SMS_URL3 : ($gw == 2 ? Conf::API_SMS_URL2 : Conf::API_SMS_URL));
            if (strpos($url, "||") !== false) {
                $arr = explode('||', $url);
                $url = $arr[0];
                $usr = $arr[1];
                $pwd = $arr[2];
                $message = str_replace('#USER#', urlencode($usr), $message);
                $message = str_replace('#PWD#', urlencode($pwd), $message);
            }
            $url = str_replace('#REFNO#', urlencode($refno), $url);
            $url = str_replace('#SENDER#', urlencode($sender), $url);
            $url = str_replace('#MSISDN#', urlencode($msisdn), $url);
            $url = str_replace('#SMS#', urlencode($message), $url);

            // Request for a tiny-url for use in place of the long url
            $context = stream_context_create(
                    array(
                        'http' => array(
                            'header' => 'content-type: application/x-www-form-urlencoded\r\n',
                            'method' => $method,
                            'content' => $message,
                            'content-length' => strlen($message)
                        )
                    )
            );
            $status = file_get_contents($url, false, $context);
            if (strpos($status, "<") === false) {
                $status = strlen($status) <= 0 ? "Failed to get response" : $status;
                $status = "<root><status>$status</status><body>$status</body></root>";
            }
            $xml = new SimpleXMLElement($status);
        } catch (Exception $e) {
            // Build Error Response
            $xml = new SimpleXMLElement(''
                    . '<root>'
                    . ' <status>' . $status . ' line ' . $e->getLine() . ' ' . $e->getMessage() . '</status>'
                    . ' <body>' . $status . ' line ' . $e->getLine() . ' ' . $e->getMessage() . '</body>'
                    . '</root>'
            );
        }
        return $xml;
    }

    public function ResetSelfcare($refno, $custkey, $msisdn, $email, $password, $url = Conf::API_ZOL_URL) {
        try {
            $ws = new SoapClient($url);
            $arr = array(
                'token' => $this->token,
                'refno' => $refno,
                'custkey' => $custkey,
                'msisdn' => $msisdn, // So that no monetory transaction is posted to billing system
                'signupemail' => $email,
                'newpassword' => $password
            );
            $xml = $ws->ResetSelfcare($arr)->ResetSelfcareResult;
        } catch (Exception $e) {
            // Build Error Response
            $xml = new SimpleXMLElement(
                    '<BaseDTO>'
                    . '<Status>UNREACHABLE ResetSelfcare line ' . $e->getLine() . '</Status>'
                    . '</BaseDTO>'
            );
        }
        return $xml;
    }

    /**
     * Create a Random String - Useful for generating passwords or hashes.
     *
     */
    public function GetRandomStr($type = 'alnum', $len = 8, $url = Conf::API_ZOL_URL) {
        $ws = new SoapClient($url);
        $arr = array('token' => $this->token, 'length' => $len, 'type' => $type, 'category' => "%");
        return $ws->GetRandomString($arr)->GetRandomStringResult;
    }

}
