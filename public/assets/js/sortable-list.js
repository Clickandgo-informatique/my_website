const sortableList = document.querySelector(".sortable-list");
const items = document.querySelectorAll(".item");
let listItems = Array.from(items);

items.forEach((item) => {
  item.addEventListener("dragstart", () => {
    //Ajout de la classe "dragging" après délai
    setTimeout(() => item.classList.add("dragging"), 0);
  });
  item.addEventListener("dragend", () => {
    //Annulation de la classe "dragging"
    item.classList.remove("dragging");
    //création du tableau de positions des items dans la liste
    listItems.forEach((item) => {
      let itemId = item.dataset.id;
      let position = listItems.indexOf(item);
      console.log("Position : ", position, "id : ", itemId);
    });
  });
});

const initSortableList = (e) => {
  e.preventDefault();

  const draggingItem = sortableList.querySelector(".dragging");
  //Récupérer tous les items exceptés ceux en mouvement
  let siblings = [...sortableList.querySelectorAll(".item:not(.dragging)")];
  //Recherche l'élément après lequel l'élément déplacé doit être placé
  let nextSibling = siblings.find((sibling) => {
    return e.clientY <= sibling.offsetTop + sibling.offsetHeight / 2;
  });
  //Insertion de l'item à la nouvelle place
  sortableList.insertBefore(draggingItem, nextSibling);
};
sortableList.addEventListener("dragover", initSortableList);
sortableList.addEventListener("dragenter", (e) => e.preventDefault());
