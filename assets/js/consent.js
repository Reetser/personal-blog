document.addEventListener("DOMContentLoaded", function () {
  const overlay = document.getElementById("consent-overlay");
  const agreeBtn = document.getElementById("consent-agree");
  const disagreeBtn = document.getElementById("consent-disagree");

  // If already agreed, hide popup
  if (localStorage.getItem("site_consent") === "true") {
    overlay.style.display = "none";
  }

  agreeBtn.addEventListener("click", function () {
    localStorage.setItem("site_consent", "true");
    overlay.style.display = "none";
  });

  disagreeBtn.addEventListener("click", function () {
    window.location.href = "https://brave.com";
  });
});