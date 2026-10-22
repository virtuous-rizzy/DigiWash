<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kritik & Saran</title>
    <!-- Memuat Bootstrap 5 dari CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #e5e5e5; }
        
        /* Container menyerupai layar HP */
        .mobile-container {
            max-width: 400px;
            background-color: #ffffff;
            min-height: 100vh;
            margin: 0 auto;
        }
        
        /* Warna Khusus dari Figma */
        .text-dark-custom { color: #1b2a3a; }
        .btn-navy { background-color: #0b3d5c; color: #ffffff; font-weight: bold; border-radius: 10px; }
        .btn-navy:hover { background-color: #082d44; color: #ffffff; }
        
        /* Trik Bintang Murni HTML/CSS */
        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            gap: 5px;
        }
        .star-rating input { display: none; }
        .star-rating label {
            font-size: 2.5rem;
            color: #eeeeee;
            cursor: pointer;
        }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label { color: #FFD700; }
    </style>
</head>
<body>
    <div class="mobile-container shadow p-4">
        
        <!-- Header (Menggunakan utility flex Bootstrap) -->
        <div class="d-flex align-items-center mb-4 text-dark-custom">
            <span class="fs-4 me-3">&#8592;</span>
            <span class="fw-bold fs-5">Kritik & Saran</span>
        </div>
        
        <!-- Ilustrasi -->
        <div class="text-center mb-4">
            <div style="font-size: 5rem; line-height: 1;">💬</div>
            <p class="fw-bold text-dark-custom mt-2 mb-0">Berikan penilaianmu</p>
        </div>

        <!-- Form -->
        <form action="/kritik-saran/simpan" method="post">
            
            <!-- Area Bintang -->
            <div class="star-rating mb-4">
                <input type="radio" id="star5" name="rating" value="5" checked>
                <label for="star5">★</label>
                <input type="radio" id="star4" name="rating" value="4">
                <label for="star4">★</label>
                <input type="radio" id="star3" name="rating" value="3">
                <label for="star3">★</label>
                <input type="radio" id="star2" name="rating" value="2">
                <label for="star2">★</label>
                <input type="radio" id="star1" name="rating" value="1">
                <label for="star1">★</label>
            </div>

            <!-- Jenis Ulasan -->
            <div class="mb-3 text-dark-custom">
                <label class="fw-bold mb-2">Jenis Ulasan</label>
                <div class="d-flex gap-4">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jenis" id="saran" value="Saran" checked>
                        <label class="form-check-label" for="saran">Saran</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jenis" id="keluhan" value="Keluhan">
                        <label class="form-check-label" for="keluhan">Keluhan</label>
                    </div>
                </div>
            </div>

            <!-- Textarea -->
            <div class="mb-4 text-dark-custom">
                <label class="fw-bold mb-2">Kritik & Saran</label>
                <textarea name="pesan" class="form-control" rows="5" placeholder="Tuliskan kritik dan saran Anda di sini..." style="border-color: #99aaaa; border-radius: 10px;" required></textarea>
            </div>
            
            <!-- Tombol Kirim -->
            <button type="submit" class="btn btn-navy w-100 py-3">Kirim</button>
        </form>
    </div>

    <!-- Script Bootstrap (opsional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>