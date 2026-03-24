// Vehicle Selection Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Enable first dropdown (brand) on page load
    const brandDropdown = document.getElementById('brand-dropdown');
    if (brandDropdown) {
        brandDropdown.disabled = false;
        
        // Populate brand dropdown with available brands
        if (window.BRANDS_DATA) {
            const brands = window.BRANDS_DATA;
            brands.forEach(function(brand) {
                const option = document.createElement('option');
                option.value = brand.brand_id;
                option.textContent = brand.brandTxt;
                brandDropdown.appendChild(option);
            });
        }
    }

    // Handle brand dropdown change
    if (brandDropdown) {
        brandDropdown.addEventListener('change', function() {
            const selectedBrandId = this.value;
            
            // Update brand cards selection
            updateBrandCardSelection(selectedBrandId);
            
            // Enable next dropdown if brand is selected
            const modelDropdown = document.getElementById('model-dropdown');
            if (selectedBrandId && modelDropdown) {
                modelDropdown.disabled = false;
                // Here you would populate models for the selected brand
                // For now, just enable it
            } else if (modelDropdown) {
                modelDropdown.disabled = true;
                modelDropdown.innerHTML = '<option value="">Избери модел</option>';
            }
            
            // Disable subsequent dropdowns
            disableSubsequentDropdowns(['year-dropdown', 'engine-dropdown', 'fuel-dropdown', 'power-dropdown']);
        });
    }

    // Handle brand card clicks
    const brandCards = document.querySelectorAll('.css-brand-card');
    brandCards.forEach(function(card) {
        card.addEventListener('click', function() {
            const brandId = this.getAttribute('data-brand-id');
            const brandName = this.getAttribute('data-brand-name');
            
            // Update dropdown selection
            if (brandDropdown) {
                brandDropdown.value = brandId;
                
                // Trigger change event
                const event = new Event('change');
                brandDropdown.dispatchEvent(event);
            }
            
            // Update visual selection
            updateBrandCardSelection(brandId);
        });
    });

    function updateBrandCardSelection(selectedBrandId) {
        const brandCards = document.querySelectorAll('.css-brand-card');
        brandCards.forEach(function(card) {
            const brandId = card.getAttribute('data-brand-id');
            if (brandId === selectedBrandId) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
    }

    function disableSubsequentDropdowns(dropdownIds) {
        dropdownIds.forEach(function(id) {
            const dropdown = document.getElementById(id);
            if (dropdown) {
                dropdown.disabled = true;
                dropdown.innerHTML = '<option value="">Избери ' + getDropdownLabel(id) + '</option>';
            }
        });
    }

    function getDropdownLabel(dropdownId) {
        const labels = {
            'model-dropdown': 'модел',
            'year-dropdown': 'година',
            'engine-dropdown': 'двигател',
            'fuel-dropdown': 'гориво',
            'power-dropdown': 'мощност'
        };
        return labels[dropdownId] || '';
    }
});
