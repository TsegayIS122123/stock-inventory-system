<?php if (isset($_SESSION['user_id'])): ?>
    </main>
    </div>

    <footer class="main-footer">
        <p>&copy; <?php echo date('Y'); ?> TK-Stock. All rights reserved.</p>
        <p class="version">
            <i class="fas fa-code"></i>
            Advanced Internet Programming - INSY3082
        </p>
        <p class="developers">Developed by: Tsegay, Chekole & Kassa</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);

        function confirmDelete() {
            return confirm('Delete this item?');
        }
    </script>
    </body>

    </html>
<?php endif; ?>