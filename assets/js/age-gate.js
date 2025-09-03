/* privacy-first age gate (session-only), robust signature & downloads */
(function () {
  const SESSION_KEY = 'age_gate_accepted_v1'; // session-only; clears when tab/Browser closed

  function onReady(fn) {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  onReady(function init() {
    // Elements
    const modal = document.getElementById('age-gate-modal');
    const agreeBtn = document.getElementById('age-gate-agree');
    const exitBtn = document.getElementById('age-gate-exit');
    const consentTextEl = document.getElementById('age-gate-consent-text');

    const nameInput = document.getElementById('visitor-name');
    const emailInput = document.getElementById('visitor-email');

    const canvas = document.getElementById('signature-pad');
    const clearSigBtn = document.getElementById('clear-signature');

    const txtBtn = document.getElementById('download-txt-btn');
    const jsonBtn = document.getElementById('download-json-btn');
    const pdfBtn = document.getElementById('download-pdf-btn');

    if (!modal || !canvas) {
      console.error('[age-gate] Required elements missing.');
      return;
    }

    // Session: show again on new session; hide if already accepted in this session
    try {
      if (sessionStorage.getItem(SESSION_KEY) === 'yes') {
        modal.style.display = 'none';
      } else {
        modal.style.display = 'flex';
      }
    } catch {
      // if sessionStorage blocked, always show
      modal.style.display = 'flex';
    }

    // Consent text
    function consentText(name, email) {
      return `I, ${name || "[Anonymous]"} (${email || "No email provided"}), confirm that I am at least 18 years old (or of legal age in my jurisdiction) and I voluntarily access the content of resteerjohn.com.

I fully understand and agree that:
1. This website may contain topics, language, humor, or perspectives intended for mature audiences.
2. The owner, Resteer John L. Lumbab, is not responsible for any emotional, psychological, or cognitive impact caused by viewing the content.
3. I am accessing this website entirely by my own free will.
4. I have been informed of my right to exit without consuming any content.
5. I am aware that the agreement will be shown every time I re-enter the website (session-based).
6. No personal data is stored by the site; all agreement records remain in my control unless I download them.
7. I can optionally use an alias email (like contact.language224@passinbox.com) for privacy.
8. I cannot hold the website or its owner legally liable for any damages, losses, or consequences arising from my choice to proceed.

By clicking "I Agree", I acknowledge and accept these terms without coercion.`;
    }

    // Live preview of agreement text
    function refreshConsentPreview() {
      if (!consentTextEl) return;
      const n = (nameInput?.value || '').trim();
      const e = (emailInput?.value || '').trim();
      consentTextEl.textContent = consentText(n, e);
    }
    refreshConsentPreview();
    nameInput?.addEventListener('input', refreshConsentPreview);
    emailInput?.addEventListener('input', refreshConsentPreview);

    // SignaturePad init with high-DPI scaling and white background
    let signaturePad = null;

    function sizeCanvas() {
      // Compute CSS size
      const cssW = canvas.clientWidth || 400;
      const cssH = Math.max(120, Math.round(cssW * 0.35)); // aspect ~ 1:0.35, min 120px high

      // Apply CSS height for layout
      canvas.style.height = cssH + 'px';

      // DPR scale
      const ratio = Math.max(window.devicePixelRatio || 1, 1);
      canvas.width = Math.round(cssW * ratio);
      canvas.height = Math.round(cssH * ratio);

      const ctx = canvas.getContext('2d');
      ctx.setTransform(1, 0, 0, 1, 0, 0);
      ctx.scale(ratio, ratio);
      ctx.fillStyle = '#fff';
      ctx.fillRect(0, 0, cssW, cssH);

      // clearing the pad to sync after resize
      if (signaturePad) signaturePad.clear();
    }

    function initSignaturePad() {
      if (!window.SignaturePad) {
        console.error('[age-gate] SignaturePad library not found.');
        return;
      }
      sizeCanvas();
      signaturePad = new window.SignaturePad(canvas, {
        backgroundColor: 'rgb(255,255,255)', // ensures non-transparent PNG
        penColor: '#111'
      });
    }

    initSignaturePad();
    window.addEventListener('resize', function () {
      // Only resize if modal is visible or signature pad exists
      if (signaturePad) sizeCanvas();
    });

    // Clear signature
    clearSigBtn?.addEventListener('click', function () {
      signaturePad?.clear();
      // re-fill white in case lib clears to transparent
      const ctx = canvas.getContext('2d');
      const rect = canvas.getBoundingClientRect();
      ctx.fillStyle = '#fff';
      ctx.fillRect(0, 0, rect.width, rect.height);
    });

    // Agree / Exit
    agreeBtn?.addEventListener('click', function () {
      const name = (nameInput?.value || '').trim();
      const email = (emailInput?.value || '').trim();

      if (!name) {
        alert('Please enter your name or alias.');
        return;
      }
      if (!signaturePad || signaturePad.isEmpty()) {
        alert('Please sign in the box before entering.');
        return;
      }

      refreshConsentPreview();

      try { sessionStorage.setItem(SESSION_KEY, 'yes'); } catch {}

      modal.style.display = 'none';
    });

    exitBtn?.addEventListener('click', function () {
      window.location.href = 'https://google.com';
    });

    // Helpers for downloads
    function ensureSignatureDataURL() {
      if (!signaturePad) {
        alert('Signature pad not available.');
        return null;
      }
      if (signaturePad.isEmpty()) {
        alert('Please sign before downloading.');
        return null;
      }
      try {
        return signaturePad.toDataURL('image/png');
      } catch (e) {
        console.warn('[age-gate] toDataURL failed', e);
        alert('Could not read signature. Try again.');
        return null;
      }
    }

    function currentConsentText() {
      // Always use the freshest info
      const n = (nameInput?.value || '').trim();
      const e = (emailInput?.value || '').trim();
      const txt = consentText(n, e);
      if (consentTextEl) consentTextEl.textContent = txt;
      return txt;
    }

    function triggerDownload(blob, filename) {
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url; a.download = filename;
      document.body.appendChild(a);
      a.click();
      a.remove();
      URL.revokeObjectURL(url);
    }

    function filename(ext) {
      return `age-gate-agreement_${new Date().toISOString().replace(/[:.]/g, '-')}.${ext}`;
    }

    // TXT
    txtBtn?.addEventListener('click', function () {
      const sig = ensureSignatureDataURL(); if (!sig) return;
      const txt = currentConsentText() + '\n\n[Signature image is not included in TXT format]';
      const blob = new Blob([txt], { type: 'text/plain;charset=utf-8' });
      triggerDownload(blob, filename('txt'));
    });

    // JSON
    jsonBtn?.addEventListener('click', function () {
      const sig = ensureSignatureDataURL(); if (!sig) return;
      const payload = {
        name: (nameInput?.value || '').trim() || null,
        email: (emailInput?.value || '').trim() || null,
        consentText: currentConsentText(),
        signatureDataUrl: sig,
        timestamp: new Date().toISOString()
      };
      const blob = new Blob([JSON.stringify(payload, null, 2)], { type: 'application/json;charset=utf-8' });
      triggerDownload(blob, filename('json'));
    });

    // PDF (jsPDF UMD) with fallback print window
    pdfBtn?.addEventListener('click', function () {
      const sig = ensureSignatureDataURL(); if (!sig) return;
      const txt = currentConsentText();

      const hasJsPDF = !!(window.jspdf && (window.jspdf.jsPDF || window.jspdf.default));
      if (hasJsPDF) {
        try {
          const JsPDF = window.jspdf.jsPDF || window.jspdf.default;
          const doc = new JsPDF({ unit: 'mm', format: 'a4' });
          const margin = 15;
          const pageW = doc.internal.pageSize.getWidth();
          const pageH = doc.internal.pageSize.getHeight();
          const usableW = pageW - margin * 2;
          let y = margin;

          doc.setFont('helvetica', 'bold');
          doc.setFontSize(18);
          doc.text('Age Verification Agreement', margin, y); y += 8;

          doc.setFont('helvetica', 'normal');
          doc.setFontSize(12);
          doc.text('Resteer John L. Lumbab – resteerjohn.com', margin, y); y += 10;

          doc.setDrawColor(0); doc.setLineWidth(0.3);
          doc.line(margin, y, pageW - margin, y); y += 6;

          doc.setFontSize(10); doc.setTextColor(100);
          const nm = (nameInput?.value || '').trim() || 'Anonymous';
          const em = (emailInput?.value || '').trim() || 'Not provided';
          doc.text(`Visitor: ${nm}`, margin, y); y += 5;
          doc.text(`Email: ${em}`, margin, y); y += 5;
          doc.text(`Generated: ${new Date().toLocaleString()}`, margin, y); y += 8;

          doc.setTextColor(0); doc.setFontSize(12);
          const lines = doc.splitTextToSize(txt, usableW);
          // Add text with page break support
          lines.forEach(line => {
            if (y > pageH - 30) { doc.addPage(); y = margin; }
            doc.text(line, margin, y);
            y += 6;
          });
          y += 4;

          // Signature sizing (keep aspect ratio, avoid overflow)
          const cssW = canvas.clientWidth || 400;
          const cssH = parseFloat(window.getComputedStyle(canvas).height) || 140;
          const ratio = cssH / cssW; // aspect from CSS size
          const imgWmm = Math.min(70, usableW); // cap width
          const imgHmm = Math.max(18, imgWmm * ratio); // at least 18mm high

          if (y + imgHmm + 20 > pageH) { doc.addPage(); y = margin; }

          doc.setFontSize(10);
          doc.text('Signature:', margin, y); y += 5;
          doc.addImage(sig, 'PNG', margin, y, imgWmm, imgHmm);
          y += imgHmm + 6;

          doc.setFontSize(8); doc.setTextColor(140);
          const footer = 'This agreement is retained solely by the visitor. resteerjohn.com does not store this file or any personal data.';
          doc.text(doc.splitTextToSize(footer, usableW), margin, y);

          doc.save(filename('pdf'));
          return;
        } catch (e) {
          console.warn('[age-gate] jsPDF failed, fallback to print window', e);
        }
      }

      // Fallback: printable window
      const w = window.open('', '_blank', 'noopener,noreferrer');
      if (!w) { alert('Popup blocked. Allow popups or use TXT/JSON.'); return; }
      const esc = s => String(s).replace(/[&<>"']/g, ch => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[ch]));
      w.document.open();
      w.document.write(`<!doctype html><html><head><meta charset="utf-8"><title>Agreement</title>
<style>
body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;margin:24px;color:#111}
img{max-width:320px;border:1px solid #ddd;padding:6px;border-radius:6px;margin-top:8px}
</style></head><body>
<h1>Age Verification Agreement</h1>
<div>${esc(txt).replace(/\n/g,'<br>')}</div>
<div><strong>Signature:</strong><br><img src="${sig}" alt="signature"></div>
<script>window.onload=function(){setTimeout(function(){window.print()},120)}</script>
</body></html>`);
      w.document.close();
    });
  });
})();