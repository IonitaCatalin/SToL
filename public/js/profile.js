const saveButton=document.querySelector('#save_changes_button');
const backButton=document.querySelector('#cancel_changes_button');

function toggleAlert(message=null)
{
    let alert=document.querySelector('.alert');
    let paragraph=document.createElement('P');
    let errorMsg=document.createTextNode(message);
    paragraph.appendChild(errorMsg);
    alert.appendChild(paragraph);
    if(alert.style.display=='none')
        alert.style.display='block';
    else alert.style.display='none';
}

function fetchUserData()
{
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
        if (xhr.readyState === 4 && xhr.status==200){
            const response=JSON.parse(xhr.responseText);
            if(response.status=='success')
            {
                let username=document.querySelector('#actual_name');
                let email=document.querySelector('#actual_email');
                username.appendChild(document.createTextNode(response.data.username));
                email.appendChild(document.createTextNode(response.data.email));
                if(response.data.onedrive==true)
                {
                    let onedrive=document.querySelector("#button-onedrive");
                    onedrive.style.backgroundColor="red";
                    onedrive.textContent='Unauthorize\u2716';
                }
                if(response.data.googledrive==true)
                {
                    let gdrive=document.querySelector("#button-gdrive");
                    gdrive.style.backgroundColor="red";
                    gdrive.textContent='Unauthorize\u2716';
                }
                if(response.data.dropbox==true)
                {
                    let dropbox=document.querySelector("#button-dropbox");
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
    
    let username=document.getElementById('new-name');
    let oldPassword=document.querySelector('old-password');
    let newPassword=document.querySelector('new-password');

}
document.addEventListener("DOMContentLoaded",fetchUserData);
saveButton.addEventListener('click',updateUserData);
