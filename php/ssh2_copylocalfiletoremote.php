
<?php


class CopyLocalFileToRemoteObject{

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
        
        $resultBool = ssh2_scp_send($this->connectionObject->getConnection() , $inExecObject['localPath'] . "/" . $inExecObject['localFileName'], $inExecObject['remotePath'] . "/" . $inExecObject['remoteFileName']);
        $errorString = "error in copy";
        $outOfStream = "had good copy";
        

        $returnArray = array();

        $returnArray['hasError'] = !($resultBool);
        if($resultBool){
            $returnArray['message'] = $outOfStream;
            $returnArray['tarCreated'] = $inExecObject['localPath'] . "/" . $inExecObject['localFileName'];
            $returnArray['errorMessage'] = ''; 
        }else{
            $this->uniErrorFlag = true;
            $returnArray['message'] = '';
            $returnArray['tarNotCreated'] = $inExecObject['remotePath'] . "/" . $inExecObject['remoteFileName'];
            $returnArray['errorMessage'] = $errorString; 
        }        

        return $returnArray;     
     
    }
}


