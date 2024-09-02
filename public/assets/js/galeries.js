import { lightbox } from "./lightbox.js";
import { supprimerImage } from "./images.js";

//Ouvre la div de drop d'images
const dropArea = document.querySelector(".container-drop-area");
const btnDropArea = document.querySelector(".btn-ouvrir-importation-images");
btnDropArea.addEventListener("click", () => {
  dropArea.style.display = "flex";
});
//ferme la div de drop d'images
const btnFermerDropArea = document.querySelector(".btn-fermer-drop-area");
btnFermerDropArea.addEventListener("click", () => {
  dropArea.style.display = "none";
});

//Ouvre la div des paramètres de galerie
const divParametres = document.querySelector(".parametres-galerie");
const btnOuvrirParametres = document.querySelector(
  ".btn-ouvrir-parametres-galerie"
);
btnOuvrirParametres.addEventListener("click", () => {
  divParametres.style.display = "flex";
});

//Afficher les images dans la galerie active
const formGalerie = document.querySelector("#form-galerie");
const galerie = document.querySelector(".container-galerie");
const selectTypeGalerie = document.querySelector("#galeries_type");
const inputsGalerie = document.querySelector(".inputs-galerie");
const inputsCarousel = document.querySelector(".inputs-carousel");

//Mode d'affichage de la galerie
export const afficherGalerie = () => {
  let url = "";
  if (selectTypeGalerie !== null) {
    switch (selectTypeGalerie.value) {
      case "galerie":
        url = "/admin/galeries/afficher-galerie-images";
        break;
      case "carousel":
        url = "/admin/galeries/afficher-carousel-images";
        break;
      case "jeu de photos":
        url = "/admin/galeries/afficher-jeu-de-photos";
        break;
      default:
        return false;
    }
  }
  async function afficherImages() {
    const response = await fetch(url, {
      methods: "GET",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/json",
      },
    });
    const data = await response.json();

    if (response.ok) {
      galerie.innerHTML = "";
      galerie.insertAdjacentHTML("afterbegin", data.content);

      //création du link js pour repositionnement des images
      const js = document.createElement("script");
      js.src = "/assets/js/sortable-images.js";
      js.type = "module";
      js.defer = true;
      document.body.appendChild(js);

      //création du link js carousel
      if (selectTypeGalerie.value == "carousel") {
        const linkjs = document.createElement("script");
        linkjs.src = "/assets/js/carousel.js";
        linkjs.type = "module";
        linkjs.defer = true;

        document.body.appendChild(linkjs);

        inputsCarousel.style.display = "block";
        inputsGalerie.style.display = "none";
      } else {
        inputsCarousel.style.display = "none";
        inputsGalerie.style.display = "block";
      }
      //Création du link pour jeu de photos
      if (selectTypeGalerie.value == "jeu de photos") {
        const linkMouseSlider = document.createElement("script");
        linkMouseSlider.src = "/assets/js/mouse-slider.js";
        linkMouseSlider.type = "module";
        linkMouseSlider.defer = true;

        document.body.appendChild(linkMouseSlider);
      }
      const imagesLinks = document.querySelectorAll(".imageLink");

      const btnSuppression = document.querySelectorAll(
        ".btn-suppression-image"
      );

      supprimerImage(btnSuppression);
      lightbox(imagesLinks);
    } else {
      alert("Erreur: la requête au serveur n'a rien retourné.");
    }
  }
  afficherImages();
};
afficherGalerie();

selectTypeGalerie.addEventListener("change", () => {
  afficherGalerie();
  formGalerie.submit();
});
