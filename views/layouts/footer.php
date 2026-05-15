<?php if (isset($_SESSION['user_id'])): ?>
    </main>
    </div>
    </div>
<?php endif; ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Auto-hide alerts after 3 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 3000);

    // Confirm delete
    function confirmDelete() {
        return confirm('Are you sure you want to delete this item? This action cannot be undone.');
    }
</script>
</body>

</html>