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
    <script>
        function showToast(message, type = 'success') {
            let bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            let icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            let toast = $(`<div class="fixed top-20 right-4 ${bgColor} text-white px-4 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2">
        <i class="fas ${icon}"></i> ${message}
    </div>`);
            $('body').append(toast);
            setTimeout(() => toast.fadeOut(300, function() {
                $(this).remove();
            }), 3000);
        }

        <?php if (isset($_SESSION['success'])): ?>
            showToast('<?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>', 'success');
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            showToast('<?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?>', 'error');
        <?php endif; ?>
    </script>
    </body>

    </html>
<?php endif; ?>