var optionToggelSemafor = 1;
var optionToggleStan = 1;
async function optionToggle(width){
	
	if(optionToggelSemafor == 1) {
	
		optionToggelSemafor = 0;
		
		if(optionToggleStan == 1) {
			
			for (let i = 0; i <= width; i+=4) {
				
				document.getElementById('option').style.width = i+"px";

				await sleep(1);
			}
			optionToggleStan = 2;
		} else {
			
			for (let i = width; i >=0; i-=4) {
				
				document.getElementById('option').style.width = i+"px";

				await sleep(1);
			}
			optionToggleStan = 1;
		}
	    optionToggelSemafor = 1;
	}
}

function optionChanger(what) {
	
	let els;
	if(what == "optioInfo") {
		
		els = "optionStatus";
	}
	else {
		
		els = "optioInfo";
	}
	
	document.getElementById(els).style.display = "none";
	document.getElementById(what).style.display = "block";
	document.getElementById(els+"Border").style.cursor = "pointer";
	document.getElementById(what+"Border").style.cursor = "default";
	
	document.getElementById(els+"Border").style.borderBottom = "none";
	document.getElementById(what+"Border").style.borderBottom = "4px solid gray";
	
	
}

function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}
