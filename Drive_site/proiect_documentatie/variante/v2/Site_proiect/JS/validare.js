function valideazaMetoda() {
    var nume = document.getElementById("nume").value.trim();
    if (nume === "") {
        alert("Numele metodei este obligatoriu!");
        return false;
    }
    return true;
}
