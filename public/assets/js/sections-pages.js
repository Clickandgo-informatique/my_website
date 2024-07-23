const btnAddItem = document.querySelector(".btnAddItem");
const listeSections = document.querySelectorAll(".sectionPage");

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
};

btnAddItem.addEventListener("click", addFormToCollection);

//Fonction ajout d'un bouton de suppression
const addRemoveItemBtn = (item) => {
  const btnRemoveItem = document.createElement("button");
  btnRemoveItem.textContent = "x";
  btnRemoveItem.type = "button";
  btnRemoveItem.className = "btn btnRemoveItem";
  item.appendChild(btnRemoveItem);

  btnRemoveItem.addEventListener("click", (e) => {
    e.preventDefault();
    item.remove();
  });
};
//Ajout d'un bouton de suppression sur chaque item créé préalablement
console.log(listeSections);
listeSections.forEach((el) => {
  addRemoveItemBtn(el);
});