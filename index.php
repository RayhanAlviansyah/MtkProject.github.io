<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perhitungan Bunga Tunggal dan Majemuk</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900 flex items-center justify-center min-h-screen">

<?php
function hitungBungaTunggal($modal, $waktu, $bungaPersen) {
    $bunga = $waktu * ($bungaPersen / 100) * $modal;
    $modalAkhir = $modal * (1 + ($waktu * ($bungaPersen / 100)));
    return [
        'bunga' => $bunga,
        'modalAkhir' => $modalAkhir
    ];
}

function hitungBungaMajemuk($modal, $waktu, $bungaPersen) {
    $modalAkhir = $modal * pow((1 + ($bungaPersen / 100)), $waktu);
    $bunga = $modalAkhir - $modal;
    return [
        'bunga' => $bunga,
        'modalAkhir' => $modalAkhir
    ];
}

function konversiWaktu($waktu, $satuan) {
    switch($satuan) {
        case 'bulan':
            return $waktu / 12;
        case 'triwulan':
            return $waktu / 4;
        case 'caturwulan':
            return $waktu / 3;
        case 'semester':
            return $waktu / 2;
        case 'tahun':
        default:
            return $waktu;
    }
}

function konversiBunga($bungaPersen, $satuanBunga) {
    if ($satuanBunga === 'bulan') {
        return $bungaPersen * 12;
    }
    return $bungaPersen;
}

$hasilBungaTunggal = null;
$hasilBungaMajemuk = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modal = (float)$_POST['modal'];
    $waktu = (float)$_POST['waktu'];
    $bungaPersen = (float)$_POST['bungaPersen'];
    $satuanWaktu = $_POST['satuanWaktu'];
    $satuanBunga = $_POST['satuanBunga'];

    // Konversi waktu dan bunga
    $waktu = konversiWaktu($waktu, $satuanWaktu);
    $bungaPersen = konversiBunga($bungaPersen, $satuanBunga);

    // Hitung bunga tunggal dan majemuk
    $hasilBungaTunggal = hitungBungaTunggal($modal, $waktu, $bungaPersen);
    $hasilBungaMajemuk = hitungBungaMajemuk($modal, $waktu, $bungaPersen);

    // Format angka sebagai Rupiah
    function formatRupiah($angka) {
        return "Rp " . number_format($angka, 0, ',', '.');
    }
}
?>

    <div class="max-w-lg bg-white shadow-md rounded-lg p-8">
        <h1 class="text-2xl font-bold text-center mb-6">Perhitungan Bunga Tunggal dan Majemuk</h1>

        <form method="POST" class="space-y-4">
            <div>
                <label for="modal" class="block text-sm font-medium text-gray-700">Modal Awal (Rp): </label>
                <input type="number" id="modal" name="modal" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label for="waktu" class="block text-sm font-medium text-gray-700">Lama Waktu: </label>
                <div class="mt-1 flex">
                    <input type="number" id="waktu" name="waktu" required class="w-full p-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <select id="satuanWaktu" name="satuanWaktu" class="p-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="bulan">Bulan</option>
                        <option value="triwulan">Triwulan</option>
                        <option value="caturwulan">Caturwulan</option>
                        <option value="semester">Semester</option>
                        <option value="tahun">Tahun</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="bungaPersen" class="block text-sm font-medium text-gray-700">Bunga (%): </label>
                <div class="mt-1 flex">
                    <input type="number" id="bungaPersen" name="bungaPersen" required class="w-full p-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <select id="satuanBunga" name="satuanBunga" class="p-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="tahun">Per Tahun</option>
                        <option value="bulan">Per Bulan</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-bold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Hitung</button>
        </form>

        <?php if ($hasilBungaTunggal && $hasilBungaMajemuk): ?>
        <h2 class="text-xl font-semibold mt-6">Hasil:</h2>
        <div id="hasilBungaTunggal" class="mt-4 bg-gray-100 p-4 rounded-md">
            Bunga Tunggal: <?= formatRupiah($hasilBungaTunggal['bunga']) ?><br>
            Modal Akhir (Tunggal): <?= formatRupiah($hasilBungaTunggal['modalAkhir']) ?>
        </div>
        <div id="hasilBungaMajemuk" class="mt-4 bg-gray-100 p-4 rounded-md">
            Bunga Majemuk: <?= formatRupiah($hasilBungaMajemuk['bunga']) ?><br>
            Modal Akhir (Majemuk): <?= formatRupiah($hasilBungaMajemuk['modalAkhir']) ?>
        </div>
        <?php endif; ?>
    </div>

</body>
</html>
