function openModal(mode) {
    const sections = ['profileFields', 'descriptionFields', 'imageFields', 'locationFields', 'backgroundImageFields', 'postFields'];

    sections.forEach(section => {
        const element = document.getElementById(section);
        if (!element) return; 
        
        if (section === mode + 'Fields') {
            element.classList.remove('hidden');
            element.classList.add('block');

            if (section === 'locationFields') {
                resetLocationFields();
            }
        } else {
            element.classList.remove('block');
            element.classList.add('hidden');
        }
    });

}

function resetLocationFields() {
    const countrySelect = document.getElementById('id_country');
    const provinceSelect = document.getElementById('id_province');

    if (countrySelect) countrySelect.selectedIndex = 0;
    if (provinceSelect) {
        provinceSelect.innerHTML = '<option value="" selected>Seleccionar Provincia</option>';
    }
}

function openImageInput(inputType) {
    const imageInput = document.getElementById('imageInput');
    const backgroundImageInput = document.getElementById('backgroundImageInput');

    // Ocultar ambos inputs antes de mostrar el adecuado
    if (imageInput && backgroundImageInput) {
        imageInput.style.display = 'none';
        backgroundImageInput.style.display = 'none';
    }

    if (inputType === 'image') {
        imageInput.style.display = 'block';  // Mostrar el input de imagen
        imageInput.click();  // Activar el input de imagen
    } else if (inputType === 'background') {
        backgroundImageInput.style.display = 'block';  // Mostrar el input de fondo
        backgroundImageInput.click();  // Activar el input de fondo
    }
}

function openFileInput() {
    const fileInput = document.getElementById('fileInput');
    if (fileInput) {
        fileInput.click();
    }
}

document.querySelectorAll('.toggle-comment-form').forEach(button => {
    button.addEventListener('click', () => {
        const postId = button.getAttribute('data-post-id');
        const form = document.getElementById(`comment-form-${postId}`);
        form.classList.toggle('hidden');
    });
});

document.querySelectorAll('.toggle-comments').forEach(element => {
    element.addEventListener('click', () => {
        const postId = element.getAttribute('data-post-id');
        const commentsDiv = document.getElementById(`comments-${postId}`);
        commentsDiv.classList.toggle('hidden');
    });
});

document.addEventListener('DOMContentLoaded', () => {
    // Seleccionar los botones y el contenedor del carousel
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const carousel = document.getElementById('indicators-carousel');
    const carouselItems = carousel.querySelector('div');

    // Definir la cantidad de desplazamiento (ajustar según sea necesario)
    const scrollAmount = 300; // Desplazar 300px por cada clic

    // Evento para mover el scroll hacia la izquierda
    prevBtn.addEventListener('click', () => {
        carousel.scrollBy({
            left: -scrollAmount,
            behavior: 'smooth'
        });
    });

    // Evento para mover el scroll hacia la derecha
    nextBtn.addEventListener('click', () => {
        carousel.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    });
});