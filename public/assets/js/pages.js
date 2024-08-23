//Activer/desactiver une page en tant que page d'accueil

const checkActivation = document.querySelector("#pages_isPageAccueil");
let etatActivation;

let is_homepage;
checkActivation.addEventListener("change", (e) => {
  if (e.target.checked) {
    is_homepage = true;
  } else {
    is_homepage = false;
  }
  activerHomepage();
});
const activerHomepage = async () => {

  try {
    const response = await fetch("/admin/pages/choisir-page-d-accueil", {
      method: "POST",
      headers: {
        "content-type": "application/json ;charset=UTF-8",
        "X-Requested-with": "XMLHttpRequest",
      },
      body: is_homepage,
    });
    const data = await response.json();
    console.log(data);
  } catch (error) {
    console.error(error);
  }
};
