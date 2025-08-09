const noResultMsg = document.createElement("div");
noResultMsg.textContent = "Aucun résultat trouvé";
noResultMsg.style.cssText =
  "display:none; text-align:center; margin-top:20px; font-weight:bold; color:red;";

document.addEventListener("DOMContentLoaded", () => {
  console.log("hello");
  const searchInput = document.getElementById("search");
  const tableBody = document.getElementById("bodyCard");

  
  tableBody.append(noResultMsg);

  searchInput.addEventListener("input", function () {
    const searchTerm = this.value.toLowerCase().trim();
    const rows = tableBody.querySelectorAll(".card_search");

    let found = false;
    rows.forEach((row) => {
      const serviceName = row.querySelector(".name");
      if (serviceName) {
        const text = serviceName.textContent.toLowerCase();
        if (text.includes(searchTerm) || searchTerm === "") {
          row.style.display = "";
          found = true;
        } else {
          row.style.display = "none";
        }
      }
    });


    noResultMsg.style.display = found ? "none" : "block";
  });
});
