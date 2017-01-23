<html>
	<head>
		<link href="https://fonts.googleapis.com/css?family=Kaushan+Script|Indie+Flower" rel="stylesheet">
		<style>
			* {
				font-family: 'Indie Flower', cursive;
				color: #FFF;
				font-size: 20px;
			}
			input {
				background: transparent;
				color: #FFF;
				border: 1px solid #FFF;
				padding: 6px 12px;
				border-radius: 4px;
			}
			input[type="submit"] {
				background: rgba(256, 256, 256, 0.2);
				padding: 6px 24px;
			}
			input[type="submit"]:hover {
				background: rgba(256, 256, 256, 0.1);
			}
			body {
				padding: 0px;
				margin: 0px;
				background-color: #184890;
				background: linear-gradient(145deg, #4878A8 0%, #184890 40%, #142A69 85%);
			}
			span.error {
				border-radius: 4px;
				background: #FCC;
				color: #142A69;
				font-weight: bold;
				font-size: 80%;
				display: inline-block;
				padding: 2px 24px;
				margin-bottom: 10px;
				border: 1px solid #FF4444;
			}
			.logo-bar {
				display: inline-block;
				background-color: #333;
				width: 33%;
				border-bottom: 4px solid #666;
				outline: 1px solid #FFF;
			}
			.logo-bar img {
				height: 80px;
				padding: 10px;
			}
			.work {
				margin-top: -50px;
				width: 90%;
				border: 1px solid #FFF;
				padding: 100px 20px 60px 20px;
				background: url('img/bg.png');
				background-color: rgba(0, 0, 0, 0.2);
			}
			.work .by .left, .work .by .right {
				bottom: 0px;
				display: inline-block;
				padding: 0 20px;
			}
			.work .by .left {
				float: left;
				left: 0px;
			}
			.work .by .right {
				float: right;
				right: 0px;
			}
			.work .title {
				margin-bottom: 30px;
				font-family: 'Kaushan Script', cursive;
				font-size: 42px;
			}
		</style>
	</head>
	<body>
		<center>
			<div class="logo-bar">
				<img src="img/YV-LOGO.png">
			</div>
			<div class="work">
				
				<div class="title">
					Face Detect
				</div>

				<?php
					if(@$_GET['error'] == 'empty') {
						?>
						<span class="error">Error : Please select image...!</span>
						<?php
					} elseif (@$_GET['error'] == 'no') {
						?>
						<span class="error">Error : Uploaded but can't process..!</span>
						<?php
					} elseif (@$_GET['error'] == 'fail') {
						?>
						<span class="error">Error : Upload fail..!</span>
						<?php
					} elseif (@$_GET['error'] == 'detect') {
						?>
						<span class="error">Error : Sorry can't detect face..!</span>
						<?php
					}
				?>
				<form action="upload.php" method="post" enctype="multipart/form-data">
					<input type="file" name="image" id="select">
					<input type="submit" name="upload" value="Upload">
				</form>
				<h3><tt>- OR -</tt></h3>
				<?php
					if (@$_GET['error'] == 'wrong') {
						?>
						<span class="error">Error : You have entered Wrong Image URL..!</span>
						<?php
					}
				?>
				<form action="data.php" method="get">
					<label for="url">Insert URL : </label>
					<input type="url" name="link" id="url">
					<input type="submit" value="Submit URL">
				</form>
				<div class="by">
					<div class="left">
						Lovepreet Singh<br />
						YV-16-10285
					</div>
					<div class="right">
						Ashish Kaktan<br />
						YV-16-10252
					</div>
				</div>
			</div>
		</center>
	</body>
</html>