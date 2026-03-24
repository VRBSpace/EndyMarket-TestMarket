/**
 * Vehicle Selection Logic - Based on avtosklad.bg
 * Implements step-by-step vehicle selection with dynamic content display
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize the vehicle selection system
    initVehicleSelection();
    
    function initVehicleSelection() {
        // Populate brand dropdown from brand cards
        populateBrandDropdown();
        
        // Setup event listeners
        setupEventListeners();
        
        // Show initial step (brands)
        showStep('brands');
    }
    
    function populateBrandDropdown() {
        const brandDropdown = document.querySelector('.js-brand-dropdown');
        const brandCards = document.querySelectorAll('.js-brand-card');
        
        if (!brandDropdown || !brandCards.length) return;
        
        // Clear existing options except the first one
        brandDropdown.innerHTML = '<option value="">Избери марка</option>';
        
        // Add brands to dropdown
        brandCards.forEach(function(card) {
            const brandId = card.getAttribute('data-brand-id');
            const brandName = card.getAttribute('data-brand-name');
            
            const option = document.createElement('option');
            option.value = brandId;
            option.textContent = brandName;
            brandDropdown.appendChild(option);
        });
    }
    
    function setupEventListeners() {
        // Brand card clicks
        const brandCards = document.querySelectorAll('.js-brand-card');
        brandCards.forEach(function(card) {
            card.addEventListener('click', function() {
                handleBrandSelection(this);
            });
        });
        
        // Dropdown changes
        const brandDropdown = document.querySelector('.js-brand-dropdown');
        if (brandDropdown) {
            brandDropdown.addEventListener('change', function() {
                if (this.value) {
                    const brandCard = document.querySelector(`[data-brand-id="${this.value}"]`);
                    if (brandCard) {
                        handleBrandSelection(brandCard);
                    }
                } else {
                    showStep('brands');
                }
            });
        }
        
        const modelDropdown = document.querySelector('.js-model-dropdown');
        if (modelDropdown) {
            modelDropdown.addEventListener('change', function() {
                if (this.value) {
                    handleModelSelection(this.value);
                } else {
                    showStep('models');
                }
            });
        }
        
        const categoryDropdown = document.querySelector('.js-category-dropdown');
        if (categoryDropdown) {
            categoryDropdown.addEventListener('change', function() {
                if (this.value) {
                    handleCategorySelection(this.value);
                } else {
                    showStep('categories');
                }
            });
        }
        
        const subcategoryDropdown = document.querySelector('.js-subcategory-dropdown');
        if (subcategoryDropdown) {
            subcategoryDropdown.addEventListener('change', function() {
                if (this.value) {
                    handleSubcategorySelection(this.value);
                }
            });
        }
    }
    
    function handleBrandSelection(brandCard) {
        const brandId = brandCard.getAttribute('data-brand-id');
        const brandName = brandCard.getAttribute('data-brand-name');
        const brandData = JSON.parse(brandCard.getAttribute('data-brand-data'));
        
        // Update dropdown
        const brandDropdown = document.querySelector('.js-brand-dropdown');
        if (brandDropdown) {
            brandDropdown.value = brandId;
        }
        
        // Update visual selection
        updateBrandCardSelection(brandId);
        
        // Show models step
        showModelsStep(brandData);
        
        // Enable model dropdown
        const modelDropdown = document.querySelector('.js-model-dropdown');
        if (modelDropdown) {
            modelDropdown.disabled = false;
            populateModelDropdown(brandData);
        }
    }
    
    function handleModelSelection(modelName) {
        // Show categories step
        showCategoriesStep();
        
        // Enable category dropdown
        const categoryDropdown = document.querySelector('.js-category-dropdown');
        if (categoryDropdown) {
            categoryDropdown.disabled = false;
            populateCategoryDropdown();
        }
    }
    
    function handleCategorySelection(categoryId) {
        // Show subcategories step
        showSubcategoriesStep(categoryId);
        
        // Enable subcategory dropdown
        const subcategoryDropdown = document.querySelector('.js-subcategory-dropdown');
        if (subcategoryDropdown) {
            subcategoryDropdown.disabled = false;
            populateSubcategoryDropdown(categoryId);
        }
    }
    
    function handleSubcategorySelection(subcategoryId) {
        // Navigate to products page with all selected filters
        const brandId = document.querySelector('.js-brand-dropdown').value;
        const modelName = document.querySelector('.js-model-dropdown').value;
        const categoryId = document.querySelector('.js-category-dropdown').value;
        
        const url = `/shop?f_brandId=${brandId}&f_models=${encodeURIComponent(modelName)}&categoryId=${subcategoryId}`;
        window.location.href = url;
    }
    
    function showStep(stepName) {
        // Hide all steps
        const allSteps = document.querySelectorAll('.js-step');
        allSteps.forEach(function(step) {
            step.style.display = 'none';
        });
        
        // Show selected step
        const targetStep = document.querySelector(`.js-step-${stepName}`);
        if (targetStep) {
            targetStep.style.display = 'block';
        }
    }
    
    function showModelsStep(brandData) {
        const modelsStep = document.querySelector('.js-step-models');
        if (!modelsStep || !brandData.children) return;
        
        // Clear existing content except title
        const title = modelsStep.querySelector('h5');
        modelsStep.innerHTML = '';
        if (title) {
            modelsStep.appendChild(title);
        } else {
            modelsStep.innerHTML = '<div class="col-12"><h5 class="mb-3">Избери модел:</h5></div>';
        }
        
        // Add model cards
        brandData.children.forEach(function(model) {
            const modelCard = createModelCard(model);
            modelsStep.appendChild(modelCard);
        });
        
        showStep('models');
    }
    
    function showCategoriesStep() {
        const categoriesStep = document.querySelector('.js-step-categories');
        if (!categoriesStep) return;
        
        // Clear existing content except title
        const title = categoriesStep.querySelector('h5');
        categoriesStep.innerHTML = '';
        if (title) {
            categoriesStep.appendChild(title);
        } else {
            categoriesStep.innerHTML = '<div class="col-12"><h5 class="mb-3">Избери категория:</h5></div>';
        }
        
        // Get categories from global data (passed from controller)
        if (typeof categories !== 'undefined') {
            categories.forEach(function(category) {
                const categoryCard = createCategoryCard(category);
                categoriesStep.appendChild(categoryCard);
            });
        }
        
        showStep('categories');
    }
    
    function showSubcategoriesStep(categoryId) {
        const subcategoriesStep = document.querySelector('.js-step-subcategories');
        if (!subcategoriesStep) return;
        
        // Clear existing content except title
        const title = subcategoriesStep.querySelector('h5');
        subcategoriesStep.innerHTML = '';
        if (title) {
            subcategoriesStep.appendChild(title);
        } else {
            subcategoriesStep.innerHTML = '<div class="col-12"><h5 class="mb-3">Избери подкатегория:</h5></div>';
        }
        
        // Get subcategories for selected category
        if (typeof categories !== 'undefined') {
            const selectedCategory = categories.find(cat => cat.category_id == categoryId);
            if (selectedCategory && selectedCategory.children) {
                selectedCategory.children.forEach(function(subcategory) {
                    const subcategoryCard = createSubcategoryCard(subcategory);
                    subcategoriesStep.appendChild(subcategoryCard);
                });
            }
        }
        
        showStep('subcategories');
    }
    
    function createModelCard(model) {
        const div = document.createElement('div');
        div.className = 'col-6 col-lg-3 mb-3';
        
        div.innerHTML = `
            <div class="css-model-card js-model-card text-center p-3 bg-white border rounded cursor-pointer" 
                 data-model-name="${model.model}">
                <div class="css-model-placeholder mb-2 d-flex align-items-center justify-content-center" 
                     style="height: 80px; background: #f8f9fa; border-radius: 4px;">
                    <i class="fas fa-car fa-2x text-muted"></i>
                </div>
                <h6 class="mb-0 font-weight-bold">${model.model}</h6>
            </div>
        `;
        
        // Add click event
        const card = div.querySelector('.js-model-card');
        card.addEventListener('click', function() {
            const modelName = this.getAttribute('data-model-name');
            
            // Update dropdown
            const modelDropdown = document.querySelector('.js-model-dropdown');
            if (modelDropdown) {
                modelDropdown.value = modelName;
            }
            
            // Update visual selection
            updateModelCardSelection(modelName);
            
            handleModelSelection(modelName);
        });
        
        return div;
    }
    
    function createCategoryCard(category) {
        const div = document.createElement('div');
        div.className = 'col-6 col-lg-3 mb-3';
        
        div.innerHTML = `
            <div class="css-category-card js-category-card text-center p-3 bg-white border rounded cursor-pointer" 
                 data-category-id="${category.category_id}">
                <div class="css-category-placeholder mb-2 d-flex align-items-center justify-content-center" 
                     style="height: 80px; background: #f8f9fa; border-radius: 4px;">
                    <i class="fas fa-cogs fa-2x text-muted"></i>
                </div>
                <h6 class="mb-0 font-weight-bold">${category.category_name}</h6>
            </div>
        `;
        
        // Add click event
        const card = div.querySelector('.js-category-card');
        card.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-category-id');
            
            // Update dropdown
            const categoryDropdown = document.querySelector('.js-category-dropdown');
            if (categoryDropdown) {
                categoryDropdown.value = categoryId;
            }
            
            // Update visual selection
            updateCategoryCardSelection(categoryId);
            
            handleCategorySelection(categoryId);
        });
        
        return div;
    }
    
    function createSubcategoryCard(subcategory) {
        const div = document.createElement('div');
        div.className = 'col-6 col-lg-3 mb-3';
        
        div.innerHTML = `
            <div class="css-subcategory-card js-subcategory-card text-center p-3 bg-white border rounded cursor-pointer" 
                 data-subcategory-id="${subcategory.category_id}">
                <div class="css-subcategory-placeholder mb-2 d-flex align-items-center justify-content-center" 
                     style="height: 80px; background: #f8f9fa; border-radius: 4px;">
                    <i class="fas fa-wrench fa-2x text-muted"></i>
                </div>
                <h6 class="mb-0 font-weight-bold">${subcategory.category_name}</h6>
            </div>
        `;
        
        // Add click event
        const card = div.querySelector('.js-subcategory-card');
        card.addEventListener('click', function() {
            const subcategoryId = this.getAttribute('data-subcategory-id');
            
            // Update dropdown
            const subcategoryDropdown = document.querySelector('.js-subcategory-dropdown');
            if (subcategoryDropdown) {
                subcategoryDropdown.value = subcategoryId;
            }
            
            // Update visual selection
            updateSubcategoryCardSelection(subcategoryId);
            
            handleSubcategorySelection(subcategoryId);
        });
        
        return div;
    }
    
    function populateModelDropdown(brandData) {
        const modelDropdown = document.querySelector('.js-model-dropdown');
        if (!modelDropdown || !brandData.children) return;
        
        // Clear existing options
        modelDropdown.innerHTML = '<option value="">Избери модел</option>';
        
        // Add models to dropdown
        brandData.children.forEach(function(model) {
            const option = document.createElement('option');
            option.value = model.model;
            option.textContent = model.model;
            modelDropdown.appendChild(option);
        });
    }
    
    function populateCategoryDropdown() {
        const categoryDropdown = document.querySelector('.js-category-dropdown');
        if (!categoryDropdown || typeof categories === 'undefined') return;
        
        // Clear existing options
        categoryDropdown.innerHTML = '<option value="">Избери категория</option>';
        
        // Add categories to dropdown
        categories.forEach(function(category) {
            const option = document.createElement('option');
            option.value = category.category_id;
            option.textContent = category.category_name;
            categoryDropdown.appendChild(option);
        });
    }
    
    function populateSubcategoryDropdown(categoryId) {
        const subcategoryDropdown = document.querySelector('.js-subcategory-dropdown');
        if (!subcategoryDropdown || typeof categories === 'undefined') return;
        
        // Clear existing options
        subcategoryDropdown.innerHTML = '<option value="">Избери подкатегория</option>';
        
        // Find selected category and add its children
        const selectedCategory = categories.find(cat => cat.category_id == categoryId);
        if (selectedCategory && selectedCategory.children) {
            selectedCategory.children.forEach(function(subcategory) {
                const option = document.createElement('option');
                option.value = subcategory.category_id;
                option.textContent = subcategory.category_name;
                subcategoryDropdown.appendChild(option);
            });
        }
    }
    
    // Visual selection update functions
    function updateBrandCardSelection(selectedBrandId) {
        const brandCards = document.querySelectorAll('.js-brand-card');
        brandCards.forEach(function(card) {
            const brandId = card.getAttribute('data-brand-id');
            if (brandId === selectedBrandId) {
                card.classList.add('css-selected');
            } else {
                card.classList.remove('css-selected');
            }
        });
    }
    
    function updateModelCardSelection(selectedModelName) {
        const modelCards = document.querySelectorAll('.js-model-card');
        modelCards.forEach(function(card) {
            const modelName = card.getAttribute('data-model-name');
            if (modelName === selectedModelName) {
                card.classList.add('css-selected');
            } else {
                card.classList.remove('css-selected');
            }
        });
    }
    
    function updateCategoryCardSelection(selectedCategoryId) {
        const categoryCards = document.querySelectorAll('.js-category-card');
        categoryCards.forEach(function(card) {
            const categoryId = card.getAttribute('data-category-id');
            if (categoryId === selectedCategoryId) {
                card.classList.add('css-selected');
            } else {
                card.classList.remove('css-selected');
            }
        });
    }
    
    function updateSubcategoryCardSelection(selectedSubcategoryId) {
        const subcategoryCards = document.querySelectorAll('.js-subcategory-card');
        subcategoryCards.forEach(function(card) {
            const subcategoryId = card.getAttribute('data-subcategory-id');
            if (subcategoryId === selectedSubcategoryId) {
                card.classList.add('css-selected');
            } else {
                card.classList.remove('css-selected');
            }
        });
    }
});
