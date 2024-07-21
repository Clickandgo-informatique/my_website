import { lightbox } from "./lightbox.js";
import { parameters } from "./galeries-parameters.js";

window.onload=()=>{
//Afficher les images dans la galerie active
const galerie = document.querySelector(".container-galerie");

const selectTypeGalerie = document.querySelector("#galeries_type");

const afficherGalerie = () => {
  let url = "";
  switch (selectTypeGalerie.value) {
    case "galerie":
      url = "/admin/galeries/afficher-galerie-images";
      break;
    case "carousel":
      url = "/admin/galeries/afficher-carousel-images";
      break;
    default:
      return false;
  }

  async function afficherImages() {
    const response = await fetch(url, {
      methods: "GET",
      headers: {
        // "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/json",
      },
    });
    const data = await response.json();
    if (response.ok) {
      galerie.innerHTML = "";
      galerie.insertAdjacentHTML("afterbegin", data.content);

      const imagesLinks = document.querySelectorAll(".imageLink");
      lightbox(imagesLinks);

      //création du link js carousel
      const linkjs = document.createElement("script");
      linkjs.src = "/assets/js/carousel.js";
      linkjs.type = "module";
      // linkjs.async = true;
      linkjs.defer = true;
      document.body.appendChild(linkjs);
    } else {
      alert("Erreur: la requête au serveur n'a rien retourné.");
    }
  }
  afficherImages();
};
afficherGalerie();
selectTypeGalerie.addEventListener("change", afficherGalerie);
}