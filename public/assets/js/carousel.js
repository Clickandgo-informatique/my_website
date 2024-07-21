window.onload = () => {
  console.log("coucou");
  let compteur = 0;
  let timer, elements, slides, slideWidth, speed, transition;

  const diapo = document.querySelector(".diapo");
  //Récupération de la vitesse de défilement
  speed = diapo.dataset.speed;
  transition = diapo.dataset.transition;
  elements = document.querySelector(".elements");

  //Clonage de la première image
  let firstImage = elements.firstElementChild.cloneNode(true);
  //Injection du clone à la fin du diaporama
  elements.appendChild(firstImage);

  slides = Array.from(elements.children);

  //Récupération de la largeur d'une slide
  slideWidth = diapo.getBoundingClientRect().width;

  // On récupère les flèches
  let next = document.querySelector("#nav-droite");
  let prev = document.querySelector("#nav-gauche");

  prev.addEventListener("click", slidePrev);
  next.addEventListener("click", slideNext);

  //Automatisation du défilement
  timer = setInterval(slideNext, speed);

  //Gestion de l'arrêt et la reprise
  diapo.addEventListener("mouseover", stopTimer);
  diapo.addEventListener("mouseout", startTimer);

  function slideNext() {
    compteur++;
    elements.style.transition = transition + "ms linear";

    let decalage = -slideWidth * compteur;
    elements.style.transform = `translateX(${decalage}px`;

    setTimeout(() => {
      if (compteur >= slides.length - 1) {
        compteur = 0;
        elements.style.transition = "unset";
        elements.style.transform = "translateX(0)";
      }
    }, transition);
  }

  function slidePrev() {
    compteur--;
    elements.style.transition = transition + "ms linear";
    if (compteur < 0) {
      compteur = slides.length - 1;
      let decalage = -slideWidth * compteur;
      elements.style.transition = "unset";
      elements.style.transform = `translateX(${decalage}px`;
      setTimeout(slidePrev, 1);
    }
    let decalage = -slideWidth * compteur;
    elements.style.transform = `translateX(${decalage}px`;
  }
  function stopTimer() {
    clearInterval(timer);
  }

  function startTimer() {
    timer = setInterval(slideNext, speed);
  }
};
