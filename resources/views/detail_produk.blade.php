{{-- resources/views/produk/detail.blade.php --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - Toko Ikan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar-brand {
            font-weight: 600;
            color: #0d6efd !important;
        }
        .product-img-box {
            background-color: #e9ecef;
            border-radius: 12px;
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 8px;
            color: #adb5bd;
            font-size: 14px;
        }
        .product-img-box i {
            font-size: 64px;
        }
        .product-img-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
        }
        .card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
        }
        .price-text {
            font-size: 28px;
            font-weight: 600;
            color: #0d6efd;
        }
        .stok-badge {
            font-size: 13px;
            padding: 4px 12px;
            border-radius: 20px;
        }
        .stat-box {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 12px 16px;
            border: 1px solid #e9ecef;
        }
        .stat-box .label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 2px;
        }
        .stat-box .value {
            font-size: 16px;
            font-weight: 500;
        }
        .btn-beli {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
        }
        .btn-beli:hover {
            background-color: #0b5ed7;
            color: white;
        }
        .btn-keranjang {
            background-color: white;
            color: #0d6efd;
            border: 1px solid #0d6efd;
            padding: 10px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
        }
        .btn-keranjang:hover {
            background-color: #e7f1ff;
            color: #0d6efd;
        }
        .qty-input {
            width: 100px;
            text-align: center;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 6px 10px;
            font-size: 15px;
        }
        .qty-input:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }
        .subtotal-box {
            background-color: #e7f1ff;
            border-radius: 8px;
            padding: 10px 14px;
        }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="navbar navbar-expand-lg bg-white border-bottom px-4 px-lg-5">
    <a class="navbar-brand" href="#">
        <i class="bi bi-fish"></i> Toko Ikan
    </a>
    <div class="ms-auto d-flex align-items: center gap-3">
        <a href="#" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-cart3"></i> Keranjang
        </a>
        <a href="#" class="btn btn-primary btn-sm">Masuk</a>
    </div>
</nav>

{{-- Breadcrumb --}}
<div class="container py-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0" style="font-size: 13px;">
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Beranda</a></li>
            <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Produk</a></li>
            {{-- Nanti diganti: {{ $produk->nama_ikan }} --}}
            <li class="breadcrumb-item active">Lele Segar</li>
        </ol>
    </nav>
</div>

{{-- Konten Utama --}}
<div class="container pb-5">
    <div class="row g-4">

        {{-- Foto Produk --}}
        <div class="col-md-5">
            <div class="product-img-box">
                {{-- Nanti diganti dengan foto asli:
                @if ($produk->foto)
                    <img src="{{ Storage::url($produk->foto) }}" alt="{{ $produk->nama_ikan }}">
                @else
                    <i class="bi bi-image"></i>
                    <span>Foto belum tersedia</span>
                @endif
                --}}
                <i class="bi bi-image"></i>
                <span>Foto produk</span>
            </div>
        </div>

        {{-- Info Produk --}}
        <div class="col-md-7">
            <div class="card p-4 shadow-sm">

                {{-- Badge stok --}}
                {{-- Nanti diganti: @if ($produk->stok_berat > 0) --}}
                <span class="stok-badge bg-success-subtle text-success fw-500 d-inline-block mb-3">
                    <i class="bi bi-check-circle-fill me-1"></i> Tersedia
                </span>

                {{-- Nama & jenis ikan --}}
                {{-- Nanti diganti: {{ $produk->nama_ikan }} & {{ $produk->jenis_ikan }} --}}
                <h1 class="fs-4 fw-500 mb-1">Lele Segar</h1>
                <p class="text-secondary mb-3" style="font-size: 14px;">Ikan Air Tawar</p>

                {{-- Harga --}}
                <div class="border-top border-bottom py-3 mb-3">
                    <p class="text-secondary mb-1" style="font-size: 12px;">Harga per kg</p>
                    {{-- Nanti diganti: Rp {{ number_format($harga, 0, ',', '.') }} --}}
                    <p class="price-text mb-0">Rp 25.000 <span class="fs-6 fw-normal text-secondary">/ kg</span></p>
                </div>

                {{-- Stok & min. beli --}}
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="stat-box">
                            <p class="label">Stok tersedia</p>
                            {{-- Nanti diganti: {{ $produk->stok_berat }} kg --}}
                            <p class="value mb-0">12.5 kg</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-box">
                            <p class="label">Min. pembelian</p>
                            <p class="value mb-0">0.5 kg</p>
                        </div>
                    </div>
                </div>

                {{-- Input jumlah --}}
                <div class="mb-3">
                    <label class="form-label text-secondary" style="font-size: 13px;">Jumlah yang ingin dibeli</label>
                    <div class="d-flex align-items-center gap-2">
                        <input
                            type="number"
                            id="qty"
                            class="qty-input"
                            value="1"
                            min="0.5"
                            step="0.5"
                            oninput="hitungSubtotal()"
                        >
                        <span class="text-secondary">kg</span>
                    </div>
                </div>

                {{-- Subtotal --}}
                <div class="subtotal-box d-flex justify-content-between align-items-center mb-4">
                    <span class="text-secondary" style="font-size: 13px;">Subtotal</span>
                    <span class="fw-500" id="subtotal">Rp 25.000</span>
                </div>

                {{-- Tombol --}}
                <div class="d-grid gap-2">
                    <button class="btn btn-beli">
                        <i class="bi bi-bolt-fill me-1"></i> Beli sekarang
                    </button>
                    <button class="btn btn-keranjang">
                        <i class="bi bi-cart-plus me-1"></i> Tambah ke keranjang
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const hargaPerKg = 25000; // Nanti diganti dengan: {{ $harga }}

    function hitungSubtotal() {
        const qty = parseFloat(document.getElementById('qty').value) || 0;
        const subtotal = qty * hargaPerKg;
        document.getElementById('subtotal').textContent =
            'Rp ' + subtotal.toLocaleString('id-ID');
    }
</script>

</body>
</html>