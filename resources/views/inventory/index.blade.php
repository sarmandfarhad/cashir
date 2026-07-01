<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventory Management</title>
    <style>
        :root {
            --bg: #f3f6fb;
            --panel: #ffffff;
            --line: #dbe3ef;
            --text: #182235;
            --muted: #71809a;
            --blue: #315fd1;
            --blue-soft: #eef4ff;
            --red: #cb4d4d;
            --amber: #d59b18;
            --shadow: 0 16px 36px rgba(18, 36, 74, 0.08);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .app {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 210px minmax(0, 1fr);
        }

        .sidebar {
            background: var(--panel);
            border-right: 1px solid var(--line);
            padding: 18px 12px;
            display: flex;
            flex-direction: column;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px 14px;
            border-bottom: 1px solid #edf2fb;
        }

        .mark {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--blue);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 13px;
            font-weight: 700;
        }

        .brand strong,
        .brand span {
            display: block;
            line-height: 1.15;
        }

        .brand strong { font-size: 13px; }
        .brand span { font-size: 10px; color: var(--muted); margin-top: 3px; }

        .nav {
            display: grid;
            gap: 8px;
            margin-top: 16px;
        }

        .nav a {
            text-decoration: none;
            color: var(--text);
            font-size: 12px;
            border-radius: 10px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav a.active {
            background: var(--blue-soft);
            color: var(--blue);
            font-weight: 700;
        }

        .logout-wrap {
            margin-top: auto;
            border-top: 1px solid #edf2fb;
            padding-top: 12px;
        }

        .logout-wrap button {
            border: 0;
            background: transparent;
            color: var(--red);
            font-size: 12px;
            padding: 8px 10px;
            cursor: pointer;
        }

        .main {
            padding: 20px;
        }

        .topbar {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .title h1 { margin: 0; font-size: 26px; }
        .title p { margin: 4px 0 0; font-size: 12px; color: var(--muted); }

        .actions { display: flex; gap: 10px; }

        .btn {
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 10px;
            min-height: 36px;
            padding: 0 14px;
            font-size: 12px;
            font-weight: 600;
            color: var(--text);
        }

        .btn.primary {
            background: var(--blue);
            border-color: var(--blue);
            color: #fff;
        }

        .alert {
            border: 1px solid #f3df9e;
            background: #fff9e6;
            color: #8b6412;
            border-radius: 10px;
            padding: 9px 12px;
            font-size: 12px;
            margin-bottom: 12px;
        }

        .table-card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 12px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .toolbar {
            padding: 12px;
            border-bottom: 1px solid var(--line);
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .toolbar select,
        .toolbar input {
            min-height: 34px;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 0 10px;
            font-size: 12px;
        }

        .toolbar input { flex: 1; min-width: 220px; }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th, td {
            text-align: left;
            padding: 10px 10px;
            border-bottom: 1px solid #edf2fb;
            white-space: nowrap;
        }

        th {
            background: #f9fbff;
            font-size: 11px;
            color: var(--muted);
        }

        tbody tr:hover {
            background: #f8fbff;
            cursor: pointer;
        }

        .stock-low { color: var(--red); font-weight: 700; }
        .action-link {
            color: var(--blue);
            text-decoration: none;
            font-weight: 700;
        }

        .modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 100;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-backdrop.open {
            display: flex;
        }

        .modal {
            background: #fff;
            border-radius: 12px;
            width: min(520px, 100%);
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--line);
        }

        .modal-header h2 {
            margin: 0;
            font-size: 16px;
        }

        .modal-close {
            border: 0;
            background: transparent;
            font-size: 18px;
            color: var(--text);
            cursor: pointer;
        }

        .modal-body {
            padding: 14px 16px;
        }

        .form-group {
            display: grid;
            gap: 6px;
            margin-bottom: 10px;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 700;
            color: var(--text);
        }

        .form-group.required label::after {
            content: ' *';
            color: var(--red);
        }

        .form-group input,
        .form-group select {
            min-height: 32px;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 0 10px;
            font-size: 12px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .modal-footer {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding: 14px 16px;
            border-top: 1px solid var(--line);
        }

        .modal-footer button {
            min-height: 32px;
            border-radius: 8px;
            border: 1px solid var(--line);
            font-weight: 700;
            cursor: pointer;
        }

        .modal-footer .cancel {
            background: #fff;
            color: var(--muted);
        }

        .modal-footer .save {
            background: var(--blue);
            border-color: var(--blue);
            color: #fff;
        }

        .image-upload-area {
            border: 2px dashed var(--line);
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #f9fbff;
        }

        .image-upload-area:hover {
            border-color: var(--blue);
            background: var(--blue-soft);
        }

        .image-upload-area.has-image {
            border-style: solid;
            padding: 8px;
            background: #fff;
        }

        .image-preview {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 6px;
            display: none;
        }

        .image-preview.show {
            display: block;
        }

        .image-upload-placeholder {
            color: var(--muted);
            font-size: 12px;
        }

        #product-image-input {
            display: none;
        }
    </style>
</head>
<body>
    <div class="app">
        <aside class="sidebar">
            <div class="brand">
                <div class="mark">P</div>
                <div>
                    <strong>Pos System</strong>
                    <span>System Cashier</span>
                </div>
            </div>

            <nav class="nav" aria-label="Main navigation">
                <a href="{{ route('dashboard') }}">POS / Cashier</a>
                <a class="active" href="{{ route('inventory.index') }}">Inventory Management</a>
                <a href="{{ route('dashboard') }}">Sales Menu</a>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{ route('dashboard') }}">Reports</a>
                <a href="{{ route('dashboard') }}">Setting</a>
            </nav>

            <div class="logout-wrap">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </aside>

        <main class="main">
            <div class="topbar">
                <div class="title">
                    <h1>Product List</h1>
                    <p>Manage Products & Inventory</p>
                </div>
                <div class="actions">
                    <button class="btn" type="button">Export PDF</button>
                    <button class="btn primary" type="button" onclick="document.getElementById('add-product-modal').classList.add('open')">Add Product</button>
                </div>
            </div>

            <div class="alert">2 product is below minimum stock</div>

            <section class="table-card">
                <div class="toolbar">
                    <select id="category-filter">
                        <option value="all">All categories</option>
                        @php
                            $categories = [];
                            foreach ($products as $product) {
                                if (! in_array($product['category'], $categories, true)) {
                                    $categories[] = $product['category'];
                                }
                            }
                        @endphp
                        @foreach ($categories as $category)
                            <option value="{{ strtolower($category) }}">{{ $category }}</option>
                        @endforeach
                    </select>
                    <input id="inventory-search" type="search" placeholder="Search product name or product code...">
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Variants</th>
                            <th>Stock</th>
                            <th>Min. Stock</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="inventory-tbody">
                        @foreach ($products as $product)
                            <tr
                                data-code="{{ strtolower($product['code']) }}"
                                data-name="{{ strtolower($product['name']) }}"
                                data-category="{{ strtolower($product['category']) }}"
                                onclick="window.location='{{ route('inventory.show', $product['code']) }}'"
                            >
                                <td>{{ $product['code'] }}</td>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ $product['category'] }}</td>
                                <td>{{ $product['variants'] }}</td>
                                <td class="{{ $product['stock'] <= $product['min_stock'] ? 'stock-low' : '' }}">{{ $product['stock'] }}</td>
                                <td>{{ $product['min_stock'] }}</td>
                                <td>IQD {{ number_format($product['price'], 0, '.', ',') }}</td>
                                <td>
                                    <a class="action-link" href="{{ route('inventory.show', $product['code']) }}">Open</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <div id="add-product-modal" class="modal-backdrop">
        <div class="modal">
            <div class="modal-header">
                <h2>Add New Product</h2>
                <button type="button" class="modal-close" onclick="document.getElementById('add-product-modal').classList.remove('open')">×</button>
            </div>
            <form id="add-product-form">
                <div class="modal-body">
                    <p style="margin: 0 0 10px; color: var(--muted); font-size: 12px;">Enter new product details</p>

                    <div class="form-group">
                        <label for="product-image">Product Image</label>
                        <div class="image-upload-area" onclick="document.getElementById('product-image-input').click()" id="image-upload-area">
                            <img id="image-preview" class="image-preview" />
                            <div class="image-upload-placeholder" id="upload-placeholder">
                                <div style="font-size: 28px; margin-bottom: 8px;">📷</div>
                                <div>Click to upload image</div>
                            </div>
                        </div>
                        <input id="product-image-input" type="file" name="image" accept="image/*">
                    </div>

                    <div class="form-group required">
                        <label for="product-name">Product Name</label>
                        <input id="product-name" type="text" name="name" placeholder="ex: product a" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group required">
                            <label for="product-code">Product Code</label>
                            <input id="product-code" type="text" name="code" placeholder="BG-001" required>
                        </div>
                        <div class="form-group">
                            <label for="product-barcode">Barcode (optional)</label>
                            <input id="product-barcode" type="text" name="barcode" placeholder="ex: 00000000">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group required">
                            <label for="product-category">Category</label>
                            <select id="product-category" name="category" required>
                                <option value="">ex: shirt</option>
                                <option value="T-shirt">T-shirt</option>
                                <option value="Shirt">Shirt</option>
                                <option value="Polo shirt">Polo shirt</option>
                                <option value="Hoodie">Hoodie</option>
                                <option value="Jacket">Jacket</option>
                                <option value="Pants">Pants</option>
                            </select>
                        </div>
                        <div class="form-group required">
                            <label for="product-variants">Variants</label>
                            <input id="product-variants" type="text" name="variants" placeholder="ex: 1 S + 1 C" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group required">
                            <label for="product-stock">Stock</label>
                            <input id="product-stock" type="number" name="stock" value="0" min="0" required>
                        </div>
                        <div class="form-group required">
                            <label for="product-min-stock">Min. Stock</label>
                            <input id="product-min-stock" type="number" name="min_stock" value="0" min="0" required>
                        </div>
                    </div>

                    <div class="form-group required">
                        <label for="product-price">Price (IQD)</label>
                        <input id="product-price" type="number" name="price" placeholder="IQD 00,000" min="0" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="cancel" onclick="document.getElementById('add-product-modal').classList.remove('open')">Cancel</button>
                    <button type="submit" class="save">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const addProductModal = document.getElementById('add-product-modal');
        const addProductForm = document.getElementById('add-product-form');

        // Close modal when clicking backdrop
        addProductModal.addEventListener('click', (e) => {
            if (e.target === addProductModal) {
                addProductModal.classList.remove('open');
            }
        });

        // Handle image upload
        const imageInput = document.getElementById('product-image-input');
        const imagePreview = document.getElementById('image-preview');
        const uploadPlaceholder = document.getElementById('upload-placeholder');
        const imageUploadArea = document.getElementById('image-upload-area');

        imageInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (event) => {
                    imagePreview.src = event.target.result;
                    imagePreview.classList.add('show');
                    uploadPlaceholder.style.display = 'none';
                    imageUploadArea.classList.add('has-image');
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle form submission
        addProductForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(addProductForm);

            try {
                const response = await fetch('{{ route('inventory.add') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: formData,
                });

                const result = await response.json();

                if (result.success) {
                    const product = result.product;
                    // Add new row to table
                    const tbody = document.getElementById('inventory-tbody');
                    const newRow = document.createElement('tr');
                    newRow.dataset.code = product.code.toLowerCase();
                    newRow.dataset.name = product.name.toLowerCase();
                    newRow.dataset.category = product.category.toLowerCase();
                    newRow.onclick = () => window.location = `/inventory-management/${product.code}`;

                    const price = parseInt(product.price);
                    newRow.innerHTML = `
                        <td>${product.code}</td>
                        <td>${product.name}</td>
                        <td>${product.category}</td>
                        <td>${product.variants}</td>
                        <td>${product.stock}</td>
                        <td>${product.min_stock}</td>
                        <td>IQD ${price.toLocaleString('en-US')}</td>
                        <td><a class="action-link" href="/inventory-management/${product.code}">Open</a></td>
                    `;
                    tbody.appendChild(newRow);

                    // Reset form and close modal
                    addProductForm.reset();
                    addProductModal.classList.remove('open');
                    imagePreview.classList.remove('show');
                    uploadPlaceholder.style.display = '';
                    imageUploadArea.classList.remove('has-image');

                    // Trigger page refresh to sync with cashier
                    setTimeout(() => window.location.reload(), 500);
                }
            } catch (error) {
                alert('Error adding product: ' + error.message);
            }
        });
    </script>
    <script>
        (() => {
            const searchInput = document.getElementById('inventory-search');
            const categoryFilter = document.getElementById('category-filter');
            const rows = Array.from(document.querySelectorAll('#inventory-tbody tr'));

            const applyFilters = () => {
                const query = searchInput.value.trim().toLowerCase();
                const category = categoryFilter.value;

                rows.forEach((row) => {
                    const matchesCategory = category === 'all' || row.dataset.category === category;
                    const matchesQuery = !query
                        || row.dataset.code.includes(query)
                        || row.dataset.name.includes(query)
                        || row.dataset.category.includes(query);

                    row.style.display = matchesCategory && matchesQuery ? '' : 'none';
                });
            };

            searchInput.addEventListener('input', applyFilters);
            categoryFilter.addEventListener('change', applyFilters);
        })();
    </script>
</body>
</html>
