<?php

/** This file is part of KCFinder project
  *
  *      @desc This file is included first, before each other
  *   @package KCFinder
  *   @version 3.12
  *    @author Pavel Tzonkov <sunhater@sunhater.com>
  * @copyright 2010-2014 KCFinder Project
  *   @license http://opensource.org/licenses/GPL-3.0 GPLv3
  *   @license http://opensource.org/licenses/LGPL-3.0 LGPLv3
  *      @link http://kcfinder.sunhater.com
  *
  * This file is the place you can put any code (at the end of the file),
  * which will be executed before any other. Suitable for:
  *     1. Set PHP ini settings using ini_set()
  *     2. Custom session save handler with session_set_save_handler()
  *     3. Any custom integration code. If you use any global variables
  *        here, they can be accessed in conf/config.php via $GLOBALS
  *        array. It's recommended to use constants instead.
  */


// PHP VERSION CHECK
if (!preg_match('/^(\d+\.\d+)/', PHP_VERSION, $ver) || ($ver[1] < 5.3))
    die("You are using PHP " . PHP_VERSION . " when KCFinder require at least version 5.3.0! Some systems has an option to change the active PHP version. Please refer to your hosting provider or upgrade your PHP distribution.");


// SAFE MODE CHECK
if (ini_get("safe_mode"))
    die("The \"safe_mode\" PHP ini setting is turned on! You cannot run KCFinder in safe mode.");


// CMS INTEGRATION
if (isset($_GET['cms']) &&
    (basename($_GET['cms']) == $_GET['cms']) &&
    is_file("integration/{$_GET['cms']}.php")
)
    require "integration/{$_GET['cms']}.php";


// REGISTER AUTOLOAD FUNCTION
require "core/autoload.php";


// json_encode() IMPLEMENTATION IF JSON EXTENSION IS MISSING
if (!function_exists("json_encode")) {

    function json_encode($data) {

        if (is_array($data)) {
            $ret = array();

            // OBJECT
            if (array_keys($data) !== range(0, count($data) - 1)) {
                foreach ($data as $key => $val)
                    $ret[] = json_encode((string) $key) . ':' . json_encode($val);
                return "{" . implode(",", $ret) . "}";

            // ARRAY
            } else {
                foreach ($data as $val)
                    $ret[] = json_encode($val);
                return "[" . implode(",", $ret) . "]";
            }

        // BOOLEAN OR NULL
        } elseif (is_bool($data) || ($data === null))
            return ($data === null)
                ? "null"
                : ($data ? "true" : "false");

        // FLOAT
        elseif (is_float($data))
            return rtrim(rtrim(number_format($data, 14, ".", ""), "0"), ".");

        // INTEGER
        elseif (is_int($data))
            return $data;

        // STRING
        return '"' .
            str_replace('/', "\\/",
            str_replace("\t", "\\t",
            str_replace("\r", "\\r",
            str_replace("\n", "\\n",
            str_replace('"', "\\\"",
            str_replace("\\", "\\\\",
        $data)))))) . '"';
    }
}


class SessionSaveHandler {
    protected $savePath;
    //protected $sessionName;
    protected $cookieName;

    public function __construct() {
        $this->sessionName = "{sessionName}";
		$this->cookieName = "{cookieName}";
		$this->savePath = "{savePath}";

        session_set_save_handler(
            array($this, "open"),
            array($this, "close"),
            array($this, "read"),
            array($this, "write"),
            array($this, "destroy"),
            array($this, "gc")
        );
    }

    // Open function, this works like a constructor in classes and is
    // executed when the session is being opened. The open function expects
    // two parameters, where the first is the save path and the second is the
    // session name.
    public function open($savePath, $sessionName) {
        return true;
    }

    // Close function, this works like a destructor in classes and is
    // executed when the session operation is done.
    public function close() {
        return true;
    }

    // Read function must return string value always to make save handler
    // work as expected. Return empty string if there is no data to read.
    // Return values from other handlers are converted to boolean expression.
    // TRUE for success, FALSE for failure.
    public function read($id) {
        $id = $_COOKIE[$this->cookieName];
        $file = $this->savePath . "/sess_$id";
        //$file = "D:\\xampp\\tmp\\sess_qi95bbprcc4ml8igq4ghscuiu1";
        //echo $file;
        return (string) @file_get_contents($file);
    }

    // Write function that is called when session data is to be saved. This
    // function expects two parameters: an identifier and the data associated
    // with it.
    public function write($id, $data) {
        return true;
    }

    // The destroy handler, this is executed when a session is destroyed with
    // session_destroy() and takes the session id as its only parameter.
    public function destroy($id) {
        return true;
    }

    // The garbage collector, this is executed when the session garbage
    // collector is executed and takes the max session lifetime as its only
    // parameter.
    public function gc($maxlifetime) {
        return true;
    }
}

new SessionSaveHandler();




// PUT YOUR ADDITIONAL CODE HERE

