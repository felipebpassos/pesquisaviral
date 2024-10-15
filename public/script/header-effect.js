let lastScrollPosition = 0;
let isFixed = false;

window.addEventListener("scroll", function () {
    const header = document.querySelector("header");
    const apresentacao = document.querySelector(".apresentacao-principal");
    const scrollPosition = window.scrollY;

    if (scrollPosition > 0 && scrollPosition < lastScrollPosition) {
        // O usuário está rolando para cima, fixe o cabeçalho no topo da janela.
        header.classList.add("header-fixed");
        apresentacao.classList.add("apresentacao-scroll");
        header.style.top = 0;
        isFixed = true;
    } else {
        // O usuário está rolando para baixo.
        if (isFixed) {
            // Volte a ser absolute com o valor de top fixo.
            header.classList.remove("header-fixed");
            apresentacao.classList.remove("apresentacao-scroll");
            header.style.top = scrollPosition + "px"; 
            isFixed = false;
        }
    }

    lastScrollPosition = scrollPosition;
});