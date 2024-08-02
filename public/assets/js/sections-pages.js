const btnAddItem = document.querySelector(".btnAddItem");
const listeSections = document.querySelectorAll(".sectionPage");
const enteteSection = document.querySelector(".entete-section");
const sectionNum = document.querySelectorAll(".sectionNum");

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
  const item = document.createElement("div");
  item.className = "sectionPage";
  item.innerHTML = collectionHolder.dataset.prototype.replace(
    /__name__/g,
    collectionHolder.dataset.index
  );
  collectionHolder.appendChild(item);

  //Ajout bouton de suppression sur chaque section
  addRemoveItemBtn(item);
  //Incrémentation
  collectionHolder.dataset.index++;
  compterSections();
};

btnAddItem.addEventListener("click", addFormToCollection);

//Fonction ajout d'un bouton de suppression
const addRemoveItemBtn = (item) => {
  const btnRemoveItem = document.createElement("button");
  // btnRemoveItem.textContent = "x";
  btnRemoveItem.type = "button";
  btnRemoveItem.className = "btn btnRemoveItem";
  btnRemoveItem.innerHTML = `<i class="fa-regular fa-trash-can"></i>Supprimer section`;
  item.appendChild(btnRemoveItem);

  btnRemoveItem.addEventListener("click", (e) => {
    e.preventDefault();
    item.remove();
    // compterSections();
  });
};

//Ouverture de la page
//Ajout d'un bouton de suppression sur chaque item créé préalablement
listeSections.forEach((el) => {
  addRemoveItemBtn(el);
});
compterSections();
