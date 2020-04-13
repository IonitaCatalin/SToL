<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Profile Page</title>
		<link href="https://fonts.googleapis.com/css?family=Arimo&display=swap" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../public/css/profile.css">
	</head>
	<body>
		<div class="grid-wrapper">
			<header>
				<h1>Stol-Universal Storage</h1>
				<p>Informations about your account, linked services and more</p>
			</header>

			<article>
				<div class="rows_group">
					<div class="generic_row">
						<div id="text_profile"><h2>Profile</h2></div>
						<label id="change_avatar_container" for="image">
							<input type="file" name="image" id="image" style="display:none;"/>
							<img id="avatar" src="images/avatar.png" alt="avatar">
					    </label>
					</div>
					<div class="generic_row">
						<div id="text_name">Name:</div>
						<div id="actual_name">Username</div>
					</div>
					<div class="generic_row">
						<div id="text_email">Email:</div>
						<div id="actual_email">example@qq.com</div>
					</div>
				</div>

				<div class="rows_group">
					<div class="generic_row">
						<div id="text_edit_profile"><h2>Change account details</h2></div>
					</div>
					<div class="generic_row">
						<div id="text_change_name">Change name:</div>
						<div id="input_edit_name">
							<label for="new-name">New name:</label>
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
						<button class="button_log_in" type="submit">Authorize</button>
					</div>
					<div class="generic_row">
						<div id="text_one_drive">MS One Drive:</div>
						<button class="button_log_in" onclick="window.location.href = 'http://localhost/ProiectTW/public/cprofile/onedriveAuth';" type="submit">Authorize</button>
					</div>
					<div class="generic_row">
						<div id="text_dropbox">DropBox:</div>
						<button class="button_log_in"  type="submit">Authorize</button>
					</div>
					<div class="generic_row">
						<button id="cancel_changes_button">Go Back</button>
						<button id="save_changes_button">Save</button>
					</div>
				</div>

			</article>

			<footer>
				Footer
			</footer>
		</div>
	</body>
</html>