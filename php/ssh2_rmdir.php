<?php


class RmDirObject{

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
    		$report[] = $this->rmdir($dirObject);
    	}
        return $report;
    }

    public function getUniErrorFlag(){
        return $this->uniErrorFlag;
    }

    private function rmdir($inDirectory){
    	$path = $inDirectory['path'];
    	//$mode = $inDirectory['mode']; 
    	//$isRecursive = $inDirectory['isRecursive'];

    	$resultBool = ssh2_sftp_rmdir(ssh2_sftp($this->connectionObject->getConnection()) , $path);		
        
    	$returnArray = array();

    	$returnArray['hasError'] = !($resultBool);
    	if($resultBool){
    		$returnArray['message'] = 'directory removed';
            $returnArray['directoryRemoved'] = $path;
    		$returnArray['errorMessage'] = ''; 
		}else{
            $this->uniErrorFlag = true;
			$returnArray['message'] = '';
            $returnArray['directoryNotRemoved'] = $path;
    		$returnArray['errorMessage'] = 'error: directory not removed'; 
		}    

        return $returnArray;  	 

    }
}