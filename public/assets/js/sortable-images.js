let items = document.querySelectorAll(".galerie .miniature");
let dragSourceElement=undefined;

function handleDragStart(e) {
  // e.preventDefault();
  this.style.opacity = "0.4";
  dragSourceElement = this;
  e.dataTransfer.effectAllowed = "move";
  e.dataTransfer.setData("image/*", this.innerHTML);
}

function handleDragEnd(e) {
  this.style.opacity = "1";
}

function handleDragOver(e) {
  e.preventDefault();
  return false;
}

function handleDragEnter(e) {
  this.classList.add("over");
}

function handleDragLeave(e) {
  this.classList.remove("over");
}

function handleDrop(e) {
  e.stopPropagation();

  if (dragSourceElement !== this) {
    dragSourceElement.innerHTML = this.innerHTML;
    this.innerHTML = e.dataTransfer.getData("image/*");
 
       //Création du tableau de positions actualisées des items dans la liste
       let items = document.querySelectorAll(".galerie .miniature img")
       let arrayItems = [...items];
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
       console.log(arrayPositions)

       const actualiserRepositionnement = async () => {
        try {
          const response = await fetch("/admin/images/repositionnement-images", {
            method: "POST",
            headers: {
              "Content-Type": "application/json ;charset=UTF-8",
              "X-Requested-with": "XMLHttpRequest",
            },
            body: JSON.stringify(arrayPositions),
          });
          const data = await response.json();
  
          if (data === 200) {
            console.log("Repositionnement de l'image réussi.");
          }
        } catch (error) {
          console.log("Erreur : ", error);
        }
      };
      actualiserRepositionnement();
  }


  return false;
}

items.forEach(function (item) {
  item.addEventListener("dragstart", handleDragStart);
  item.addEventListener("dragover", handleDragOver);
  item.addEventListener("dragenter", handleDragEnter);
  item.addEventListener("dragleave", handleDragLeave);
  item.addEventListener("dragend", handleDragEnd);
  item.addEventListener("drop", handleDrop);
});
