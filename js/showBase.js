function rangeUpdate(value) {
	
	document.getElementById('baseSizeRangeBaner').innerText = value;
}

function getXMLHttpRequest() {
	  let request = false;
		
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
	
function loadBase(type){
	
	if(type == "click") { 
	
		let howMuch = document.getElementById('baseSizeRange').value;
		loadBasePerformer(howMuch);
		
		let request;
		
		request = getXMLHttpRequest();
		request.open('GET', 'phpScripts/setBaseToLoad.php?size='+howMuch+'&from=3', true);
		request.send(null);
		
	} else {
		
	    let reqq; 
		reqq = getXMLHttpRequest(); 	
		

		reqq.open('GET', 'phpScripts/getBaseToLoad.php', true);
		reqq.onreadystatechange = ansver;
		reqq.send(null);
		
		function ansver() {
			
			if (reqq.readyState == 4) {
				
				if (reqq.status == 200) {
	
					loadBasePerformer(reqq.responseText);
				}
			}
		}
	}
}

function loadBasePerformer(howMuch) {

	function baseUpdate()
	{
	  if (req.readyState == 4) {
		if (req.status == 200) {
		  var jsonObj = JSON.parse(req.responseText);
		 
		  let BackColor = "#e88061";
		  for(let i = 1; i <= howMuch; i++) {
			  
			  document.getElementById('baseId'+i).innerText = jsonObj[i-1].id;
			  
			  
			  if(document.getElementById('RedHighlightCheckbox').checked == true) {
			  
				  if(jsonObj[i-1].name == "") {  //dla imienia
					  document.getElementById('baseName'+i).style.backgroundColor = BackColor;
				  } else {
					  document.getElementById('baseName'+i).innerText = jsonObj[i-1].name;
				  }
				  
				  if(jsonObj[i-1].surname == "") { //dla nazwiska
					  document.getElementById('baseSurname'+i).style.backgroundColor = BackColor;
				  } else {
					  document.getElementById('baseSurname'+i).innerText = jsonObj[i-1].surname;
				  }

				  if(jsonObj[i-1].email == "") { //dla emaila
					  document.getElementById('baseEmail'+i).style.backgroundColor = BackColor;
				  } else {
					  document.getElementById('baseEmail'+i).innerText = jsonObj[i-1].email;
				  }

                  if((jsonObj[i-1].phone== "")||(jsonObj[i-1].phone== 0)) { //dla telefonu
					  document.getElementById('basePhone'+i).style.backgroundColor = BackColor;
				  } else {
					  document.getElementById('basePhone'+i).innerText = jsonObj[i-1].phone;
				  }				  

				  if((jsonObj[i-1].old == "")||(jsonObj[i-1].old == 0)) { //dla wieku
					  document.getElementById('baseOld'+i).style.backgroundColor = BackColor;
				  } else {
					  document.getElementById('baseOld'+i).innerText = jsonObj[i-1].old;
				  }	
				  
				  if(jsonObj[i-1].address == "") { //dla adresu
					  document.getElementById('baseAddress'+i).style.backgroundColor = BackColor;
				  } else {
					  document.getElementById('baseAddress'+i).innerText = jsonObj[i-1].address;
				  }	
				  
				  if(jsonObj[i-1].country == "") { //dla kraju
					  document.getElementById('baseCountry'+i).style.backgroundColor = BackColor;
				  } else {
					  document.getElementById('baseCountry'+i).innerText = jsonObj[i-1].country;
				  }	
			  
			  } else {
				  
				  //czyszczenie tła
				  document.getElementById('baseName'+i).style.backgroundColor = "transparent";
				  document.getElementById('baseSurname'+i).style.backgroundColor = "transparent";
				  document.getElementById('baseEmail'+i).style.backgroundColor = "transparent";
				  document.getElementById('basePhone'+i).style.backgroundColor = "transparent";
				  document.getElementById('baseOld'+i).style.backgroundColor = "transparent";
				  document.getElementById('baseAddress'+i).style.backgroundColor = "transparent";
				  document.getElementById('baseCountry'+i).style.backgroundColor = "transparent";
				  
				  document.getElementById('baseName'+i).innerText = jsonObj[i-1].name;
				  document.getElementById('baseSurname'+i).innerText = jsonObj[i-1].surname;
			   	  document.getElementById('baseEmail'+i).innerText = jsonObj[i-1].email;
				  document.getElementById('basePhone'+i).innerText = jsonObj[i-1].phone;
				  document.getElementById('baseOld'+i).innerText = jsonObj[i-1].old;
				  document.getElementById('baseAddress'+i).innerText = jsonObj[i-1].address;
				  document.getElementById('baseCountry'+i).innerText = jsonObj[i-1].country;
			  }
		  }
		};
	  };
	}
	
	var req; 
	req = getXMLHttpRequest(); 	
	

	req.open('GET', 'phpScripts/getBase.php?size='+howMuch, true);
	req.onreadystatechange = baseUpdate;
	req.send(null);
}