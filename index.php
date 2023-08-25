<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['pilihan'])) {
    $pilihan = $_POST['pilihan'];
    if ($pilihan === 'lama') {
      header('Location: pasien_lama.php');
      exit();
    } else if ($pilihan === 'baru') {
      header('Location: pasien_baru.php');
      exit();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pemilihan Pasien</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400;1,600;1,700&display=swap"
    rel="stylesheet">
  <style>
    * {
      font-family: 'Poppins', sans-serif;
    }

    #home .card,
    #home .btn {
      border-radius: 15px;
    }

    #home p {
      margin: 0;
    }

    #home .row {
      min-height: 100vh;
    }

    #home .card:hover {
      cursor: pointer;
    }

    #home .card.active {
      border: 1px solid var(--bs-dark);
    }

    #home .card:not(.active) .indicator::before {
      content: '\f28a';
      font-family: bootstrap-icons !important;
      color: var(--bs-card-border-color);
    }

    #home .card.active .indicator::before {
      content: '\f26a';
      font-family: bootstrap-icons !important;
    }

    #home .side-section {
      background-image: url(./assets/img/side-section.jpeg);
      background-size: cover;
      background-position: center;
      min-height: 100vh;
    }
  </style>
</head>

<body>
<main>
    <section id="home">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="col-12 col-lg-6 px-lg-5">
            <div class="text-center">
              <h1>ngobat.com</h1>
              <p class="text-muted">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
            <div class="my-5">
              <div onclick="setDestination('pasien_baru.php')" class="card mb-3">
                <div class="card-body d-flex align-items-center justify-content-evenly">
                  <img src="./assets/img/pasien-baru.png" alt="pengguna baru" width="75">
                  <div class="body px-2">
                    <h4>Pasien Baru</h4>
                    <p>Belum memiliki nomer induk pasien</p>
                  </div>
                  <div class="indicator"></div>
                </div>
              </div>
              <div onclick="setDestination('pasien_lama.php')" class="card mb-3 active">
                <div class="card-body d-flex align-items-center justify-content-evenly">
                  <img src="./assets/img/pasien-lama.png" alt="pengguna lama" width="75">
                  <div class="body px-2">
                    <h4>Pasien Lama</h4>
                    <p>Sudah memiliki nomer induk pasien</p>
                  </div>
                  <div class="indicator">
                  </div>
                </div>
              </div>
            </div>

            <div class="text-center">
              <button onclick="start()" class="btn btn-dark btn-lg">Pergi</button>
            </div>
          </div>
          <div class="d-none d-lg-flex align-items-center col-lg-6 side-section p-5">
            <figure>
              <blockquote class="blockquote">
                <p class="fw-semibold">Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui eius molestiae
                  aspernatur
                  nam unde velit
                  autem voluptate facilis suscipit nostrum nulla, cum perferendis voluptatum labore ducimus. Culpa in a
                  similique.</p>

              </blockquote>
              <figcaption class="blockquote-footer mt-3">
                Someone famous in <cite title="Source Title">Source Title</cite>
              </figcaption>
            </figure>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>

  <script>
    const options = document.querySelectorAll('#home .card')
    options.forEach(el => {
      el.addEventListener('click', e => {
        options.forEach(el => { el.classList.remove('active') })
        el.classList.add('active')
      })
    })
    let destination = 'pasien_lama.php'
    const setDestination = (path) => {
      destination = path
    }
    const start = () => {
      window.location.href = destination
    }
  </script>
</body>

</html>