var beforeId = -1;

function idSet(id) {
    
	if(beforeId > 0) {
		
		//czyszczenie t³a
		document.getElementById('baseId'+beforeId).style.backgroundColor = "transparent";
		document.getElementById('baseName'+beforeId).style.backgroundColor = "transparent";
		document.getElementById('baseSurname'+beforeId).style.backgroundColor = "transparent";
		document.getElementById('baseEmail'+beforeId).style.backgroundColor = "transparent";
		document.getElementById('baseOld'+beforeId).style.backgroundColor = "transparent";
		document.getElementById('baseAddress'+beforeId).style.backgroundColor = "transparent";
		document.getElementById('baseCountry'+beforeId).style.backgroundColor = "transparent";
	}
	beforeId = id;
	document.getElementById('idIndexToChange').value = id;
	
	let color = "#6581af";
	document.getElementById('baseId'+beforeId).style.backgroundColor = "#60779e";
	document.getElementById('baseName'+id).style.backgroundColor = color;
	document.getElementById('baseSurname'+id).style.backgroundColor = color;
	document.getElementById('baseEmail'+id).style.backgroundColor = color;
	document.getElementById('baseOld'+id).style.backgroundColor = color;
	document.getElementById('baseAddress'+id).style.backgroundColor = color;
	document.getElementById('baseCountry'+id).style.backgroundColor = color;
}