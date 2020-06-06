<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Administration Page</title>
		<link href="https://fonts.googleapis.com/css?family=Arimo&display=swap" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="../page/css/admin.css">
		<link rel="shortcut icon" href="../page/images/favicon.ico">
	</head>
	<body>
		<div class="grid-wrapper">
			<header>
				<h1>Stol - Administration Page</h1>
				<p>Informations about users, allowed services and more</p>
			</header>
			
			<article>
				<div class="rows_group">

					<div class="alert" style="display:none">
						  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
						  <p id="alert_text"></p>
					</div>

					<div class="generic_row">
						<div><h2>Users</h2></div>
					</div>

					<div class="table_row">
						<table>
							<thead>
								<tr>
									<th><input type="checkbox" id="select-all" /></th>
									<th>Username</th>
									<th>Email</th>
									<th>No. of files</th>
									<th>Onedrive</th>
									<th>GDrive</th>
									<th>Dropbox</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input type="checkbox" value="name0" /></td>
									<td>name0</td>
									<td>name0@example.com</td>
									<td>42</td>
									<td>Yes</td>
									<td>No</td>
									<td>Yes</td>
								</tr>
								<tr>
									<td><input type="checkbox" value="name1" /></td>
									<td>name1</td>
									<td>name1@example.com</td>
									<td>1</td>
									<td>No</td>
									<td>No</td>
									<td>No</td>
								</tr>
								<tr>
									<td><input type="checkbox" value="name2" /></td>
									<td>name2</td>
									<td>name2@example.com</td>
									<td>100</td>
									<td>Yes</td>
									<td>Yes</td>
									<td>Yes</td>
								</tr>
								<tr><td><input type="checkbox" value="name3" /></td><td>name3</td><td>name3@example.com</td><td>100</td><td>Yes</td><td>Yes</td><td>Yes</td></tr>
								<tr><td><input type="checkbox" value="name4" /></td><td>name4</td><td>name4@example.com</td><td>100</td><td>Yes</td><td>Yes</td><td>Yes</td></tr>
								<tr><td><input type="checkbox" value="name5" /></td><td>name5</td><td>name5@example.com</td><td>100</td><td>Yes</td><td>Yes</td><td>Yes</td></tr>
								<tr><td><input type="checkbox" value="name6" /></td><td>name6</td><td>name6@example.com</td><td>100</td><td>Yes</td><td>Yes</td><td>Yes</td></tr>
								<tr><td><input type="checkbox" value="name7" /></td><td>name7</td><td>name7@example.com</td><td>100</td><td>Yes</td><td>Yes</td><td>Yes</td></tr>
								<tr><td><input type="checkbox" value="name8" /></td><td>name8</td><td>name8@example.com</td><td>100</td><td>Yes</td><td>Yes</td><td>Yes</td></tr>
								<tr><td><input type="checkbox" value="name9" /></td><td>name9</td><td>name9@example.com</td><td>100</td><td>Yes</td><td>Yes</td><td>Yes</td></tr>
								<tr><td><input type="checkbox" value="name10" /></td><td>name10</td><td>name10@example.com</td><td>100</td><td>Yes</td><td>Yes</td><td>Yes</td></tr>
							</tbody>
						</table>
					</div>

					<div class="generic_row">
						<button id="export-csv-btn" class="button_style" type="submit">Export CSV</button>
					</div>
				</div>

				<div id="services_container" class="rows_group">
					<div class="generic_row">
						<div><h2>Services Configuration</h2></div>
					</div>
					<div class="generic_row">
						<div>Google Drive:</div>
						<button id="button-gdrive" class="button_style" type="submit">Enable</button>
					</div>
					<div class="generic_row">
						<div>MS One Drive:</div>
						<button id="button-onedrive" class="button_style" type="submit">Enable</button>
					</div>
					<div class="generic_row">
						<div>DropBox:</div>
						<button id="button-dropbox" class="button_style" type="submit">Enable</button>
					</div>
					<div id="save_back_btns" class="generic_row">
						<button id="admin_back_button" class="button_style" onclick="location.href='http://localhost/ProiectTW/page/files'">Go Back</button>
						<button id="admin_save_button" class="button_style">Save</button>
					</div>
				</div>

			</article>

			<footer>
				Footer
			</footer>
				<script src='../page/js/admin.js'></script>
		</div>
	</body>
</html>