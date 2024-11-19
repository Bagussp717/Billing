@extends('layouts.invoice_small')

@section('title', 'Invoice')
@section('content')

    <div class="p-2">
        <!-- Header Section -->
        <div class="row">
            <div class="col-4">
                <img src="{{ asset('assets/images/logos/logo_invoice.png') }}" alt="Logo" width="150">
                <p><strong>Support ISP:</strong></p>
                <p>Yth Bapak/Ibu: {{ $invoice->pelanggan->nm_pelanggan }}<br>{{ $invoice->pelanggan->alamat }}</p>
            </div>
            <div class="col-5 text-end">
                <p><strong>INV-240{{ $invoice->pelanggan->kd_pelanggan }}</strong><br>
                    <strong>KODE PELANGGAN:</strong> 000{{ $invoice->pelanggan->kd_user }}<br>
                    <strong>TANGGAL TERBIT:</strong> {{ \Carbon\Carbon::parse($invoice->tgl_invoice)->locale('id')->translatedFormat('d F Y') }}<br>
                    <strong>JATUH TEMPO:</strong> {{ \Carbon\Carbon::parse($invoice->tgl_akhir)->locale('id')->translatedFormat('d F Y') }}<br>
                    <strong>JENIS BILLING:</strong> PRABAYAR
                </p>
            </div>
            <div class="col-3">
                <p style="margin-bottom: 0;"><strong>BAYAR DENGAN QRIS</strong></p>
                <img src="{{ asset('assets/images/logos/qris-semesta.jpg') }}" alt="QR Code" width="95"
                class="pb-1">
            </div>
        </div>

        <!-- Payment Status Section -->
        <div class="row mt-2">
            <div class="col-12 text-left">
                <h5><strong>Status:</strong>
                    @if($invoice->pembayaran->sum('jml_bayar') > 0)
                        <span style="color: green;">PAID</span>
                    @else
                        <span style="color: red;">UNPAID</span>
                    @endif
                </h5>
                @if($invoice->pembayaran->sum('jml_bayar') > 0)
                    <p>Invoice Date Paid: {{ \Carbon\Carbon::parse($invoice->pembayaran->first()->tgl_bayar)->locale('id')->translatedFormat('d F Y') }}</p>
                @endif
            </div>
        </div>

        <!-- Invoice Details Section -->
        <table class="table table-bordered mt-2">
            <thead style="background-color: #2e2e2e; color:#ffff">
                <tr>
                    <th><strong>DESKRIPSI / LAYANAN</strong></th>
                    <th class="text-end"><strong>JUMLAH</strong></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->pelanggan->profile_pppoe }}</td>
                    <td class="text-end">Rp {{ number_format($invoice->pelanggan->paket->hrg_paket, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="1" class="text-end"><strong>Total</strong></td>
                    <td class="text-end"><strong>Rp {{ number_format($invoice->pelanggan->paket->hrg_paket, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Footer Section -->

        <p class="mb-0"><strong>Catatan :</strong></p>
        <ul>
            <li>Harap melakukan pembayaran setiap tanggal 1 s/d 5 setiap bulannya untuk menghindari pemblokiran oleh sistem.</li>
            <li>Untuk permintaan nomor rekening, layanan pelanggan dan keluhan jaringan silahkan hubungi nomor: 081 216 416 437</li>
        </ul>

        <!-- Print Button -->
        <div class="text-center mt-3">
            <button onclick="window.print()" class="btn btn-primary" id="print-btn">Print Invoice</button>
        </div>
    </div>

    <!-- Custom CSS to hide button on print -->
    <style>
        @media print {
            #print-btn {
                display: none;
            }
        }
    </style>

@endsection
