const sortableList = document.querySelector(".sortable-list");
let items = document.querySelectorAll(".item");

items.forEach((item) => {
  item.addEventListener("dragstart", () => {
    //Ajout de la classe "dragging" après délai
    setTimeout(() => item.classList.add("dragging"), 0);
  });
  item.addEventListener("dragend", () => {
    //Annulation de la classe "dragging"
    item.classList.remove("dragging");

    //Création du tableau de positions actualisées des items dans la liste
    let listItems = document.querySelectorAll(".item");
    let arrayItems = [...listItems];
    let arrayPositions = [];
    for (let i = 0; i < arrayItems.length; i++) {
      arrayItems[i].setAttribute(
        "data-position",
        arrayItems.indexOf(arrayItems[i]) + 1
      );

      //Création du tableau json pour envoi au controller symfony
      arrayPositions.push({
        id: arrayItems[i].getAttribute("data-id"),
        position: arrayItems[i].getAttribute("data-position"),
      });
    }

    const actualiserRepositionnement = async () => {
      try {
        const response = await fetch("/admin/pages/repositionnement-pages", {
          method: "POST",
          headers: {
            "Content-Type": "application/json ;charset=UTF-8",
            "X-Requested-with": "XMLHttpRequest",
          },
          body: JSON.stringify(arrayPositions),
        });
        const data = await response.json();

        if (data === 200) {
          console.log("Repositionnement de page réussi.");
        }
      } catch (error) {
        console.log("Erreur : ", error);
      }
    };
    actualiserRepositionnement();

    //Actualiser la numérotation au bout d'un petit moment
    setTimeout(() => {
      actualiserNumerotation();
    }, 1000);
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

//Actualiser numérotation de la liste de pages
const actualiserNumerotation = () => {
  //Liste actualisée pour numérotation
  const numerotationListe = document.querySelectorAll(".numerotation-item");

  for (let i = 0; i < numerotationListe.length; i++) {
    numerotationListe[i].textContent = i + 1;
  }
};
