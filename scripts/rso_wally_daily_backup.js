//RemoteScriptObject : RemoteScriptObject


var RemoteScriptObject = function(inEventData){

	var domain = 'walnutcracker.net';
	var port = 22;
	var userName = 'root';
	var password = 'CYJavKxicZ';
	var eventData = inEventData;



	var scriptJson = 
		{
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
					'minute'	:[26]
				},
			'scheduleInstance':'',
			'commandSeries':
				[
					{
						'command':'mkdir',
						'data':
							[
								{
									'path':'/test',
									'mode':'777',
									'isRecursive':true
								},
								{
									'path':'/test/one',
									'mode':'777',
									'isRecursive':true
								}
							],
						'reportData':
							[
								
							]
						
					},
					{
						'command':'mkdir',
						'data':
							[
								{
									'path':'/testA',
									'mode':'777',
									'isRecursive':true
								},
								{
									'path':'/testA/one',
									'mode':'777',
									'isRecursive':true
								}
							],
						'reportData':
							[
								
							]
					},					

				]
		}

		this.getScriptJson = function(){
			return scriptJson;
		}


	


















}
module.exports = RemoteScriptObject;