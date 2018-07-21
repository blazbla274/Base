function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}


var customGroupToogleStatement = 0;

async function customGroupToogle(height) {
    
    if (customGroupToogleStatement == 0) {
        
        for(let i = 0; i <= height; i+= 2) {
            document.getElementById('sendingMailPerference').style.height = i+"px";
            await sleep(1);
        }
        customGroupToogleStatement = 1;
    } else {
        
        for(let i = height; i >= 0; i-= 2) {
            document.getElementById('sendingMailPerference').style.height = i+"px";
            await sleep(1);
        }
        customGroupToogleStatement = 0;
    }

}

function sendingOptionGuardian(what, id) {
    
    function guardianChanger(whatG, idG) {
        
        let checkedColor = "red";
        if(idG == '1') {
        
            document.getElementById(whatG+'2').checked = false;          
            document.getElementById(whatG+'3').checked = false;           
            document.getElementById(whatG+'1').checked = true;
        } else if (id == '2') {

            document.getElementById(whatG+'1').checked = false;
            document.getElementById(whatG+'3').checked = false;    
            document.getElementById(whatG+'2').checked = true;
        } else {
                                
            document.getElementById(whatG+'1').checked = false;
            document.getElementById(whatG+'2').checked = false;            
            document.getElementById(whatG+'3').checked = true;
        }
        
    }
    
    switch (what) {
    
        case 'newsletter': if(id == 'newsletterOff') {
        
                                document.getElementById('newsletterOn').checked = false;
                                document.getElementById('newsletterOff').checked = true;
                            } else {

                                document.getElementById('newsletterOn').checked = true;
                                document.getElementById('newsletterOff').checked = false;
                            }
            break;
        default: guardianChanger(what, id); 
    }
}