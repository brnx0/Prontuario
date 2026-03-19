document.getElementById("toggleMenu").addEventListener("click", function() {
    let sidebar = document.getElementById("sidebar");
    let container = document.querySelector(".containerPrincipal");
    sidebar.classList.toggle("hidden-sidebar");
    if (sidebar.classList.contains("hidden-sidebar")) {
        container.classList.add("full-width");
    } else {
        container.classList.remove("full-width");
    }
});