// Vehicle Selection Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Enable first dropdown (brand) on page load
    const brandDropdown = document.querySelector('.js-brand-dropdown');
    if (brandDropdown) {
        brandDropdown.disabled = false;
        
        // Get brands data from brand cards
        const brandCards = document.querySelectorAll('.js-brand-card');
        brandCards.forEach(function(card) {
            const brandId = card.getAttribute('data-brand-id');
            const brandName = card.getAttribute('data-brand-name');
            
            if (brandId && brandName) {
                const option = document.createElement('option');
                option.value = brandId;
                option.textContent = brandName;
                brandDropdown.appendChild(option);
            }
        });
    }

    // Handle brand dropdown change
    if (brandDropdown) {
        brandDropdown.addEventListener('change', function() {
            const selectedBrandId = this.value;
            
            // Update brand cards selection
            updateBrandCardSelection(selectedBrandId);
            
            // Enable and populate models dropdown if brand is selected
            if (selectedBrandId) {
                populateModelsDropdown(selectedBrandId);
            } else {
                const modelDropdown = document.querySelector('.js-model-dropdown');
                if (modelDropdown) {
                    modelDropdown.disabled = true;
                    modelDropdown.innerHTML = '<option value="">Избери модел</option>';
                }
            }
            
            // Disable subsequent dropdowns
            disableSubsequentDropdowns([
                '.js-year-dropdown', 
                '.js-subcategory-dropdown', 
                '.js-subsubcategory-dropdown', 
                '.js-subsubsubcategory-dropdown'
            ]);
        });
    }

    // Handle model dropdown change
    const modelDropdown = document.querySelector('.js-model-dropdown');
    if (modelDropdown) {
        modelDropdown.addEventListener('change', function() {
            const selectedModel = this.value;
            const selectedBrandId = brandDropdown ? brandDropdown.value : null;
            
            if (selectedModel && selectedBrandId) {
                // Navigate to the shop page with selected brand and model
                const brandName = brandDropdown.options[brandDropdown.selectedIndex].text;
                const url = `/shop?catToModel=1&f_brandId=${selectedBrandId}&brandTxt=${encodeURIComponent(brandName)}&f_models=${encodeURIComponent(selectedModel)}`;
                window.location.href = url;
            }
            
            // Enable next dropdown if model is selected
            const yearDropdown = document.querySelector('.js-year-dropdown');
            if (selectedModel && yearDropdown) {
                yearDropdown.disabled = false;
                // Here you could populate years if needed
            }
        });
    }

    // Handle brand card clicks
    const brandCards = document.querySelectorAll('.js-brand-card');
    brandCards.forEach(function(card) {
        card.addEventListener('click', function() {
            const brandId = this.getAttribute('data-brand-id');
            const brandName = this.getAttribute('data-brand-name');
            
            // Update dropdown selection
            if (brandDropdown) {
                brandDropdown.value = brandId;
                
                // Trigger change event to populate models
                const event = new Event('change');
                brandDropdown.dispatchEvent(event);
            }
            
            // Update visual selection
            updateBrandCardSelection(brandId);
            
            // Populate models dropdown
            populateModelsDropdown(brandId);
        });
    });

    // Function to populate models dropdown
    function populateModelsDropdown(brandId) {
        const modelDropdown = document.querySelector('.js-model-dropdown');
        if (!modelDropdown) return;
        
        // Clear existing options
        modelDropdown.innerHTML = '<option value="">Избери модел</option>';
        
        // Find the brand data from the card
        const brandCard = document.querySelector(`[data-brand-id="${brandId}"]`);
        if (!brandCard) return;
        
        // Get models from the original model block (if exists)
        const modelBlock = document.getElementById('block-prodModel');
        if (modelBlock) {
            // Find the corresponding brand section in model block
            const brandLinks = modelBlock.querySelectorAll('a[href*="f_brandId=' + brandId + '"]');
            const models = new Set(); // Use Set to avoid duplicates
            
            brandLinks.forEach(function(link) {
                const href = link.getAttribute('href');
                const modelMatch = href.match(/f_models=([^&]+)/);
                if (modelMatch) {
                    const modelName = decodeURIComponent(modelMatch[1]);
                    models.add(modelName);
                }
            });
            
            // Add models to dropdown
            models.forEach(function(modelName) {
                const option = document.createElement('option');
                option.value = modelName;
                option.textContent = modelName;
                modelDropdown.appendChild(option);
            });
        }
        
        // Enable the models dropdown
        modelDropdown.disabled = false;
    }

    function updateBrandCardSelection(selectedBrandId) {
        const brandCards = document.querySelectorAll('.js-brand-card');
        brandCards.forEach(function(card) {
            const brandId = card.getAttribute('data-brand-id');
            if (brandId === selectedBrandId) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
    }

    function disableSubsequentDropdowns(dropdownSelectors) {
        dropdownSelectors.forEach(function(selector) {
            const dropdown = document.querySelector(selector);
            if (dropdown) {
                dropdown.disabled = true;
                dropdown.innerHTML = '<option value="">Избери ' + getDropdownLabel(selector) + '</option>';
            }
        });
    }

    function getDropdownLabel(selector) {
        const labels = {
            '.js-model-dropdown': 'модел',
            '.js-year-dropdown': 'година',
            '.js-subcategory-dropdown': 'подкатегория',
            '.js-subsubcategory-dropdown': 'под-подкатегория',
            '.js-subsubsubcategory-dropdown': 'под-под-подкатегория'
        };
        return labels[selector] || '';
    }
});
