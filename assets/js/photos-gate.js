/* Photos Gate (session-only), unique namespace (pg-), PDF required */
(function () {
  const SESSION_KEY = 'photos_gate_accepted_v1';

  function onReady(fn) {
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
  }

  onReady(init);

  function init() {
    const modal      = document.getElementById('pg-modal');
    const nameInput  = document.getElementById('pg-name');
    const emailInput = document.getElementById('pg-email');
    const consentEl  = document.getElementById('pg-consent-text');

    const canvas     = document.getElementById('pg-signature-pad');
    const clearBtn   = document.getElementById('pg-clear-signature');

    const btnGenEnter = document.getElementById('pg-generate-and-enter');
    const btnExit     = document.getElementById('pg-exit');
    const btnTxt      = document.getElementById('pg-download-txt');
    const btnJson     = document.getElementById('pg-download-json');
    const btnPdf      = document.getElementById('pg-download-pdf');

    const gallery    = document.getElementById('pg-gallery');

    if (!modal || !canvas || !gallery) {
      console.error('[photos-gate] Missing required elements.');
      return;
    }

    // Show or hide modal based on session
    try {
      if (sessionStorage.getItem(SESSION_KEY) === 'yes') {
        modal.style.display = 'none';
        loadGallery();
      } else {
        modal.style.display = 'flex';
      }
    } catch {
      modal.style.display = 'flex';
    }

    // Agreement text (privacy & dignity focus)
    function consentText(name, email) {
      const n = name || '[Anonymous]';
      const e = email || 'No email provided';
      return `
<h2>Purpose</h2>
<p>
  You’re about to view a personal photo gallery (mostly me and nature).
  These photos aren’t “secret,” but I ask for respectful use and responsible viewing.
  I’m an independent/non-expert site owner and I use AI to help build features.
</p>

<h3>Visitor</h3>
<p><strong>Name/Alias:</strong> ${escapeHtml(n)}<br>
<strong>Email (optional):</strong> ${escapeHtml(e)}</p>

<h3>What You Agree To</h3>
<ul>
  <li><strong>No redistribution or reuploading</strong> of my photos to other sites, apps, or feeds without my explicit permission.</li>
  <li><strong>No scraping or bulk downloading.</strong></li>
  <li><strong>No unlawful, unethical, harassing, defamatory, or harmful use</strong> of my images, likeness, or identity.</li>
  <li><strong>No training AI models</strong> with these photos without my explicit permission.</li>
  <li><strong>No deepfakes, impersonation, or misleading contexts.</strong></li>
  <li><strong>Respect attribution:</strong> If you want to reference a photo, ask first.</li>
</ul>

<h3>Fair Use & Exceptions</h3>
<ul>
  <li>Personal viewing is fine.</li>
  <li>Linking to this page (not rehosting content) is okay.</li>
  <li>If you’re a developer/security researcher: ethical testing is appreciated; please report issues responsibly.</li>
</ul>

<h3>Privacy & Data</h3>
<ul>
  <li>I don’t store your name, email, or signature. The agreement PDF is for <em>you</em>.</li>
  <li>Access is <strong>session-based</strong>; you may be asked to agree again later.</li>
</ul>

<h3>Liability</h3>
<p>
  You access at your own discretion. I’m not responsible for how third parties use the web, or for any outcomes from your choices.
</p>

<p><em>By proceeding, you promise to treat my photos and me with basic decency and care.</em></p>
      `;
    }

    // Write agreement HTML
    function refreshAgreement() {
      if (!consentEl) return;
      const n = (nameInput?.value || '').trim();
      const e = (emailInput?.value || '').trim();
      consentEl.innerHTML = consentText(n, e);
    }
    refreshAgreement();
    nameInput?.addEventListener('input', refreshAgreement);
    emailInput?.addEventListener('input', refreshAgreement);

    // Signature setup
    let signaturePad = null;
    function sizeCanvas() {
      const cssW = canvas.clientWidth || 420;
      const cssH = Math.max(120, Math.round(cssW * 0.35));
      canvas.style.height = cssH + 'px';

      const ratio = Math.max(window.devicePixelRatio || 1, 1);
      canvas.width = Math.round(cssW * ratio);
      canvas.height = Math.round(cssH * ratio);

      const ctx = canvas.getContext('2d');
      ctx.setTransform(1, 0, 0, 1, 0, 0);
      ctx.scale(ratio, ratio);
      ctx.fillStyle = '#fff';
      ctx.fillRect(0, 0, cssW, cssH);

      if (signaturePad) signaturePad.clear();
    }
    function initSignaturePad() {
      if (!window.SignaturePad) { console.error('[photos-gate] SignaturePad missing'); return; }
      sizeCanvas();
      signaturePad = new window.SignaturePad(canvas, {
        backgroundColor: 'rgb(255,255,255)',
        penColor: '#111'
      });
    }
    initSignaturePad();
    window.addEventListener('resize', () => { if (signaturePad) sizeCanvas(); });

    clearBtn?.addEventListener('click', () => {
      signaturePad?.clear();
      const ctx = canvas.getContext('2d');
      const r = canvas.getBoundingClientRect();
      ctx.fillStyle = '#fff';
      ctx.fillRect(0, 0, r.width, r.height);
    });

    // Helper utilities
    function ensureSignature() {
      if (!signaturePad) { alert('Signature pad not available.'); return null; }
      if (signaturePad.isEmpty()) { alert('Please sign before continuing.'); return null; }
      try { return signaturePad.toDataURL('image/png'); }
      catch (e) { console.warn(e); alert('Cannot read signature.'); return null; }
    }
    function filename(ext) {
      return `photos-access-agreement_${new Date().toISOString().replace(/[:.]/g, '-')}.${ext}`;
    }
    function triggerDownload(blob, fname) {
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url; a.download = fname;
      document.body.appendChild(a);
      a.click();
      a.remove();
      URL.revokeObjectURL(url);
    }
    function esc(s){ return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c])); }
    function textAgreement() {
      const n = (nameInput?.value || '').trim() || 'Anonymous';
      const e = (emailInput?.value || '').trim() || 'No email provided';
      // Strip tags from consentEl for TXT/JSON
      const tmp = document.createElement('div');
      tmp.innerHTML = consentText(n, e);
      const text = tmp.textContent || tmp.innerText || '';
      return `Visitor: ${n}\nEmail: ${e}\n\n${text}`;
    }

    // Load gallery only after server session is set
    function loadGallery() {
      fetch('../../templates/gallery-data.php', { credentials: 'same-origin' })
        .then(r => r.ok ? r.text() : Promise.reject(r.status))
        .then(html => { gallery.innerHTML = html; })
        .catch(() => { gallery.innerHTML = '<p style="color:#c00">Could not load photos.</p>'; });
    }

    // Set PHP session flag
    function setServerSession() {
      return fetch('../../secure/set-photos-gate.php', { method: 'POST', credentials: 'same-origin' });
    }

    // Generate PDF (jsPDF preferred; no duplicate)
    function generatePdf(sigDataUrl) {
      const hasJsPDF = !!(window.jspdf && (window.jspdf.jsPDF || window.jspdf.default));
      const n = (nameInput?.value || '').trim() || 'Anonymous';
      const e = (emailInput?.value || '').trim() || 'No email provided';
      const html = consentText(n, e);

      if (hasJsPDF) {
        const JsPDF = window.jspdf.jsPDF || window.jspdf.default;
        const doc = new JsPDF({ unit: 'mm', format: 'a4' });
        const margin = 15;
        const pageW = doc.internal.pageSize.getWidth();
        const pageH = doc.internal.pageSize.getHeight();
        const usableW = pageW - margin * 2;
        let y = margin;

        doc.setFont('helvetica', 'bold'); doc.setFontSize(18);
        doc.text('Photos Access Agreement', margin, y); y += 8;

        doc.setFont('helvetica', 'normal'); doc.setFontSize(12);
        doc.text('resteerjohn.com', margin, y); y += 10;

        doc.setDrawColor(0); doc.setLineWidth(0.3);
        doc.line(margin, y, pageW - margin, y); y += 6;

        doc.setFontSize(10); doc.setTextColor(100);
        doc.text(`Visitor: ${n}`, margin, y); y += 5;
        doc.text(`Email: ${e}`, margin, y); y += 5;
        doc.text(`Generated: ${new Date().toLocaleString()}`, margin, y); y += 8;

        // Convert HTML agreement to plain lines
        doc.setFontSize(12); doc.setTextColor(0);
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        const plain = tmp.textContent || tmp.innerText || '';
        const lines = doc.splitTextToSize(plain, usableW);
        for (const line of lines) {
          if (y > pageH - 30) { doc.addPage(); y = margin; }
          doc.text(line, margin, y); y += 6;
        }
        y += 4;

        // Signature add
        const cssW = canvas.clientWidth || 400;
        const cssH = parseFloat(getComputedStyle(canvas).height) || 140;
        const ratio = cssH / cssW;
        const imgWmm = Math.min(70, usableW);
        const imgHmm = Math.max(18, imgWmm * ratio);
        if (y + imgHmm + 20 > pageH) { doc.addPage(); y = margin; }

        doc.setFontSize(10);
        doc.text('Signature:', margin, y); y += 5;
        doc.addImage(sigDataUrl, 'PNG', margin, y, imgWmm, imgHmm); y += imgHmm + 6;

        doc.setFontSize(8); doc.setTextColor(140);
        doc.text(doc.splitTextToSize('This file is kept by the visitor. The site does not store your name, email, or signature.', usableW), margin, y);

        doc.save(filename('pdf'));
        return true;
      }

      // Fallback printable window (only if jsPDF missing)
      const w = window.open('', '_blank', 'noopener,noreferrer');
      if (!w) { alert('Popup blocked. Allow popups or use TXT/JSON.'); return false; }
      const escHtml = (s) => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c]));
      w.document.open();
      w.document.write(`<!doctype html><html><head><meta charset="utf-8"><title>Agreement</title>
<style>
body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;margin:24px;color:#111}
img{max-width:320px;border:1px solid #ddd;padding:6px;border-radius:6px;margin-top:8px}
h1{margin:0 0 6px}
</style></head><body>
<h1>Photos Access Agreement</h1>
<div>${html}</div>
<div><strong>Signature:</strong><br><img src="${escHtml(sigDataUrl)}" alt="signature"></div>
<script>window.onload=function(){setTimeout(function(){window.print()},120)}</script>
</body></html>`);
      w.document.close();
      return true;
    }

    // Buttons
    btnGenEnter?.addEventListener('click', () => {
      const name = (nameInput?.value || '').trim();
      if (!name) { alert('Please enter your name or alias.'); return; }

      const sig = ensureSignature(); if (!sig) return;

      // 1) Generate PDF (required)
      const ok = generatePdf(sig);
      if (!ok) return;

      // 2) Set front-end session
      try { sessionStorage.setItem(SESSION_KEY, 'yes'); } catch {}

      // 3) Set server session, then load gallery and close modal
      setServerSession()
        .finally(() => {
          modal.style.display = 'none';
          loadGallery();
        });
    });

    btnExit?.addEventListener('click', () => {
      window.location.href = '/';
    });

    // Optional extra downloads
    btnTxt?.addEventListener('click', () => {
      const sig = ensureSignature(); if (!sig) return;
      const txt = textAgreement() + '\n\n[Signature image is not included in TXT format]';
      const blob = new Blob([txt], { type: 'text/plain;charset=utf-8' });
      triggerDownload(blob, filename('txt'));
    });

    btnJson?.addEventListener('click', () => {
      const sig = ensureSignature(); if (!sig) return;
      const payload = {
        name: (nameInput?.value || '').trim() || null,
        email: (emailInput?.value || '').trim() || null,
        consentText: textAgreement(),
        signatureDataUrl: sig,
        timestamp: new Date().toISOString()
      };
      const blob = new Blob([JSON.stringify(payload, null, 2)], { type: 'application/json;charset=utf-8' });
      triggerDownload(blob, filename('json'));
    });

    btnPdf?.addEventListener('click', () => {
      const sig = ensureSignature(); if (!sig) return;
      generatePdf(sig);
    });

    // Escape helper
    function escapeHtml(s) {
      return String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c]));
    }
  }
})();