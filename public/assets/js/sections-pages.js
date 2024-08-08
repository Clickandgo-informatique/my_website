const btnAddItem = document.querySelector(".btnAddItem");
const btnRemoveItem = document.querySelectorAll(".btnRemoveItem");
const listeSections = document.querySelectorAll(".sectionPage");
const enteteSection = document.querySelector(".entete-section");
const sectionNum = document.querySelectorAll(".sectionNum");
const piedSection = document.querySelectorAll(".pied-section");
const btnOuvrirGalerie = document.querySelectorAll(".btn-ouvrir-galerie");
const selectGaleries = document.querySelectorAll(".select-galeries");

let galerieId = "";

let compteur = 1;

const compterSections = () => {
  sectionNum.forEach((el) => {
    let index = Array.from(sectionNum).indexOf(el) + 1;
    el.insertAdjacentHTML("afterbegin", `Section ${index}`);
  });
};

const addFormToCollection = (e) => {
  const collectionHolder = document.querySelector(
    "." + e.currentTarget.dataset.collectionHolderClass
  );
  //Création de la section de page
  const item = document.createElement("div");
  item.className = "sectionPage";
  item.innerHTML = collectionHolder.dataset.prototype.replace(
    /__name__/g,
    collectionHolder.dataset.index
  );
  collectionHolder.appendChild(item);

  //Incrémentation
  collectionHolder.dataset.index++;
  // compterSections();
};
btnAddItem.addEventListener("click", addFormToCollection);

//Bouton suppression d'une section de page (item)
btnRemoveItem.forEach((el) => {
  el.addEventListener("click", (e) => {
    e.preventDefault();
    const sectionPage = document.querySelector(".sectionPage");
    sectionPage.remove();
    compterSections();
  });
});
//Affichage horizontal des miniatures de la galerie au changement du select
async function afficherMiniatures(galerieId, containerMiniatures) {
  const response = await fetch(
    `/admin/galeries/afficher-miniatures-horizontale/${galerieId}`,
    {
      methods: "get",
      headers: {
        // "X-Requested-With": "XMLHttpRequest",
        "Content-type": "Application/Json",
      },
    }
  );
  const data = await response.json();
  //Afficher les miniatures dans la section active :
  containerMiniatures.innerHTML = "";
  containerMiniatures.insertAdjacentHTML("afterbegin", data.content);
}
//Bouton pour ouvrir la galerie selectionnée
btnOuvrirGalerie.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    // //Selection du champ select des galeries actif
    let selectGalerie = document.querySelector(".select-galeries");
    console.log("valeur select galerie : ", selectGalerie.value);
    if (selectGalerie.value !== "") {
      //Création du href du bouton pour ouvrir la page de galerie
      btn.setAttribute(
        "href",
        "/admin/galeries/modifier-galerie/" + selectGalerie.value
      );
    } else {
      e.preventDefault();
      alert("Veuillez sélectionner une galerie dans la liste.");
    }
  });
});

//Afficher miniatures après choix du select de galeries dans la section active
selectGaleries.forEach((el) => {
  el.addEventListener("change", (e) => {
    if (e.currentTarget.value !== "") {
      galerieId = e.currentTarget.value;
      //Recherche de la div de galerie correspondant à la section active
      const containerMiniatures = e.currentTarget
        .closest(".section-page")
        .querySelector(".container-miniatures");
      afficherMiniatures(galerieId, containerMiniatures);
      return { galerieId: galerieId, containerMiniatures: containerMiniatures };
    }
  });
});

//Ouverture de la page
compterSections();

