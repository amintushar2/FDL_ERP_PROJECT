/**
 * lov_helper.js
 * Enhanced LOV Helper with Value & Text Support
 * Place at: public/mainjs/lov_helper.js
 *
 * Usage:
 *   lovSelect('#mySelect', '/lov/dept', 'Select Department', 'DEPT001', 'Human Resources');
 */

/**
 * Initialize a Select2 LOV dropdown
 * 
 * @param {string|Element} selector - jQuery selector or DOM element
 * @param {string} url - LOV endpoint returning {results:[{id,text}]}
 * @param {string} placeholder - Placeholder text
 * @param {string} currentVal - Current value to pre-select (edit mode), optional
 * @param {string} currentTxt - Current display text (edit mode), optional
 */
function lovSelect(selector, url, placeholder, currentVal, currentTxt) {
    const $el = $(selector);
    if (!$el.length) return;

    // Pre-select existing value in edit mode (no extra request needed)
    if (currentVal) {
        const opt = new Option(currentTxt || currentVal, currentVal, true, true);
        $el.append(opt);
    }

    $el.select2({
        placeholder: placeholder || 'Search…',
        allowClear: true,
        minimumInputLength: 0,
        ajax: {
            url: url,
            dataType: 'json',
            delay: 250,
            data: params => ({ q: params.term || '' }),
            processResults: data => ({ results: data.results }),
            cache: true
        }
    });
}

/**
 * Get the selected VALUE (ID) from a LOV select
 * 
 * @param {string|Element} selector - jQuery selector
 * @returns {string} Selected value/ID or empty string
 */
function lovValue(selector) {
    const val = $(selector).val();
    return val ? String(val) : '';
}

/**
 * Get the selected TEXT (display name) from a LOV select
 * 
 * @param {string|Element} selector - jQuery selector
 * @returns {string} Selected text or empty string
 */
function lovText(selector) {
    return $(selector).select2('data')?.[0]?.text || '';
}

/**
 * Get both VALUE and TEXT from a LOV select
 * 
 * @param {string|Element} selector - jQuery selector
 * @returns {Object} {value: string, text: string}
 */
function lovData(selector) {
    const data = $(selector).select2('data')?.[0];
    return {
        value: data?.id || '',
        text: data?.text || ''
    };
}

/**
 * Get all LOV data from a form
 * Returns an object with all LOV fields containing both value and text
 * 
 * @param {string|Element} formSelector - Form selector
 * @returns {Object} Object with field names as keys, {value, text} as values
 * 
 * Example return:
 * {
 *   dept_no: {value: 'DEPT001', text: 'Human Resources'},
 *   section_no: {value: 'SEC005', text: 'Payroll Section'}
 * }
 */
function lovFormData(formSelector) {
    const result = {};
    $(formSelector).find('select.select2-hidden-accessible').each(function() {
        const name = $(this).attr('name');
        const id = $(this).attr('id');
        if (name && id) {
            result[name] = lovData('#' + id);
        }
    });
    return result;
}

/**
 * Set both value and text for a LOV select (for pre-population)
 * 
 * @param {string|Element} selector - jQuery selector
 * @param {string} value - Value to set
 * @param {string} text - Text to display
 */
function lovSet(selector, value, text) {
    const $el = $(selector);
    if (!$el.length || !value) return;
    
    // Clear existing options
    $el.empty();
    
    // Add new option and select it
    const opt = new Option(text || value, value, true, true);
    $el.append(opt).trigger('change');
}

/**
 * Clear/reset a LOV select
 * 
 * @param {string|Element} selector - jQuery selector
 */
function lovClear(selector) {
    $(selector).val(null).trigger('change');
}

/**
 * Check if a LOV select has a value selected
 * 
 * @param {string|Element} selector - jQuery selector
 * @returns {boolean} True if a value is selected
 */
function lovHasValue(selector) {
    const val = $(selector).val();
    return val !== null && val !== undefined && val !== '';
}

/**
 * Get all selected LOV values as a simple object (value only)
 * Useful for quick form serialization
 * 
 * @param {string|Element} formSelector - Form selector
 * @returns {Object} {fieldName: value}
 */
function lovFormValues(formSelector) {
    const result = {};
    $(formSelector).find('select.select2-hidden-accessible').each(function() {
        const name = $(this).attr('name');
        if (name) {
            result[name] = lovValue(this);
        }
    });
    return result;
}

/**
 * Initialize all LOV selects within a container using data attributes
 * Automatically reads data-lov, data-ph, data-val, data-txt from each element
 * 
 * @param {string|Element} containerSelector - Container selector (default: document)
 * 
 * Usage in HTML:
 * <select class="lov" name="dept_no" id="dept" 
 *         data-lov="/lov/dept" 
 *         data-ph="Search Department"
 *         data-val="DEPT001" 
 *         data-txt="Human Resources"></select>
 */
function lovInitAll(containerSelector = document) {
    $(containerSelector).find('.lov').each(function() {
        const $this = $(this);
        lovSelect(
            this,
            $this.data('lov'),
            $this.data('ph'),
            $this.data('val'),
            $this.data('txt')
        );
    });
}

/**
 * Validate that all required LOV fields have values
 * 
 * @param {string|Element} formSelector - Form selector
 * @param {Array} requiredFields - Array of field names that are required
 * @returns {Object} {isValid: boolean, missing: Array}
 * 
 * Example:
 * const validation = lovValidate('#frmOfficial', ['dept_no', 'des_id', 'grade_id']);
 * if (!validation.isValid) {
 *   alert('Please fill: ' + validation.missing.join(', '));
 * }
 */
function lovValidate(formSelector, requiredFields = []) {
    const missing = [];
    
    requiredFields.forEach(fieldName => {
        const $field = $(formSelector).find(`[name="${fieldName}"]`);
        if ($field.length && !lovHasValue($field)) {
            const label = $field.closest('.row').find('label').text().replace(':', '').trim();
            missing.push(label || fieldName);
        }
    });
    
    return {
        isValid: missing.length === 0,
        missing: missing
    };
}

/**
 * Create a FormData object with both LOV values and text fields
 * Automatically appends _name fields for each LOV field
 * 
 * @param {string|Element} formSelector - Form selector
 * @returns {FormData} FormData with both IDs and names
 * 
 * Example result:
 * dept_no = "DEPT001"
 * dept_no_name = "Human Resources"
 * section_no = "SEC005"
 * section_no_name = "Payroll Section"
 */
function lovFormDataWithNames(formSelector) {
    const form = $(formSelector)[0];
    const formData = new FormData(form);
    
    // Add text values for all LOV fields
    $(formSelector).find('select.select2-hidden-accessible').each(function() {
        const name = $(this).attr('name');
        if (name) {
            const text = lovText(this);
            if (text) {
                formData.append(name + '_name', text);
            }
        }
    });
    
    return formData;
}

/**
 * Create a JSON object with both LOV values and text fields
 * Similar to lovFormDataWithNames but returns plain object
 * 
 * @param {string|Element} formSelector - Form selector
 * @returns {Object} Object with both IDs and names
 */
function lovFormObjectWithNames(formSelector) {
    const data = {};
    
    // Get all form fields
    $(formSelector).serializeArray().forEach(field => {
        data[field.name] = field.value;
    });
    
    // Add text values for all LOV fields
    $(formSelector).find('select.select2-hidden-accessible').each(function() {
        const name = $(this).attr('name');
        if (name) {
            const text = lovText(this);
            if (text) {
                data[name + '_name'] = text;
            }
        }
    });
    
    return data;
}

/**
 * Enable/Disable a LOV select
 * 
 * @param {string|Element} selector - jQuery selector
 * @param {boolean} enabled - True to enable, false to disable
 */
function lovSetEnabled(selector, enabled) {
    $(selector).prop('disabled', !enabled);
}

/**
 * Get the complete data array from select2 (all selected items for multi-select)
 * 
 * @param {string|Element} selector - jQuery selector
 * @returns {Array} Array of {id, text} objects
 */
function lovGetAll(selector) {
    return $(selector).select2('data') || [];
}

// Export for module systems (optional)
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        lovSelect,
        lovValue,
        lovText,
        lovData,
        lovFormData,
        lovSet,
        lovClear,
        lovHasValue,
        lovFormValues,
        lovInitAll,
        lovValidate,
        lovFormDataWithNames,
        lovFormObjectWithNames,
        lovSetEnabled,
        lovGetAll
    };
}