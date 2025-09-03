<!-- Age Gate Modal -->
<div id="age-gate-modal">
  <div class="age-gate-box">
    <h2>Age Verification & Agreement</h2>
    <p>Please read this agreement carefully before proceeding.</p>

    <label for="visitor-name">Name / Alias:</label>
    <input type="text" id="visitor-name" placeholder="Enter your name or alias">

    <label for="visitor-email">Email (optional):</label>
    <input type="email" id="visitor-email" placeholder="Enter email or leave blank">

    <div class="consent-text" id="age-gate-consent-text">
      <!-- Consent text will be generated after entering details -->
    </div>

    <div class="signature-area">
      <p><strong>Signature:</strong></p>
      <canvas id="signature-pad" width="400" height="150"></canvas>
      <button type="button" id="clear-signature">Clear Signature</button>
    </div>

    <div class="button-group">
      <button id="age-gate-agree" class="agree-btn">I Agree</button>
      <button id="age-gate-exit" class="exit-btn">Exit</button>
    </div>

    <div class="download-group">
      <p>Download a copy of your agreement:</p>
      <button id="download-txt-btn">Download TXT</button>
      <button id="download-json-btn">Download JSON</button>
      <button id="download-pdf-btn">Download PDF</button>
    </div>
  </div>
</div>