document.querySelectorAll(".input_commande").forEach((inputCommande, index) => {
  inputCommande.addEventListener("change", function () {
    let quantite = inputCommande.value;
    let inputHidden = document.querySelectorAll(".input_hidden")[index];
    let idCommande = inputHidden.value;
    let id_product_hidden = document.querySelector(".id_product_hidden").value;
    let token = document.querySelector("#token").value;
    console.log(idCommande);

    console.log(token);

    //  console.log(id_product_hidden);
    if (quantite > 0) {
      fetch(
        `http://localhost/projet_stage/user/api/commande.php?&token=${token}&id=${idCommande}&quantite=${quantite}&id_product_hidden=${id_product_hidden}`
      )
        .then((response) => response.json())
        .then((data) => {
          Swal.fire({
            icon: `${data.type}`,
            title: "Opération réussie !",
            text: `${data.message}`,
            timer: 3000,
          });
        })
        .catch((error) => {
          console.log(error);
        });
    }
  });
});




// // Démarrage de la fonction de suppression
// document.querySelectorAll(".deleteCommande1").forEach((element) => {
//   element.addEventListener("click", () => {
//     let res = confirm("Êtes-vous sûr de vouloir supprimer cette commande ?");
//     if (res === true) {
//       // On récupère le input caché dans l'élément cliqué
//       let input = element.querySelector(".deleteCommande");
//       let value = input.value;
//             console.log(value);
//       fetch(`http://localhost/projet_stage/user/api/delete_commande.php?id=${value}`)
//         .then((response) => response.json())
//         .then((data) => {
//           console.log("Commande supprimée avec succès");
//           location.reload(); // Recharger la page après suppression
//         })
//         .catch((error) => {
//           console.log("Erreur : " + error);
//         });
//     }
//   });
// });

