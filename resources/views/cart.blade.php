@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen px-6 py-10">
    <div class="container mx-auto pt-32 pb-10 bg-white">

        <h2 class="text-3xl font-bold mb-8 text-black">Keranjang Belanja</h2>
        @if(empty($cart))
            <p class="text-gray-600 text-justify py-10">Keranjang Anda kosong.</p>
        @else

        {{-- ✅ Kontainer untuk "Pilih Semua" dan "Hapus" di tampilan mobile --}}
        <div class="flex items-center justify-between mb-4 md:hidden">
            <div class="flex items-center gap-2">
                <input type="checkbox" id="selectAllCheckbox" class="w-5 h-5 cursor-pointer">
                <label for="selectAllCheckbox" class="text-black">Pilih Semua (<span id="productCount"></span>)</label>
            </div>
            
            <form id="bulkDeleteForm" action="{{ route('cart.bulkDelete') }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="selected" id="selectedForDelete">
                <button id="deleteBtn" type="submit" 
                    class="text-black hover:text-red-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                    <i class="fa-solid fa-trash mr-1"></i>
                </button>
            </form>
        </div>
        
        <style>
            /* Custom CSS untuk membuat tabel responsif di mobile */
            @media screen and (max-width: 767px) {
                table {
                    border: 0;
                }

                thead {
                    border: none;
                    clip: rect(0 0 0 0);
                    height: 1px;
                    margin: -1px;
                    overflow: hidden;
                    padding: 0;
                    position: absolute;
                    width: 1px;
                }

                tr {
                    border: 1px solid #e2e8f0;
                    margin-bottom: 1rem;
                    display: block;
                    border-radius: 0.5rem;
                    overflow: hidden;
                    background-color: #ffffff;
                }
                
                td {
                    border-bottom: 1px solid #ddd;
                    display: block;
                    font-size: 0.8em;
                    text-align: right;
                    padding: 1rem;
                    position: relative;
                }

                td:last-child {
                    border-bottom: 0;
                }

                td:before {
                    content: attr(data-label);
                    float: left;
                    font-weight: 600;
                    text-transform: uppercase;
                }
                
                /* Mengatur ulang tampilan untuk kolom Jumlah di mobile */
                td[data-label="Jumlah"] {
                    text-align: right;
                }

                td[data-label="Jumlah"] .flex {
                    justify-content: flex-end;
                }
            }
        </style>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse md:border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2 border text-center w-41">
                            {{-- ✅ Tombol Pilih Semua dan Hapus di tampilan desktop --}}
                            <div class="flex items-center justify-center gap-2 hidden md:flex">
                                <input type="checkbox" id="selectAllCheckboxDesktop" class="w-5 h-5 cursor-pointer">
                                <label for="selectAllCheckboxDesktop" class="text-black">Pilih Semua (<span id="productCountDesktop"></span>)</label>
                                <form id="bulkDeleteFormDesktop" action="{{ route('cart.bulkDelete') }}" method="POST" class="ml-4 hidden">
                                    @csrf
                                    <input type="hidden" name="selected" id="selectedForDeleteDesktop">
                                    <button id="deleteBtnDesktop" type="submit" 
                                        class="text-black hover:text-red-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                        <i class="fa-solid fa-trash mr-1"></i>
                                    </button>
                                </form>
                            </div>
                        </th>
                        <th class="p-2 border text-left md:text-center">Produk</th>
                        <th class="p-2 border text-center">Jumlah</th>
                        <th class="p-2 border text-center">Total</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($cart as $index => $item)
                    <tr class="bg-white hover:bg-gray-50 transition-colors">
                        <td class="p-4 border md:text-center" data-label="Pilih">
                            <div class="flex items-center gap-2 md:justify-center">
                                <input type="checkbox" 
                                    class="product-checkbox w-5 h-5 cursor-pointer" 
                                    value="{{ $index }}" 
                                    data-price="{{ $item['price'] }}" 
                                    data-quantity="{{ $item['quantity'] }}" 
                                    data-stock="{{ $item['stock'] ?? 99 }}">
                                {{-- Tombol Hapus per Produk (Hanya di mobile) --}}
                                <div class="md:hidden">
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="index" value="{{ $index }}">
                                        <button class="text-black hover:text-red-600 transition-colors ml-2">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>

                        <td class="p-4 border" data-label="Produk">
                            <div class="flex items-center gap-3">
                                @php 
                                    $images = is_array($item['image']) ? $item['image'] : json_decode($item['image'], true);
                                    $productId = $item['id'];
                                @endphp
                                
                                <a href="{{ route('product.show', $productId) }}" class="group relative">
                                    <div class="relative w-[75px] aspect-square border rounded overflow-hidden">
                                        <img src="{{ $images ? Storage::url($images[0]) : asset('no-image.png') }}" 
                                             alt="Gambar Produk"
                                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-300">
                                    </div>
                                </a>
                            
                                <div>
                                    <a href="{{ route('product.show', $productId) }}" class="block">
                                        <h3><strong>Twoeight - {{ $item['name'] }}</strong></h3>
                                    </a>
                                    <p class="text-gray-700">Ukuran {{ $item['size'] }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="p-4 border" data-label="Jumlah">
                            <div class="flex items-center justify-start md:justify-center space-x-2">
                                <form class="quantity-form flex items-center" data-index="{{ $index }}" data-stock="{{ $item['stock'] ?? 99 }}">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">
                                    <div class="flex border rounded-lg overflow-hidden">
                                        <button type="button" class="btn-decrease bg-gray-100 px-4 py-2 text-xl font-bold hover:bg-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                            &minus;
                                        </button>
                                        <input type="text" readonly class="qty-input w-12 text-center font-semibold border-x focus:outline-none" 
                                               value="{{ $item['quantity'] }}">
                                        <button type="button" class="btn-increase bg-gray-100 px-4 py-2 text-xl font-bold hover:bg-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" 
                                                data-stock="{{ $item['stock'] ?? 99 }}">
                                            +
                                        </button>
                                    </div>
                                </form>
                                {{-- Tombol Hapus per Produk (Hanya di Desktop) --}}
                                <form action="{{ route('cart.remove') }}" method="POST" class="hidden md:block">
                                    @csrf
                                    <input type="hidden" name="index" value="{{ $index }}">
                                    <button class="text-black hover:text-red-600 transition-colors ml-2">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                        <td class="p-4 border font-semibold text-black text-right" data-label="Total">
                            <span class="product-total text-lg">Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ✅ Subtotal & Checkout Section --}}
        <div class="flex flex-col md:flex-row justify-end items-center gap-4 mt-8 pt-4 border-t border-gray-200">
            <div class="font-bold text-black text-lg">
                Subtotal: <span id="subtotal" class="text-black">Rp0</span>
            </div>
            
            <form id="checkoutForm" action="{{ route('checkout.multiple') }}" method="POST">
                @csrf
                <input type="hidden" name="selected_products" id="selectedProductsInput">
                <button id="checkoutBtn" type="submit" 
                    class="bg-black text-white px-8 py-3 rounded-lg font-semibold shadow-lg transition-colors
                           disabled:bg-gray-400 disabled:cursor-not-allowed disabled:shadow-none" 
                    disabled>
                    Checkout
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const subtotalEl = document.getElementById('subtotal');
        const checkoutInput = document.getElementById('selectedProductsInput');

        // References for both mobile and desktop controls
        const selectAllCheckboxMobile = document.getElementById('selectAllCheckbox');
        const selectAllCheckboxDesktop = document.getElementById('selectAllCheckboxDesktop');
        const productCountElMobile = document.getElementById('productCount');
        const productCountElDesktop = document.getElementById('productCountDesktop');
        const bulkDeleteFormMobile = document.getElementById('bulkDeleteForm');
        const bulkDeleteFormDesktop = document.getElementById('bulkDeleteFormDesktop');
        const selectedForDeleteMobile = document.getElementById('selectedForDelete');
        const selectedForDeleteDesktop = document.getElementById('selectedForDeleteDesktop');
    
        productCountElMobile.textContent = productCheckboxes.length;
        productCountElDesktop.textContent = productCheckboxes.length;

        function updateSubtotal() {
            let subtotal = 0;
            let selectedIndexes = [];
            const checkedCount = document.querySelectorAll('.product-checkbox:checked').length;
    
            productCheckboxes.forEach(cb => {
                if (cb.checked) {
                    subtotal += parseInt(cb.dataset.price) * parseInt(cb.dataset.quantity);
                    selectedIndexes.push(cb.value);
                }
            });
    
            subtotalEl.innerText = "Rp" + subtotal.toLocaleString('id-ID');
            checkoutInput.value = selectedIndexes.join(',');
            
            selectedForDeleteMobile.value = selectedIndexes.join(',');
            selectedForDeleteDesktop.value = selectedIndexes.join(',');
            
            checkoutBtn.disabled = (checkedCount === 0);
            checkoutBtn.textContent = checkedCount > 0 ? `Checkout (${checkedCount})` : "Checkout";
    
            // ✅ Logika baru: Tombol hapus hanya muncul jika "Pilih Semua" dicentang
            const isAllChecked = productCheckboxes.length > 0 && checkedCount === productCheckboxes.length;

            if (isAllChecked) {
                bulkDeleteFormMobile.classList.remove('hidden');
                bulkDeleteFormDesktop.classList.remove('hidden');
            } else {
                bulkDeleteFormMobile.classList.add('hidden');
                bulkDeleteFormDesktop.classList.add('hidden');
            }

            // Update selectAllCheckbox state for both
            selectAllCheckboxMobile.checked = isAllChecked;
            selectAllCheckboxDesktop.checked = isAllChecked;
        }
    
        selectAllCheckboxMobile.addEventListener('change', (e) => {
            const isChecked = e.target.checked;
            productCheckboxes.forEach(cb => {
                cb.checked = isChecked;
            });
            updateSubtotal();
        });

        selectAllCheckboxDesktop.addEventListener('change', (e) => {
            const isChecked = e.target.checked;
            productCheckboxes.forEach(cb => {
                cb.checked = isChecked;
            });
            updateSubtotal();
        });
    
        productCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                updateSubtotal();
            });
        });
    
        bulkDeleteFormMobile.onsubmit = function () {
            const selectedCount = document.querySelectorAll('.product-checkbox:checked').length;
            return confirm(`Hapus ${selectedCount} produk? Produk yang anda pilih akan dihapus dari Keranjang.`);
        };
        
        bulkDeleteFormDesktop.onsubmit = function () {
            const selectedCount = document.querySelectorAll('.product-checkbox:checked').length;
            return confirm(`Hapus ${selectedCount} produk? Produk yang anda pilih akan dihapus dari Keranjang.`);
        };
    
        // Logic for quantity buttons
        document.querySelectorAll('.quantity-form').forEach(form => {
            const index = form.dataset.index;
            const qtyInput = form.querySelector('.qty-input');
            const decreaseBtn = form.querySelector('.btn-decrease');
            const increaseBtn = form.querySelector('.btn-increase');
            
            const token = form.querySelector('input[name="_token"]').value;
            const method = form.querySelector('input[name="_method"]').value;

            function updateButtonState(qty, maxStock) {
                decreaseBtn.disabled = qty <= 1;
                increaseBtn.disabled = qty >= maxStock;
            }

            function sendUpdate(action) {
                const formData = new FormData();
                formData.append('_token', token);
                formData.append('action', action);
                formData.append('index', index);
                formData.append('_method', method);

                fetch(`/cart/update`, {
                    method: 'POST',
                    body: formData,
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        if (data.message) {
                           console.error(data.message);
                        }
                        return;
                    }

                    qtyInput.value = data.new_quantity;
                    const maxStock = data.stock;
                    
                    updateButtonState(data.new_quantity, maxStock);

                    const productTotalCell = form.closest('tr').querySelector('.product-total');
                    productTotalCell.innerText = "Rp" + data.product_total.toLocaleString('id-ID');

                    const cb = form.closest('tr').querySelector('.product-checkbox');
                    cb.dataset.quantity = data.new_quantity;
                    cb.dataset.stock = maxStock;
                    
                    updateSubtotal();
                    document.dispatchEvent(new CustomEvent('cart-updated', { detail: { totalQty: data.cart_total_qty } }));
                })
                .catch(err => {
                    console.error('Fetch error:', err);
                });
            }

            decreaseBtn.addEventListener('click', e => {
                e.preventDefault();
                sendUpdate('decrease');
            });

            increaseBtn.addEventListener('click', e => {
                e.preventDefault();
                sendUpdate('increase');
            });

            updateButtonState(parseInt(qtyInput.value), parseInt(increaseBtn.dataset.stock));
        });
    
        updateSubtotal();
    });
</script>

<script>
    document.addEventListener('cart-updated', function (e) {
        const cartCountEl = document.getElementById('cartCount');
        const totalQty = e.detail.totalQty;
        
        if (cartCountEl) {
            if (totalQty > 0) {
                cartCountEl.textContent = totalQty;
            } else {
                cartCountEl.textContent = '';
            }
        }
    });
</script>