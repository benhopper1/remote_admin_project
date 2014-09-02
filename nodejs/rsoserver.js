var schedule = require('/nodejs_modules/node_modules/node-schedule');
var mySql = require('/nodejs_modules/node_modules/mysql');
var exec = require('child_process').exec;
var uuid = require('/nodejs_modules/node_modules/node-uuid');
var DomainObjects = require('/home/kerry/backupproject/nodejs/domainobjects');


var LogModel = function(){
	var mySqlConnection = mySql.createConnection({
	  host     : '127.0.0.1',
	  user     : 'script_user',
	  password : 'hist0l0gy',
	  database : 'backupdatabase'
	});
	mySqlConnection.connect();

	this.addLogEntry = function(inMessage, inGroupKey, inClass, inWhen, inDomain, inExtra, inCode){		
		inMessage = mySqlConnection.escape(inMessage);
		mySqlConnection.query("INSERT INTO tb_backupLog(message, groupKey, class, whenEntry, domain, extra, code) VALUES(" + inMessage + ", '" + inGroupKey + "', '" + inClass + "', '" + inWhen + "', '" + inDomain + "', '" + inExtra + "', '" + inCode + "' )");
	}

	this.getArrayOfIgnoreErrors = function(){

	}


};

var logModel = new LogModel();

var IgnoreErrorsObject = function(){
	var _this = this;

	var ignoreErrors = {};

	this.add = function(inErrorMessage){
		ignoreErrors[inErrorMessage.trim()] = true;	
	}

	this.match = function(inMessage){
		if(inMessage.trim() in ignoreErrors){
			return true;
		}else{
			return false;
		}	
	}

	var mySqlConnection = mySql.createConnection({
	  	host     : '127.0.0.1',
	  	user     : 'script_user',
	  	password : 'hist0l0gy',
	  	database : 'backupdatabase'
	});
	mySqlConnection.connect();
	mySqlConnection.query("SELECT message from tb_ignoreErrors", 
		function(err, rows, fields) { 	 		
 	 		for(rowIndex in rows){
 	 			console.log("IGNORE ERROR ADD:"+rows[rowIndex].message);
 	 			_this.add(rows[rowIndex].message);
 	 		}  			
		}
	);

	mySqlConnection.end();
}

ignoreErrorsObject = new IgnoreErrorsObject();





// --- BUILD -------------------------------------
var backupBaseDirectory = '/home/kerry/backupproject/';

/*
*@param:input scriptFile:,onMessage:,onFail:,onClose:, onSuccess:
*
*
*/
var JsonScriptObject = function(inJsonData){
	var _this = this;
	var request = require('/nodejs_modules/node_modules/request-json');	
	var client = request.newClient('http://127.0.0.1/');
	var RSO = require(inJsonData.scriptFile);
	var rso = new RSO(inJsonData);	
	
	this.exec = function(){
		rso.exec();
	}

	if(rso.getRule()){
		console.log('Sceduled :' + rso.getScriptJson().label);
		var scheduleInstance = schedule.scheduleJob(rso.getRule(), function(){
			_this.exec();
		});
		rso.setScheduleInstance(scheduleInstance)
	}
	

}






//--------- EMAIL OBJECT ----------------->

var EmailObject = function(inDestinationEmail){
	var _this = this;
	var nodemailer = require('/nodejs_modules/node_modules/nodemailer');
	var destinationEmail = inDestinationEmail;
	var transporter = nodemailer.createTransport(
		{
	    	service: 'gmail',
	    	auth: 
		    	{
			        user: 'arfcomm@gmail.com',
			        pass: 'hist0l0gy'
			    }
		}
	);


	this.sendMail = function(inSubject, inMessage){
		transporter.sendMail(
			{
			    from: 'arfcomm@gmail.com',
			    to: destinationEmail,
			    subject: inSubject,
			    text: inMessage
			}
		);
	}

	this.sendFormatedMessage = function(inMessage, inGroupKey, inClass, inWhen, inDomain , inXtra, inCode){

		var errorMessage = 
			'' + '\n' +
				'time: ' + inWhen + '\n' +
				'groupKey:' + inGroupKey + '\n' +
				'domain:' + inDomain + '\n' +
				'xtra:' + inXtra + '\n' +
				'code:' + inCode + '\n' +
				'class:' + inClass + '\n' +
				'message:' + inMessage +
			'';


		_this.sendMail(inClass, errorMessage);
	}	
}



var emailObject = new EmailObject('error@walnutcracker.net');
var hoppDevEmailObject = new EmailObject('hopperdevelopment@gmail.com');

emailObject.sendMail('serverBoot', "nodeJs has just booted!!!!");
hoppDevEmailObject.sendMail('serverBoot', "nodeJs has just booted!!!!");

var wallyBackup = new JsonScriptObject(
	{
		'scriptFile':'/home/kerry/backupproject/ajax/rso_test_run_wallybackup.js',

		'onStart': function(inDate, inLabel, inRef){
			console.log(inLabel + 'Started at :' + inDate);
			hoppDevEmailObject.sendFormatedMessage('Startup for rso file ' + inRef.getFileName() , inLabel, 'Startup', inDate, inRef.getDomain() , 'xtra', 'code');
		},

		'onMessage': function(inReport, indexA, indexB, inRef){
			console.log('[' + indexA +'.' + indexB + ']' + inRef.getScriptJson().label + " Report:"+JSON.stringify(inReport.message));
		},

		'onFail': function(inReport, indexA, indexB, inRef){
			console.log("FAIL--Report:"+JSON.stringify(inReport));
		},

		'onClose': function(){/*not implemented yet!! */},

		'onSuccess': function(inFlatReport, inBody, inRef){
			console.log("All went well !!!!]");
			hoppDevEmailObject.sendFormatedMessage('reportData for rso file ' + inRef.getFileName()+"  " + JSON.stringify(inFlatReport) , inRef.getFileName(), 'Startup', 'na', inRef.getDomain() , 'xtra', 'code');
		
		},		

		'onComplete': function(inDate, inLabel, hadError, inRef){
			console.log(inLabel + 'Completed at :' + inDate + ' hadErrors=' + hadError);
			hoppDevEmailObject.sendFormatedMessage('Completion for rso file ' + inRef.getFileName() , inLabel, 'Complete', inDate, inRef.getDomain() , 'xtra', 'code');
		
		},




	}
);











