<?php
/**
 *  Orbited PHP5 Client
 *  
 *  Copyright (c) 2007 Michael Zaic <mzaic@cafemom.com>, CafeMom.com
 *  http://www.cafemom.com
 *  http://www.orbited.org
 * 
 *  Licensed under The MIT License: <http://www.opensource.org/licenses/mit-license.php>
 */

class OrbitedClient {
    
    // CONSTANTS
    const   BUFFER_SIZE = 2048;
    const   LINE_END    = "\r\n";
    const   VERSION     = 'Orbit 1.0';
    
    // PRIVATE VARIABLES
    private $address;
    private $port;
    private $socket;
    private $error_code;
    private $error_string;
    private $id;
    private $connected;
    
    // CONSTRUCTOR
    function __construct($address, $port)
    {
        $this->address      = $address;
        $this->port         = $port;
        $this->socket       = null;
        $this->id           = 0;
        $this->connected    = false;
    }
    
    // PRIVATE FUNCTIONS
    private function sendline( $line = '' )
    {
        $line = $line.self::LINE_END;
        fwrite($this->socket, $line);
        return $line;
    }
    
    private function read_response()
    {
        $contents = '';
        while(1) {
            $packet = fread($this->socket, self::BUFFER_SIZE);
            $contents .= $packet;
            if(substr($packet, -2) == self::LINE_END) { break; }
        }
        return $contents;
    }
    
    // PUBLIC FUNCTIONS
    public function connect()
    {
        if($this->socket) {
            return;
        }
        else {
            $this->socket = fsockopen($this->address, $this->port, $this->error_code, $this->error_string);
            if($this->socket) {
                $this->connected = true;
                return;
            }
            else{
                $this->error();
            }
        }
    }
    
    public function disconnect()
    {
        if($connected) {
            fclose($this->socket);
            $this->socket    = null;
            $this->connected = false;
        }
        
        return;
    }
    
    public function reconnect()
    {
        $this->disconnect();
        $this->connect();
        return;
    }
    
    public function event($recipients, $body, $json = true, $retry = true)
    {
        if(!$this->connected){ $this->connect(); }
        if($json) { $body = json_encode($body); }
        
        try {
            if(!is_array($recipients)) {
                $recipients = array($recipients);
            }
            if(!$this->socket) { 
                throw new Exception('Connection Lost');
            }
            try {
                $this->id = $this->id + 1;
                $this->sendline(self::VERSION);
                $this->sendline('Event');
                $this->sendline('id: '.$this->id);
                foreach($recipients as $recipient){
                    $recipient = (string)$recipient;
                    $this->sendline('recipient: '.$recipient);
                }
                $this->sendline('length: '.strlen($body));
                $this->sendline();
                fwrite($this->socket, $body);
                return $this->read_response();
            } catch (Exception $e) {
                $this->disconnect();
                throw new Exception('Connection Lost');
            }
        } catch (Exception $e) {
            if($retry) {
                $this->reconnect();
                $this->event($recipients, $body, $json, false);
            }
            else {
                throw new Exception('Send Failed');
            }
        }
    }
    
    public function get_error_code()
    {
        return $this->error_code;
    }
    
    public function get_error_string()
    {
        return $this->error_string;
    }
}
?>
