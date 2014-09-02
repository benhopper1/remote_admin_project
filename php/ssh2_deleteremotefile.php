<?php


class DeleteRemoteFileObject{

	private $subSeriesArray;
	private $connectionObject;
    private $uniErrorFlag = false;

    public function __construct($inConnectionObject, $inSubSeriesArray){
    	$this->connectionObject = $inConnectionObject;
        $this->subSeriesArray = $inSubSeriesArray;
    }

    public function exec(){
        $report = array();
    	foreach($this->subSeriesArray as $execObject){
    		$report[] = $this->subExecute($execObject);
    	}
        return $report;
    }

    public function getUniErrorFlag(){
        return $this->uniErrorFlag;
    }

    private function subExecute($inExecObject){
        $resultBool = ssh2_sftp_unlink (ssh2_sftp($this->connectionObject->getConnection()), $inExecObject['remoteFilePath']);
        //$echo "XX:" . $inExecObject['remoteFilePath'];
        $errorString = 'file not deleted';
        $outOfStream = 'file deleted';

    	$returnArray = array();

    	$returnArray['hasError'] = !($resultBool);
    	if($resultBool){
    		$returnArray['message'] = $outOfStream;
            $returnArray['tarCreated'] = $path;
    		$returnArray['errorMessage'] = ''; 
		}else{
            $this->uniErrorFlag = true;
			$returnArray['message'] = '';
            $returnArray['tarNotCreated'] = $path;
    		$returnArray['errorMessage'] = $errorString; 
		}        

        $returnArray['xxxx'] = $inExecObject['remoteFilePath'];
        return $returnArray;  	 

    }
}


