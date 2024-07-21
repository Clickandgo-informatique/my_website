const btnAddItem = document.querySelector(".btnAddItem");

const addFormToCollection = (e) => {
  const collectionHolder = document.querySelector(
    "." + e.currentTarget.dataset.collectionHolderClass
  );
  const item = document.createElement("li");

  item.innerHTML = collectionHolder.dataset.prototype.replace(
    /__name__/g,
    collectionHolder.dataset.index
  );
  collectionHolder.appendChild(item);

  //Ajout bouton de suppression
  addRemoveItemBtn(item);

  //Incrémentation
  collectionHolder.dataset.index++;
};

btnAddItem.addEventListener("click", addFormToCollection);

//Fonction ajout d'un bouton de suppression
const addRemoveItemBtn = (item) => {
  const btnRemoveItem = document.createElement("button");
  btnRemoveItem.innerText = "X";
  btnRemoveItem.type = "button";
  btnRemoveItem.className = "btnRemoveItem";
  item.append(btnRemoveItem);

  btnRemoveItem.addEventListener("click", (e) => {
    e.preventDefault();
    item.remove();
  });
};
