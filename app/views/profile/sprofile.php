<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Profile Page</title>
		<link href="https://fonts.googleapis.com/css?family=Arimo&display=swap" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../page/css/profile.css">
		<link rel="shortcut icon" href="../page/images/favicon.ico">
	</head>
	<body>
		<div class="grid-wrapper">
			<header>
				<h1>Stol-Universal Storage</h1>
				<p>Informations about your account, linked services and more</p>
			</header>
			
			<article>
				<div class="rows_group">
					<div class="alert" style="display:none">
						  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
						  <p id="alert_text"></p>
					</div>

					<div class="generic_row">
					
						<div id="text_profile"><h2>Profile</h2></div>
						<label id="change_avatar_container" for="image">
							<input type="file" name="image" id="image" style="display:none;"/>
							<img id="avatar" src="images/avatar.png" alt="avatar">
					    </label>
					</div>
					<div class="generic_row">
						<div id="text_name">Username:</div>
						<div id="actual_name"></div>
					</div>
					<div class="generic_row">
						<div id="text_email">Email:</div>
						<div id="actual_email"></div>
					</div>
				</div>

				<div class="rows_group">
					<div class="generic_row">
						<div id="text_edit_profile"><h2>Change account details</h2></div>
					</div>
					<div class="generic_row">
						<div id="text_change_name">Change username:</div>
						<div id="input_edit_name">
							<label for="new-name">New username:</label>
							<input type="text" name="new_name" id="new-name">
						</div>
					</div>
					<div class="generic_row">
						<div id="text_change_password">Change password:</div>
						<div id="input_edit_password">
							<label for="old-password">Old password:</label>
							<input type="password" name="old_password" id="old-password">
							<label for="new-password">New password:</label>
							<input type="password" name="new_password" id="new-password">
						</div>
					</div>
					<div class="generic_row">
						<div id="text_g_drive">Google Drive:</div>
						<button id="button-gdrive" class="button_log_in" type="submit">Authorize</button>
					</div>
					<div class="generic_row">
						<div id="text_one_drive">MS One Drive:</div>
						<button id="button-onedrive" class="button_log_in" type="submit">Authorize</button>
					</div>
					<div class="generic_row">
						<div id="text_dropbox">DropBox:</div>
						<button id="button-dropbox" class="button_log_in" type="submit">Authorize</button>
					</div>
					<div class="generic_row">
						<button id="cancel_changes_button" onclick="location.href='http://localhost/ProiectTW/page/files'">Go Back</button>
						<button id="admin_button" style="display:none" onclick="location.href='http://localhost/ProiectTW/page/admin'">Admin</button>
						<button id="save_changes_button">Save</button>
					</div>
				</div>

			</article>

			<footer>
				Footer
			</footer>
			    <script src='../page/js/profile.js'></script>
		</div>
	</body>
</html>