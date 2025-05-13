document.addEventListener("DOMContentLoaded", function () {
    lucide.createIcons();

});

function toggleVisibilityButton(input) {
    const toggleBtn = document.getElementById('toggle-' + input.id);
    toggleBtn.classList.toggle('hidden', input.value.trim() === '');
}

function togglePassword(id, btn) {
    const input = document.getElementById(id);
    const icon = btn.querySelector('svg');

    if (input.type === 'password') {
        input.type = 'text';
        icon.setAttribute('data-lucide', 'eye-off');
    } else {
        input.type = 'password';
        icon.setAttribute('data-lucide', 'eye');
    }

    lucide.createIcons();
}