
function contentChanger(witch){
	
	document.getElementById('contentBox1').style.display = "none";
	document.getElementById('contentBox2').style.display = "none";
	document.getElementById('contentBox3').style.display = "none";
	document.getElementById('contentBox4').style.display = "none";
	document.getElementById('contentBox0').style.display = "none";
	
	document.getElementById('contentBox'+witch).style.display = "block";
}