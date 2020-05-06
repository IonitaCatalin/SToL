const saveButton = document.getElementById('save_changes_button');
const backButton = document.getElementById('cancel_changes_button');

function toggleAlert(message = null, error = true, disable = false)
{
    const alert = document.querySelector('.alert');
    const alertText = document.getElementById('alert_text');

    if(disable == true) {
        alert.style.display = 'none';
        alertText.innerHTML = '';
    } else {
        alert.style.display = 'block';
        if(error) {
            alert.style.backgroundColor = '#f44336';
        } else {
            alert.style.backgroundColor = '#33cc33';
        }
        alertText.innerHTML = message;
    }

}

function getCookieValue(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2)
        return parts.pop().split(";").shift();
}

function authorizeService(serviceName) {

    let xhr = new XMLHttpRequest();

    switch(serviceName) {
        case 'googledrive': 
            xhr.open('GET', 'http://localhost/ProiectTW/api/user/authorize/googledrive');
            break;
        case 'onedrive':
            xhr.open('GET', 'http://localhost/ProiectTW/api/user/authorize/onedrive');
            break;
        case 'dropbox':
            xhr.open('GET', 'http://localhost/ProiectTW/api/user/authorize/dropbox');
            break;
    }

    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
    xhr.send();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4)
        {
            const response = JSON.parse(xhr.responseText);
            console.log(response);

            if(response.status=='success' && xhr.status==200) {
                const redirect_url = response.data;
                location.href = redirect_url;
            }
            else {
                toggleAlert(response.message, true);
            }

        }
    }

}

function deauthorizeService(serviceName) {

    let xhr = new XMLHttpRequest();

    switch(serviceName) {
        case 'googledrive': 
            xhr.open('DELETE', 'http://localhost/ProiectTW/api/user/deauthorize/googledrive');
            break;
        case 'onedrive':
            xhr.open('DELETE', 'http://localhost/ProiectTW/api/user/deauthorize/onedrive');
            break;
        case 'dropbox':
            xhr.open('DELETE', 'http://localhost/ProiectTW/api/user/deauthorize/dropbox');
            break;
    }

    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
    xhr.send();

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4)
        {
            console.log(xhr.responseText);
            const response = JSON.parse(xhr.responseText);
            console.log(response);

            if(response.status=='success' && xhr.status==200) {
                fetchUserData();
                toggleAlert(response.message, false);
            }
            else {
                toggleAlert(response.message, true);
            }

        }
    }
}

function fetchUserData()
{
    let xhr = new XMLHttpRequest();
    xhr.open('GET', 'http://localhost/ProiectTW/api/user');
    xhr.setRequestHeader('Content-type', 'application/json');
    xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
    xhr.send();

    xhr.onreadystatechange = function()
    {
        if (xhr.readyState === 4)
        {
            const response = JSON.parse(xhr.responseText);
            const user_data = JSON.parse(response.data);

            console.log(response);

            if(response.status=='success' && xhr.status==200)
            {
                let onedrive = document.getElementById("button-onedrive");
                let gdrive = document.getElementById("button-gdrive");
                let dropbox=document.getElementById("button-dropbox");
                let username = document.getElementById('actual_name');
                let email = document.getElementById('actual_email');

                username.innerHTML = '';
                email.innerHTML = '';
                username.appendChild(document.createTextNode(user_data.username));
                email.appendChild(document.createTextNode(user_data.email));

                if(user_data.onedrive == true)
                {
                    onedrive.style.backgroundColor = "red";
                    onedrive.textContent = 'Unauthorize\u2716';
                    onedrive.onclick = function(){ deauthorizeService('onedrive'); }
                }
                else
                {
                    onedrive.style.backgroundColor="#0066ff";
                    onedrive.textContent='Authorize';
                    onedrive.onclick = function() { authorizeService('onedrive'); }
                }

                if(user_data.googledrive==true)
                {
                    gdrive.style.backgroundColor="red";
                    gdrive.textContent='Unauthorize\u2716';
                    gdrive.onclick = function(){ deauthorizeService('googledrive'); }
                }
                else
                {
                    gdrive.style.backgroundColor="#0066ff";
                    gdrive.textContent='Authorize';
                    gdrive.onclick = function() { authorizeService('googledrive'); }
                }
                if(user_data.dropbox==true)
                {
                   
                    dropbox.style.backgroundColor='red';
                    dropbox.textContent='Unauthorize\u2716';
                    dropbox.onclick = function() { deauthorizeService('dropbox'); }
                }
                else
                {
                    dropbox.style.backgroundColor="#0066ff";
                    dropbox.textContent='Authorize';
                    dropbox.onclick = function() { authorizeService('dropbox'); }
                }
            }
            else
            {
               if(xhr.status==500)
               {
                   toggleAlert(response.message, true);
               }
            }
        }
    };

}

function updateUserData()
{
    const username = document.getElementById('new-name').value;
    const oldPassword = document.getElementById('old-password').value;
    const newPassword = document.getElementById('new-password').value;


    if( username == '' && newPassword == '' && oldPassword == '') {
        toggleAlert('Nothing to change :)', false);
    }
    else if( newPassword != '' && oldPassword == '')
    {
        toggleAlert('In order to change the password the old password is needed!');
    }
    else if( oldPassword!='' && newPassword=='')
    {
        toggleAlert('Please introduce your desired new password in the field above!');
    }
    else
    {
        if(username!='' || oldPassword !='' || newPassword!='')
        {
            let xhr = new XMLHttpRequest();

            const params = {
                username: username,
                oldpassword: oldPassword,
                newpassword: newPassword
            }

            xhr.open('PATCH', 'http://localhost/ProiectTW/api/user');
            xhr.setRequestHeader('Content-type', 'application/json');
            xhr.setRequestHeader('Authorization', 'Bearer ' + getCookieValue('jwt_token'));
            //xhr.setRequestHeader('Accept','application/json');
            xhr.send(JSON.stringify(params));

            xhr.onreadystatechange = function()
            {
                if (xhr.readyState === 4)
                {
                    console.log(xhr.responseText);
                    const response = JSON.parse(xhr.responseText);
                    if(xhr.status==200 && response.status=='success')
                    {
                        fetchUserData();
                        document.getElementById('new-name').value = '';
                        document.getElementById('old-password').value = '';
                        document.getElementById('new-password').value = '';
                        toggleAlert(response.message,false);
                    }
                    if(xhr.status==409)
                    {
                        toggleAlert(response.message,true);
                    }
                    if(xhr.status==422)
                    {
                        toggleAlert(response.message,true);
                    }
                    if(xhr.status==401)
                    {
                        toggleAlert(response.message,true);
                    }
                    
                }
            };
            
        }
    }
   
}


document.addEventListener("DOMContentLoaded", fetchUserData);
saveButton.addEventListener('click', updateUserData);

