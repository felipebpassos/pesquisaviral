$(document).ready(function () {
    var offset = 0;
    var postsPerPage = 30;

    // Variável para armazenar o tipo de mídia selecionado
    var selectedMediaType = "ALL"; // Padrão, exibindo todos os tipos

    function displayPosts(start, end) {
        for (var i = start; i < end; i++) {
            var result = medias[i];
            var postHTML = createPostHTML(result);
            $("#results-container-posts").append(postHTML);
        }
    }

    function createPostHTML(result) {
        var likes = result.like_count || 0;
        var comments = result.comments_count || 0;
        var postEngagement = ((parseInt(likes) + parseInt(comments)) / parseInt(results.followers_count)) * 100;

        var mediaHTML = '';
        if (result.media_type === 'IMAGE' || result.media_type === 'CAROUSEL_ALBUM') {
            mediaHTML = '<img src="' + result.media_url + '" class="img-fluid" alt="Image">';
        } else if (result.media_type === 'VIDEO') {
            if (result.media_url) {
                mediaHTML = '<video class="img-fluid" controls><source src="' + result.media_url + '"></video>';
            } else {
                mediaHTML = '<img src="' + result.thumbnail_url + '" class="img-fluid" alt="Video Thumbnail">';
            }
        }

        var postHTML =
            '<div class="col-md-4 post-container" data-likes="' + likes + '" data-comments="' + comments + '">' +
            '<div class="post">' +
            '<div class="media">' + mediaHTML + '</div>' +
            '<div class="bottom">' +
            '<footer><div class="date"></div>' +
            '<div class="buttons">' +
            '<a class="download-media" href="' + (result.media_url || result.thumbnail_url) + '" download>' +
            '<i class="fa-solid fa-cloud-arrow-down"></i></a>' +
            '<a class="link-post" href="' + (result.permalink || '#') + '" target="_blank">' +
            '<button class="btn-3 ver-post">Ver Post</button></a>' +
            '</div></footer>' +
            '<div class="captionBox">' +
            '<p class="caption">' + (result.caption || "") + '</p>' +
            '<button class="mostrar-mais">Mostrar Mais</button>' +
            '<div class="fade-bottom"></div>' +
            '</div>' +
            '<div class="social-counts">' +
            '<p><i class="fa-solid fa-heart"></i> ' + likes + '</p>' +
            '<p><i class="fa-solid fa-comment"></i> ' + comments + '</p>' +
            '<p style="margin-right: 0;"><i class="fa-solid fa-users"></i> ' + postEngagement.toFixed(2) + '%</p>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';

        return postHTML;
    }

    // Exibir os primeiros 30 posts ao carregar a página
    displayPosts(offset, offset + postsPerPage);
    offset += postsPerPage;

    $(".load-more-btn").on("click", function () {
        // Exibir mais 30 posts ao clicar no botão "Carregar Mais"
        if (offset + postsPerPage > medias.length) {
            displayPosts(offset, medias.length);
        } else {
            displayPosts(offset, offset + postsPerPage);
        }
        offset += postsPerPage;

        // Ocultar o botão se não houver mais posts para exibir
        if (offset >= medias.length) {
            $(".load-more-btn").hide();
        }
    });

    // Função para filtrar por tipo de mídia
    function filterByMediaType(medias, mediaType) {
        if (mediaType === "ALL") return medias; // Retorna todos se "ALL" estiver selecionado
        return medias.filter(function (media) {
            return media.media_type === mediaType;
        });
    }

    // Função para atualizar os resultados com base na opção de ordenação e no filtro de tipo
    function updateResults(orderBy) {
        var orderedMedias;

        // Define a lista ordenada com base na opção selecionada
        if (orderBy === "recent") {
            orderedMedias = resultsByTime;
        } else if (orderBy === "oldest") {
            orderedMedias = resultsByTime.slice().reverse();
        } else if (orderBy === "likes") {
            orderedMedias = resultsByLikes;
        } else if (orderBy === "comments") {
            orderedMedias = resultsByComments;
        }

        // Aplica o filtro de tipo ao resultado ordenado
        medias = filterByMediaType(orderedMedias, selectedMediaType);
    }

    // Evento para atualizar a ordenação ao selecionar no #order-select
    $("#order-select").on("change", function () {
        var selectedValue = $(this).val();
        updateResults(selectedValue);
        offset = 0;
        $("#results-container-posts").empty();
        displayPosts(offset, offset + postsPerPage);
        offset += postsPerPage;
        $(".load-more-btn").show();
    });

    // Evento para atualizar o filtro de tipo ao selecionar no #postType-select
    $("#postType-select").on("change", function () {
        selectedMediaType = $(this).val();
        var selectedOrder = $("#order-select").val();
        updateResults(selectedOrder);
        offset = 0;
        $("#results-container-posts").empty();
        displayPosts(offset, offset + postsPerPage);
        offset += postsPerPage;
        $(".load-more-btn").show();
    });
});