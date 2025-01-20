document.addEventListener('DOMContentLoaded', function () {
    // TEMA FONDO CLARO-OSCURO
    const light_btn = document.getElementById('light_btn');
    const dark_btn = document.getElementById('dark_btn');
    const main = document.querySelector('main');
    const titulo = document.getElementById('titulo');

    // Función para tema claro
    function setLightTema() {
        main.style.backgroundImage = 'url("/proyecto_gym_MVC/img/fondo7.jpeg")';
        main.style.backgroundRepeat = 'no-repeat';
        main.style.backgroundSize = 'cover';
        main.style.color = '#2f5b96';
        titulo.style.color= '#2f5b96'; 
        localStorage.setItem('tema', 'light');
    }

    // Función para tema oscuro
    function setDarkTema() {
        main.style.backgroundImage = 'url("/proyecto_gym_MVC/img/fondo6.jpg")';
        main.style.backgroundRepeat = 'no-repeat';
        main.style.backgroundSize = 'cover';
        main.style.color = '#fff';
        titulo.style.color= '#f1be69'; 
        localStorage.setItem('tema', 'dark');
    }

    // Event listeners para los botones
    light_btn.addEventListener('click', setLightTema);
    dark_btn.addEventListener('click', setDarkTema);

    // Cargar el tema guardado al iniciar la página
    const tema_preferido = localStorage.getItem('tema');
    if (tema_preferido === 'light') {
        setLightTema();
    } else if (tema_preferido === 'dark') {
        setDarkTema();
    } else {
        setLightTema(); // Tema por defecto
    }

    // FUNCION PARA EL TÍTULO
    function iniciarAnimacion() {
        const titulo = document.getElementById('titulo');
        if (!titulo) {
            console.error("Elemento con id 'titulo' no encontrado.");
            return;
        }
        const texto = titulo.textContent;
        titulo.innerHTML = ''; // Limpiamos el contenido del elemento

        texto.split('').forEach((caracter, i) => {
            const span = document.createElement('span');
            span.textContent = caracter === ' ' ? '\u00A0' : caracter; // Preservamos los espacios
            span.className = 'letra';
            span.style.animationDelay = `${i * 0.1}s`; // Retraso escalonado para efecto de escritura manual
            titulo.appendChild(span);
        });
    }

    // Iniciar animación al cargar
    iniciarAnimacion();
});
