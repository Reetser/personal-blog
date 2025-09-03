<?php
session_start();

if (empty($_SESSION['photos_gate_agreed']) || $_SESSION['photos_gate_agreed'] !== true) {
  header('HTTP/1.1 403 Forbidden');
  echo "Access denied. You must agree to the Photos Access Agreement first.";
  exit;
}

header('Content-Type: text/html; charset=utf-8');
?>
<section>
    <h3>Random</h3>
    <div>
      <a class="example-image-link" href="../assets/images/default.jpeg" data-lightbox="example-1"><img class="example-image" src="../assets/images/default.jpeg" alt="image-1" /></a>
      <a class="example-image-link" href="../assets/images/default.jpeg" data-lightbox="example-2" data-title="Optional caption."><img class="example-image" src="../assets/images/default.jpeg" alt="image-1"/></img>
    </div>

    <hr />

    <h3>Travel</h3>
    <div>
      <a class="example-image-link" href="../assets/images/default.jpeg" data-lightbox="example-set" data-title="Click the right half of the image to move forward."><img class="example-image" src="../assets/images/default.jpeg" alt=""/></a>
      
      <a class="example-image-link" href="../assets/images/default.jpeg" data-lightbox="example-set" data-title="Or press the right arrow on your keyboard."><img class="example-image" src="../assets/images/default.jpeg" alt="" /></a>
      

    </div>
  </section>

  <section>