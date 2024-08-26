<?php

$email = $_GET['email'];
$totalTime = $_GET['totalTime'];
$workType = $_GET['workType'];
$price = $_GET['price'];
$regNr = $_GET['regNr'];
$name = $_GET['name'];
$mobile = $_GET['mobile'];
$date = $_GET['date'];
$serviceIDs = $_GET['serviceIDs'];
$time = $_GET['time'];
$tyres = $_GET['tyres'];

?>

<div class="container">
    <div class="row mt-4">
        <div class="jumbotron col-12">
          <h1 class="display-4">Takk for bestillingen!</h1>
          <p class="lead">Kvittering har blitt sendt til <?= $email ?></p>
          <hr class="my-4">
          <h4>Detaljer:</h4>
          <p><strong>Type arbeid:</strong> <?= $workType ?></p>
          <p><strong>Navn:</strong> <?= $name ?></p>
          <p><strong>E-post:</strong> <?= $email ?></p>
          <p><strong>Mobil:</strong> <?= $mobile ?></p>
          <p><strong>RegNr:</strong> <?= $regNr ?></p>
          <p><strong>Tilleggsservice:</strong> <?= $serviceIDs ?></p>
          <p><strong>Dato & tid:</strong> <?= $date ?> <?= $time ?></p>
          <p><strong>Bestilt av </strong> <?= $name ?></p>
          <p><strong>Beløp:</strong> kr <?= $price ?></p>
          <p class="lead">
            <a class="btn btn-success btn-lg" href="index.php" role="button">Til hovedside</a>
          </p>
        </div>
    </div>
</div>
<?php include('buyTyreModal.php'); ?>