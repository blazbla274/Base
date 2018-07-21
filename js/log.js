function getXMLHttpRequest() {
	  var request = false;
		
	  try {
		request = new XMLHttpRequest();
	  } catch(err1) {
		try {
		  request = new ActiveXObject('Msxml2.XMLHTTP');
		} catch(err2) {
		  try {
			request = new ActiveXObject('Microsoft.XMLHTTP');                
		  } catch(err3) {
			request = false;
		  }
		}
	  }
	  return request;
}   

function processResponse(){
	if (r.readyState == 4) {
		if (r.status == 200) {
			document.getElementById('logStatement'+numOfLogIndex).innerHTML = r.responseText;
			let err = r.responseText;
			if ((err[42] == 'e')&&(err[43] == 'r')&&(err[44] == 'r')&&(err[45] == 'o')&&(err[46] == 'r')&&(err[47] == '.'))
			return false;
			numOfLogIndex++;
			if(numOfLogIndex > 10) {numOfLogIndex = 1;}
			setTimeout("log()", 300);
		};
    };
}
	
var r;
var numOfLogIndex = 1;
r = getXMLHttpRequest(); 
	
function log(){ //odpowiada za wypełnianie treścią
	
    r.open('GET', 'phpScripts/log.php?id='+numOfLogIndex, true);
	r.onreadystatechange = processResponse;
	r.send(null);
}


var logToggleHeightState = 2;
async function logToggle(height, margin) {
	
	clearTimeout(timer1);
	height -= margin;
	
	if(logToggleHeightState == 1) {
		for(let i = height; i >= margin; i-=4){
			
			document.getElementById('logBox').style.height = i+"px";
			await sleep(1);
			
		}
		logToggleHeightState = 2;
	} else {
		for(let i = margin; i <= (height + margin); i+=4){
			
			document.getElementById('logBox').style.height = i+"px";
			await sleep(1);
		}
		logToggleHeightState = 1;
	}
}

async function logClose(height, margin){
	
	
	for(let i = height; i >= margin; i-=1){
	
		document.getElementById('logBox').style.height = i+"px";
		await sleep(10);
	}		
	
}

var timer1 = 0;
async function logSlide(){
	
	let height = 75; //Poziom początkowego wysunięcia logu
	let margin = 35;
	
	await sleep(300);
	for(let i = margin; i <= height; i+=1){
	
		document.getElementById('logBox').style.height = i+"px";
		await sleep(10);
	}	
	
	timer1 = setTimeout("logClose("+height+","+margin+")", 3000);
}


function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}