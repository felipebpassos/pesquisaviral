// Mostra o verificador ao clicar no link "Verificar conta"
document.getElementById('verificarConta').addEventListener('click', function(e) {
    e.preventDefault(); // Evita o comportamento padrão do link
    document.getElementById('verificador').style.display = 'flex';
});

// Oculta o verificador ao clicar no botão "Voltar"
document.getElementById('btnVoltar').addEventListener('click', function(e) {
    e.preventDefault(); // Evita o comportamento padrão do link
    document.getElementById('verificador').style.display = 'none';
});

$(document).ready(function() {

    $('#emailForm').on('submit', function(e) {
        e.preventDefault(); // Previne o comportamento padrão do formulário

        var email = $('#emailInput').val();

        $.ajax({
            type: 'POST',
            url: config.BASE_URL + 'plans/verify_email',  // URL do Controller responsável pela verificação
            data: { email: email },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Redireciona para a página de autenticação de dois fatores
                    window.location.href = config.BASE_URL + 'plans/auth2FA';
                } else {
                    // Mostra a mensagem de erro
                    $('#error-message').text(response.message).show();
                }
            },
            error: function() {
                $('#error-message').text('Ocorreu um erro. Tente novamente.').show();
            }
        });
    });
});