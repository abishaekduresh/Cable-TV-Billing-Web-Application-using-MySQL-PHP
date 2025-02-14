
<?php ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, user-scalable=1">
<title>Home</title>
<!--<link rel="stylesheet" type="text/css" href="css/style.css">-->
<style>
*, *:after, *:before {
	 -webkit-box-sizing: border-box;
	 -moz-box-sizing: border-box;
	 -ms-box-sizing: border-box;
	 box-sizing: border-box;
}
 body {
	 font-family: arial;
	 font-size: 16px;
	 margin: 0;
	 background: #000;
	 color: #000;
}
.preloader-wrap{
	position: fixed;
	left: 0;
	top: 0;
	right: 0;
	bottom: 0;
	z-index: 1000;
	display: flex;
	align-items: center;
	justify-content: center;
}
.preloader {
	position: relative;
	width: 200px;
	height: 200px;
	border-radius: 50%;
	perspective: 780px;
}
.loading-circle {
	position: absolute;
	width: 100%;
	height: 100%;
	box-sizing: border-box;
	border-radius: 50%;
}
.loading-circle-one {
	left: 0%;
	top: 0%;
	animation: loadingCircleOne 1.2s linear infinite;
	border-bottom: 8px solid #722dff;
}
.loading-circle-two {
	top: 0%;
	right: 0%;
	animation: loadingCircleTwo 1.2s linear infinite;
	border-right: 8px solid #722dff;
}
.loading-circle-three {
	right: 0%;
	bottom: 0%;
	animation: loadingCircleThree 1.2s linear infinite;
	border-top: 8px solid #722dff;
}
@keyframes loadingCircleOne {
	0% {
		transform: rotateX(40deg) rotateY(-40deg) rotateZ(0deg);
	}
	100% {
		transform: rotateX(40deg) rotateY(-40deg) rotateZ(360deg);
	}
}
@keyframes loadingCircleTwo {
	0% {
		transform: rotateX(50deg) rotateY(15deg) rotateZ(0deg);
	}
	100% {
		transform: rotateX(50deg) rotateY(15deg) rotateZ(360deg);
	}
}
@keyframes loadingCircleThree {
	0% {
		transform: rotateX(15deg) rotateY(50deg) rotateZ(0deg);
	}
	100% {
		transform: rotateX(15deg) rotateY(50deg) rotateZ(360deg);
	}
}
 
</style>
</head>
<body>
<!--<div id="preloader">-->
<!--<div class="preloader-wrap">-->
<!--  <div class="preloader">-->
<!--    <div class="loading-circle loading-circle-one"></div>-->
<!--    <div class="loading-circle loading-circle-two"></div>-->
<!--    <div class="loading-circle loading-circle-three"></div>-->
<!--  </div>-->
<!--</div>-->
<!--</div>-->

</body>
</html>


    <script>
        // JavaScript to hide the preloader when all page assets are loaded
        // window.onload = function () {
        //     document.getElementById('preloader').style.display = 'none';
        // };
    </script>

    <!-- <script>
        // JavaScript to hide the preloader after a specified time delay (in milliseconds)
        setTimeout(function () {
            document.getElementById('preloader').style.display = 'none';
        }, 2000); // Adjust the time in milliseconds as needed (e.g., 3000 milliseconds = 3 seconds)
    </script> -->

