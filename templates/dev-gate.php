<!-- Developer Actions Agreement Modal -->
<div id="dev-gate-modal" class="dev-gate-modal">
  <div class="dev-gate-content">
    <h2>Developer Actions Agreement</h2>
    <p id="dev-gate-consent-text"></p>

    <div class="dev-gate-inputs">
      <label>
        Name / Alias:
        <input type="text" id="dev-name" placeholder="Your name or alias">
      </label>
      <label>
        Email (optional):
        <input type="email" id="dev-email" placeholder="For bug reporting or feedback">
      </label>
    </div>

    <canvas id="dev-signature-pad"></canvas>
    <button id="dev-clear-signature">Clear Signature</button>

    <div class="dev-gate-actions">
      <button id="dev-agree">I Understand & Continue</button>
      <button id="dev-exit">Exit Site</button>
    </div>

    <div class="dev-gate-downloads">
      <button id="dev-download-txt-btn">Download TXT</button>
      <button id="dev-download-json-btn">Download JSON</button>
      <button id="dev-download-pdf-btn">Download PDF</button>
    </div>
  </div>
</div>