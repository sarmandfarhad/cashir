
'use strict';

/* ─── Utility: show toast notification ───────── */
function showToast(type, title, subtitle = '') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    const icon = type === 'success' ? '✅' : '❌';
    toast.innerHTML = `
        <span class="toast-icon">${icon}</span>
        <p>${title}${subtitle ? `<span>${subtitle}</span>` : ''}</p>
    `;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.transition = 'opacity 0.4s, transform 0.4s';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        setTimeout(() => toast.remove(), 450);
    }, 3500);
}

/* ─── Format currency IQD ─────────────────────── */
function formatIQD(amount) {
    return 'IQD ' + Number(amount).toLocaleString('en-US');
}

/* ══════════════════════════════════════════════
   MODAL OPEN / CLOSE
══════════════════════════════════════════════ */
const addProductModal = document.getElementById('add-product-modal');
const addProductForm  = document.getElementById('add-product-form');

function openAddModal() {
    addProductModal.classList.add('open');
    document.body.style.overflow = 'hidden';
    generateProductCode();
    showStep(1);
    setTimeout(() => document.getElementById('product-name').focus(), 80);
}

function closeAddModal() {
    addProductModal.classList.remove('open');
    document.body.style.overflow = '';
    resetForm();
}

document.getElementById('add-product-btn').addEventListener('click', openAddModal);
document.getElementById('modal-close-btn').addEventListener('click', closeAddModal);
document.getElementById('modal-cancel-btn').addEventListener('click', closeAddModal);
addProductModal.addEventListener('click', (e) => { if (e.target === addProductModal) closeAddModal(); });

/* Keyboard: close on Escape */
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && addProductModal.classList.contains('open')) closeAddModal();
});

/* ══════════════════════════════════════════════
   IMAGE UPLOAD PREVIEW
══════════════════════════════════════════════ */
const imageInput        = document.getElementById('product-image-input');
const imagePreview      = document.getElementById('image-preview');
const uploadPlaceholder = document.getElementById('upload-placeholder');
const imageUploadArea   = document.getElementById('image-upload-area');

imageInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (ev) => {
        imagePreview.src = ev.target.result;
        imagePreview.classList.add('show');
        uploadPlaceholder.style.display = 'none';
        imageUploadArea.classList.add('has-image');
    };
    reader.readAsDataURL(file);
});

/* ══════════════════════════════════════════════
   WIZARD NAVIGATION & LOGIC
══════════════════════════════════════════════ */
let currentStep = 1;
let addedVariants = []; // array of { size, color, colorHex, qty }

let selectedSize = '';
let selectedColor = '';
let selectedColorHex = '';

const wizardStep1 = document.getElementById('wizard-step-1');
const wizardStep2 = document.getElementById('wizard-step-2');
const step1Header = document.getElementById('step-1-header');
const step2Header = document.getElementById('step-2-header');
const stepSep     = document.getElementById('step-sep');

const backBtn     = document.getElementById('wizard-back-btn');
const nextBtn     = document.getElementById('wizard-next-btn');

function showStep(step) {
    currentStep = step;
    if (step === 1) {
        wizardStep1.classList.add('active');
        wizardStep2.classList.remove('active');
        step1Header.classList.add('active');
        step2Header.classList.remove('active');
        stepSep.classList.remove('active');
        backBtn.textContent = 'Cancel';
        nextBtn.textContent = 'Next';
    } else {
        wizardStep1.classList.remove('active');
        wizardStep2.classList.add('active');
        step1Header.classList.add('active');
        step2Header.classList.add('active');
        stepSep.classList.add('active');
        backBtn.textContent = 'Back';
        nextBtn.textContent = 'Save';
    }
}

// Generate Product Code Starting dynamically
function generateProductCode() {
    const tbody = document.getElementById('inventory-tbody');
    const existingRows = tbody ? tbody.querySelectorAll('tr') : [];
    let maxId = 0;
    
    existingRows.forEach(row => {
        const code = row.dataset.code || '';
        // Extract numeric sequence
        const numPart = code.replace(/[^0-9]/g, '');
        if (numPart) {
            const val = parseInt(numPart, 10);
            if (val > maxId) {
                maxId = val;
            }
        }
    });
    
    const nextId = maxId > 0 ? maxId + 1 : 1;
    document.getElementById('product-code').value = 'PRD-' + nextId;
}

// Step 1 Validation
function validateStep1() {
    const name = document.getElementById('product-name').value.trim();
    const category = document.getElementById('product-category').value;
    const price = document.getElementById('product-price').value.trim();
    const minStock = document.getElementById('product-min-stock').value.trim();

    if (!name) {
        showToast('error', 'Validation error', 'Product name is required.');
        return false;
    }
    if (!category) {
        showToast('error', 'Validation error', 'Please select a category.');
        return false;
    }
    if (!price || parseFloat(price) < 0) {
        showToast('error', 'Validation error', 'Please enter a valid price.');
        return false;
    }
    if (!minStock || parseInt(minStock, 10) < 0) {
        showToast('error', 'Validation error', 'Please enter a valid min stock.');
        return false;
    }
    return true;
}

// Next / Save button action
nextBtn.addEventListener('click', (e) => {
    if (currentStep === 1) {
        if (validateStep1()) {
            showStep(2);
        }
    } else {
        // Step 2 Save submission: trigger form submit
        if (addedVariants.length === 0) {
            showToast('error', 'Validation error', 'Please add at least one size & color variant combination.');
            return;
        }
        
        // Prepare hidden inputs
        let totalStock = 0;
        const uniqueSizes = new Set();
        const uniqueColors = new Set();
        
        addedVariants.forEach(v => {
            totalStock += parseInt(v.qty, 10);
            uniqueSizes.add(v.size);
            uniqueColors.add(v.color);
        });

        document.getElementById('product-stock').value = totalStock;
        
        // Format variants string to match existing standard e.g. "4 S + 3 C"
        const variantStr = `${uniqueSizes.size} S + ${uniqueColors.size} C`;
        document.getElementById('product-variants').value = variantStr;

        // Submit form
        const formSubmitBtn = document.getElementById('form-submit-btn');
        // Let's programmatically submit the form
        const event = new Event('submit', { cancelable: true });
        addProductForm.dispatchEvent(event);
    }
});

// Back / Cancel button action
backBtn.addEventListener('click', () => {
    if (currentStep === 2) {
        showStep(1);
    } else {
        closeAddModal();
    }
});

/* ─── Size & Color Selection Inside Builder ─── */
const sizeChips = document.getElementById('wizard-sizes');
const colorGrid = document.getElementById('wizard-colors');

sizeChips.addEventListener('click', (e) => {
    const chip = e.target.closest('.variant-chip');
    if (!chip) return;
    sizeChips.querySelectorAll('.variant-chip').forEach(c => c.classList.remove('selected'));
    chip.classList.add('selected');
    selectedSize = chip.dataset.size;
});

colorGrid.addEventListener('click', (e) => {
    const dot = e.target.closest('.color-dot-wrap');
    if (!dot) return;
    colorGrid.querySelectorAll('.color-dot-wrap').forEach(d => d.classList.remove('selected'));
    dot.classList.add('selected');
    selectedColor = dot.dataset.color;
    selectedColorHex = dot.dataset.hex;
});

/* Qty Counter Buttons */
const qtyInput = document.getElementById('wizard-qty-input');
document.getElementById('wizard-qty-dec').addEventListener('click', () => {
    let val = parseInt(qtyInput.value, 10) || 0;
    if (val > 1) qtyInput.value = val - 1;
});
document.getElementById('wizard-qty-inc').addEventListener('click', () => {
    let val = parseInt(qtyInput.value, 10) || 0;
    qtyInput.value = val + 1;
});

/* Add Variant Combination */
const addVariantBtn = document.getElementById('wizard-add-variant-btn');
addVariantBtn.addEventListener('click', () => {
    if (!selectedSize) {
        showToast('error', 'Select Size', 'Please select a size first.');
        return;
    }
    if (!selectedColor) {
        showToast('error', 'Select Color', 'Please select a color first.');
        return;
    }
    const qty = parseInt(qtyInput.value, 10) || 0;
    if (qty <= 0) {
        showToast('error', 'Invalid Quantity', 'Please enter a valid stock quantity.');
        return;
    }

    // Check if combination already exists
    const duplicateIndex = addedVariants.findIndex(v => v.size === selectedSize && v.color === selectedColor);
    if (duplicateIndex > -1) {
        addedVariants[duplicateIndex].qty += qty;
    } else {
        addedVariants.push({
            size: selectedSize,
            color: selectedColor,
            colorHex: selectedColorHex,
            qty: qty
        });
    }

    updateVariantsList();
});

function updateVariantsList() {
    const container = document.getElementById('added-variants-list');
    container.innerHTML = '';

    if (addedVariants.length === 0) {
        container.innerHTML = `<div style="padding: 16px; text-align: center; color: var(--muted); font-size: 12px;">No combinations added yet.</div>`;
        document.getElementById('var-summary-text').textContent = '0 Stock · 0 Variants';
        document.getElementById('var-summary-badge').textContent = 'Total Qty: 0';
        return;
    }

    let totalStock = 0;
    const uniqueSizes = new Set();
    const uniqueColors = new Set();

    addedVariants.forEach((v, index) => {
        totalStock += v.qty;
        uniqueSizes.add(v.size);
        uniqueColors.add(v.color);

        const row = document.createElement('div');
        row.className = 'added-variant-row';
        row.innerHTML = `
            <div class="added-variant-info">
                <span class="added-variant-color-dot" style="background:${v.colorHex}"></span>
                <span>Size: <strong>${v.size}</strong> · Color: <strong>${v.color}</strong></span>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <span class="added-variant-qty">${v.qty} items</span>
                <button type="button" class="remove-variant-btn" onclick="removeVariant(${index})">×</button>
            </div>
        `;
        container.appendChild(row);
    });

    document.getElementById('var-summary-text').textContent = `${totalStock} Stock · ${uniqueSizes.size} Sizes · ${uniqueColors.size} Colors`;
    document.getElementById('var-summary-badge').textContent = `Total Qty: ${totalStock}`;
}

window.removeVariant = function(index) {
    addedVariants.splice(index, 1);
    updateVariantsList();
};

/* ══════════════════════════════════════════════
   FORM RESET
══════════════════════════════════════════════ */
function resetForm() {
    addProductForm.reset();
    addedVariants = [];
    selectedSize = '';
    selectedColor = '';
    selectedColorHex = '';
    sizeChips.querySelectorAll('.variant-chip').forEach(c => c.classList.remove('selected'));
    colorGrid.querySelectorAll('.color-dot-wrap').forEach(d => d.classList.remove('selected'));
    qtyInput.value = '10';
    updateVariantsList();
    
    /* Image */
    imagePreview.src = '';
    imagePreview.classList.remove('show');
    uploadPlaceholder.style.display = '';
    imageUploadArea.classList.remove('has-image');
    imageInput.value = '';
}

/* ══════════════════════════════════════════════
   FORM SUBMISSION — adds to BOTH Inventory & POS
══════════════════════════════════════════════ */
addProductForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    /* Client-side validation */
    const category = document.getElementById('product-category').value;
    const variants = document.getElementById('product-variants').value;

    if (!category.trim()) {
        showToast('error', 'Category required', 'Please select a category for this product.');
        return;
    }
    if (!variants.trim()) {
        showToast('error', 'Variants required', 'Please select size and color variants.');
        return;
    }

    const submitBtn = document.getElementById('wizard-next-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="btn-spinner"></span>Saving…';

    const formData = new FormData(addProductForm);

    try {
        const response = await fetch('{{ route('inventory.add') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: formData,
        });

        if (!response.ok) throw new Error(`Server error: ${response.status}`);
        const result = await response.json();

        if (result.success) {
            const product = result.product;

            /* ── 1. Add row to Inventory table ─────────── */
            const tbody = document.getElementById('inventory-tbody');
            const tr = document.createElement('tr');
            tr.dataset.code     = product.code.toLowerCase();
            tr.dataset.name     = product.name.toLowerCase();
            tr.dataset.category = product.category.toLowerCase();
            tr.style.animation  = 'slideUp 0.3s ease';
            tr.onclick = () => window.location = `/inventory-management/${product.code}`;

            const price = parseFloat(product.price);
            tr.innerHTML = `
                <td>${product.code}</td>
                <td>${product.name}</td>
                <td>${product.category}</td>
                <td>${product.variants}</td>
                <td>${product.stock}</td>
                <td>${product.min_stock}</td>
                <td>${formatIQD(price)}</td>
                <td onclick="event.stopPropagation()">
                    <a class="action-link" href="/inventory-management/${product.code}">Open</a>
                </td>
            `;
            tbody.appendChild(tr);

            /* ── 2. Notify POS via BroadcastChannel so
                     the POS page (if open) reloads its
                     product grid without a full reload ── */
            if (typeof BroadcastChannel !== 'undefined') {
                const ch = new BroadcastChannel('pos_products');
                ch.postMessage({ type: 'product_added', product });
                ch.close();
            }

            /* ── 3. Close modal, reset, show toast ─────── */
            closeAddModal();
            showToast(
                'success',
                `"${product.name}" added successfully!`,
                'Product is now live in Inventory & POS Cashier.'
            );
        } else {
            showToast('error', 'Failed to add product.', 'Please check all required fields.');
        }

    } catch (err) {
        console.error('[AddProduct]', err);
        showToast('error', 'Network error.', err.message);
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Save';
    }
});

/* ══════════════════════════════════════════════
   TABLE SEARCH & FILTER
══════════════════════════════════════════════ */
(() => {
    const searchInput    = document.getElementById('inventory-search');
    const categoryFilter = document.getElementById('category-filter');

    function getRows() {
        return Array.from(document.querySelectorAll('#inventory-tbody tr'));
    }

    function applyFilters() {
        const query    = searchInput.value.trim().toLowerCase();
        const category = categoryFilter.value;

        getRows().forEach(row => {
            const matchesCat   = category === 'all' || row.dataset.category === category;
            const matchesQuery = !query
                || row.dataset.code.includes(query)
                || row.dataset.name.includes(query)
                || row.dataset.category.includes(query);

            row.style.display = matchesCat && matchesQuery ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', applyFilters);
    categoryFilter.addEventListener('change', applyFilters);
})();
