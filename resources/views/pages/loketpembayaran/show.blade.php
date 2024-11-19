@extends('layouts.backend')

@section('title', 'Data Invoice')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Data Invoice</h5>
                </div>
                <div class="p-4 card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card" style="background-color: #ccffcc">
                                <div class="card-body">
                                    <p class="mb-1 fw-semibold">Total Bayar</p>
                                    <h5 class="mb-1 fw-semibold">
                                        {{ 'Rp ' . number_format($totalBayar, 0, ',', '.') }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card" style="background-color: #cceeff">
                                <div class="card-body">
                                    <p class="mb-1 fw-semibold">Komisi</p>
                                    <h5 class="mb-1 fw-semibold">
                                        {{ 'Rp ' . number_format($jml_komisi, 0, ',', '.') }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card" style="background-color: #e5ccff">
                                <div class="card-body">
                                    <p class="mb-1 fw-semibold">Total Setoran</p>
                                    <h5 class="mb-1 fw-semibold">
                                        {{ 'Rp ' . number_format($totalBayar - $jml_komisi, 0, ',', '.') }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 d-flex gap-2">
                        <a class="mb-4 btn btn-sm btn-success"
                            href="{{ route('cetakDaftarTagihan.invoice', ['kd_loket' => $kd_loket, 'tgl_invoice' => $tgl_invoice]) }}">
                            Cetak Daftar Tagihan
                        </a>
                        <a href="{{ route('cetakAllInvoice.invoice', ['kd_loket' => $kd_loket, 'tgl_invoice' => $tgl_invoice]) }}"
                            class="mb-4 btn btn-sm btn-warning">Cetak Semua Invoice</a>
                    </div>
                    @role('super-admin|isp')
                        <a href="{{ route('loketPembayaran.index') }}" class="mb-4 btn btn-danger">Kembali</a>
                    @endrole
                    <div class="table-responsive">
                        <table class="table border table-striped" id="dataTableloket"
                            style=" width: 100%;
                                                min-width: 0;
                                                max-width: 200px;
                                                white-space: nowrap;">
                            <thead class="mb-0 align-middle text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold" style="width: 20px">No</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">User Mikrotik</h6>
                                    </th>
                                    {{-- <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Nama</h6>
                                    </th> --}}
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Status Mikrotik</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Tanggal Invoice</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Jumlah Bayar</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Paket</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Aksi</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoices as $value => $invoice)
                                    @if ($invoice->status_pppoe != 'Gratis' && optional($invoice->pelanggan->paket)->keterangan !== 'uji coba')
                                        <!-- Kondisi untuk menyembunyikan status "Gratis" -->
                                        <tr class="clickable-row hoverable-row"
                                            data-href="{{ route('pembayaran.create', Crypt::encrypt($invoice->kd_invoice)) }}">
                                            <td class="align-middle border-bottom-0">
                                                <h6 class="mb-0 fw-normal">{{ $value + 1 }}</h6>
                                            </td>
                                            <td class="align-middle border-bottom-0">
                                                <p class="mb-0 fw-normal">{{ $invoice->pelanggan->username_pppoe }}</p>
                                            </td>
                                            {{-- <td class="align-middle border-bottom-0">
                                                <p class="mb-0 fw-normal">{{ $invoice->pelanggan->nm_pelanggan }}</p>
                                            </td> --}}
                                            <td class="align-middle border-bottom-0">
                                                @if ($invoice->status_pppoe == 'isolir')
                                                    <span class="badge badge-danger">Isolir</span>
                                                @else
                                                    <p class="mb-0 fw-normal badge badge-success">
                                                        {{ $invoice->status_pppoe }}</p>
                                                @endif
                                            </td>
                                            <td class="align-middle border-bottom-0">
                                                <p class="mb-0 fw-normal">
                                                    {{ \Carbon\Carbon::parse($invoice->tgl_invoice)->format('d M Y') }}
                                                </p>
                                            </td>
                                            <td class="align-middle border-bottom-0">
                                                @php
                                                    $totalPembayaran = $invoice->pembayaran->sum('jml_bayar');
                                                    $hrgPaket = optional($invoice->pelanggan->paket)->hrg_paket ?? 0; // Atur default jika null
                                                    $badgeClass = 'badge-primary';

                                                    if ($totalPembayaran == $hrgPaket) {
                                                        $badgeClass = 'badge-success';
                                                    } elseif ($totalPembayaran < $hrgPaket && $totalPembayaran > 0) {
                                                        $badgeClass = 'badge-warning';
                                                    } elseif ($totalPembayaran <= 0) {
                                                        $badgeClass = 'badge-danger';
                                                    }
                                                @endphp


                                                <span class="badge {{ $badgeClass }}">
                                                    @if ($totalPembayaran > 0)
                                                        Rp {{ number_format($totalPembayaran) }}
                                                    @else
                                                        Belum Bayar
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="align-middle border-bottom-0">
                                                <p class="mb-0 fw-semibold badge badge-success">
                                                    Rp {{ number_format($hrgPaket) }}
                                                </p>
                                            </td>
                                            <td class="align-middle border-bottom-0">
                                                <div class="gap-2 d-flex align-items-center justify-content-center">
                                                    <!-- Full Invoice Print -->
                                                    <a href="{{ route('invoice.full', Crypt::encryptString($invoice->kd_invoice)) }}"
                                                        title="invoice 1" data-bs-toggle="tooltip" class="trigger-print"
                                                        data-target="{{ route('invoice.full', Crypt::encryptString($invoice->kd_invoice)) }}">
                                                        <span><i style="font-size: 20px;"
                                                                class="text-secondary ti ti-printer"></i></span>
                                                    </a>

                                                    <!-- Small Invoice Print -->
                                                    <a href="{{ route('invoice.small', Crypt::encryptString($invoice->kd_invoice)) }}"
                                                        title="invoice 2" data-bs-toggle="tooltip" class="trigger-print"
                                                        data-target="{{ route('invoice.small', Crypt::encryptString($invoice->kd_invoice)) }}">
                                                        <span><i style="font-size: 20px;"
                                                                class="text-danger ti ti-layers-intersect"></i></span>
                                                    </a>
                                                    @if ($totalPembayaran <= 0)
                                                        <a href="{{ route('invoice.isolir', $invoice->kd_pelanggan) }}"
                                                            title="Isolir" data-bs-toggle="tooltip">
                                                            <span><i style="font-size: 20px;"
                                                                    class="text-danger ti ti-cloud-lock"></i></span>
                                                        </a>
                                                        <a href="{{ route('invoice.pulihkan', $invoice->kd_pelanggan) }}"
                                                            title="Pulihkan" data-bs-toggle="tooltip">
                                                            <span><i style="font-size: 20px;"
                                                                    class="text-secondary ti ti-lock-open"></i></span>
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('pembayaran.create', Crypt::encrypt($invoice->kd_invoice)) }}"
                                                        title="tambah" data-bs-toggle="tooltip">
                                                        <span><i style="font-size: 20px;"
                                                                class="text-blue ti ti-pencil-plus"></i></span>
                                                    </a>
                                                </div>
                                                <!-- JavaScript to handle the print functionality -->
                                                <script type="text/javascript">
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const printButtons = document.querySelectorAll('.trigger-print');

                                                        printButtons.forEach(function(button) {
                                                            button.addEventListener('click', function(event) {
                                                                event.preventDefault();
                                                                const url = this.getAttribute('data-target');

                                                                // Open the print page in a new window
                                                                const printWindow = window.open(url, '_blank');

                                                                // Wait until the print window is fully loaded
                                                                printWindow.onload = function() {
                                                                    // Trigger the print dialog
                                                                    printWindow.print();

                                                                    // Close the print window after printing
                                                                    printWindow.onafterprint = function() {
                                                                        printWindow.close();
                                                                    };
                                                                };
                                                            });
                                                        });
                                                    });
                                                </script>
                                                <script type="text/javascript">
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const urlParams = new URLSearchParams(window.location.search);
                                                        const username = urlParams.get('username');

                                                        if (username) {
                                                            // Find the table row corresponding to the specific username
                                                            const tableRows = document.querySelectorAll('#dataTableloket tbody tr');
                                                            tableRows.forEach(function(row) {
                                                                const usernameCell = row.querySelector('td:nth-child(2) p');
                                                                if (usernameCell && usernameCell.textContent.trim() === username) {
                                                                    // Scroll the row into view and highlight it
                                                                    row.scrollIntoView({
                                                                        behavior: 'smooth',
                                                                        block: 'center'
                                                                    });
                                                                    row.style.backgroundColor = '#ffff99'; // Highlight with a yellow color
                                                                }
                                                            });
                                                        }
                                                    });
                                                </script>

                                                {{-- non aktif data tabel --}}

                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <link href="{{ asset('assets/libs/datatables2/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <!-- DataTables2 -->
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables2/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables2/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables2/datatables-demo.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#dataTableloket").DataTable({
                "pageLength": -1,
            });
        });
    </script>
    <!-- JavaScript to handle row click -->
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            const rows = document.querySelectorAll('.clickable-row');
            rows.forEach(function(row) {
                row.addEventListener('click', function() {
                    const href = this.getAttribute('data-href');
                    window.location.href = href;
                });

                row.addEventListener('mousedown', function() {
                    this.classList.add('row-active');
                });

                row.addEventListener('mouseup', function() {
                    this.classList.remove('row-active');
                });
            });
        });
    </script>

    <!-- CSS Styles for hover and active click effect -->
    <style>
        .hoverable-row:hover {
            background-color: #D8D8D8FF;
            /* Light gray on hover */
            cursor: pointer;
        }

        .row-active {
            background-color: #D8D8D8FF;
            /* Darker gray when clicked */
        }

        #dataTableloket_wrapper {
            position: relative !important;
            overflow: hidden;
        }

        #dataTableloket_wrapper table {
            width: 100% !important;
            overflow-x: auto;
            white-space: nowrap;
        }

        #dataTableloket_length {
            float: left;
            margin-bottom: 20px;
        }

        #dataTableloket_filter {
            float: left;
        }

        #dataTableloket_filter input[type="search"] {
            width: auto !important;
        }

        @media (max-width: 767px) {
            #dataTableloket_wrapper table {
                display: block;
                overflow-x: auto;
            }

            #dataTableloket_filter input[type="search"] {
                width: 15rem !important;
                margin-left: 0 !important;
                margin-bottom: 5px;
            }

            #dataTableloket_filter label {
                margin-left: 0 !important;
            }
        }

        @media (min-width: 767px) {
            #dataTableloket_length {
                float: none;
                margin-bottom: 0px;
            }

            #dataTableloket_filter {
                float: right;
            }
        }
    </style>
@endsection
