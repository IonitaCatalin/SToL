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
                let onedrive=document.getElementById("button-onedrive");
                let gdrive=document.getElementById("button-gdrive");
                let username=document.getElementById('actual_name');
                let email=document.getElementById('actual_email');
                username.innerHTML='';
                email.innerHTML='';
                username.appendChild(document.createTextNode(response.data.username));
                email.appendChild(document.createTextNode(response.data.email));
                let dropbox=document.getElementById("button-dropbox");
                if(response.data.onedrive==true)
                {
                    
                    onedrive.style.backgroundColor="red";
                    onedrive.textContent='Unauthorize\u2716';
                    onedrive.onclick=function(){
                        location.href = 'http://localhost/ProiectTW/public/cprofile/deauth/?service=onedrive';
                    };
                }
                else
                {
                    onedrive.onclick=function(){
                        location.href='http://localhost/ProiectTW/public/cprofile/onedriveAuth';
                    }
                }
                if(response.data.googledrive==true)
                {
                    
                    gdrive.style.backgroundColor="red";
                    gdrive.textContent='Unauthorize\u2716';
                    //gdrive.addEventListener('click',deauthenticateService('googledrive'));
                    gdrive.onclick=function(){
                        location.href = 'http://localhost/ProiectTW/public/cprofile/deauth/?service=googledrive';
                    };
                }
                else
                {

                    gdrive.onclick=function(){
                        onclick='http://localhost/ProiectTW/public/cprofile/googledriveAuth';
                    }
                }
                if(response.data.dropbox==true)
                {
                   
                    dropbox.style.backgroundColor='red';
                    dropbox.textContent='Unauthorize\u2716';
                    dropbox.onclick=function(){
                        location.href = 'http://localhost/ProiectTW/public/cprofile/deauth/?service=dropbox';
                    };
                }
                else
                {
                    dropbox.onclick=function(){
                        onclick='http://localhost/ProiectTW/public/cprofile/dropboxAuth';
                    }
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
        xhr.open('PUT', 'http://localhost/ProiectTW/public/cprofile/user');
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(JSON.stringify(params));
    }
    }
   
}


document.addEventListener("DOMContentLoaded",fetchUserData);
saveButton.addEventListener('click',updateUserData);

