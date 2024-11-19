$(document).ready(function () {
    // Inisialisasi DataTable dengan pagingType berdasarkan ukuran layar
    function initializeDataTable(pagingType) {
        // Jika DataTable sudah ada, destroy terlebih dahulu sebelum inisialisasi ulang
        if ($.fn.DataTable.isDataTable("#dataTable")) {
            $("#dataTable").DataTable().destroy();
        }

        // Inisialisasi DataTable
        $("#dataTable").DataTable({
            language: {
                searchPlaceholder: "Search...",
                search: "",
            },
            pagingType: pagingType,
        });
    }

    // Fungsi untuk mendapatkan pagingType berdasarkan ukuran layar
    function getPagingType() {
        // Jika lebar layar kurang dari atau sama dengan 767px (mobile)
        if ($(window).width() <= 767) {
            return "simple"; // Menggunakan pagingType 'simple' untuk mobile
        } else {
            return "simple_numbers"; // Menggunakan pagingType 'simple_numbers' untuk desktop/tablet
        }
    }

    // Inisialisasi pertama DataTable dengan pagingType sesuai lebar layar saat ini
    initializeDataTable(getPagingType());

    // Event listener untuk perubahan ukuran layar
    $(window).resize(function () {
        // Re-initialize DataTable saat ukuran layar berubah
        initializeDataTable(getPagingType());
    });
});
