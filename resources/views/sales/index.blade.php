@extends('layouts.app')

@section('content')

<form action="{{ route('sales.store') }}" method="POST" id="salesForm">
    @csrf

    {{-- HIDDEN INPUTS --}}
    <input type="hidden" name="cart" id="cartInput">
    <input type="hidden" name="subtotal" id="subtotalInput">
    <input type="hidden" name="tax" id="taxInput">
    <input type="hidden" name="total" id="totalInput">
    <input type="hidden" name="payment_method" id="paymentMethodInput">

    <div class="flex justify-center gap-6">

        {{-- LEFT: PRODUCTS --}}
        <div class="w-full max-w-5xl bg-white rounded-2xl shadow p-6 border">
            <h1 class="text-3xl font-bold text-slate-800 mb-1">POS</h1>
            <p class="text-slate-500 text-sm mb-4">Process customer transactions efficiently</p>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($products as $product)
                <div class="product-card border rounded-xl p-4 cursor-pointer hover:shadow-lg transition"
                     data-id="{{ $product->id }}"
                     data-name="{{ $product->name }}"
                     data-price="{{ $product->price }}"
                     data-stock="{{ $product->stock }}">

                    <h2 class="font-semibold text-lg">{{ $product->name }}</h2>
                    <p class="text-slate-500 text-sm">Stock: {{ $product->stock }}</p>
                    <p class="text-emerald-600 font-bold text-xl mt-2">
                        ₱{{ number_format($product->price, 2) }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- RIGHT: SUMMARY --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 max-h-[70vh] overflow-y-auto">

            <h2 class="text-2xl font-bold mb-4">Transaction Summary</h2>

            <div id="cartItems" class="space-y-3 mb-4"></div>

            <div class="border-t pt-4 space-y-2">
                <div class="flex justify-between">
                    <span>Subtotal</span>
                    <span id="subtotal">₱0.00</span>
                </div>

                <div class="flex justify-between">
                    <span>Tax (12%)</span>
                    <span id="tax">₱0.00</span>
                </div>

                <div class="flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span id="total">₱0.00</span>
                </div>
            </div>

            <div class="mt-4">
    <label class="text-sm">Payment Method</label>
    <select id="paymentMethod" class="w-full border rounded-lg p-2">
        <option value="cash">Cash</option>
        <option value="gcash">GCash</option>
        <option value="card">Card</option>
    </select>
</div>

{{-- CASH --}}
<div class="mt-4" id="cashField">
    <label class="text-sm">Payment Amount</label>
    <input type="number" name="payment" id="paymentInput"
           class="w-full border rounded-lg p-2">
</div>

{{-- GCASH --}}
<div class="mt-4 hidden" id="gcashField">
    <label class="text-sm">Reference (Last 4 digits)</label>
    <input type="text" id="gcashRef"
           maxlength="4"
           class="w-full border rounded-lg p-2"
           placeholder="e.g. 1234">
</div>

{{-- CARD --}}
<div class="mt-4 hidden" id="cardField">
    <label class="text-sm">Card Type</label>
    <select id="cardType" class="w-full border rounded-lg p-2">
        <option value="credit">Credit</option>
        <option value="debit">Debit</option>
    </select>

    <label class="text-sm mt-2 block">Last 4 digits</label>
    <input type="text" id="cardLast4"
           maxlength="4"
           class="w-full border rounded-lg p-2"
           placeholder="1234">
</div>

            <div class="mt-4 flex justify-between text-xl font-bold">
                <span>Change</span>
                <span id="change">₱0.00</span>
            </div>

            {{-- ✅ SUBMIT BUTTON (IMPORTANT) --}}
            <button type="submit"
                class="w-full mt-6 bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-xl font-semibold">
                Complete Sale
            </button>

        </div>

    </div>
</form>

<script>
let cart = [];

/* =========================
   ELEMENT REFERENCES
========================= */
const cartContainer = document.getElementById('cartItems');
const subtotalEl = document.getElementById('subtotal');
const taxEl = document.getElementById('tax');
const totalEl = document.getElementById('total');
const paymentInput = document.getElementById('paymentInput');
const changeEl = document.getElementById('change');

const paymentMethod = document.getElementById('paymentMethod');
const cashField = document.getElementById('cashField');
const gcashField = document.getElementById('gcashField');
const cardField = document.getElementById('cardField');

/* =========================
   PRODUCT CLICK (ADD TO CART)
========================= */
document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('click', () => {
        const product = {
            id: card.dataset.id,
            name: card.dataset.name,
            price: parseFloat(card.dataset.price),
            qty: 1
        };

        const existing = cart.find(item => item.id === product.id);

        if (existing) {
            existing.qty++;
        } else {
            cart.push(product);
        }

        renderCart();
    });
});

/* =========================
   RENDER CART
========================= */
function renderCart() {
    cartContainer.innerHTML = '';

    let subtotal = 0;

    cart.forEach(item => {
        const itemTotal = item.price * item.qty;
        subtotal += itemTotal;

        cartContainer.innerHTML += `
            <div class="flex justify-between items-center border-b pb-2">
                <div>
                    <p class="font-semibold">${item.name}</p>
                    <p class="text-sm">₱${item.price.toFixed(2)} x ${item.qty}</p>
                </div>

                <div class="flex items-center gap-2">
                    <button onclick="updateQty('${item.id}', -1)" class="px-2 bg-gray-200">-</button>
                    <span>${item.qty}</span>
                    <button onclick="updateQty('${item.id}', 1)" class="px-2 bg-gray-200">+</button>
                </div>
            </div>
        `;
    });

    updateTotals(subtotal);
}

/* =========================
   UPDATE TOTALS
========================= */
function updateTotals(subtotal) {
    const tax = subtotal * 0.12;
    const total = subtotal + tax;

    subtotalEl.textContent = formatPeso(subtotal);
    taxEl.textContent = formatPeso(tax);
    totalEl.textContent = formatPeso(total);

    updateChange(total);
}

/* =========================
   UPDATE QUANTITY
========================= */
function updateQty(id, change) {
    const item = cart.find(i => i.id === id);
    if (!item) return;

    item.qty += change;

    if (item.qty <= 0) {
        cart = cart.filter(i => i.id !== id);
    }

    renderCart();
}

/* =========================
   PAYMENT METHOD SWITCH
========================= */
paymentMethod.addEventListener('change', () => {
    const method = paymentMethod.value;

    // hide all
    cashField.classList.add('hidden');
    gcashField.classList.add('hidden');
    cardField.classList.add('hidden');

    // show selected
    if (method === 'cash') {
        cashField.classList.remove('hidden');
    } else if (method === 'gcash') {
        gcashField.classList.remove('hidden');
    } else if (method === 'card') {
        cardField.classList.remove('hidden');
    }
});

/* =========================
   CHANGE CALCULATION
========================= */
function updateChange(total) {
    const payment = parseFloat(paymentInput.value) || 0;
    const change = payment - total;

    changeEl.textContent = formatPeso(change > 0 ? change : 0);
}

paymentInput.addEventListener('input', () => {
    const total = extractNumber(totalEl.textContent);
    updateChange(total);
});

/* =========================
   FORM SUBMISSION
========================= */
document.getElementById('salesForm').addEventListener('submit', function () {

    const subtotal = extractNumber(subtotalEl.textContent);
    const tax = extractNumber(taxEl.textContent);
    const total = extractNumber(totalEl.textContent);

    document.getElementById('cartInput').value = JSON.stringify(cart);
    document.getElementById('subtotalInput').value = subtotal;
    document.getElementById('taxInput').value = tax;
    document.getElementById('totalInput').value = total;
    document.getElementById('paymentMethodInput').value = paymentMethod.value;

    // dynamic fields
    if (paymentMethod.value === 'gcash') {
        document.getElementById('referenceInput').value =
            document.getElementById('gcashRef').value;
    }

    if (paymentMethod.value === 'card') {
        document.getElementById('referenceInput').value =
            document.getElementById('cardLast4').value;

        document.getElementById('cardTypeInput').value =
            document.getElementById('cardType').value;
    }
});

/* =========================
   HELPERS
========================= */
function formatPeso(value) {
    return '₱' + value.toFixed(2);
}

function extractNumber(text) {
    return parseFloat(text.replace('₱', '')) || 0;
}
</script>   
@endsection