
<?php

require('/home/kerry/backupproject/ajax/ssh2_connectionobject.php');
require('/home/kerry/backupproject/ajax/ssh2_mkdir.php');
require('/home/kerry/backupproject/ajax/ssh2_rmdir.php');
require('/home/kerry/backupproject/ajax/ssh2_createtar.php');
require('/home/kerry/backupproject/ajax/ssh2_copyremotefiletolocal.php');
require('/home/kerry/backupproject/ajax/ssh2_copylocalfiletoremote.php');
require('/home/kerry/backupproject/ajax/ssh2_deleteremotefile.php');
require('/home/kerry/backupproject/ajax/ssh2_createdatestampfile.php');
require('/home/kerry/backupproject/ajax/ssh2_createmysqldumpfile.php');

$asscArray = array();       
$json_data = json_decode(trim(file_get_contents('php://input')), true);

$reports = array();
$report = array();

$cobj = new ConnectionObject($json_data['connection']['domain'], $json_data['connection']['port'], $json_data['connection']['userName'], $json_data['connection']['password']);

$uniErrorFlag = false;
foreach($json_data['commandSeries'] as $commandSeriesItem){
	//?Which commad?--
	if($commandSeriesItem['command'] == 'mkdir'){
		$mkdirObj = new MkDirObject($cobj, $commandSeriesItem['data']);
		$report = $mkdirObj->exec();
		$reports[] = $report;
		if($mkdirObj->getUniErrorFlag()){$uniErrorFlag = true;}		
	}

	if($commandSeriesItem['command'] == 'rmdir'){		
		$rmdirObj = new RmDirObject($cobj, $commandSeriesItem['data']);
		$report = $rmdirObj->exec();
		$reports[] = $report;
		if($rmdirObj->getUniErrorFlag()){$uniErrorFlag = true;}		
	}

	if($commandSeriesItem['command'] == 'createTar'){		
		$tmpObj = new CreateTarObject($cobj, $commandSeriesItem['data']);
		$report = $tmpObj->exec();
		$reports[] = $report;
		if($tmpObj->getUniErrorFlag()){$uniErrorFlag = true;}		
	}

	if($commandSeriesItem['command'] == 'copyRemoteFileToLocal'){		
		$tmpObj = new CopyRemoteFileToLocalObject($cobj, $commandSeriesItem['data']);
		$report = $tmpObj->exec();
		$reports[] = $report;
		if($tmpObj->getUniErrorFlag()){$uniErrorFlag = true;}		
	}

	if($commandSeriesItem['command'] == 'copyLocalFileToRemote'){		
		$tmpObj = new CopyLocalFileToRemoteObject($cobj, $commandSeriesItem['data']);
		$report = $tmpObj->exec();
		$reports[] = $report;
		if($tmpObj->getUniErrorFlag()){$uniErrorFlag = true;}		
	}

	if($commandSeriesItem['command'] == 'deleteRemoteFile'){		
		$tmpObj = new DeleteRemoteFileObject($cobj, $commandSeriesItem['data']);
		$report = $tmpObj->exec();
		$reports[] = $report;
		if($tmpObj->getUniErrorFlag()){$uniErrorFlag = true;}		
	}

	if($commandSeriesItem['command'] == 'createDateStampFile'){		
		$tmpObj = new CreateDateStampFileObject($cobj, $commandSeriesItem['data']);
		$report = $tmpObj->exec();
		$reports[] = $report;
		if($tmpObj->getUniErrorFlag()){$uniErrorFlag = true;}		
	}

	if($commandSeriesItem['command'] == 'createMySqlDumpFile'){		
		$tmpObj = new CreateMySqlDumpFileObject($cobj, $commandSeriesItem['data']);
		$report = $tmpObj->exec();
		$reports[] = $report;
		if($tmpObj->getUniErrorFlag()){$uniErrorFlag = true;}		
	}


}

$asscArray['reports'] = $reports;
echo json_encode($asscArray);
























?>