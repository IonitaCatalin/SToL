<?php
require_once 'core/Router.php';
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Db.php';
require_once 'core/JsonResponse.php';
require_once 'controllers/clogin.php';
require_once 'controllers/cregister.php';
require_once 'controllers/csession.php';
require_once 'controllers/cprofile.php';
require_once 'controllers/cpage.php';

require_once 'core/Onedrive/Onedrive.php';
require_once 'core/Onedrive/OnedriveException.php';
require_once 'core/GDrive/Googledrive.php';
require_once 'core/GDrive/GoogledriveException.php';
require_once 'core/Dropbox/Dropbox.php';
require_once 'core/Dropbox/DropboxException.php';
require_once 'core/JsonResponse.php';
require_once 'core/Exceptions/CredentialsExceptions.php';

?>