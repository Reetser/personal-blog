/* Developer Actions Agreement JS - Formatted Version */

(function() {
  const SESSION_KEY = 'dev_gate_accepted_v2';
  let devtoolsOpen = false;

  const modal = document.getElementById('dev-gate-modal');
  const consentEl = document.getElementById('dev-gate-consent-text');
  const nameInput = document.getElementById('dev-name');
  const emailInput = document.getElementById('dev-email');
  const canvas = document.getElementById('dev-signature-pad');
  const clearSigBtn = document.getElementById('dev-clear-signature');
  const agreeBtn = document.getElementById('dev-agree');
  const exitBtn = document.getElementById('dev-exit');

  const txtBtn = document.getElementById('dev-download-txt-btn');
  const jsonBtn = document.getElementById('dev-download-json-btn');
  const pdfBtn = document.getElementById('dev-download-pdf-btn');

  let signaturePad;

  function onReady(fn){
    if(document.readyState==='loading') document.addEventListener('DOMContentLoaded', fn);
    else fn();
  }

  onReady(function(){
    if(sessionStorage.getItem(SESSION_KEY)==='yes') return;

    // Initialize signature pad
    function sizeCanvas(){
      const cssW = canvas.clientWidth || 400;
      const cssH = Math.max(120, Math.round(cssW*0.35));
      canvas.style.height = cssH+'px';
      const ratio = Math.max(window.devicePixelRatio||1,1);
      canvas.width = Math.round(cssW*ratio);
      canvas.height = Math.round(cssH*ratio);
      const ctx = canvas.getContext('2d');
      ctx.setTransform(1,0,0,1,0,0);
      ctx.scale(ratio,ratio);
      ctx.fillStyle = '#fff';
      ctx.fillRect(0,0,cssW,cssH);
      if(signaturePad) signaturePad.clear();
    }

    function initSignaturePad(){
      if(!window.SignaturePad) return;
      sizeCanvas();
      signaturePad = new window.SignaturePad(canvas,{
        backgroundColor:'rgb(255,255,255)',
        penColor:'#111'
      });
    }

    initSignaturePad();
    window.addEventListener('resize', sizeCanvas);
    clearSigBtn.addEventListener('click',()=>{
      if(signaturePad) signaturePad.clear();
      const ctx = canvas.getContext('2d');
      const rect = canvas.getBoundingClientRect();
      ctx.fillStyle = '#fff';
      ctx.fillRect(0,0,rect.width,rect.height);
    });

function consentText(name, email) {
  const visitor = name || "[Anonymous]";
  const visitorEmail = email || "No email provided";

  return `
=============================
     DEVELOPER ACTIONS AGREEMENT
=============================

Visitor: ${visitor}
Email: ${visitorEmail}

I acknowledge that I am interacting with this website in ways that may involve:
  • Inspecting code
  • Using developer tools
  • Debugging
  • Switching device views

POSSIBLE REASONS FOR THIS ACTION:

1. Bypass Age Verification
   - Attempting to circumvent age restriction is prohibited.
   - The content is intended for mature audiences.

2. Inspect or Check Code
   - You might be a developer exploring code, learning, or testing features.
   - If you find issues, bugs, or security gaps, report them ethically to the site owner.

3. Reverse Engineering
   - Copying, reproducing, or modifying site content without permission is forbidden.
   - Only analyze with explicit approval.

4. Responsive / Device Testing
   - Switching between mobile/desktop for testing is acceptable.
   - Consent must remain acknowledged.

IMPORTANT NOTES:
  • Actions may have legal or ethical consequences.
  • Respect the website owner and other users.
  • Pseudonymous identity is allowed for reporting or research.
  • By signing below, I confirm that I understand and accept responsibility for my actions.

-----------------------------
This agreement is retained solely by the visitor.
resteerjohn.com does not store this file or any personal data.
-----------------------------
`;
}

    function refreshConsentPreview(){
      if(!consentEl) return;
      consentEl.textContent = consentText(nameInput.value.trim(), emailInput.value.trim());
    }

    nameInput.addEventListener('input',refreshConsentPreview);
    emailInput.addEventListener('input',refreshConsentPreview);
    refreshConsentPreview();

    function showModal(){ 
      modal.style.display='flex'; 
      refreshConsentPreview(); 
    }

    // Detect DevTools or device mode change (optimized)
    let lastWidth=window.innerWidth, lastHeight=window.innerHeight;
    setInterval(()=>{
      const widthDiff = Math.abs(window.outerWidth - window.innerWidth) > 160;
      const heightDiff = Math.abs(window.outerHeight - window.innerHeight) > 160;
      const deviceChange = window.innerWidth!==lastWidth;
      if(widthDiff || heightDiff || deviceChange){
        if(!devtoolsOpen) showModal();
        devtoolsOpen = true;
      }
      lastWidth=window.innerWidth;
      lastHeight=window.innerHeight;
    },500);

    // Agree / Exit
    agreeBtn.addEventListener('click',()=>{
      const name = nameInput.value.trim();
      if(!name){ alert('Please enter your name or alias.'); return; }
      if(!signaturePad || signaturePad.isEmpty()){ alert('Please sign before continuing.'); return; }
      sessionStorage.setItem(SESSION_KEY,'yes');
      modal.style.display='none';
    });

    exitBtn.addEventListener('click',()=> window.location.href='https://google.com');

    function ensureSignatureDataURL(){
      if(!signaturePad){ alert('Signature pad not available'); return null; }
      if(signaturePad.isEmpty()){ alert('Please sign before downloading.'); return null; }
      return signaturePad.toDataURL('image/png');
    }

    function currentConsentText(){ return consentText(nameInput.value.trim(), emailInput.value.trim()); }

    function triggerDownload(blob,fname){
      const url=URL.createObjectURL(blob);
      const a=document.createElement('a');
      a.href=url;
      a.download=fname;
      document.body.appendChild(a);
      a.click();
      a.remove();
      URL.revokeObjectURL(url);
    }

    function filename(ext){ return `dev-gate-agreement_${new Date().toISOString().replace(/[:.]/g,'-')}.${ext}`; }

    txtBtn.addEventListener('click',()=>{
      const sig=ensureSignatureDataURL(); if(!sig)return;
      const txt=currentConsentText()+'\n\n[Signature image is not included in TXT format]';
      triggerDownload(new Blob([txt],{type:'text/plain;charset=utf-8'}),filename('txt'));
    });

    jsonBtn.addEventListener('click',()=>{
      const sig=ensureSignatureDataURL(); if(!sig)return;
      const payload={
        name:nameInput.value||null,
        email:emailInput.value||null,
        consentText:currentConsentText(),
        signatureDataUrl:sig,
        timestamp:new Date().toISOString()
      };
      triggerDownload(new Blob([JSON.stringify(payload,null,2)],{type:'application/json;charset=utf-8'}),filename('json'));
    });

    pdfBtn.addEventListener('click',()=>{
      const sig=ensureSignatureDataURL(); if(!sig)return;
      const txt=currentConsentText();
      if(!(window.jspdf && (window.jspdf.jsPDF||window.jspdf.default))){ alert('jsPDF not loaded'); return; }
      try{
        const JsPDF = window.jspdf.jsPDF||window.jspdf.default;
        const doc = new JsPDF({unit:'mm',format:'a4'});
        const margin=15, pageW=doc.internal.pageSize.getWidth(), pageH=doc.internal.pageSize.getHeight(), usableW=pageW-margin*2;
        let y=margin;

        doc.setFont('helvetica','bold'); 
        doc.setFontSize(18); 
        doc.text('Developer Actions Agreement',margin,y); y+=10;

        doc.setFont('helvetica','normal'); 
        doc.setFontSize(12); 
        doc.text('Resteer John L. Lumbab – resteerjohn.com',margin,y); y+=10;

        doc.setDrawColor(0); doc.setLineWidth(0.3); doc.line(margin,y,pageW-margin,y); y+=6;

        doc.setFontSize(10); doc.setTextColor(100);
        doc.text(`Visitor: ${nameInput.value||'Anonymous'}`,margin,y); y+=5;
        doc.text(`Email: ${emailInput.value||'Not provided'}`,margin,y); y+=5;
        doc.text(`Generated: ${new Date().toLocaleString()}`,margin,y); y+=8;

        doc.setFontSize(12); doc.setTextColor(0);
        const lines=doc.splitTextToSize(txt,usableW);
        lines.forEach(line=>{ if(y>pageH-30){doc.addPage(); y=margin;} doc.text(line,margin,y); y+=6; }); y+=4;

        const cssW=canvas.clientWidth||400; 
        const cssH=parseFloat(window.getComputedStyle(canvas).height)||140;
        const ratio=cssH/cssW; 
        const imgWmm=Math.min(70,usableW); 
        const imgHmm=Math.max(18,imgWmm*ratio);
        if(y+imgHmm+20>pageH){ doc.addPage(); y=margin; }

        doc.setFontSize(10); doc.text('Signature:',margin,y); y+=5; 
        doc.addImage(sig,'PNG',margin,y,imgWmm,imgHmm); y+=imgHmm+6;

        doc.setFontSize(8); doc.setTextColor(140); 
        doc.text(doc.splitTextToSize('This agreement is retained solely by the visitor. resteerjohn.com does not store this file or any personal data.',usableW),margin,y);

        doc.save(filename('pdf'));
      }catch(e){ alert('PDF generation failed'); console.error(e); }
    });

  });
})();