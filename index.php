<?php
// SETTING DATABASE;
$config = mysqli_connect("localhost", "root", "", "tesremote");


// fungsi buat query
function query($query)
{
    global $config;
    $result =  mysqli_query($config, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}
// fungsi untuk insert data
function insertData($insert)
{
    global $config;
    $result = mysqli_query($config, $insert);
    if (mysqli_affected_rows(($config))) {
        return $result;
    }
}
//  --- table laporan  ---
// -- last laporan
$laporan = query("SELECT * FROM laporan GROUP BY tanggal DESC LIMIT 1")[0];
//tanggal terakhir
$tgl = $laporan["Tanggal"];
//  last omset 
$omset = $laporan["omset"];
// last total quantity
$total_quantity = $laporan["Total_Quantity"];
// -- table transaksi
$ts = query("SELECT * FROM transaksi");

// -- history
$history  = query("SELECT * FROM history");


if (!empty($_POST)) {
    foreach ($ts  as $t) {
        $nama_pelanggan = $t["nama_pelanggan"];

        $quantity = $t["Quantity"];
        $total_belanja = $omset / $total_quantity * $quantity;
        $result = insertData("INSERT INTO history(id,nama_pelanggan,tanggal,total_belanja) VALUES(NULL,'$nama_pelanggan','$tgl','$total_belanja');");
        header("Location: index.php");
    }
}

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Tes kerja Remote</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-4 mt-3">
                <h1>Form Input</h1>
                <form action="" method="POST">
                    <input type="hidden" name="active">
                    <button type="submit" class="btn btn-secondary">
                        Proses semua History <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-octagon" viewBox="0 0 16 16">
                            <path d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353L4.54.146zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1H5.1z" />
                            <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z" />
                        </svg>
                    </button>

                </form>
            </div>

        </div>
        <div class="row">
            <div class="col-4 mt-3">
                <span class="badge bg-primary">History</span>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-lg-8">

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama pelanggan</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Total Belanja</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($history)) : ?>
                            <tr>
                                <td colspan="4" class="text-center text-danger">
                                    <h3> Data kosong ....</h3>
                                    <span>Silahkan klik tombol proses semua history</span>
                                </td>
                            </tr>

                        <?php endif; ?>

                        <?php foreach ($history as $hs) : ?>

                            <tr>
                                <th scope="row"><?= $hs["id"] ?></th>
                                <td><?= $hs["Nama_Pelanggan"] ?></td>
                                <td><?= $hs["Tanggal"] ?></td>
                                <td><?= $hs["Total_Belanja"] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
    -->
</body>

</html>