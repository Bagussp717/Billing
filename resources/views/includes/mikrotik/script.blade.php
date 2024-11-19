<script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/dist/simplebar.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>

<!-- DataTables -->
<script src="{{ asset('assets/libs/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/libs/datatables/datatables-demo.js') }}"></script>

<!-- Toastr CSS and JS -->
<link rel="stylesheet" href="{{ asset('assets/libs/toastr/toastr.min.css') }}">
<script src="{{ asset('assets/libs/toastr/toastr.min.js') }}"></script>


<!-- Toastr Initialization -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10.0.1/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.0.1/dist/sweetalert2.all.min.js"></script>


<!-- Maps -->

<!-- Toastr Initialization -->
<script>
    $(document).ready(function() {
        toastr.options = {
            "closeButton": true
        };

        @if (Session::has('success'))
            toastr.success('{{ Session::get('success') }}', 'Success');
        @endif

        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}', 'Error');
        @endif

        @if (Session::has('status'))
            toastr.success('{{ Session::get('status') }}', 'Success');
        @endif

        @if (Session::has('resent'))
            toastr.success('Email verifikasi telah terkirim ulang.', 'Success');
        @endif
    });
</script>

<script>
    function notificationBeforeDelete(event, el) {
        event.preventDefault();
        const form = $(el).closest('form'); // Find the form closest to the clicked link
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: 'Anda akan menghapus data ini!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#696cff',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Submit the form if confirmed
            }
        });
    }
</script>

<script>
    function formatTelepon(element) {
        // Hapus semua karakter non-digit
        let value = element.value.replace(/\D/g, "");

        // Pastikan nomor dimulai dengan '62' dan tidak dimulai dengan '0'
        if (!value.startsWith('62')) {
            value = '62' + value.replace(/^0+/, ''); // Mengganti '0' di depan dengan ''
        }

        // Mengupdate nilai input tanpa mengizinkan '0' di depan
        element.value = value;
    }
</script>
