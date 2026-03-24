/**
 * Йерархичен филтър за shop страницата
 * Управлява dropdown менютата за марка, модел, година и категории
 */

class HierarchyFilter {
    constructor() {
        this.currentLevel = 1;
        this.selectedData = {
            brandId: '',
            brandTxt: '',
            model: '',
            year: '',
            categoryId: ''
        };
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadInitialBrands();
        console.log('HierarchyFilter initialized');
    }

    bindEvents() {
        // Dropdown change events
        $('#js-hierarchy-brand').on('change', (e) => {
            console.log('Brand dropdown changed:', e.target.value);
            this.onBrandChange(e);
        });
        
        $('#js-hierarchy-model').on('change', (e) => {
            console.log('Model dropdown changed:', e.target.value);
            this.onModelChange(e);
        });
        
        $('#js-hierarchy-year').on('change', (e) => {
            console.log('Year dropdown changed:', e.target.value);
            this.onYearChange(e);
        });
        
        $('#js-hierarchy-category1').on('change', (e) => this.onCategoryChange(e, 1));
        $('#js-hierarchy-category2').on('change', (e) => this.onCategoryChange(e, 2));
        $('#js-hierarchy-category3').on('change', (e) => this.onCategoryChange(e, 3));
        
        // Click events for results
        $(document).on('click', '.css-hierarchy-option', (e) => {
            console.log('Result clicked:', e.currentTarget);
            this.onResultClick(e);
        });
    }

    onResultClick(e) {
        const $element = $(e.currentTarget);
        const action = $element.data('action');
        
        switch (action) {
            case 'selectBrand':
                const brandId = $element.data('brand-id');
                const brandTxt = $element.data('brand-txt');
                this.selectBrand(brandId, brandTxt);
                break;
            case 'selectModel':
                const model = $element.data('model');
                this.selectModel(model);
                break;
            case 'selectYear':
                const year = $element.data('year');
                this.selectYear(year);
                break;
            case 'selectCategory':
                const categoryId = $element.data('category-id');
                this.selectCategory(categoryId);
                break;
        }
    }

    onBrandChange(e) {
        const brandId = $(e.target).val();
        const brandTxt = $(e.target).find('option:selected').text();
        
        console.log('Brand selected:', brandId, brandTxt);
        
        this.selectedData.brandId = brandId;
        this.selectedData.brandTxt = brandTxt;
        
        if (brandId) {
            this.currentLevel = 2;
            this.enableDropdown('#js-hierarchy-model');
            this.loadModels(brandId);
            this.updateResultsTitle('Модели за ' + brandTxt);
        } else {
            this.resetFromLevel(2);
            this.updateResultsTitle('Всички марки');
        }
    }

    onModelChange(e) {
        const model = $(e.target).val();
        this.selectedData.model = model;
        
        if (model) {
            this.currentLevel = 3;
            this.enableDropdown('#js-hierarchy-year');
            this.loadYears(this.selectedData.brandId, model);
            this.updateResults(3);
        } else {
            this.resetFromLevel(3);
        }
    }

    onYearChange(e) {
        const year = $(e.target).val();
        this.selectedData.year = year;
        
        if (year) {
            this.currentLevel = 4;
            this.enableDropdown('#js-hierarchy-category1');
            this.loadCategories(4);
            this.updateResults(4);
        } else {
            this.resetFromLevel(4);
        }
    }

    onCategoryChange(e, categoryLevel) {
        const categoryId = $(e.target).val();
        this.selectedData.categoryId = categoryId;
        
        if (categoryId) {
            const nextLevel = 4 + categoryLevel;
            this.currentLevel = nextLevel;
            
            if (categoryLevel < 3) {
                const nextDropdown = `#js-hierarchy-category${categoryLevel + 1}`;
                this.enableDropdown(nextDropdown);
                this.loadCategories(nextLevel, categoryId);
            }
            
            this.updateResults(nextLevel);
        } else {
            this.resetFromLevel(4 + categoryLevel);
        }
    }

    loadModels(brandId) {
        console.log('Loading models for brand:', brandId);
        this.showLoading('#js-hierarchy-model');
        
        $.ajax({
            url: '/shop/getHierarchyModels',
            method: 'POST',
            data: { brandId: brandId },
            dataType: 'json',
            success: (response) => {
                console.log('Models response:', response);
                this.hideLoading('#js-hierarchy-model');
                
                if (response.success && response.models) {
                    const modelOptions = response.models.map(model => ({
                        value: model.model || model.product_model_id,
                        text: model.model
                    }));
                    this.populateDropdown('#js-hierarchy-model', modelOptions, 'Избери модел');
                    this.displayModelsInResults(response.models);
                } else {
                    console.error('No models found or error in response');
                    this.populateDropdown('#js-hierarchy-model', [], 'Няма модели');
                }
            },
            error: (xhr, status, error) => {
                console.error('AJAX error loading models:', error);
                this.hideLoading('#js-hierarchy-model');
                this.populateDropdown('#js-hierarchy-model', [], 'Грешка при зареждане');
            }
        });
    }

    loadYears(brandId, model) {
        this.showLoading('#js-hierarchy-year');
        
        $.ajax({
            url: '/shop/getHierarchyYears',
            method: 'POST',
            data: { brandId: brandId, model: model },
            dataType: 'json',
            success: (response) => {
                this.hideLoading('#js-hierarchy-year');
                
                if (response.success) {
                    this.populateDropdown('#js-hierarchy-year', response.years, 'Избери година');
                } else {
                    this.showError('Грешка при зареждане на годините');
                }
            },
            error: () => {
                this.hideLoading('#js-hierarchy-year');
                this.showError('Грешка при свързване със сървъра');
            }
        });
    }

    loadCategories(level, parentId = null) {
        const dropdownId = level === 4 ? '#js-hierarchy-category1' : 
                          level === 5 ? '#js-hierarchy-category2' : '#js-hierarchy-category3';
        
        this.showLoading(dropdownId);
        
        $.ajax({
            url: '/shop/getHierarchyCategories',
            method: 'POST',
            data: {
                level: level,
                parentId: parentId,
                brandId: this.selectedData.brandId,
                model: this.selectedData.model,
                year: this.selectedData.year
            },
            dataType: 'json',
            success: (response) => {
                this.hideLoading(dropdownId);
                
                if (response.success) {
                    const placeholder = level === 4 ? 'Избери категория' :
                                       level === 5 ? 'Избери подкатегория' : 'Избери под-подкатегория';
                    this.populateDropdown(dropdownId, response.categories, placeholder);
                } else {
                    this.showError('Грешка при зареждане на категориите');
                }
            },
            error: () => {
                this.hideLoading(dropdownId);
                this.showError('Грешка при свързване със сървъра');
            }
        });
    }

    updateResults(level) {
        this.showResultsLoading();
        
        $.ajax({
            url: '/shop/getHierarchyResults',
            method: 'POST',
            data: {
                level: level,
                brandId: this.selectedData.brandId,
                model: this.selectedData.model,
                year: this.selectedData.year,
                categoryId: this.selectedData.categoryId
            },
            dataType: 'json',
            success: (response) => {
                this.hideResultsLoading();
                
                if (response.success) {
                    this.displayResults(response.results, response.title, response.count, response.level);
                } else {
                    this.showError('Грешка при зареждане на резултатите');
                }
            },
            error: () => {
                this.hideResultsLoading();
                this.showError('Грешка при свързване със сървъра');
            }
        });
    }

    displayResults(results, title, count, level) {
        $('#js-results-title').text(title);
        $('#js-results-count').text(count);
        
        let html = '';
        
        if (level === 'products') {
            // Показваме продукти
            html = this.renderProducts(results);
        } else {
            // Показваме списък с опции
            html = this.renderOptions(results, level);
        }
        
        $('#js-results-content').html(html);
    }

    renderProducts(products) {
        if (products.length === 0) {
            return '<div class="text-center py-4"><p class="text-muted">Няма намерени продукти</p></div>';
        }
        
        let html = '<div class="row">';
        
        products.forEach(product => {
            html += `
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">${product.product_name}</h6>
                            <p class="card-text">
                                <small class="text-muted">Марка: ${product.brandTxt}</small><br>
                                <small class="text-muted">Модел: ${product.model || 'N/A'}</small>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-primary fw-bold">${product.price || 'По запитване'}</span>
                                <a href="/shop/product/${product.product_id}" class="btn btn-sm btn-outline-primary">
                                    Виж повече
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        return html;
    }

    renderOptions(options, level) {
        if (options.length === 0) {
            return '<div class="text-center py-4"><p class="text-muted">Няма налични опции</p></div>';
        }
        
        let html = '<div class="list-group list-group-flush">';
        
        options.forEach(option => {
            let displayText = '';
            let dataAttrs = '';
            
            switch (level) {
                case 1:
                case '1': // Марки
                    displayText = option.brandTxt || option.brand_name;
                    dataAttrs = `data-brand-id="${option.brand_id}" data-brand-txt="${displayText}" data-action="selectBrand"`;
                    break;
                case 2:
                case '2': // Модели
                    displayText = option.model;
                    dataAttrs = `data-model="${option.model}" data-action="selectModel"`;
                    break;
                case 3:
                case '3': // Години
                    displayText = option.year || option.value || option.text;
                    dataAttrs = `data-year="${displayText}" data-action="selectYear"`;
                    break;
                default: // Категории
                    displayText = option.category_name || option.name || option.text;
                    dataAttrs = `data-category-id="${option.category_id || option.id || option.value}" data-action="selectCategory"`;
                    break;
            }
            
            html += `
                <a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center css-hierarchy-option" ${dataAttrs}>
                    <span>${displayText}</span>
                    <i class="fas fa-chevron-right text-muted"></i>
                </a>
            `;
        });
        
        html += '</div>';
        return html;
    }

    // Методи за директно избиране от резултатите
    selectBrand(brandId, brandTxt) {
        console.log('Selecting brand:', brandId, brandTxt);
        $('#js-hierarchy-brand').val(brandId);
        
        // Manually trigger the change event
        this.selectedData.brandId = brandId;
        this.selectedData.brandTxt = brandTxt;
        this.currentLevel = 2;
        this.enableDropdown('#js-hierarchy-model');
        this.loadModels(brandId);
        this.updateResultsTitle('Модели за ' + brandTxt);
    }

    selectModel(model) {
        console.log('Selecting model:', model);
        $('#js-hierarchy-model').val(model);
        
        // Manually trigger the change event
        this.selectedData.model = model;
        this.currentLevel = 3;
        this.enableDropdown('#js-hierarchy-year');
        this.loadYears(this.selectedData.brandId, model);
        this.updateResultsTitle('Години за ' + model);
    }

    selectYear(year) {
        $('#js-hierarchy-year').val(year).trigger('change');
    }

    selectCategory(categoryId) {
        const activeDropdown = this.currentLevel === 4 ? '#js-hierarchy-category1' :
                              this.currentLevel === 5 ? '#js-hierarchy-category2' : '#js-hierarchy-category3';
        $(activeDropdown).val(categoryId).trigger('change');
    }

    // Utility методи
    populateDropdown(selector, options, placeholder) {
        const $dropdown = $(selector);
        $dropdown.empty().append(`<option value="">${placeholder}</option>`);
        
        options.forEach(option => {
            $dropdown.append(`<option value="${option.value}">${option.text}</option>`);
        });
    }

    enableDropdown(selector) {
        $(selector).prop('disabled', false).removeClass('bg-light');
    }

    disableDropdown(selector) {
        $(selector).prop('disabled', true).addClass('bg-light').val('');
    }

    resetFromLevel(level) {
        const dropdowns = ['#js-hierarchy-model', '#js-hierarchy-year', '#js-hierarchy-category1', '#js-hierarchy-category2', '#js-hierarchy-category3'];
        const levelMap = { 2: 0, 3: 1, 4: 2, 5: 3, 6: 4 };
        
        for (let i = levelMap[level]; i < dropdowns.length; i++) {
            this.disableDropdown(dropdowns[i]);
        }
        
        // Reset selected data
        if (level <= 2) this.selectedData.model = '';
        if (level <= 3) this.selectedData.year = '';
        if (level <= 4) this.selectedData.categoryId = '';
        
        this.currentLevel = level - 1;
        this.updateResults(this.currentLevel);
    }


    showLoading(selector) {
        $(selector).prop('disabled', true).html('<option value="">Зареждане...</option>');
    }

    hideLoading(selector) {
        $(selector).prop('disabled', false);
    }

    showResultsLoading() {
        $('#js-results-content').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary css-spinner-border" role="status">
                    <span class="visually-hidden">Зареждане...</span>
                </div>
                <p class="mt-2 text-muted">Зареждане на данни...</p>
            </div>
        `);
    }

    hideResultsLoading() {
        // Loading ще бъде скрит когато се покажат резултатите
    }

    showError(message) {
        $('#js-results-content').html(`
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${message}
            </div>
        `);
    }

    updateResultsTitle(title) {
        $('#js-results-title').text(title);
    }

    displayModelsInResults(models) {
        let html = '<div class="list-group list-group-flush">';
        
        models.forEach(model => {
            html += `
                <a href="javascript:void(0)" 
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center css-hierarchy-option" 
                   data-model="${model.model}" 
                   data-action="selectModel">
                    <span>${model.model}</span>
                    <i class="fas fa-chevron-right text-muted"></i>
                </a>
            `;
        });
        
        html += '</div>';
        $('#js-results-content').html(html);
        $('#js-results-count').text(models.length);
    }
}

// Инициализация при зареждане на страницата
$(document).ready(function() {
    if (typeof window.hierarchyFilter === 'undefined') {
        window.hierarchyFilter = new HierarchyFilter();
        console.log('HierarchyFilter initialized successfully');
    }
});
