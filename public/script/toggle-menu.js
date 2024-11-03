document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menu-toggle');
    const overlay = document.getElementById('overlay');
    const aside = document.querySelector('aside');

    // Evento de clique para o botão de menu toggle
    menuToggle.addEventListener('click', function () {
        aside.classList.toggle('expandido'); // Adiciona ou remove a classe expandido
        overlay.style.display = aside.classList.contains('expandido') ? 'block' : 'none'; // Mostra ou esconde o overlay
    });

    // Evento de clique para o overlay
    overlay.addEventListener('click', function () {
        aside.classList.remove('expandido'); // Remove a classe expandido
        overlay.style.display = 'none'; // Esconde o overlay
    });
});
