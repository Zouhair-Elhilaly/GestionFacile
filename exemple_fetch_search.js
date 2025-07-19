let search = document.getElementById("Search_view_category");



search.addEventListener('keyup' , () => {
    let val = ['name' = search.value];

    fetch("http://localhost/projet_stage/test.php",{
        method: "POST",
        body: JSON.stringify(val),
    }).then(res => res.json()).then(data => {
        console.log(data)
    });
});