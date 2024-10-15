$(document).ready(function () {
    var offset = 0;
    var postsPerPage = 30;

    function displayPosts(start, end) {
        for (var i = start; i < end; i++) {
            var result = medias[i];
            var postHTML = createPostHTML(result);
            $("#results-container").append(postHTML);
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
            '<button class="btn-3">Ver Post</button></a>' +
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

    // Função para atualizar os resultados com base na opção selecionada
    function updateResults(orderBy) {
        if (orderBy === "recent") {
            medias = resultsByTime;
        } else if (orderBy === "oldest") {
            medias = resultsByTime.slice().reverse(); 
        } else if (orderBy === "likes") {
            medias = resultsByLikes;
        } else if (orderBy === "comments") {
            medias = resultsByComments;
        }
    }

    $("#order-select").on("change", function () {
        var selectedValue = $(this).val();
        updateResults(selectedValue); // Atualizar os resultados com base na opção selecionada
        offset = 0; // Reiniciar o offset
        $("#results-container").empty(); // Esvaziar o container
        displayPosts(offset, offset + postsPerPage); // Exibir os primeiros 30 posts
        offset += postsPerPage;
        $(".load-more-btn").show();
    });
});