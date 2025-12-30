<?php ob_start(); ?>
<!-- Preloader CSS/JS/HTML Removed to prevent conflicts. Preloader was already commented out. -->
<!-- If preloader is needed, it should be included as a partial without html/head/body tags -->
<style>
/* Preloader styles preserved if needed */
.preloader-wrap {
	position: fixed;
	left: 0;
	top: 0;
	right: 0;
	bottom: 0;
	z-index: 1000;
	display: flex;
	align-items: center;
	justify-content: center;
    pointer-events: none; /* added to be safe */
}
/* ... remaining styles omitted for brevity as they are unused ... */
</style>

<!-- Preloader HTML (Commented Out) -->
<!-- 
<div id="preloader">
...
</div> 
-->


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

