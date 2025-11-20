const botaoTema = document.getElementById('tema');
const  html = document.documentElement;

if(localStorage.getItem('tema') === 'escuro') {
    html.setAttribute('data-tema', 'escuro');
    botaoTema.innerHTML = '<ion-icon name="sunny-outline"></ion-icon>';
}

botaoTema.addEventListener('click', () => {
    const temaAtual = html.getAttribute('data-tema');

    if (temaAtual === 'escuro') {
        html.removeAttribute('data-tema');
        botaoTema.innerHTML = '<ion-icon name="moon-outline"></ion-icon>';
        localStorage.setItem('tema', 'claro');
    } else {
        html.setAttribute('data-tema', 'escuro');
        botaoTema.innerHTML = '<ion-icon name="sunny-outline"></ion-icon>';
        localStorage.setItem('tema', 'escuro');
    }
})