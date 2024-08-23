export const supprimerImage=(btnSuppression)=>{

btnSuppression.forEach((btn) => {
  btn.addEventListener("click", (e) => {  
    e.preventDefault(); 
  
    if (confirm("Etes-vous sûr(e) de vouloir supprimer cette image ?")) {
      fetch(btn.getAttribute("href"), {
        method: "DELETE",
        headers: {
          "X-Requested-With": "XMLHttpRequest",
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ _token: btn.dataset.token }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            btn.parentElement.remove();
          } else {
            alert(data.error);
          }
        })
        .catch((e) => alert(e));
    }
  });
});
}