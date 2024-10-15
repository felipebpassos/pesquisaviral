// Assumindo que os botões estão dentro de um container com o ID 'results-container'
$('#results-container').on('click', '.result-btn', function() {
    var username = $(this).data('id'); // Usando jQuery para acessar o atributo data-id
    window.location.href = config.BASE_URL + 'search/result/' + username;
});
