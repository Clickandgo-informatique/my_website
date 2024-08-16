import { lightbox } from "./lightbox.js";

console.log("Mode galerie");
//Afficher les images dans la galerie active
const galerie = document.querySelector(".container-galerie");
const selectTypeGalerie = document.querySelector("#galeries_type");
const inputsGalerie = document.querySelector(".inputs-galerie");
const inputsCarousel = document.querySelector(".inputs-carousel");

//Mode d'affichage de la galerie (carousel ou simple)
const afficherGalerie = () => {
  let url = "";
  if (selectTypeGalerie !==null) {
    
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
      const imagesLinks = document.querySelectorAll(".imageLink");
      lightbox(imagesLinks);
    } else {
      alert("Erreur: la requête au serveur n'a rien retourné.");
    }
  }
  afficherImages();
};
afficherGalerie();


