const multiSelectTags = document.querySelector(".multi-select-tags");
let tagsList,
  selectedTagsList,
  selectedTag,
  divSelectedTags,
  draggablesTags,
  deletedTag,
  container,
  arraySelectedTags = [];

//Créer et afficher le multiselect
const afficherMultiSelect = async () => {
  //
  const ajoutTag = document.createElement("a");
  ajoutTag.className = "btn btn-ajout-tag";
  ajoutTag.setAttribute("href", "#");

  const iconeAjoutTag = document.createElement("i");
  iconeAjoutTag.className = "fa-solid fa-circle-plus";
  ajoutTag.appendChild(iconeAjoutTag);

  const labelMultiSelect = document.createElement("label");
  labelMultiSelect.innerText = "Etiquettes";

  const labelDropdownCheckbox = document.createElement("label");
  labelDropdownCheckbox.className = "label-dropdown-checkbox";
  labelDropdownCheckbox.setAttribute("for", "checkbox-dropdown-multi-select");

  const dropdownCheckbox = document.createElement("input");
  dropdownCheckbox.type = "checkbox";
  dropdownCheckbox.className = "checkbox-dropdown-multi-select";
  dropdownCheckbox.appendChild(labelDropdownCheckbox);

  //Création du container de liste de tags non sélectionnés
  const tagsContainer = document.createElement("div");
  tagsContainer.className = "tags-container";
  tagsContainer.setAttribute("id", "tags-container");
  tagsContainer.appendChild(ajoutTag);

  //Création du container de tags sélectionnés
  const selectedTagsContainer = document.createElement("div");
  selectedTagsContainer.className = "selected-tags-container";
  selectedTagsContainer.setAttribute("id", "selected-tags-container");

  //Appliquer les containers au multi-select
  multiSelectTags.appendChild(labelMultiSelect);
  multiSelectTags.appendChild(selectedTagsContainer);
  selectedTagsContainer.appendChild(dropdownCheckbox);
  multiSelectTags.appendChild(tagsContainer);
};
//Fin fonction asynchrone afficherMultiSelect

const creerTag = (tag, container) => {
  //Création de la div du tag
  const divTag = document.createElement("div");
  divTag.className = "tag draggable";
  divTag.setAttribute("draggable", true);
  divTag.setAttribute("id", tag.id);

  //Création de l'icône du tag
  const icon = document.createElement("i");
  icon.className = tag.icone.toString();

  //traitement pour affichage
  divTag.style.backgroundColor = tag.couleur;
  divTag.style.color = "white";
  divTag.appendChild(icon);
  divTag.appendChild(document.createTextNode(tag.titre));
  container.appendChild(divTag);
};

//Obtenir la liste de tous les tags de galeries en bdd
//Excéptés ceux déjà sélectionnés et les afficher
const getTagsList = async () => {
  try {
    const response = await fetch("/admin/tags/tags-galeries/", {
      methods: "get",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Content-type": "Application/Json",
      },
    });
    tagsList = await response.json();

    //Création de chaque tag dans le container pour tags non sélectionnés
    container = document.getElementById("tags-container");
    tagsList.forEach((tag) => {
      creerTag(tag, container);
    });
  } catch (error) {
    console.error(error);
  }
};

//Obtenir la liste des tags déjà sélectionnés pour cette entité au démarrage
const getSelectedTagsList = async () => {
  try {
    const response = await fetch("/admin/tags/tags-galeries/selection", {
      methods: "GET",
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Content-type": "Application/Json",
      },
    });
    selectedTagsList = await response.json();

    //Création de chaque tag sélectionné dans le container
    container = document.getElementById("selected-tags-container");
    selectedTagsList.forEach((tag) => {
      creerTag(tag, container);
    });
  } catch (error) {
    console.error(error);
  }
};

//Initialiser les fonctions
const init = async () => {
  await afficherMultiSelect();
  await getTagsList();
  await getSelectedTagsList();
  draggablesTags = document.querySelectorAll(".tag");

  return draggablesTags;
};

//Gestion du drop de tags
//Gestion du drag and drop pour un tag
const gestionTags = async () => {
  await init();

  const divSelectedTags = document.getElementById("selected-tags-container");
  const divTags = document.getElementById("tags-container");
  divSelectedTags.placeholder="Aucune étiquette enregistrée, glissez-déposez des étiquettes ici."

  //Créer un place holder si le container de tags sélectionné est vide
  // const setPlaceholder = () => {
  //   if (arraySelectedTags.length == 0) {
  //     divSelectedTags.placeholder="Aucune étiquette enregistrée, glissez-déposez des étiquettes ici."
  //     // divSelectedTags.innerHTML = "";
  //     // divSelectedTags.insertAdjacentHTML(
  //     //   "afterbegin",
  //     //   "Aucune étiquette enregistrée, glissez déposez des étiquettes ici."
  //     // );
  //   } else {
  //     divSelectedTags.placeholder = "";
  //   }
  // };

  for (let tag of draggablesTags) {
    //
    tag.ondragstart = (e) => {
      selectedTag = e.target;
      divSelectedTags.ondragover = (e) => {
        e.preventDefault();
      };
    };
  }
  divSelectedTags.ondragover = (e) => {
    e.preventDefault();
    divSelectedTags.classList.add("dragging");
  };
  divSelectedTags.ondrop = () => {
    divSelectedTags.appendChild(selectedTag);
    arraySelectedTags.push({ id: selectedTag.id });
    selectedTag = null;
    sauvegardeTags();
  };
  divSelectedTags.ondragend = () => {
    divSelectedTags.classList.remove("dragging");
  };
  divTags.ondragover = () => {};
  divTags.addEventListener("dragover", function (e) {
    e.preventDefault();
  });

  divTags.ondrop = () => {
    divTags.appendChild(selectedTag);

    //effacement du tag dans le tableau de tags en sélection
    deletedTag = selectedTag.id;
    selectedTag = null;
    arraySelectedTags.splice(arraySelectedTags.indexOf(deletedTag), 1);
    effacerTags();
  };
};
gestionTags();

//sauvegarde des tags
const sauvegardeTags = async () => {
  //récupération de la liste des tags sélectionnés

  const response = await fetch("/admin/tags/sauvegarde-tags", {
    method: "POST",
    body: JSON.stringify(arraySelectedTags),
    headers: {
      "X-Requested-with": "XMLHttpRequest",
      "Content-Type": "application/json charset=UTF-8",
    },
  });
  const data = await response.json();
};

//effacement des tags
const effacerTags = async () => {
  const response = await fetch("/admin/tags/effacer-tags", {
    method: "DELETE",
    body: JSON.stringify({ id: deletedTag }),
    headers: {
      "X-Requested-with": "XMLHttpRequest",
      "Content-Type": "application/json charset=UTF-8",
    },
  });
  const data = await response.json();
};

//Ouverture et fermeture du container de tags
const dropdownCheckbox = document.querySelector(
  ".checkbox-dropdown-multi-select"
);
const tagsContainer = document.getElementById("tags-container");

dropdownCheckbox.addEventListener("change", function () {
  if (this.checked) {
    tagsContainer.style.maxHeight = "100%";
    tagsContainer.style.padding = "10px";
    tagsContainer.style.overflowY = "auto";
    tagsContainer.style.display = "flex";
  } else {
    tagsContainer.style.maxHeight = "0";
    tagsContainer.style.overflowY = "hidden";
    tagsContainer.style.padding = "0";
    tagsContainer.style.display = "none";
  }
});
