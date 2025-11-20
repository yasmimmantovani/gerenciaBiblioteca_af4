document.getElementById('telefone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, ""); // Remove tudo que não for número

    if (value.length > 11) {
        value = value.substring(0, 11);
    }

    if (value.length > 6) {
        e.target.value = value.replace(/^(\d{2})(\d{5})(\d{0,4})/, "($1) $2-$3");
    } else if (value.length > 2) {
        e.target.value = value.replace(/^(\d{2})(\d{0,5})/, "($1) $2");
    } else {
        e.target.value = value.replace(/^(\d*)/, "($1");
    }
});

document.querySelector("form").addEventListener("submit", function() {
    const phone = document.getElementById("telefone");
    phone.value = phone.value.replace(/\D/g, ""); // Envia só números ao PHP
});
