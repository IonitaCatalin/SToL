const saveButton=document.getElementById('save_changes_button');
const backButton=document.getElementById('cancel_changes_button');

function toggleAlert(message=null)
{
    const alert=document.querySelector('.alert');
    const alertText=document.getElementById('alert_text');
    
    if(alert.style.display!=='block')
    {
        alert.style.display='block';
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
                username.appendChild(document.createTextNode(response.data.username));
                email.appendChild(document.createTextNode(response.data.email));
                if(response.data.onedrive==true)
                {
                    let onedrive=document.getElementById("button-onedrive");
                    onedrive.style.backgroundColor="red";
                    onedrive.textContent='Unauthorize\u2716';
                }
                if(response.data.googledrive==true)
                {
                    let gdrive=document.getElementById("button-gdrive");
                    gdrive.style.backgroundColor="red";
                    gdrive.textContent='Unauthorize\u2716';
                }
                if(response.data.dropbox==true)
                {
                    let dropbox=document.getElementById("button-dropbox");
                    dropbox.style.backgroundColor='red';
                    dropbox.textContent='Unauthorize\u2716';
                }
            }
            else
            {
                toggleAlert(response.message);
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
                if(response.status=='error')
                {
                    toggleAlert(response.message);
                }
                else if(response.status=='succes')
                {
                    toggleAlert('Profile data have been updated succesfully!');
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

