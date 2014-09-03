<?php
//$remoteBackupDirectories = array();
//$remoteBackupDirectories[] = '/home/wally';   //use: tar -cf backup.tar /root /data /dnchost /drawings

//$whenFailCode = "776";

/*
*@ directory{path:,mode:,isRecursive}
*
*
*/




class MkDirObject{

	private $directoryArray;
	private $connectionObject;
	private $uniErrorFlag = false;

	public function __construct($inConnectionObject, $inArrayOfDirectory){
		$this->connectionObject = $inConnectionObject;
		$this->directoryArray = $inArrayOfDirectory;
	}

	public function exec(){
		$report = array();
		foreach($this->directoryArray as $dirObject){
			$report[] = $this->mkdir($dirObject);
		}
		//$report['uniErrorFlag'] = $this->uniErrorFlag;

		return $report;
	}

	public function getUniErrorFlag(){
		return $this->uniErrorFlag;
	}

	private function mkdir($inDirectory){
		$path = $inDirectory['path'];
		$mode = $inDirectory['mode']; 
		$isRecursive = $inDirectory['isRecursive'];

		$resultBool = ssh2_sftp_mkdir(ssh2_sftp($this->connectionObject->getConnection()) , $path, $mode, $isRecursive);		
		
		$returnArray = array();

		$returnArray['hasError'] = !($resultBool);
		if($resultBool){
			$returnArray['message'] = 'directory created';
			$returnArray['directoryCreated'] = $path;
			$returnArray['errorMessage'] = '';
		}else{
			$this->uniErrorFlag = true;
			$returnArray['message'] = '';
			$returnArray['directoryNotCreated'] = $path;
			$returnArray['errorMessage'] = 'error: directory not created'; 
		}    

		return $returnArray;  	 

	}
}


















































