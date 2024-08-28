import { readableFileSize } from "./readableFileSize.js";
import { afficherGalerie } from "./galeries.js";

const initApp = () => {
  const droparea = document.querySelector(".images-drop-area");
  const active = () => droparea.classList.add("dropping");
  const inactive = () => droparea.classList.remove("dropping");
  const prevents = (e) => e.preventDefault();

  ["dragenter", "dragover", "dragleave", "drop"].forEach((evtName) => {
    droparea.addEventListener(evtName, prevents);
  });

  ["dragenter", "dragover"].forEach((evtName) => {
    droparea.addEventListener(evtName, active);
  });

  ["dragleave", "drop"].forEach((evtName) => {
    droparea.addEventListener(evtName, inactive);
  });
  droparea.addEventListener("drop", handleDrop);
};

document.addEventListener("DOMContentLoaded", initApp);

const tableauDetails = document.querySelector(".tableau-details-images");

const handleDrop = (e) => {
  e.stopPropagation();
  const dataTransfer = e.dataTransfer;
  const files = dataTransfer.files;
  const droppedFiles = [...files];
  const listeImages = [];

  tableauDetails.innerHTML = "";

  //Vérification du format de fichier
  const allowedTypes = [
    "image/jpeg",
    "image/jpg",
    "image/png",
    "image/gif",
    "image/bmp",
    "image/webp",
  ];

  let i = 1;
  droppedFiles.forEach((file) => {
    //
    //Si format d'image correct remplir tableau listeImages
    if (allowedTypes.includes(file.type)) {
      //On ajoute l'image valide au tableau listeImages
      listeImages.push(file);
      tableauDetails.insertAdjacentHTML(
        "beforeend",
        `<div class="details-image text-green">${i}. ${file.name} ${
          file.type
        } ${readableFileSize(file.size)}</div>`
      );
      i++;
    } else {
      //Affichage du tableau de détails fichiers non autorisés
      tableauDetails.insertAdjacentHTML(
        "beforeend",
        `<div class="details-image text-red">${i} ${file.name} ${file.type} n'est pas un format de fichier autorisé.</div>`
      );
      i++;
    }
  });
  //Affichage des détails des images en tableau en cours d'importation
  tableauDetails.insertAdjacentHTML(
    "afterbegin",
    `<span>${listeImages.length} images importées : </span>
    <a href="#" class="btn-fermer-details-images">x</a>
    `
  );
  const btnFermerDetailsImages = document.querySelector(
    ".btn-fermer-details-images"
  );

  btnFermerDetailsImages.addEventListener("click", () => {
    btnFermerDetailsImages.parentElement.remove();
  });

  console.log("liste images :", listeImages);

  //Si il y a des images dans le tableau on continue la fonction ...
  if (listeImages.length > 0) {
    //envoi de la liste d'images vers le controller Galeries de symfony
    const envoiListeImages = async () => {
      const galerie = document.querySelector(".container-galerie");
      let galerieId = galerie.dataset.id;
      console.log("galerieId = ", galerieId);

      //Création du formData
      const formData = new FormData();
      //Traitement du tableau d'images
      listeImages.forEach((file) => {
        formData.append("listeImages[]", file);
        // formData.append("imageWidth", file.width);
        // formData.append("imageHeight", file.height);
        formData.append("imageSize", readableFileSize(file.size));
      });
      formData.append("galerieId", galerieId);
      console.log(formData);
      try {
        const response = await fetch(`/admin/galeries/importer-images`, {
          method: "POST",
          headers: {
            "X-Requested-with": "XMLHttpRequest",
          },
          body: formData,
        });
        const data = await response.json();
        console.log("postData : ", data);

        //Si retour = true alors actualiser affichage images dans la galerie en cours
        if (data === true) {
          afficherGalerie();
        }
      } catch (error) {}
    };
    envoiListeImages();
  }
};
