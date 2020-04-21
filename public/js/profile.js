const saveButton=document.getElementById('save_changes_button');
const backButton=document.getElementById('cancel_changes_button');

function toggleAlert(message=null,error=true)
{
    const alert=document.querySelector('.alert');
    const alertText=document.getElementById('alert_text');
    
    if(alert.style.display!=='block')
    {
        
        alert.style.display='block';
        if(error)
        {
            alert.style.backgroundColor='#f44336';
        }
        else
        {
            alert.style.backgroundColor='#33cc33';
        }
        alertText.innerHTML=message;
    }
    else 
    {
        alert.style.display='none';
        alertText.innerHTML='';
    }
}

function fetchUserData()
{
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
        if (xhr.readyState === 4 && xhr.status==200){
            const response=JSON.parse(xhr.responseText);
            if(response.status=='success')
            {
                let username=document.getElementById('actual_name');
                let email=document.getElementById('actual_email');
                username.innerHTML='';
                email.innerHTML='';
                username.appendChild(document.createTextNode(response.data.username));
                email.appendChild(document.createTextNode(response.data.email));
                if(response.data.onedrive==true)
                {
                    let onedrive=document.getElementById("button-onedrive");
                    onedrive.style.backgroundColor="red";
                    onedrive.textContent='Unauthorize\u2716';
                    //onedrive.addEventListener('click',deauthenticateService('onedrive'));
                }
                if(response.data.googledrive==true)
                {
                    let gdrive=document.getElementById("button-gdrive");
                    gdrive.style.backgroundColor="red";
                    gdrive.textContent='Unauthorize\u2716';
                    //gdrive.addEventListener('click',deauthenticateService('googledrive'));
                }
                if(response.data.dropbox==true)
                {
                    let dropbox=document.getElementById("button-dropbox");
                    dropbox.style.backgroundColor='red';
                    dropbox.textContent='Unauthorize\u2716';
                   // dropbox.addEventListener('click',deauthenticateService('dropbox'));
                }
            }
            else
            {
                toggleAlert(response.message,true);
            }
        }
    };
    xhr.open('GET', 'http://localhost/ProiectTW/public/cprofile/user');
    xhr.send();
}

function updateUserData()
{
    const username=document.getElementById('new-name').value;
    const oldPassword=document.getElementById('old-password').value;
    const newPassword=document.getElementById('new-password').value;

    if(newPassword!='' && oldPassword=='')
    {
        toggleAlert('In order to change the password the old password is needed!');
    }
    else if(oldPassword!='' && newPassword=='')
    {
        toggleAlert('Please introduce your desired new password in the field above!');
    }
    else
    {
        if(username!='' || oldPassword !='' || newPassword!='')
        {
        let xhr = new XMLHttpRequest();
        
        const params = {
            username:username,
            oldpass: oldPassword,
            newpass: newPassword
        }
        xhr.onreadystatechange = function(){
            if (xhr.readyState === 4 && xhr.status==200){
                const response=JSON.parse(xhr.responseText);
                console.log(response);
                if(response.status=='error')
                {
                    toggleAlert(response.message,true);
                }
                else if(response.status=='success')
                {
                    fetchUserData();
                    toggleAlert(response.message,false);

                }
            }
        };
        xhr.open('DELETE', 'http://localhost/ProiectTW/public/cprofile/user');
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(JSON.stringify(params));
    }
    }
   
}

function deauthenticateService(service)
{
    let xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function(){
            if (xhr.readyState === 4 && xhr.status==200){
                const response=JSON.parse(xhr.responseText);
                console.log(response);
                if(response.status=='error')
                {
                    toggleAlert(response.message,true);
                }
                else if(response.status=='success')
                {
                    fetchUserData();
                    toggleAlert(response.message,false);

                }
            }
        };
        xhr.open('DELETE', 'http://localhost/ProiectTW/public/cprofile/deauth?service='+service);
        xhr.send();
}

document.addEventListener("DOMContentLoaded",fetchUserData);
saveButton.addEventListener('click',updateUserData);

