
<?php
session_start();
include '/../secure/set-photos-gate.php'; // path adjusted based on location
?>

    <?php include("../templates/header.php") ?>

<section class="section section-header-offset ">
  
    <!-- Gallery container: filled only after agreement -->
  <main id="pg-gallery" class="pg-gallery" aria-live="polite">
    <div class="pg-gallery-placeholder">Loading photos…</div>
  </main>

  <!-- Photos Gate Modal -->
  <div id="pg-modal" class="pg-modal" aria-hidden="true" role="dialog" aria-labelledby="pg-title" aria-modal="true">
    <div class="pg-content">
      <h1 id="pg-title" class="pg-title">Photos Access Agreement</h1>

      <p class="pg-intro">
        Before viewing my photos (mostly me and nature), please read this agreement.  
        It’s not about secrecy — it’s about respecting my <strong>privacy, dignity, and boundaries</strong>.  
        These photos are not for redistribution, scraping, or misuse.
      </p>

      <div class="pg-inputs">
        <label>
          Name / Alias (required)
          <input id="pg-name" type="text" autocomplete="name" placeholder="Your name or alias">
        </label>
        <label>
          Optional Email
          <input id="pg-email" type="email" autocomplete="email" placeholder="you@example.com">
        </label>
      </div>

      <div class="pg-agreement-block">
        <div class="pg-agreement" id="pg-consent-text"></div>
      </div>

      <div class="pg-sign-wrap">
        <label class="pg-sig-label">Signature (draw below)</label>
        <canvas id="pg-signature-pad"></canvas>
        <button id="pg-clear-signature" type="button" class="pg-btn pg-btn-ghost">Clear Signature</button>
      </div>

      <div class="pg-actions">
        <button id="pg-generate-and-enter" class="pg-btn pg-btn-primary">
          Generate PDF & Enter
        </button>
        <button id="pg-exit" class="pg-btn pg-btn-danger">Exit</button>
      </div>

      <div class="pg-downloads">
        <button id="pg-download-txt"  class="pg-btn pg-btn-lite">Download TXT</button>
        <button id="pg-download-json" class="pg-btn pg-btn-lite">Download JSON</button>
        <button id="pg-download-pdf"  class="pg-btn pg-btn-lite">Download PDF (again)</button>
      </div>

      <p class="pg-note">
        I don’t store your data. The PDF stays with you.  
        Access is session-based and may be requested again next time.
      </p>
    </div>
  </div>
  
</section>
  

  

    <?php include("../templates/footer.php") ?>