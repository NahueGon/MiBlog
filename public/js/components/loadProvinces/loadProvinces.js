document.addEventListener('DOMContentLoaded', function () {
    const countrySelect = document.getElementById('id_country');
    const provinceSelect = document.getElementById('id_province');
    
    const provinces = window.provinces || [];

    countrySelect.addEventListener('change', function () {
        const selectedCountryId = parseInt(this.value);
        
        // Limpio el select de provincias
        provinceSelect.innerHTML = '<option selected value""></option>';
        
        if (provinces.length > 0) {
            provinces
                .filter(province => province.country_id === selectedCountryId)
                .forEach(function (province) {
                    const option = document.createElement('option');

                    option.value = province.id;
                    option.textContent = province.name;

                    provinceSelect.appendChild(option);
                });
        }
    });
});
