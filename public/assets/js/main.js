const btnFermerFlashbag = document.querySelectorAll(".btn-fermer-flashbag");
const flashbag = document.querySelector(".flashbag");
//Fermeture des flashbags par bouton
if (flashbag) {
  btnFermerFlashbag.forEach((btn) => {
    btn.addEventListener("click", () => {
      setTimeout(() => {
        btn.parentNode.remove();
      }, 500);
    });
  });
}

