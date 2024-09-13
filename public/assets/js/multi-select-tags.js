const initApp = (e) => {
  const multiSelectTags = document.querySelector(".multi-select-tags");
  let tags, selectedTag, deletedTag;

  //Obtenir la liste de tous les tags de galeries en bdd
  //Excéptés ceux déjà sélectionnés
  const getTags = async () => {
    try {
      const response = await fetch("/admin/tags/tags-galeries/", {
        methods: "get",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
          "Content-type": "Application/Json",
        },
      });
      const tags = await response.json();
      console.log('liste de tags =',tags)
      afficherMultiSelect(tags);
    } catch (error) {
      console.error(error);
    }
  };
  getTags(tags);

  //Créer et afficher le multiselect
  const afficherMultiSelect = (tags) => {
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

    //Ouverture et fermeture du container de tags
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

    const tagsContainer = document.createElement("div");
    tagsContainer.className = "tags-container";

    const selectedTagsContainer = document.createElement("div");
    selectedTagsContainer.className = "selected-tags-container";
    tagsContainer.appendChild(ajoutTag);
    selectedTagsContainer.appendChild(dropdownCheckbox);
    multiSelectTags.appendChild(labelMultiSelect);
    multiSelectTags.appendChild(selectedTagsContainer);
    multiSelectTags.appendChild(tagsContainer);

    tags.forEach((tag) => {
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
      tagsContainer.appendChild(divTag);
    });

    //Gestion du drop de tags
    const divSelectedTags = document.querySelector(".selected-tags-container");
    const divTags = document.querySelector(".tags-container");
    const tagsList = document.querySelectorAll(".tag");
    const arraySelectedTags = [];

    //Obtenir la liste des tags sélectionnés pour cette entité au démarrage
    const getSelectedTagsList = async () => {
      try {
        const response = await fetch("/admin/tags/tags-galeries/selection", {
          methods: "GET",
          headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Content-type": "Application/Json",
          },
        });
        const selectedTagsList = await response.json();
        //afficher les tags déjà sélectionnés
        selectedTagsList.forEach((tag) => {
          //Remplissage du tableau de tags sélectionnés
          arraySelectedTags.push({ id: tag.id });
          //Création de la div pour chaque objet
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
          selectedTagsContainer.appendChild(divTag);
        });
      } catch (error) {
        console.error(error);
      }
      console.log(arraySelectedTags);
      return arraySelectedTags;
    };
    getSelectedTagsList();

    //Gestion du drag and drop
    for (let tag of tagsList) {
      tag.addEventListener("dragstart", function (e) {
        selectedTag = e.target;
        console.log(selectedTag);

        divSelectedTags.addEventListener("dragover", function (e) {
          e.preventDefault();
        });

        divSelectedTags.addEventListener("drop", function () {
          divSelectedTags.appendChild(selectedTag);
          arraySelectedTags.push({ id: selectedTag.id });
          selectedTag = null;
          sauvegardeTags();
          console.log(arraySelectedTags);
        });

        divTags.addEventListener("dragover", function (e) {
          e.preventDefault();
        });

        divTags.addEventListener("drop", function () {
          divTags.appendChild(selectedTag);
          //effacement du tag dans le tableau de tags en sélection
          deletedTag = selectedTag.id;
          arraySelectedTags.splice(arraySelectedTags.indexOf(deletedTag), 1);
          selectedTag = null;
          effacerTags();
          console.log(arraySelectedTags);
        });
      });
    }
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
      console.log(data);
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
      console.log(data);
    };
  };

};

window.addEventListener("DOMContentLoaded", initApp);
