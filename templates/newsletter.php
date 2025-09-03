<section class="newsletter section">
  <div class="container">
    <h2 class="title section-title" data-name="Newsletter">Newsletter</h2>
    <div class="form-container-inner">
      <h6 class="title newsletter-title">Subscribe to Resteer</h6>
      <p class="newsletter-description">
        Want to hear my nonsense? I'm a kinda professional yapper haha.
        This newsletter is <strong>100% privacy-by-default</strong> — no tracking, no third parties, no storage until you choose to send your email.
        💡 Tip: For maximum privacy, you can use an alias or disposable address that forwards to your inbox (e.g. <em>contact.language224@passinbox.com</em>).
      </p>

      <form id="newsletter-form" class="form" autocomplete="off" novalidate>
        <input id="email-input" class="form-input" type="email" placeholder="Enter your email address or alias" required />
        <button class="btn form-btn" type="submit" aria-label="Open consent and subscribe">
          <i class="ri-mail-send-line" aria-hidden="true"></i>
        </button>
      </form>
    </div>
  </div>
</section>

<!-- Consent Modal -->
<div id="consent-modal" style="display:none;">
  <div id="consent-content" role="dialog" aria-modal="true" aria-labelledby="consent-title">
    <h3 id="consent-title">Read Me First</h3>
    <pre id="consent-text"></pre>

    <div class="consent-actions">
      <div class="download-actions">
        <button id="download-pdf-btn" class="btn" type="button">Download My Consent (PDF)</button>
        <button id="download-txt-btn" class="btn" type="button">Download as .txt</button>
      </div>
      <div class="decision-actions">
        <button id="agree-btn" class="btn" type="button">I Agree</button>
        <button id="cancel-btn" class="btn" type="button">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script> 
(function() {
  const recipient = "contact.language224@passinbox.com"; // Receiving alias
  let pendingEmail = "";

  const form = document.getElementById('newsletter-form');
  const modal = document.getElementById('consent-modal');
  const consentTextEl = document.getElementById('consent-text');

  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const email = document.getElementById('email-input').value.trim();
    if (!email || !email.includes("@")) {
      alert('Please enter a valid email or alias (must contain "@").');
      return;
    }
    pendingEmail = email;
    consentTextEl.textContent = getConsentText(email);
    openModal();
  });

  document.getElementById('agree-btn').addEventListener('click', function() {
    sendSubscription(pendingEmail);
    closeModal();
  });

  document.getElementById('cancel-btn').addEventListener('click', function() {
    pendingEmail = "";
    closeModal();
  });

  document.getElementById('download-pdf-btn').addEventListener('click', function() {
  const consent = consentTextEl.textContent;
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();

  // === Styles ===
  const margin = 15;
  let y = margin;

  // Title
  doc.setFont("helvetica", "bold");
  doc.setFontSize(18);
  doc.text("Newsletter Consent", margin, y);
  y += 8;

  // Subtitle
  doc.setFont("helvetica", "normal");
  doc.setFontSize(12);
  doc.text("Resteer John L. Lumbab – resteerjohn.com", margin, y);
  y += 10;

  // Horizontal line
  doc.setDrawColor(0);
  doc.setLineWidth(0.5);
  doc.line(margin, y, 200 - margin, y);
  y += 6;

  // Metadata
  doc.setFontSize(10);
  doc.setTextColor(100);
  doc.text(`Subscriber: ${pendingEmail}`, margin, y); y += 5;
  doc.text(`Generated: ${new Date().toLocaleString()}`, margin, y);
  y += 8;

  // Main consent text
  doc.setTextColor(0);
  doc.setFontSize(12);
  const splitConsent = doc.splitTextToSize(consent, 180);
  doc.text(splitConsent, margin, y);
  y += splitConsent.length * 6 + 5;

  // Signature line
  y += 10;
  doc.setDrawColor(50);
  doc.line(margin, y, margin + 80, y);
  y += 5;
  doc.setFontSize(10);
  doc.text("Signature (optional)", margin, y);

  // Save
  doc.save(filenameForConsent(pendingEmail, "pdf"));
});

  document.getElementById('download-txt-btn').addEventListener('click', function() {
    const consent = consentTextEl.textContent;
    downloadText(consent, filenameForConsent(pendingEmail, "txt"));
  });

  function openModal() { modal.style.display = 'flex'; }
  function closeModal() { modal.style.display = 'none'; }

  function getConsentText(email) {
    return `I, ${email}, am fully aware and consciously agree to voluntarily subscribe to the newsletters of Resteer John L. Lumbab, owner of resteerjohn.com.

I confirm that:
1. I am personally entering my email or alias without coercion, automation, or third-party involvement.
2. No one else, no service, and no tracker is involved in this subscription process.
3. My email (or alias) will be sent directly from my own email provider to Resteer John L. Lumbab.
4. I understand I may use a privacy-protecting alias such as contact.language224@passinbox.com or any disposable/forwarding address I control.
5. I can unsubscribe at any time simply by emailing "Unsubscribe" to the same address.

This is 100% private, offline until I choose to send my email. No hidden scripts, no analytics, no storage by resteerjohn.com until I send my message.`;
  }

  function filenameForConsent(email, ext) {
    const safe = (email || "newsletter").replace(/[^a-z0-9._-]+/gi, "_");
    return `resteer-consent_${safe}.${ext}`;
  }

  function openPrintWindow(consentText, email) {
    const w = window.open("", "_blank", "noopener,noreferrer");
    if (!w) {
      alert("Popup blocked. Please allow popups to download as PDF.");
      return;
    }
    const title = "Newsletter Consent – Resteer John L. Lumbab";
    const now = new Date().toISOString();
    w.document.open();
    w.document.write(`<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>${title}</title>
<style>
  body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif; color:#111; margin: 40px; }
  h1 { font-size: 20px; margin: 0 0 8px; }
  .meta { font-size: 12px; color: #444; margin-bottom: 16px; }
  pre { white-space: pre-wrap; word-wrap: break-word; background:#f7f7f9; border:1px solid #ececf1; padding:16px; border-radius:8px; font-size:14px; line-height:1.45; }
  @media print { body { margin: 10mm; } }
</style>
</head>
<body>
  <h1>${title}</h1>
  <div class="meta">
    Subscriber: ${email ? escapeHtml(email) : ""}<br>
    Generated: ${now}
  </div>
  <pre>${escapeHtml(consentText)}</pre>
  <script>
    window.onload = function(){ setTimeout(function(){ window.print(); }, 50); };
  <\/script>
</body>
</html>`);
    w.document.close();
  }

  function sendSubscription(email) {
    const subject = encodeURIComponent("I want to subscribe to your newsletter");
    const bodyText = getConsentText(email);
    const body = encodeURIComponent(bodyText);
    const domain = (email.split("@")[1] || "").toLowerCase();

    let url = "";
    if (domain.includes("gmail.com")) {
      url = `https://mail.google.com/mail/?view=cm&fs=1&to=${recipient}&su=${subject}&body=${body}`;
    } else if (domain.includes("yahoo.com")) {
      url = `https://compose.mail.yahoo.com/?to=${recipient}&subject=${subject}&body=${body}`;
    } else if (domain.includes("outlook.com") || domain.includes("hotmail.com") || domain.includes("live.com")) {
      url = `https://outlook.live.com/owa/?path=/mail/action/compose&to=${recipient}&subject=${subject}&body=${body}`;
    } else if (domain.includes("proton.me") || domain.includes("protonmail.com")) {
      alert("Proton Mail users: Please copy the consent text and paste it into a new message — Proton blocks prefilled compose.");
      copyToClipboard(bodyText);
      return;
    } else {
      url = `mailto:${recipient}?subject=${subject}&body=${body}`;
    }

    window.location.href = url;
  }

  function copyToClipboard(text) {
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(text).then(
        () => alert("Consent text copied! Open your email app, paste, and send."),
        () => alert("Could not copy automatically. Please copy manually.")
      );
    } else {
      const ta = document.createElement('textarea');
      ta.value = text;
      document.body.appendChild(ta);
      ta.select(); document.execCommand('copy');
      document.body.removeChild(ta);
      alert("Consent text copied! Open your email app, paste, and send.");
    }
  }

  function downloadText(text, filename) {
    const blob = new Blob([text], { type: "text/plain;charset=utf-8" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url; a.download = filename;
    document.body.appendChild(a);
    a.click();
    a.remove(); URL.revokeObjectURL(url);
  }

  function escapeHtml(s) {
    return String(s).replace(/[&<>"']/g, ch => (
      { "&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;", "'":"&#39;" }[ch]
    ));
  }
})();

</script>




