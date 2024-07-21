export const lightbox = (imagesLinks) => {
  const modale = document.querySelector("#modale-lightbox");
  const btnClose = document.querySelector(".btn-close-modal-lightbox");
  const navgauche = document.querySelector("#lightbox-nav-gauche");
  const navdroite = document.querySelector("#lightbox-nav-droite");
  const image = document.querySelector(".modal-content img");
  const tblUrlImages = Array.from(imagesLinks);
  const numerotation = document.querySelector(".numerotation");
  const gallery = tblUrlImages.map((link) => link.getAttribute("href"));
  let position = "";

  //Ouverture de la lightbox sur l'image cliquée
  for (let link of imagesLinks) {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      image.src = this.href;
      modale.classList.add("show");
      getPosition();
      getNumerotation();
    });
  }

  //nav-gauche
  navgauche.addEventListener("click", (e) => {
    e.preventDefault();
    getPosition();
    getNumerotation();

    if (position > 0) {
      let previousImageUrl = gallery[position - 1];
      image.src = previousImageUrl;
      getPosition();
      getNumerotation();
    }
  });
  //nav-droite
  navdroite.addEventListener("click", (e) => {
    e.preventDefault();
    getPosition();
    getNumerotation();
    if (position < gallery.length - 1) {
      let nextImageUrl = gallery[position + 1];
      image.src = nextImageUrl;
      getPosition();
      getNumerotation();
    }
  });

  //Obtenir la position de l'image dans le tableau + numérotation
  const getPosition = () => {
    let urlImage = new URL(image.src);
    position = gallery.findIndex((pos) => pos === urlImage.pathname);
 
    return position;
  };
  const getNumerotation = () => {
    numerotation.style.display = "block";
    numerotation.textContent = `${position + 1}/${gallery.length}`;
    setTimeout(() => {
      numerotation.style.display = "none";
    }, 1000);
  };
  //Fermeture de la lightbox
  btnClose.addEventListener("click", (e) => {
    e.preventDefault();
    modale.classList.remove("show");
    // setTimeout(() => {
    //   modale.classList.add("fadeOut");
    // }, 500);
  });
};
