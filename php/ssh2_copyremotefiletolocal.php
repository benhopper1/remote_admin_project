
<?php


class CopyRemoteFileToLocalObject{

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
        
        $resultBool = ssh2_scp_recv($this->connectionObject->getConnection() , $inExecObject['remotePath'] . "/" . $inExecObject['remoteFileName'], $inExecObject['localPath'] . "/" . $inExecObject['localFileName']);
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


