const btnAddItem = document.querySelector(".btnAddItem");
const btnRemoveItem = document.querySelectorAll(".btnRemoveItem");
const listeSections = document.querySelectorAll(".sectionPage");
const enteteSection = document.querySelector(".entete-section");
const sectionNum = document.querySelectorAll(".sectionNum");
const piedSection = document.querySelectorAll(".pied-section");
const btnOuvrirGalerie = document.querySelectorAll(".btn-ouvrir-galerie");
const selectGaleries = document.querySelectorAll(".select-galeries");
console.log(selectGaleries);
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
  console.log(item);
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
let galerieId = "";
//Bouton pour ouvrir la galerie selectionnée
btnOuvrirGalerie.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    //Selection du champ select des galeries actif
    const selectGalerie = document.querySelector(".select-galeries");
    console.log(selectGalerie.value);
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

//Valeur du select de galeries
selectGaleries.forEach((el) => {
  el.addEventListener("change", (e) => {
    galerieId = e.target.value;
    return galerieId;
  });
});

//Ouverture de la page
compterSections();
