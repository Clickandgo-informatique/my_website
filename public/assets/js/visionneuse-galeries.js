import { lightbox } from "./lightbox.js";

const selectGaleries = document.querySelector("#selectGaleries");
const galerie = document.querySelector(".container-galerie");
const btnOuvrirGalerie = document.querySelector(".btn-ouvrir-galerie");

selectGaleries.addEventListener("change", () => {
  if (selectGaleries.value !== "") {
    const galerieId = selectGaleries.value;
    afficherGalerie(galerieId);
  } else {
    galerie.innerHTML = "";
    alert("veuillez sélectionner une galerie à afficher dans la liste.");
  }
});

async function afficherGalerie(galerieId) {
  const response = await fetch(
    "/admin/galeries/afficher-galerie-visionneuse/" + galerieId,
    {
      methods: "GET",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/json",
      },
    }
  );
  const data = await response.json();
  if (response.ok) {
    galerie.innerHTML = "";
    galerie.insertAdjacentHTML("afterbegin", data.content);
    const imagesLinks = document.querySelectorAll(".imageLink");
    lightbox(imagesLinks);
  } else {
    galerie.innerHTML = "";
    galerie.insertAdjacentHTML(
      "afterbegin",
      "Il n'existe encore aucune image pour cette galerie."
    );
  }
  //Bouton ouverture de galerie
  btnOuvrirGalerie.addEventListener("click", (e) => {
    if (selectGaleries.value !== "") {
      //Création du href du bouton pour ouvrir la page de galerie
      btnOuvrirGalerie.setAttribute(
        "href",
        "/admin/galeries/modifier-galerie/" + selectGaleries.value
      );
    } else {
      e.preventDefault();
      alert("veuillez sélectionner une galerie à afficher dans la liste.");
    }
  });
}
