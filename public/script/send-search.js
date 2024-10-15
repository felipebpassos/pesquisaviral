document.getElementById('search-btn').addEventListener('click', function() {
    var inputVal = document.getElementById('campoPesquisa').value;
    if (inputVal) {
        window.location.href = config.BASE_URL + 'search/?profile=' + encodeURIComponent(inputVal);
    }
});
