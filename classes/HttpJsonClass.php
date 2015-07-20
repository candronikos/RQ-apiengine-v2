<?php


    require('./config.inc'); # $client is the connection

    
    class HttpJsonClass {
    
        public $server_request;
        public $scrname;

        private function _trap () {
            if ($error = json_last_error_msg()) {
                throw new \LogicException(sprintf("Failed to parse json string '%s', error: '%s'", HttpJsonClass::data, $error));
            }
        } 
                
        public static function encode ($value) {
            $result = json_encode($value, JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT);
            if($result)  {
               print_r ($result);
            }
            HttpJsonClass::_trap();
        }
            
        public static function decode ($value) {
            $result = json_decode($json, $assoc);
 
            if($result) {
                return $result;
            }
            HttpJsonClass::_trap();
        }      

        public function __construct($server_request, $scrname) {
        
            $method = $server_request['REQUEST_METHOD'];
            $request = explode("/", substr(@$server_request['PATH_INFO'], 1));

            switch ($method) {
            case 'PUT':
                call_user_func ($scrname . '_put', $request);
            break;
            case 'POST':
                call_user_func ($scrname . '_post', $request);
            break;
            case 'GET':
                call_user_func ($scrname . '_get', $request);
            break;
            case 'HEAD':
                call_user_func ($scrname . '_head', $request);
            break;
            case 'DELETE':
                call_user_func ($scrname . '_head', $request);
            break;
            case 'OPTIONS':
                call_user_func ($scrname . '_options', $request);
            break;
            default:
                call_user_func ($scrname . '_error', $request);
            break;
        }
        
    }

}
  
?>

  
