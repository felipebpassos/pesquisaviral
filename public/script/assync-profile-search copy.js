// Função de validação do nome de usuário
function validateInstagramUsername(username) {
    var regex = /^(?!.*\.\.)(?!.*\.\.)(?!.*__)(?!.*\.\.$)(?!.*__$)(?!^\.)[^.][\w.]{0,28}[\w]$/;

    if (!regex.test(username)) {
        console.error('Erro: O nome de usuário é inválido.');
        alert('Por favor, insira um nome de usuário válido para o Instagram.');
        return false;
    }

    return true;
}

// Manipulação do formulário de submissão
$(document).ready(function () {
    $('#search-form').submit(function (event) {
        event.preventDefault();
        var accountName = $('#account-name').val();
        if (validateInstagramUsername(accountName)) {
            startSearch(accountName);
        }
    });
});

// Iniciar a pesquisa quando a página é carregada com um parâmetro GET
document.addEventListener('DOMContentLoaded', function () {
    var urlParams = new URLSearchParams(window.location.search);
    var profile = urlParams.get('profile');
    if (profile && validateInstagramUsername(profile)) {
        startSearch(profile);
    }
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
            console.log(response);
            createResultCard(response, accountName);
        },
        error: function (xhr, status, error) {
            let errorMessage = 'Erro ao iniciar a pesquisa:';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage += ` ${xhr.responseJSON.message}`; // Mensagem de erro da resposta
            } else {
                errorMessage += ` ${error}`; // Mensagem de erro genérica
            }
            alert(errorMessage); // Alerta com mensagem de erro
            console.error('Erro ao iniciar a pesquisa:', error);
        }
    });
}

function createResultCard(response, accountName) {
    var resultsContainer = $('#results-container');

    // Verifica se já existem 5 cards no container e remove o último se necessário
    if ($('.card', resultsContainer).length >= 5) {
        $('.card:last', resultsContainer).remove();
    }

    // Cria o novo card
    var card = $('<div>').addClass('card').data('accountName', accountName);
    var cardBody = $('<div>').addClass('card-body').appendTo(card);
    $('<img>').attr('src', response.profile_picture_url).addClass('card-img-top').appendTo(cardBody);
    $('<p>').addClass('card-text').text('@' + accountName).appendTo(cardBody);
    var progressBarContainer = $('<div>').addClass('progress').appendTo(cardBody);
    $('<div>').addClass('progress-bar').attr({
        role: 'progressbar',
        'aria-valuenow': '0',
        'aria-valuemin': '0',
        'aria-valuemax': '100'
    }).css('width', '0%').appendTo(progressBarContainer);

    // Adiciona o novo card no início do container
    resultsContainer.prepend(card);

    // Iniciar a atualização do progresso
    updateProgress(cardBody, accountName);
}


function updateProgress(cardBody, accountName) {
    var interval = setInterval(function () {
        $.ajax({
            url: config.BASE_URL + 'async/progress/',
            method: 'GET',
            success: function (response) {
                console.log(response);
                var progress = response.progress;
                $('.progress-bar', cardBody).css('width', progress + '%');
                if (progress === 100) {
                    clearInterval(interval);
                    finalizeSearch(cardBody, accountName);
                }
            },
            error: function (xhr, status, error) {
                console.error('Erro ao obter o progresso da pesquisa:', error);
                clearInterval(interval);
            }
        });
    }, 1000);
}

function finalizeSearch(cardBody, accountName) {
    $('.progress', cardBody).hide();
    var cardBottom = $('<div>').addClass('card-bottom').appendTo(cardBody);

    // Chama createButton com ícone para o botão de resultados
    createResultButton(accountName, cardBottom);

    // Chama createAnalysisButton com SVG para o botão de análise
    createAnalysisButton(accountName, cardBottom);
}

function createResultButton(dataId, container) {
    var button = $('<button>', {
        'class': 'result-btn',
        'data-id': dataId
    }).append($('<i>').addClass('fa-solid fa-images')).append(' Resultado');

    container.append(button);
}

function createAnalysisButton(dataId, container) {
    var button = $('<button>', {
        'class': 'analysis-btn',
        'data-id': dataId
    }).append(`
        <svg width="18" height="18" viewBox="0 0 256 256" xml:space="preserve">
            <defs>
            </defs>
            <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                <path d="M 87.994 0 H 69.342 c -1.787 0 -2.682 2.16 -1.418 3.424 l 5.795 5.795 l -33.82 33.82 L 28.056 31.196 l -3.174 -3.174 c -1.074 -1.074 -2.815 -1.074 -3.889 0 L 0.805 48.209 c -1.074 1.074 -1.074 2.815 0 3.889 l 3.174 3.174 c 1.074 1.074 2.815 1.074 3.889 0 l 15.069 -15.069 l 14.994 14.994 c 1.074 1.074 2.815 1.074 3.889 0 l 1.614 -1.614 c 0.083 -0.066 0.17 -0.125 0.247 -0.202 l 37.1 -37.1 l 5.795 5.795 C 87.84 23.34 90 22.445 90 20.658 V 2.006 C 90 0.898 89.102 0 87.994 0 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 65.626 37.8 v 49.45 c 0 1.519 1.231 2.75 2.75 2.75 h 8.782 c 1.519 0 2.75 -1.231 2.75 -2.75 V 23.518 L 65.626 37.8 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 47.115 56.312 V 87.25 c 0 1.519 1.231 2.75 2.75 2.75 h 8.782 c 1.519 0 2.75 -1.231 2.75 -2.75 V 42.03 L 47.115 56.312 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 39.876 60.503 c -1.937 0 -3.757 -0.754 -5.127 -2.124 l -6.146 -6.145 V 87.25 c 0 1.519 1.231 2.75 2.75 2.75 h 8.782 c 1.519 0 2.75 -1.231 2.75 -2.75 V 59.844 C 41.952 60.271 40.933 60.503 39.876 60.503 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                <path d="M 22.937 46.567 L 11.051 58.453 c -0.298 0.298 -0.621 0.562 -0.959 0.8 V 87.25 c 0 1.519 1.231 2.75 2.75 2.75 h 8.782 c 1.519 0 2.75 -1.231 2.75 -2.75 V 48.004 L 22.937 46.567 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
            </g>
        </svg>
    `);
    container.append(button);
}
