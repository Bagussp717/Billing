@php
    use Carbon\Carbon;

    // Create a Carbon instance from the invoice date
    $date = Carbon::parse($tgl_invoice);

    // Set the locale to Indonesian
    Carbon::setLocale('id');

    // Format the month name in Indonesian
    $monthName = $date->translatedFormat('F'); // 'F' gives the full textual month representation
    $year = $date->year;
@endphp
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>DAFTAR TAGIHAN - {{ strtoupper($loket->nm_loket) }}</title>
    <style type="text/css">
        table {
            font-size: 13px;
        }

        @media print {

            /* Hide headers and footers */
            @page {
                margin: 7mm;
                /* Remove default margins */
            }

            body {
                margin: 0;
                /* Optional: set margin for the body */
            }

            header,
            footer {
                display: none;
                /* Hide header and footer */
            }
        }
    </style>
</head>

<body>
    <p align="center" style="font-weight: bold;">DAFTAR TAGIHAN
        {{ strtoupper($loket->nm_loket) }}<br>{{ strtoupper($monthName) }} {{ $year }}</p>
    <table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse;" align="center">
        <tbody>
            <tr>
                <td><strong>No.</strong></td>
                <td><strong>Username</strong></td>
                <td><strong>Nama</strong></td>
                <td ><strong>Alamat</strong></td>
                <td><strong>Paket</strong></td>
                <td><strong>Jumlah Bayar</strong></td>
            </tr>
            @php
                $no = 1;
                $sum = 0;
            @endphp
            @foreach ($invoices as $invoice)
                {{-- @if ($invoice->pelanggan->paket->hrg_paket > 0) --}}
                    <!-- Only display if hrg_paket > 0 -->
                @if ($invoice->pelanggan->profile_pppoe !== 'Gratis' && optional($invoice->pelanggan->paket)->keterangan !== 'uji coba')
                    <tr>
                        <td align="center">{{ $no }}</td>
                        <td>{{ $invoice->pelanggan->username_pppoe }}</td>
                        <td>{{ ucfirst($invoice->pelanggan->nm_pelanggan) }}</td>
                        <td>{{ ucfirst($invoice->pelanggan->alamat) }}</td>
                        <td align="right">Rp{{ number_format($invoice->pelanggan->paket->hrg_paket, 0, ',', '.') }}
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                @endif
                    @php
                        $no++; // Increment the counter
                        $sum += $invoice->pelanggan->paket->hrg_paket; // Add harga paket to the sum
                    @endphp
                {{-- @endif --}}
            @endforeach
            {{-- @if ($sum > 0) --}}
                <!-- Only display total if sum is greater than 0 -->
                <tr>
                    <td colspan="4" align="right"><strong>Estimasi Total:</strong></td>
                    <td align="right"><strong>Rp{{ number_format($sum, 0, ',', '.') }}</strong></td>
                    <td>&nbsp;</td>
                </tr>
            {{-- @endif --}}
        </tbody>
    </table>
</body>

</html>
<script type="text/javascript">
    window.print();
</script>
