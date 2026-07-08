    <script>
        function toggleSidebar() {
            document.getElementById("mySidebar").classList.toggle("open");
            document.querySelector(".overlay").classList.toggle("active");
        }

        document.querySelectorAll('.form-confirm').forEach((form) => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin?',
                    text: 'Data yang diproses tidak bisa dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>
</body>
</html>
