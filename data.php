<?php

$SITE = "http://facedetect.hol.es/";

$API_K = "2761f7166bf949109a13c7f2ab1e4c2a";
$API_S = "3ea3f4a1b11a4f09926afa5fb3d64465";

$img = '';

if (@$_GET['link'] != '') {
	$img = $_GET['link'];
}


if (@$_GET['link'] != '' || @$_GET['img'] != '') {
	if ($_GET['link'] == '') {
		$img = $SITE . 'uploads/' . $_GET['img'];
	}

	if (@getimagesize($img)) {
		# code...
	} else {
		header("location: index.php?error=wrong");
	}

	$url = "http://api.skybiometry.com/fc/faces/detect.xml?api_key=".$API_K."&api_secret=".$API_S."&urls=".$img;

	@$get = file_get_contents($url);
	$arr = simplexml_load_string($get);

	$tag = $arr->photos->photo->tags->tag;
	
//Eyes
	$le_x = $tag->eye_left->x;
	$le_y = $tag->eye_left->y;
	$re_x = $tag->eye_right->x;
	$re_y = $tag->eye_right->y;
	$no_x = $tag->nose->x;
	$no_y = $tag->nose->y;
	$mc_x= $tag->mouth_center->x;
	$mc_y= $tag->mouth_center->y;
//Uploaded Image
	$oni = imagecreatefromjpeg($img);
	$s_x = imagesx($oni);
	$s_y = imagesy($oni);
//Frame
	$i2 = imagecreatefrompng($SITE.'frame/f2.png');
	$i2_x = imagesx($i2);
	$i2_y = imagesy($i2);
	$i2_r = $i2_x / $i2_y;
//Moust
	$m1 = imagecreatefrompng($SITE.'moust/m2.png');
	$m1_x = imagesx($m1);
	$m1_y = imagesy($m1);
	$m1_r = $m1_x / $m1_y;
//Head
	$h1 = imagecreatefrompng($SITE.'head/h1.png');
	$h1_x = imagesx($h1);
	$h1_y = imagesy($h1);
	$h1_r = $h1_x / $h1_y;
//Eye Center
	$ce_x=($le_x+$re_x)/2;
	$ce_y=($le_y+$re_y)/2;

//Face Angle
	$slope= ($le_y-$re_y)/($le_x-$re_x);
	$deg = rad2deg($slope);
//Eye Distance
	$dist=sqrt(pow($le_y-$re_y,2)+pow($le_x-$re_x,2));
//mouth-nose Distance
	$dist_mn=sqrt(pow($mc_y-$no_y,2)+pow($mc_x-$no_x,2));
	$dist_mn/=2;
//Calc Frame Size
	$i2_w = $s_x * (2.2*$dist / 100);
	$i2_h = $i2_w / $i2_r;
//Calc Moust Size
	$m1_w = $s_x * (1.65*$dist/100);
	$m1_h = $m1_w / $m1_r;
//Calc Head Size
	$h1_w = $i2_w*1.4;
	$h1_h = $h1_w / $h1_r;
//Ear
	$ler_x=(($s_x * ($re_x - $dist/1.75)/100));
	$ler_y=$no_y;

	$rer_x=$ler_x+$i2_w;
	$rer_y=$no_y;
//Distance b/w CE & MC
	$h_x;
	$h_y;
	$slope_cn = 0;
	$dist_cn = sqrt(pow($mc_y-$ce_y,2)+pow($mc_x-$ce_x,2));
	if ($mc_x-$ce_x == 0) {
		$h_y = $ce_y - $dist_cn;
		$h_x = $ce_x;
	} else {
		$slope_cn = ($mc_y-$ce_y)/($mc_x-$ce_x);
	}
	$deg_cn = rad2deg($slope_cn);
	
//Head Co-od
	if ($slope_cn > 0) {
		$h_x = $ce_x - $dist_cn*(1/(sqrt(1+$slope_cn*$slope_cn)));
		$h_y = $ce_y - $dist_cn*($slope_cn/(sqrt(1+$slope_cn*$slope_cn)));
	} elseif ($slope_cn < 0) {
		$h_x = $ce_x + $dist_cn*(1/(sqrt(1+$slope_cn*$slope_cn)));
		$h_y = $ce_y + $dist_cn*($slope_cn/(sqrt(1+$slope_cn*$slope_cn)));
	}
//Head Co-od
	$be_x;
	$be_y;
	if ($slope_cn > 0) {
		$be_x = $mc_x + $dist_cn*(1/(sqrt(1+$slope_cn*$slope_cn))) * 0.25;
		$be_y = $mc_y + $dist_cn*($slope_cn/(sqrt(1+$slope_cn*$slope_cn))) * 0.25;
	} elseif ($slope_cn < 0) {
		$be_x = $mc_x - $dist_cn*(1/(sqrt(1+$slope_cn*$slope_cn))) * 0.25;
		$be_y = $mc_y - $dist_cn*($slope_cn/(sqrt(1+$slope_cn*$slope_cn))) * 0.25;
	}
	?>

<html>
	<head>
		<link href="https://fonts.googleapis.com/css?family=Kaushan+Script|Indie+Flower" rel="stylesheet">
		<style>
			* {
				font-family: 'Indie Flower', cursive;
				color: #FFF;
				font-size: 20px;
			}
			body {
				padding: 0px;
				margin: 0px;
				background-color: #184890;
				background: linear-gradient(180deg #184890 33%, #142A69 66%, #184890 99%);
			}
			h1, h2, h3, h4, h5, h6 {
				margin: 0px;
			}
			.title {
				padding: 4px 16px;
			}
			.logo-bar {
				display: inline-block;
				position: fixed;
				background-color: #333;
				border-bottom: 4px solid #666;
				display: inline-block;
				bottom: 0px;
				right: 0px;
			}
			.logo-bar .t {
				font-size: 75%;
			}
			.logo-bar .t .mt {
				padding: 0 10px;
				background: #666;
			}
			.logo-bar img {
				height: 80px;
				padding: 10px;
			}
			div.co {
				position: absolute;
				border-radius: 100%;
				height: 5px;
				width: 5px;
				background: #F00;
			}
			div.menu {
				position: absolute;
				top: <?php echo $s_y + 20; ?>px;
			}
			div.opt {
				border: 1px solid #FFF;
				width: 800px;
				margin: 0 auto;
				background: url('img/bg.png');
				background-color: rgba(0, 0, 0, 0.2);
			}
			div.scroll {
				overflow: scroll;
				overflow-y: hidden;
				white-space: nowrap;
			}
			div.opt div.opt-f, div.opt div.opt-m, div.opt div.opt-h {
				margin: 12px 6px;
				border: 1px solid #FFF;
			}
			div.opt div.opt-i {
				margin: 8px;
				border: 1px solid #FFF;
				display: inline-block;
				padding: 4px 12px;
			}
			div.le {
				left: <?php echo($s_x * ($le_x / 100)); ?>px;
				top: <?php echo($s_y * ($le_y / 100)); ?>px;
			}
			div.re {
				left: <?php echo($s_x * ($re_x / 100)); ?>px;
				top: <?php echo($s_y * ($re_y / 100)); ?>px;
			}
			div.ce {
				left: <?php echo($s_x * ($ce_x / 100)); ?>px;
				top: <?php echo($s_y * ($ce_y / 100)); ?>px;
			}
			div.no {
				left: <?php echo($s_x * ($no_x / 100)); ?>px;
				top: <?php echo($s_y * ($no_y / 100)); ?>px;
			}
			div.mc {
				left: <?php echo($s_x * ($mc_x / 100)); ?>px;
				top: <?php echo($s_y * ($mc_y / 100)); ?>px;
			}
			div.he {
				left: <?php echo($s_x * ($h_x / 100)); ?>px;
				top: <?php echo($s_y * ($h_y / 100)); ?>px;
			}
			div.ler {
				left: <?php echo($ler_x); ?>px;
				top: <?php echo($s_y * ($ler_y / 100)); ?>px;
			}
			div.rer {
				left: <?php echo($rer_x); ?>px;
				top: <?php echo($s_y * ($rer_y / 100)); ?>px;
			}
			div.bear {
				left: <?php echo($s_x * ($be_x / 100)); ?>px;
				top: <?php echo($s_y * ($be_y / 100)); ?>px;
			}
			img.ab {
				position: absolute;
			}
			img.frame {
				top: <?php echo($s_y * ($re_y / 100) - $i2_h/2.8); ?>px;
				left: <?php echo(($s_x * ($re_x - $dist/1.75)/100)); ?>px;
				width: <?php echo($i2_w); ?>px;
				-webkit-transform: rotate(<?php echo $deg; ?>deg);
				-ms-transform: rotate(<?php echo $deg; ?>deg);
				-o-transform: rotate(<?php echo $deg; ?>deg);
				transform: rotate(<?php echo $deg; ?>deg);
			}
			img.moust {
				top: <?php echo $s_y*(($mc_y-$dist_mn)/100); ?>px;
				left: <?php echo $s_x*(($mc_x)/100)-$m1_w/2; ?>px;
				width: <?php echo($m1_w); ?>px;
				height: <?php  echo $s_y*($dist_mn)/100;  ?>px;
				-webkit-transform: rotate(<?php echo $deg; ?>deg);
				-ms-transform: rotate(<?php echo $deg; ?>deg);
				-o-transform: rotate(<?php echo $deg; ?>deg);
				transform: rotate(<?php echo $deg; ?>deg);
			}
			img.head {
				top: <?php echo $s_y*($h_y/100) - $h1_h; ?>px;
				left: <?php echo $s_x*($h_x/100) - ($i2_w*1.3)/2; ?>px;
				width: <?php echo($h1_w); ?>px;
				-webkit-transform: rotate(<?php echo $deg; ?>deg);
				-ms-transform: rotate(<?php echo $deg; ?>deg);
				-o-transform: rotate(<?php echo $deg; ?>deg);
				transform: rotate(<?php echo $deg; ?>deg);
			}
			img.beard {
				top: <?php echo $s_y*($be_y/100); ?>px;
				left: <?php echo $s_x*($be_x - ($dist/2.2))/100; ?>px;
				width: <?php echo $s_x*($dist / 100); ?>px;
				-webkit-transform: rotate(<?php echo $deg; ?>deg);
				-ms-transform: rotate(<?php echo $deg; ?>deg);
				-o-transform: rotate(<?php echo $deg; ?>deg);
				transform: rotate(<?php echo $deg; ?>deg);
			}
		</style>
	</head>
	<body>
		<img src="<?php echo $img; ?>" class="ab">
		<div class="points" id="cood">
			<div class="co le"></div>
			<div class="co re"></div>
			<div class="co ce"></div>
			<div class="co no"></div>
			<div class="co mc"></div>
			<div class="co he"></div>
<!--			<div class="co ler"></div>
			<div class="co rer"></div>
			<div class="co bear"></div> -->
		</div>

		<img src="frame/f2.png" id="glass" class="ab frame" />
		<img src="moust/m2.png" id="moust" class="ab moust" />
		<img src="head/h1.png" id="head" class="ab head" />
<!--		<img src="beard/b1.png" id="beard" class="ab beard" /> -->
<!--		<?php echo $dist_mn.' '.$mc_y.' Angle-CN:'.$deg_cn.' Slope-CN:'.$slope_cn.' Distance-CN:'.$dist_cn; ?> -->
		<div class="menu">
			<label for="points"><input onclick="p()" type="checkbox" id="points"> Hide Detect Points</label>
			<div class="opt">
				<div class="opt-h">
					<h3 class="title">Head Gears</h3>
					<div class="scroll">
						<?php
							for ($i=1; $i <= 6; $i++) { 
							?>
								<div class="opt-i" onclick="hgear('h<?php echo $i; ?>')">
									<img src="thumbs/h<?php echo $i; ?>.png" height="40px" />
								</div>
							<?php
							}
						?>
					</div>
				</div>
				<div class="opt-f">
					<h3 class="title">Glasses</h3>
					<div class="scroll">
						<?php
							for ($i=1; $i <= 5; $i++) { 
							?>
								<div class="opt-i" onclick="frame('f<?php echo $i; ?>')">
									<img src="thumbs/f<?php echo $i; ?>.png" height="40px" />
								</div>
							<?php
							}
						?>
					</div>
				</div>
				<div class="opt-m">
					<h3 class="title">Mustache / Beard</h3>
					<div class="scroll">
						<?php
							for ($i=1; $i <= 5; $i++) { 
							?>
								<div class="opt-i" onclick="mustache('m<?php echo $i; ?>')">
									<img src="thumbs/m<?php echo $i; ?>.png" height="40px">
								</div>
							<?php
							}
						?>
					</div>
				</div>
			</div>
		</div>

		<script>
			var h_w = parseInt(window.getComputedStyle(document.getElementById('head')).getPropertyValue('width'));
			var m_h = parseInt(window.getComputedStyle(document.getElementById('moust')).getPropertyValue('height'));
			var m_w = parseInt(window.getComputedStyle(document.getElementById('moust')).getPropertyValue('width'));
			function p() {
				var po = document.getElementById('points').checked;
				if (po) {
					document.getElementById('cood').style.display='none';
				} else {
					document.getElementById('cood').style.display='block';
				}
			}
			function frame(f) {
				var fr = document.getElementById('glass');
				fr.src = 'frame/' + f + '.png';
				if (f = 'f5') {
					fr.style.marginTop = (-1 * parseInt(window.getComputedStyle(fr).getPropertyValue('height')) /8) + 'px';
				} else {
					fr.style.marginTop = '0px';
				}
			}
			function mustache(m) {
				var mo = document.getElementById('moust');
				mo.src = 'moust/' + m + '.png';
				if (m == 'm1') {
					mo.style.marginTop = (-1 * parseInt(window.getComputedStyle(mo).getPropertyValue('height')) /8) + 'px';
					mo.style.height = m_h;
				} else if (m == 'm2') {
					mo.style.marginTop = (-1 * parseInt(window.getComputedStyle(mo).getPropertyValue('height')) /5) + 'px';
					mo.style.height = m_h;
				} else if (m == 'm3') {
					mo.style.marginTop = (-1 * parseInt(window.getComputedStyle(mo).getPropertyValue('height')) /3) + 'px';
					mo.style.height = m_h;
				} else if (m == 'm4') {
					mo.style.marginTop = (-1 * parseInt(window.getComputedStyle(mo).getPropertyValue('height')) /8) + 'px';
					mo.style.height = m_h;
				} else if (m == 'm5') {
					mo.style.marginTop = (-1 * parseInt(window.getComputedStyle(mo).getPropertyValue('height')) /8) + 'px';
					mo.style.height = m_h;
				} else if (m == 'm6') {
					mo.style.marginTop = (-1 * parseInt(window.getComputedStyle(mo).getPropertyValue('height')) /7) + 'px';
					mo.style.height = m_h;
					mo.style.width = m_w*1.18;
					mo.style.height = "auto";
					mo.style.marginLeft = -m_w*0.09;
				} else if (m == 'm7') {
					mo.style.marginTop = '0px';
					mo.style.width = m_w*1.2;
					mo.style.height = "auto";
					mo.style.marginLeft = -m_w*0.09;
				} else if (m == 'm8') {
					mo.style.marginTop = (parseInt(window.getComputedStyle(mo).getPropertyValue('height')) /3) + 'px';
					mo.style.width = m_w*1.2;
					mo.style.height = "auto";
					mo.style.marginLeft = -m_w*0.1;
				} else if (m == 'm9') {
					mo.style.marginTop = (-1 * parseInt(window.getComputedStyle(mo).getPropertyValue('height')) /7) + 'px';
					mo.style.width = m_w*1.2;
					mo.style.height = "auto";
					mo.style.marginLeft = -m_w*0.1;
				} else if (m == 'm10') {
					//mo.style.marginTop = (-1 * parseInt(window.getComputedStyle(mo).getPropertyValue('height')) /3) + 'px';
					mo.style.width = m_w*1.2;
					mo.style.height = "auto";
					mo.style.marginLeft = -m_w*0.1;
				} else if (m == 'm11') {
					//mo.style.marginTop = (-1 * parseInt(window.getComputedStyle(mo).getPropertyValue('height')) /3) + 'px';
					mo.style.width = m_w*1.2;
					mo.style.height = "auto";
					mo.style.marginLeft = -m_w*0.1;
				} else if (m == 'm12') {
					//mo.style.marginTop = (-1 * parseInt(window.getComputedStyle(mo).getPropertyValue('height')) /3) + 'px';
					mo.style.width = m_w*1.2;
					mo.style.height = "auto";
					mo.style.marginLeft = -m_w*0.1;
				} else {
					mo.style.marginTop = '0px';
					he.style.marginLeft = '0px';
					mo.style.height = m_h;
					mo.style.width = m_w;
				}
			}
			function hgear(h) {
				var he = document.getElementById('head');
				he.src = 'head/' + h + '.png';
				if (h == 'h3') {
					he.style.marginTop = (-1 * parseInt(window.getComputedStyle(he).getPropertyValue('height')) / 4) + 'px';
				} else if (h == 'h5') {
					he.style.width = h_w * 1.25;
					he.style.marginLeft = -h_w / 8;
				} else if (h == 'h6') {
					he.style.marginTop = (-1 * parseInt(window.getComputedStyle(he).getPropertyValue('height')) /2) + 'px';
				} else {
					he.style.marginTop = '0px';
					he.style.marginLeft = '0px';
					he.style.width = h_w;
				}
			}
		</script>

		<center>
			<div class="logo-bar">
				<div class="t">
					<div class="mt">FaceDetect</div>
					Lovepreet Singh<br />(YV-16-10285)<br />Ashish Kaktan<br />(YV-16-10252)
				</div>
				<img src="img/YV-LOGO.png">
			</div>
		</center>
	</body>
</html>
	<?php

} else {
	header("location: index.php");
}

?>					