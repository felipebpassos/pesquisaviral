// Função de validação do nome de usuário
function validateInstagramUsername(username) {
    var regex = /^(?!.*\.\.)(?!.*__)(?!.*\.\.$)(?!.*__$)(?!^\.)[^.][\w.]{0,28}[\w]$/;

    if (!regex.test(username)) {
        console.error('Erro: O nome de usuário é inválido.');
        alert('Por favor, insira um nome de usuário válido para o Instagram.');
        return false;
    }

    return true;
}

// Função para tratar o username 
function trataUsername(username) {
    // Remove o caractere @ do início, se existir
    if (username.startsWith('@')) {
        username = username.slice(1);
    }

    // Converte o username para minúsculas
    return username.toLowerCase();
}

// Manipulação do formulário de submissão
$(document).ready(function () {
    $('#search-form').submit(function (event) {
        event.preventDefault();
        var accountName = $('#account-name').val();

        // Trata o username antes de validar
        accountName = trataUsername(accountName);

        if (validateInstagramUsername(accountName)) {
            startSearch(accountName);
        }
    });
});

// Iniciar a pesquisa quando a página é carregada com um parâmetro GET
document.addEventListener('DOMContentLoaded', function () {
    var urlParams = new URLSearchParams(window.location.search);
    var profile = urlParams.get('profile');

    // Trata o username do parâmetro GET antes de validar
    if (profile) {
        profile = trataUsername(profile);
        if (validateInstagramUsername(profile)) {
            startSearch(profile);
        }
    }

    // Chama a função para verificar e criar o card se necessário
    checkSearchAndCreateCard();
});

// Função para iniciar a pesquisa
function startSearch(accountName) {
    if (!accountName) {
        alert('Erro: O campo de nome de conta está vazio.'); // Alerta para campo vazio
        console.error('Erro: O campo de nome de conta está vazio.');
        return;
    }

    $.ajax({
        url: config.BASE_URL + 'search/startSearch/',
        method: 'POST',
        data: { 'account-name': accountName },
        success: function (response) {
            if (response.username) {
                createResultCard(response, accountName);
            } else {
                alert('Usuário não encontrado.');
            }
        },
        error: function (xhr, status, error) {
            console.error("Erro ao iniciar a pesquisa:", xhr.responseText || error);
            alert("Erro ao iniciar a pesquisa: " + (xhr.responseText || error));
        }
    });
}

function checkSearchAndCreateCard() {
    $.ajax({
        url: config.BASE_URL + 'search/checkSearch',
        type: 'GET',
        success: function (response) {
            try {
                response = typeof response === "string" ? JSON.parse(response) : response;

                if (response.status === 'finished' && response.data) {
                    // Pesquisa finalizada, cria o card com botão de resultado
                    createResultCard(response.data.merged_data, response.data.username, true);
                } else if (response.status === 'in_progress' && response.data) {
                    // Pesquisa em andamento, cria o card com mensagem de tempo estimado
                    createResultCard(response.data, response.data.username, false);
                } else {
                    console.log('Nenhuma pesquisa em andamento ou finalizada encontrada.');
                }
            } catch (e) {
                console.error("Erro ao analisar a resposta JSON:", e);
            }
        },
        error: function (xhr, status, error) {
            console.error('Erro ao verificar a pesquisa:', error);
        }
    });
}

function createResultCard(response, accountName, isFinished) {
    var resultsContainer = $('#results-container');

    // Limita o número de cards no container a 5
    if ($('.card', resultsContainer).length >= 5) {
        $('.card:last', resultsContainer).remove();
    }

    // Cria o novo card
    var card = $('<div>').addClass('card').data('accountName', accountName);
    var cardBody = $('<div>').addClass('card-body').appendTo(card);

    // Adiciona a imagem de perfil e o nome do usuário
    $('<img>').attr('src', response.profile_picture_url).addClass('card-img-top').appendTo(cardBody);
    $('<p>').addClass('card-text').text('@' + accountName).appendTo(cardBody);

    if (isFinished) {
        // Pesquisa finalizada, exibe o botão de resultado
        finalizeSearch(cardBody, accountName);
    } else {
        // Pesquisa em andamento, exibe a mensagem de tempo estimado
        $('<p>').addClass('estimated-time').text('A pesquisa estará pronta em aproximadamente 5 minutos.').appendTo(cardBody);
    }

    // Adiciona o novo card no início do container
    resultsContainer.prepend(card);
}

function finalizeSearch(cardBody, accountName) {
    // Esconde a mensagem de tempo estimado
    $('.estimated-time', cardBody).remove();

    // Cria e exibe o botão de resultado
    var cardBottom = $('<div>').addClass('card-bottom').appendTo(cardBody);
    createResultButton(accountName, cardBottom);
}

function createResultButton(dataId, container) {
    var button = $('<button>', {
        'class': 'result-btn',
        'data-id': dataId
    }).append($('<i>').addClass('fa-solid fa-images')).append(' Resultado');

    container.append(button);
}
