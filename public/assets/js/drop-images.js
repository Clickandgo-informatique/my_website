import { readableFileSize } from "./readableFileSize.js";

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
const handleDrop = (e) => {
  const dataTransfer = e.dataTransfer;
  const files = dataTransfer.files;
  const listeImages = [...files];
  console.log(listeImages);

  //Affichage des détails des images en tableau en cours d'importation
  const tableauDetails = document.querySelector(".tableau-details-images");
  tableauDetails.innerHTML = "";

  tableauDetails.insertAdjacentHTML(
    "afterbegin",
    `<span>${listeImages.length} images sélectionnées : </span>`
  );
  let i = 1;
  listeImages.forEach((img) => {
    tableauDetails.insertAdjacentHTML(
      "beforeend",
      `<div class="details-image">${i}. ${img.name} ${readableFileSize(
        img.size
      )} ${img.type}</div>`
    );
    i++;
  });

  //envoi de la liste d'images vers le controller Galeries de symfony
  const envoiListeImages = async () => {
    const galerie = document.querySelector(".container-galerie");
    let galerieId = galerie.dataset.id;

    try {
      const response = await fetch(`/admin/galeries/importer-images`, {
        method: "POST",
        headers: {
          "X-Requested-with": "XMLHttpRequest",
          "Content-Type": "application/json;charset=UTF-8",
        },
        body: JSON.stringify({ listeImages, galerieId }),
      });
      const data = await response.json();
      console.log(data);
    } catch (error) {
      console.error("Errors : ", error);
    }
  };
  envoiListeImages();
};
