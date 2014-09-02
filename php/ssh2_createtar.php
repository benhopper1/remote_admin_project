<?php


class CreateTarObject{

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
        $execString = 'tar ' . $inExecObject['switch'] . ' ' . $inExecObject['remoteTargetPath'] . '/' . $inExecObject['tarName'] . ' -P ' . $inExecObject['sourceTargetPath'];

        $stream = ssh2_exec($this->connectionObject->getConnection(), $execString);//$execString);
    
        $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);        
        
        // Enable blocking for both streams
        stream_set_blocking($errorStream, true);
        stream_set_blocking($stream, true);

        $errorString = stream_get_contents($errorStream);
        $outOfStream = stream_get_contents($stream);
        $resultBool = true;
        if(strlen(trim($errorString)) > 0){
            $resultBool = false;
        }

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

        return $returnArray;  	 

    }
}


