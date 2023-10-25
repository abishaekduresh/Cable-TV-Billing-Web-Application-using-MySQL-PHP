<style>

        /* Style for the preloader container */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 2);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

</style>

<!-- Preloader -->
    <div id="preloader">
        <img src="assets/brand-logo-preloader.gif" alt="Preloader..." height="400px" width="500px">
    </div>

    <script>
        // JavaScript to hide the preloader when all page assets are loaded
        window.onload = function () {
            document.getElementById('preloader').style.display = 'none';
        };
    </script>

    <!-- <script>
        // JavaScript to hide the preloader after a specified time delay (in milliseconds)
        setTimeout(function () {
            document.getElementById('preloader').style.display = 'none';
        }, 2000); // Adjust the time in milliseconds as needed (e.g., 3000 milliseconds = 3 seconds)
    </script> -->

