<?php
namespace elipa\sockets;
class Sock{
    
    function __construct($url, $persistent = false)
    {
        if(parse_url($url)){
        $this->original_url =  $url;
        $this->url = (parse_url($url, PHP_URL_QUERY))? parse_url($url): parse_url($url);
        $this->port = $this->port_check();
        }else{
            throw new \InvalidArgumentException("Invalid URL given " .$url);
        }
        $this->fp = ($persistent)? fsockopen($this->url['host'], $this->port, $errno, $errstr, 30):pfsockopen($this->url['host'], $this->port, $errno, $errstr, 30);//pfsockopen() is for persistent connections 
        $this->sslmode();
        if(!$this->fp){
            throw new \Exception("Error Processing Socket Request".$errno." - ".$errstr, 1);         
        }
    }
    private function build_params($data)
    {
        return (is_array($data))?http_build_query($data):'';
    }
    /*
    *   @function Sock::puter writes to the socket rather puts to it, similar to the writer function below it
    **/
    function puter($string)
    { 
        return (empty($string))?'': fputs($this->fp, $string);
    }
    /**
    * @function Sock::writer writes to the socket it can be called on many times and is used internally by this class
    */
    function writer($string)
    { 
        return (empty($string))?'': fwrite($this->fp, $string);
    }
    private function port_check()
    {
        if(isset($this->url['port'])){
            $port = $this->url['port'];
        }elseif(!isset($this->url['port'])){
            $port =($this->url['scheme']=== 'https')?443:80;
        }
        return $port;

    }
    private function sslmode()
    {
        return ($this->url['scheme'] === 'https')?stream_socket_enable_crypto($this->fp, true, STREAM_CRYPTO_METHOD_SSLv23_CLIENT):false;
    }
    /**
    *      @function Sock::method, takes in method e.g. GET, [content_type,  content] these two especially needed for post
    *      but are optional therefore not needed most of the time, keepalive also for the keepalive header and protocol in case 
    *      you are accessing another protocol default is HTTP/1.1 
    */
    function method($method, $content="", $content_type="www-form", $keepalive = true, $protocol = 'HTTP/1.1')
    {
        //Get the path if any default to / if not 
        $this->path = (isset($this->url['path']))?$this->url['path']:'/';
        //Get the query params if any useful for GET
        $query = (isset($this->url['query']))? "?".$this->url['query']:'';
        //set encoding for now just gzip
        $this->encoding = (substr_compare($protocol, 'HTTP', 0, 4) === 0)? "Accept-Encoding: gzip, deflate, br\r\n" :'';
        //connection header with keep-alive and close as options
        $this->keepalive = ($keepalive)?"Connection: keep-alive\r\n":"Connection: Close\r\n";
        //content for post maybe get?
        $this->content = $this->build_params($content);
        // exit(var_dump($this->content));
        switch ($method) {
            case 'POST':
                    return ($this->post($this->content, $content_type) === false)? false: true;
                break;
            case 'GET':
                    return ($this->get($query, $content_type) === false)? false: true;
                break;
            default:
                throw new \Exception($method." Not a Supported Method ", 1);
                break;
        }
    }
    /*
    *       @function Sock::get() creates a socket for a get request with all headers
    */
    function get($content, $content_type)
    {
            $writer = "GET ".$this->path.$content." HTTP/1.1\r\n";
            $writer .= "Host: ".$this->url['host']." \r\n";
            $writer .= $this->encoding;
            // $writer .= $this->content_type($content_type);
            $writer .= $this->keepalive;//this sure helped
            $writer .= "\r\n";
  
            return $this->writer($writer);
    }
    /*
    *      @function Sock::post() creates a socket for a post request with all headers
    */
    function post($content, $content_type)
    {
            $writer = "POST ".$this->url['path']." HTTP/1.1\r\n";
            $writer .= "Host: ".$this->url['host']." \r\n";
            $writer .= $this->encoding;
            $writer .= $this->content_type($content_type);
            $writer .= "Content-Length: ".strlen($content)."\r\n";
            $writer .= $this->keepalive;//this sure helpd
            $writer .= "\r\n";
            $writer .= $content; //the POST content here
            return $this->writer($writer);
    }      
    // An array of all available content types used in this class
    function content_type($content_type)
    {
        $ct = array('json'=>"Content-Type: application/json\r\n",
                    'formdata'=>"Content-Type: multipart/form-data\r\n",
                    'www-form'=>"Content-Type: application/x-www-form-urlencoded\r\n",
                    'xml'=>"Content-Type: application/xml\r\n",
                    'html'=>"Content-Type: text/html; charset=utf-8\r\n",
                    'text'=>"Content-Type: text/plain; charset=utf-8\r\n");
        return $ct[$content_type];
    }
    /*
    * @function Sock::read() to read response from socket otherwise remove it for a trigger and leave. 
    *   the Sock class will wait for Sock::read() to finish if it is called
    */
    function read()
    {
        $read ='';
          while (!feof($this->fp)) {
                $read .=  fgets($this->fp);
            }
        return $read;
    }
    /**
    * @function Sock::read_data()
    */
    function read_data()
    {
        //Split data into an array by the \r\n lines
        $read = preg_split("/[\r\n]+/", $this->read());
        //Then extract the Content-type header
        $header = array_filter($read, function($value){
            return (substr_compare($value,'Content-Type',0,11)=== 0)? $value :false;
        });
        header(end($header));//set Content-type header
        echo end($read);//echo to the buffer
    }
    /*
    * @function Sock::close() Closes a socket connection call it always to close socket
    */
    function close()
    {
        return fclose($this->fp);
    }
}?>