$(document).ready(function () {

    // Evento de clique para reenviar o código
    $('#resendCodeButton').on('click', function () {

        $.ajax({
            type: 'POST',
            url: config.BASE_URL + 'plans/verify_email', // URL para reenviar o código
            data: { email: email },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    $('#error-message').hide();
                    $('#success-message').text('Código reenviado com sucesso. Verifique seu e-mail.').show();
                } else {
                    $('#success-message').hide();
                    $('#error-message').text(response.message).show();
                }
            },
            error: function () {
                $('#error-message').text('Ocorreu um erro ao reenviar o código. Tente novamente.').show();
            }
        });
    });

    // Evento de submissão do formulário de verificação 
    $('#verificationForm').on('submit', function (e) {
        e.preventDefault(); // Previne o comportamento padrão do formulário

        var code = $('#codeInput').val();

        $.ajax({
            type: 'POST',
            url: config.BASE_URL + 'plans/verify_code', // URL do Controller responsável pela verificação do código
            data: { code: code },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'success') {
                    console.log(response.message);
                    // Redireciona para a URL base após sucesso
                    window.location.href = config.BASE_URL;
                } else {
                    // Mostra a mensagem de erro
                    $('#error-message').text(response.message).show();
                }
            },
            error: function () {
                $('#error-message').text('Ocorreu um erro. Tente novamente.').show();
            }
        });
    });

});