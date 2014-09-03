//RemoteScriptObject : RemoteScriptObject


var RemoteScriptObject = function(inEventData){
	console.log("Loading " + module.filename);
	var _this = this;

	var label = 'something daily backup';
	var domain = 'domain.net';
	var port = 22;
	var userName = 'root';
	var password = 'password';

	var serverPathToBackup = '/home/wally/';
	var backupTarName = 'wally9999.tar.gz';

	var sqlDatabaseName = 'wally_arf';
	var sqlDumpTarName = 'wally_arf_sql8888.tar.gz';

	var eventData = inEventData;

	var onMessageFunction;
	var onFailFunction;	
	var onCloseFunction;
	var onSuccessFunction;
	var onStartFunction;
	var onCompleteFunction;	

	var scriptJson = 
		{	
			'label':label,
			'connection':
				{
					'domain':domain,
					'port':port,
					'userName':userName,
					'password':password
				},
			'rule':
				{
					'dayOfWeek'	:[0,1,2,3,4,5,6],
					'hour'		:[9],
					'minute'	:[27]
				},
			'scheduleInstance':'',




			'commandSeries':
				[
					{
						'command':'mkdir',
						'data':
							[
								{
									'path':'/backup',
									'mode':'755',
									'isRecursive':true									
								},
								{
									'path':'/backup/sql',
									'mode':'755',
									'isRecursive':true										
								}
							],
						'reportData':
							[
								
							]
					},

					{
						'command':'createDateStampFile',
						'data':
							[
								{
									'remoteFilePath':serverPathToBackup + '/datestamp.txt'
								}
							],
						'reportData':
							[
								
							]
					},

					{
						'command':'createTar',
						'data':
							[
								{									
									'switch':'-czf',
									'sourceTargetPath':serverPathToBackup,
									'remoteTargetPath':'/backup',
									'tarName':backupTarName
																		
								}
							],
						'reportData':
							[
								
							]
						
					},

					{
						'command':'copyRemoteFileToLocal',
						'data':
							[
								{
									'remotePath':'/backup',
									'remoteFileName':backupTarName,
									'localPath':'../data',
									'localFileName':backupTarName
								}
							],
						'reportData':
							[
								
							]
					},

					{
						'command':'createMySqlDumpFile',
						'data':
							[
								{
									'databaseName':sqlDatabaseName,
									'dumpDirectory':'/backup/sql',
									'dumpFileName':'wally_arf_dump2.sql'
								}
							],
						'reportData':
							[
								
							]
					},

					{
						'command':'createDateStampFile',
						'data':
							[
								{
									'remoteFilePath':'/backup/sql/datestamp.txt'
								}
							],
						'reportData':
							[
								
							]
					},

					{
						'command':'createTar',
						'data':
							[
								{									
									'switch':'-czf',
									'sourceTargetPath':'/backup/sql',
									'remoteTargetPath':'/backup',
									'tarName':sqlDumpTarName
																		
								}
							],
						'reportData':
							[
								
							]
						
					},

					{
						'command':'copyRemoteFileToLocal',
						'data':
							[
								{
									'remotePath':'/backup',
									'remoteFileName':sqlDumpTarName,
									'localPath':'../data',
									'localFileName':sqlDumpTarName
								}
							],
						'reportData':
							[
								
							]
					},


					{
						'command':'deleteRemoteFile',
						'data':
							[
								{
									'remoteFilePath':'/backup/' + sqlDumpTarName
								},
								{
									'remoteFilePath':'/backup/' + backupTarName
								},
								
								{
									'remoteFilePath':'/backup/sql/datestamp.txt'
								},
								{
									'remoteFilePath':'/backup/sql/wally_arf_dump2.sql'
								}
								
							],
						'reportData':
							[
								
							]
					},

					{
						'command':'rmdir',
						'data':
							[
								{
									'path':'/backup/sql'									
								},
								{
									'path':'/backup'									
								}

							],
						'reportData':
							[
								
							]
						
					}






					

				]
		}

	if(inEventData){
		if(inEventData.onMessage){onMessageFunction = inEventData.onMessage;}
		if(inEventData.onFail){onFailFunction = inEventData.onFail;}
		if(inEventData.onClose){onCloseFunction = inEventData.onClose;}
		if(inEventData.onSuccess){onSuccessFunction = inEventData.onSuccess;}
		if(inEventData.onStart){onStartFunction = inEventData.onStart;}
		if(inEventData.onComplete){onCompleteFunction = inEventData.onComplete;}
	}

	this.setOnMessage = function(inFunction){onMessageFunction = inFunction;}
	this.setOnFail = function(inFunction){onFailFunction = inFunction;}
	this.setOnClose = function(inFunction){onCloseFunction = inFunction;}
	
	this.getFileName = function(){
		return module.filename;
	}

	this.getDomain = function(){
		return scriptJson.connection.domain;
	}
	this.getRule = function(){
		return scriptJson.rule;
	}

	this.getLabel = function(){
		return scriptJson.label;
	}

	this.setScheduleInstance = function(inInstance){
		scriptJson.scheduleInstance = inInstance;
	}

		var request = require('../nodejs_modules/node_modules/request-json');
		var client = request.newClient('http://127.0.0.1/');

		this.getScriptJson = function(){
			return scriptJson;
		}

		this.exec = function(){
			if(onStartFunction){onStartFunction(new Date(),_this.getLabel(), _this);}			
			var tJson = _this.getScriptJson();			
			client.post('phpserviceobject.php', tJson, function(err, res, body){	
				var flatReport = [];
				var hadError = false;
				for(var i = 0; i < body.reports.length; i++){
					for(var k = 0; k < body.reports[i].length; k++){
						if(!(body.reports[i][k].hasError)){
							if(onMessageFunction){onMessageFunction(body.reports[i][k],i,k, _this);}
						}
						if(body.reports[i][k].hasError){
							hadError = true;
							if(onFailFunction){onFailFunction(body.reports[i][k],i,k, _this);}
						}


						flatReport.push(body.reports[i][k]);
					}
				}

				if(!(hadError)){  		
		  			if(onSuccessFunction){onSuccessFunction(flatReport,body,_this);}
	  			}

	  			if(onCompleteFunction){onCompleteFunction(new Date(), _this.getLabel(), hadError, _this);}

		  		return true;
  			});


		}


}
module.exports = RemoteScriptObject;