$(document).ready(function() {
    // Adicionar evento de clique ao botão mostrar-mais
    $('#results-container-posts').on('click', '.mostrar-mais', function() {
        var captionBox = $(this).closest('.captionBox');
        var caption = captionBox.find('.caption');
        var fade = captionBox.find('.fade-bottom');

        // Alternar entre mostrar mais e mostrar menos
        if (caption.hasClass('expandido')) {
            // Se expandido, mostrar menos
            fade.css('opacity', '1');
            caption.css('max-height', '60px');
            $(this).text('Mostrar Mais');
        } else {
            caption.css("max-height", caption.prop("scrollHeight") + "px");
            fade.css('opacity', '0');
            $(this).text('Mostrar Menos');
        }
        
        // Alternar a classe expandido
        caption.toggleClass('expandido');
    });
});