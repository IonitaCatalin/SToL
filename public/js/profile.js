document.addEventListener("DOMContentLoaded", function(){
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function(){
        if (xhr.readyState === 4 && xhr.status==200){
            const response=JSON.parse(xhr.responseText);
            console.log(response);
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
                    dropbox.style.backgroundColor="red";
                    dropbox.textContent='Unauthorize\u2716';
                }
            }
            else
            {

                let alert=document.querySelector('.alert');
                let paragraph=document.createElement('P');
                let errorMsg=document.createTextNode(data.message);
                paragraph.appendChild(errorMsg);
                alert.appendChild(paragraph);
                alert.style.display='block';
                
            }
        }
    };
    
    xhr.open('GET', 'http://localhost/ProiectTW/public/cprofile/user');
    xhr.send();

    });