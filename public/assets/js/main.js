import { draggable } from "./dynamic-element.js";
draggable();
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

//Application des themes (dark ...)
const THEME_STORAGE_KEY = "theme";
const cachedTheme = localStorage.getItem(THEME_STORAGE_KEY);

const themePicker = document.querySelector(".theme-switcher");
// if (!themePicker){ return false} ;

const initialTheme = cachedTheme ?? "auto";
themePicker.querySelector("input[checked]").removeAttribute("checked");
themePicker
  .querySelector(`input[value="${initialTheme}"]`)
  .setAttribute("checked", "");

themePicker.addEventListener("change", (e) => {
  const theme = e.target.value;
  if (theme === "auto") {
    localStorage.removeItem(THEME_STORAGE_KEY);
  } else {
    localStorage.setItem(THEME_STORAGE_KEY, theme);
  }
});
//Slide-up slide-down d'un element
//Sélection du control qui permet l'action de toggle
const expandableControl = document.querySelector(".expandable-control");
if (expandableControl != undefined) {
  expandableControl.addEventListener("change", () => {
    //Sélection de l'enfant
    const expandable = document.querySelector(".expandable");
    expandable.classList.toggle("expandable-show");
  });
}
