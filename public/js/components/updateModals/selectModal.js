function openModal(mode) {
    const sections = [
        'profileFields', 'descriptionFields', 'imageFields',
        'locationFields', 'backgroundImageFields', 'postFields',
    ];

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

function toggleHidden(mode) {
    const element = document.getElementById(mode);

    if (!element) return;

    const isHidden = element.classList.contains('hidden');

    if (isHidden) {
        element.classList.remove('hidden');
        element.classList.add('block');
    } else {
        element.classList.remove('block');
        element.classList.add('hidden');
    }
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
    const profileImageInput = document.getElementById('profileImageInput');
    const backgroundImageInput = document.getElementById('backgroundImageInput');

    // Ocultar ambos inputs antes de mostrar el adecuado
    if (profileImageInput && backgroundImageInput) {
        profileImageInput.style.display = 'none';
        backgroundImageInput.style.display = 'none';
    }

    if (inputType === 'image') {
        profileImageInput.style.display = 'block';  // Mostrar el input de imagen
        profileImageInput.click();  // Activar el input de imagen
    } else if (inputType === 'background') {
        backgroundImageInput.style.display = 'block';  // Mostrar el input de fondo
        backgroundImageInput.click();  // Activar el input de fondo
    }
}

function openFileInput() {
    const filePost = document.getElementById('file');
    if (filePost) {
        filePost.click();
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