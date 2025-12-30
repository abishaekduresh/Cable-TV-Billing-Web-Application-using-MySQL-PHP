<footer class="bg-white border-top py-3 mt-auto shadow-sm" style="position: fixed; bottom: 0; width: 100%; z-index: 1030;">
    <div class="container text-center">
        <p class="mb-0 text-muted small">
            &copy; <?php echo date("Y"); ?> <strong>PDP GROUPS.</strong> All rights reserved. 
            <span class="mx-2">|</span> 
            Developed by <strong class="text-primary">Duresh Tech</strong>
        </p>
    </div>
</footer>

<script>
    // Adjust body padding to prevent footer overlap
    document.addEventListener("DOMContentLoaded", function() {
        const footer = document.querySelector('footer');
        if(footer) {
            document.body.style.paddingBottom = (footer.offsetHeight + 20) + 'px';
        }
    });
</script>

<!-- Legacy Scripts (Preserved for compatibility) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
