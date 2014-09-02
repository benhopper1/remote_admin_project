<?php

class ConnectionObject{

	private $domain;
	private $port;
	private $userName;
	private $password;
	private $connection;
	private $isConnectedx;

	public function __construct($inDomain, $inPort, $inUserName, $inPassword){
        $this->domain = $inDomain;
        $this->port = $inPort;
     	$this->userName = $inUserName;
      	$this->password = $inPassword;

      	$this->connection = ssh2_connect($this->domain , $this->port);
      	$this->isConnected = ssh2_auth_password($this->connection, $this->userName, $this->password);
    }

    public function getConnection(){    	
    	if($this->isConnected){
    		return $this->connection;
    	}else{
    		ssh2_auth_password($this->connection, $this->userName, $this->password);
    		return $this->connection;
    	}
    }
    public function getDomain(){
    	return $this->domain;
    }

    public function isConnected(){
    	return $this->isConnectedx;
    }

    public function getSuccessData(){

    }
}


//$cobj = new ConnectionObject('walnutcracker.net',22,'root','CYJavKxicZ');


//$stream = ssh2_exec($cobj->getConnection(), 'cd /home/wally && ls');
//$errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);

//stream_set_blocking($errorStream, true);
//stream_set_blocking($stream, true);

//echo "Output: " . stream_get_contents($stream);
//echo "Error: " . stream_get_contents($errorStream);

//echo "\n";
//echo $cobj->getDomain();



















