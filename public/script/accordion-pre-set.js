function criarSVG() {
	return `<svg width="15" height="10" viewBox="0 0 42 25">`
}

function criarPath() {
	return `<path d="M3 3L21 21L39 3" stroke="white" stroke-width="7" stroke-linecap="round"></path>`
}

function criarModulo(modulo, titulo) {
	var svg = criarSVG();
	var path = criarPath();
	var item = `Módulo ${modulo}: ${titulo} ${svg} ${path} </svg>`;
	return item;
}

document.addEventListener('DOMContentLoaded', function() {
	const listaModulos = document.getElementById('lista-modulos');

	const liModulos = Array.from(listaModulos.getElementsByClassName('uni'));

	for (let i = 0; i < liModulos.length; i++) {
		const item = liModulos[i]
		const modulo = item.getAttribute('data-modulo');
		const titulo = item.getAttribute('data-titulo');
		item.innerHTML = criarModulo(modulo, titulo);
	}
})