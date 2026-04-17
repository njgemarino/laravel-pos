@extends('layouts.app')

@section('content')

<style>
*{box-sizing:border-box}

.pos-root{display:flex;height:calc(100vh - 80px);gap:14px;padding:14px}

/* ── LEFT ── */
.left{flex:1;display:flex;flex-direction:column;min-width:0;overflow:hidden}
.top-bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-shrink:0}
.top-bar-title{font-size:18px;font-weight:500;color:#0f0f0f}
.top-bar-sub{font-size:12px;color:#999;margin-top:2px}
.search-box{display:flex;align-items:center;gap:8px;background:#fff;border:0.5px solid #e5e2d8;border-radius:8px;padding:7px 12px;width:210px}
.search-box svg{width:14px;height:14px;opacity:.4;flex-shrink:0}
.search-box input{border:none;outline:none;background:transparent;font-size:13px;color:#0f0f0f;width:100%;font-family:'DM Sans',sans-serif}
.search-box input::placeholder{color:#bbb}

.cat-tabs{display:flex;gap:6px;margin-bottom:12px;flex-shrink:0;flex-wrap:wrap}
.cat-tab{padding:5px 13px;border-radius:20px;font-size:12px;cursor:pointer;border:0.5px solid #e5e2d8;background:#fff;color:#888;transition:all .15s;font-family:'DM Sans',sans-serif}
.cat-tab.active{background:#0f0f0f;color:#fff;border-color:#0f0f0f}

.prod-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(148px,1fr));gap:10px;overflow-y:auto;flex:1;padding-right:4px;align-content:start}
.prod-grid::-webkit-scrollbar{width:4px}
.prod-grid::-webkit-scrollbar-thumb{background:#e0ddd4;border-radius:4px}

.prod-card{background:#fff;border:0.5px solid #e5e2d8;border-radius:12px;padding:14px;cursor:pointer;transition:border-color .15s,transform .12s;position:relative;user-select:none}
.prod-card:hover{border-color:#0f0f0f;transform:translateY(-1px)}
.prod-card.in-cart{border-color:#2563eb;background:#eff6ff}
.prod-card.out-of-stock{opacity:.4;pointer-events:none}
.qty-badge{position:absolute;top:10px;right:10px;background:#2563eb;color:#fff;font-size:10px;font-weight:500;width:20px;height:20px;border-radius:50%;display:none;align-items:center;justify-content:center;line-height:1}
.in-cart .qty-badge{display:flex}
.prod-icon{width:36px;height:36px;border-radius:8px;background:#f5f3ec;display:flex;align-items:center;justify-content:center;margin-bottom:10px;font-size:16px}
.prod-name{font-size:13px;font-weight:500;color:#1a1a1a;margin-bottom:3px;line-height:1.3}
.prod-cat{font-size:11px;color:#bbb;margin-bottom:8px}
.prod-footer{display:flex;align-items:center;justify-content:space-between}
.prod-price{font-size:15px;font-weight:500;color:#0f0f0f}
.stock-pill{font-size:10px;padding:2px 7px;border-radius:20px;background:#ecfdf5;color:#059669;border:0.5px solid #a7f3d0}
.stock-pill.low{background:#fffbeb;color:#d97706;border-color:#fcd34d}
.stock-pill.out{background:#fef2f2;color:#dc2626;border-color:#fca5a5}

/* ── RIGHT ── */
.right{width:300px;flex-shrink:0;display:flex;flex-direction:column;background:#fff;border:0.5px solid #e5e2d8;border-radius:12px;overflow:hidden}
.chk-head{padding:16px;border-bottom:0.5px solid #e5e2d8;display:flex;align-items:center;justify-content:space-between;flex-shrink:0}
.chk-head-left h3{font-size:15px;font-weight:500;color:#0f0f0f;margin-bottom:2px}
.chk-head-left p{font-size:11px;color:#999}
.clear-btn{font-size:11px;color:#999;background:none;border:0.5px solid #e5e2d8;border-radius:8px;padding:4px 9px;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .15s}
.clear-btn:hover{color:#dc2626;border-color:#fca5a5}

.cart-body{flex:1;overflow-y:auto;padding:10px 14px}
.cart-body::-webkit-scrollbar{width:3px}
.cart-body::-webkit-scrollbar-thumb{background:#e0ddd4;border-radius:3px}
.empty-state{display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;gap:10px;color:#ccc}
.empty-state p{font-size:12px;text-align:center}
.cart-item{padding:10px 0;border-bottom:0.5px solid #f0ece0}
.cart-item:last-child{border-bottom:none}
.ci-name{font-size:12px;font-weight:500;color:#1a1a1a;margin-bottom:7px}
.ci-row{display:flex;align-items:center;justify-content:space-between}
.qty-ctrl{display:flex;align-items:center;gap:6px}
.qb{width:24px;height:24px;border-radius:6px;border:0.5px solid #e5e2d8;background:transparent;color:#888;font-size:16px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .12s;font-family:'DM Sans',sans-serif}
.qb:hover{background:#f5f3ec;color:#0f0f0f}
.qb.remove:hover{background:#fef2f2;color:#dc2626;border-color:#fca5a5}
.qty-num{font-size:13px;color:#0f0f0f;min-width:20px;text-align:center;font-weight:500}
.ci-right{text-align:right}
.ci-unit{font-size:10px;color:#bbb}
.ci-total{font-size:13px;font-weight:500;color:#0f0f0f}

.chk-foot{padding:14px;border-top:0.5px solid #e5e2d8;flex-shrink:0}
.tot-row{display:flex;justify-content:space-between;font-size:12px;color:#888;padding:3px 0}
.tot-grand{display:flex;justify-content:space-between;font-size:15px;font-weight:500;color:#0f0f0f;padding:10px 0 12px;border-top:0.5px solid #e5e2d8;margin-top:6px}
.field-label{font-size:10px;color:#bbb;text-transform:uppercase;letter-spacing:.07em;margin-bottom:5px;margin-top:10px}
.pos-select,.pos-input{width:100%;background:#faf9f5;border:0.5px solid #e5e2d8;border-radius:8px;padding:8px 10px;font-size:12px;color:#0f0f0f;outline:none;font-family:'DM Sans',sans-serif;transition:border-color .15s;-webkit-appearance:none;appearance:none}
.pos-select:focus,.pos-input:focus{border-color:#2563eb}
.change-row{display:flex;justify-content:space-between;align-items:center;padding:8px 0;margin-top:4px}
.change-label{font-size:12px;color:#888}
.change-val{font-size:15px;font-weight:500;color:#059669}
.payment-methods{display:grid;grid-template-columns:repeat(3,1fr);gap:6px;margin-bottom:2px}
.pay-opt{padding:7px 0;border:0.5px solid #e5e2d8;border-radius:8px;text-align:center;font-size:11px;cursor:pointer;color:#888;background:#faf9f5;transition:all .15s;font-family:'DM Sans',sans-serif}
.pay-opt.active{border-color:#2563eb;color:#2563eb;background:#eff6ff}
.submit-btn{width:100%;padding:11px;background:#0f0f0f;border:none;border-radius:8px;font-size:13px;font-weight:500;color:#fff;cursor:pointer;font-family:'DM Sans',sans-serif;transition:opacity .15s;display:flex;align-items:center;justify-content:center;gap:8px}
.submit-btn:hover{opacity:.85}
.submit-btn:disabled{opacity:.3;cursor:not-allowed}
.submit-btn svg{width:14px;height:14px}
</style>

<form action="{{ route('sales.store') }}" method="POST" id="salesForm">
    @csrf
    <input type="hidden" name="cart" id="cartInput">
    <input type="hidden" name="subtotal" id="subtotalInput">
    <input type="hidden" name="tax" id="taxInput">
    <input type="hidden" name="total" id="totalInput">
    <input type="hidden" name="payment_method" id="paymentMethodInput">
    <input type="hidden" name="reference" id="referenceInput">
    <input type="hidden" name="card_type" id="cardTypeInput">

    <div class="pos-root">

        {{-- ── LEFT: PRODUCTS ── --}}
        <div class="left">
            <div class="top-bar">
                <div>
                    <div class="top-bar-title">POS Sales</div>
                    <div class="top-bar-sub">Tap a product to add it to the order</div>
                </div>
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search products…">
                </div>
            </div>

            <div class="cat-tabs" id="catTabs">
                <button type="button" class="cat-tab active" data-cat="all">All</button>
                @foreach($products->pluck('category')->unique()->filter() as $cat)
                    <button type="button" class="cat-tab" data-cat="{{ $cat }}">{{ $cat }}</button>
                @endforeach
            </div>

            <div class="prod-grid" id="prodGrid">
                @foreach($products as $product)
                <div class="prod-card {{ $product->stock <= 0 ? 'out-of-stock' : '' }}"
                     data-id="{{ $product->id }}"
                     data-name="{{ $product->name }}"
                     data-price="{{ $product->price }}"
                     data-stock="{{ $product->stock }}"
                     data-category="{{ $product->category }}">

                    <div class="qty-badge">0</div>
                    <div class="prod-icon">{{ $product->icon ?? '📦' }}</div>
                    <div class="prod-name">{{ $product->name }}</div>
                    <div class="prod-cat">{{ $product->category ?? 'Product' }}</div>
                    <div class="prod-footer">
                        <div class="prod-price">₱{{ number_format($product->price, 2) }}</div>
                        <div class="stock-pill {{ $product->stock <= 0 ? 'out' : ($product->stock <= 3 ? 'low' : '') }}">
                            {{ $product->stock <= 0 ? 'Out of stock' : ($product->stock <= 3 ? $product->stock . ' left' : $product->stock) }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ── RIGHT: CHECKOUT PANEL ── --}}
        <div class="right">
            <div class="chk-head">
                <div class="chk-head-left">
                    <h3>Order</h3>
                    <p id="itemCount">No items yet</p>
                </div>
                <button type="button" class="clear-btn" id="clearBtn">Clear</button>
            </div>

            <div class="cart-body" id="cartBody">
                <div class="empty-state">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:32px;height:32px;opacity:.2">
                        <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                        <path d="M1 1h4l2.68 13.39a2 2 0 001.98 1.61h9.72a2 2 0 001.98-1.61L23 6H6"/>
                    </svg>
                    <p>Tap a product to start the order</p>
                </div>
            </div>

            <div class="chk-foot">
                <div class="tot-row"><span>Subtotal</span><span id="subtotal">₱0.00</span></div>
                <div class="tot-row"><span>Tax (12%)</span><span id="tax">₱0.00</span></div>
                <div class="tot-grand"><span>Total</span><span id="total">₱0.00</span></div>

                <div class="field-label">Payment method</div>
                <div class="payment-methods">
                    <button type="button" class="pay-opt active" data-method="cash">Cash</button>
                    <button type="button" class="pay-opt" data-method="gcash">GCash</button>
                    <button type="button" class="pay-opt" data-method="card">Card</button>
                </div>

                <div id="cashField">
                    <div class="field-label">Amount received</div>
                    <input type="number" class="pos-input" name="payment" id="paymentInput" placeholder="₱0.00" min="0">
                </div>
                <div id="gcashField" style="display:none">
                    <div class="field-label">GCash ref (last 4 digits)</div>
                    <input type="text" class="pos-input" id="gcashRef" maxlength="4" placeholder="1234">
                </div>
                <div id="cardField" style="display:none">
                    <div class="field-label">Card type</div>
                    <select class="pos-select" id="cardType">
                        <option value="credit">Credit card</option>
                        <option value="debit">Debit card</option>
                    </select>
                    <div class="field-label">Last 4 digits</div>
                    <input type="text" class="pos-input" id="cardLast4" maxlength="4" placeholder="1234">
                </div>

                <div class="change-row">
                    <span class="change-label">Change</span>
                    <span class="change-val" id="change">₱0.00</span>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn" disabled>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    Complete sale
                </button>
            </div>
        </div>

    </div>
</form>

<script>
let cart = [];
let activeMethod = 'cash';
let activeCategory = 'all';
let searchQuery = '';

/* ── CATEGORY TABS ── */
document.querySelectorAll('.cat-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        activeCategory = btn.dataset.cat;
        document.querySelectorAll('.cat-tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        filterProducts();
    });
});

/* ── SEARCH ── */
document.getElementById('searchInput').addEventListener('input', e => {
    searchQuery = e.target.value.toLowerCase();
    filterProducts();
});

function filterProducts() {
    document.querySelectorAll('.prod-card').forEach(card => {
        const catMatch = activeCategory === 'all' || card.dataset.category === activeCategory;
        const srchMatch = card.dataset.name.toLowerCase().includes(searchQuery);
        card.style.display = (catMatch && srchMatch) ? '' : 'none';
    });
}

/* ── ADD TO CART ── */
document.querySelectorAll('.prod-card').forEach(card => {
    card.addEventListener('click', () => {
        const id    = card.dataset.id;
        const name  = card.dataset.name;
        const price = parseFloat(card.dataset.price);
        const stock = parseInt(card.dataset.stock);
        const ex    = cart.find(i => i.id === id);
        if (ex) { if (ex.qty >= stock) return; ex.qty++; }
        else cart.push({ id, name, price, stock, qty: 1 });
        renderAll();
    });
});

/* ── RENDER PRODUCTS ── */
function renderProducts() {
    document.querySelectorAll('.prod-card').forEach(card => {
        const item  = cart.find(i => i.id === card.dataset.id);
        const badge = card.querySelector('.qty-badge');
        card.classList.toggle('in-cart', !!item);
        badge.textContent = item ? item.qty : 0;
    });
}

/* ── RENDER CART ── */
function renderCart() {
    const body  = document.getElementById('cartBody');
    const count = cart.reduce((s, i) => s + i.qty, 0);
    document.getElementById('itemCount').textContent =
        count > 0 ? count + ' item' + (count !== 1 ? 's' : '') + ' in order' : 'No items yet';

    if (!cart.length) {
        body.innerHTML = `<div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:32px;height:32px;opacity:.2">
                <circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/>
                <path d="M1 1h4l2.68 13.39a2 2 0 001.98 1.61h9.72a2 2 0 001.98-1.61L23 6H6"/>
            </svg>
            <p>Tap a product to start the order</p></div>`;
        return;
    }

    body.innerHTML = cart.map(item => `
        <div class="cart-item">
            <div class="ci-name">${item.name}</div>
            <div class="ci-row">
                <div class="qty-ctrl">
                    <button type="button" class="qb remove" onclick="updateQty('${item.id}', -1)">−</button>
                    <span class="qty-num">${item.qty}</span>
                    <button type="button" class="qb" onclick="updateQty('${item.id}', 1)">+</button>
                </div>
                <div class="ci-right">
                    <div class="ci-unit">₱${item.price.toFixed(2)} × ${item.qty}</div>
                    <div class="ci-total">₱${(item.price * item.qty).toFixed(2)}</div>
                </div>
            </div>
        </div>`).join('');
}

/* ── RENDER TOTALS ── */
function renderTotals() {
    const sub  = cart.reduce((s, i) => s + i.price * i.qty, 0);
    const tax  = sub * 0.12;
    const tot  = sub + tax;
    document.getElementById('subtotal').textContent = '₱' + sub.toFixed(2);
    document.getElementById('tax').textContent      = '₱' + tax.toFixed(2);
    document.getElementById('total').textContent    = '₱' + tot.toFixed(2);
    document.getElementById('submitBtn').disabled   = cart.length === 0;
    updateChange(tot);
}

function renderAll() { renderProducts(); renderCart(); renderTotals(); }

/* ── QTY BUTTONS ── */
function updateQty(id, delta) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty += delta;
    if (item.qty <= 0) cart = cart.filter(i => i.id !== id);
    renderAll();
}

/* ── CLEAR CART ── */
document.getElementById('clearBtn').addEventListener('click', () => { cart = []; renderAll(); });

/* ── PAYMENT METHOD ── */
document.querySelectorAll('.pay-opt').forEach(btn => {
    btn.addEventListener('click', () => {
        activeMethod = btn.dataset.method;
        document.querySelectorAll('.pay-opt').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('cashField').style.display  = activeMethod === 'cash'  ? 'block' : 'none';
        document.getElementById('gcashField').style.display = activeMethod === 'gcash' ? 'block' : 'none';
        document.getElementById('cardField').style.display  = activeMethod === 'card'  ? 'block' : 'none';
        renderTotals();
    });
});

/* ── CHANGE CALC ── */
function updateChange(total) {
    if (activeMethod !== 'cash') { document.getElementById('change').textContent = '₱0.00'; return; }
    const paid   = parseFloat(document.getElementById('paymentInput').value) || 0;
    const change = paid - total;
    document.getElementById('change').textContent = '₱' + (change > 0 ? change : 0).toFixed(2);
}

document.getElementById('paymentInput').addEventListener('input', () => {
    const tot = parseFloat(document.getElementById('total').textContent.replace('₱', '')) || 0;
    updateChange(tot);
});

/* ── FORM SUBMIT ── */
document.getElementById('salesForm').addEventListener('submit', function () {
    const sub    = parseFloat(document.getElementById('subtotal').textContent.replace('₱', '')) || 0;
    const tax    = parseFloat(document.getElementById('tax').textContent.replace('₱', ''))      || 0;
    const tot    = parseFloat(document.getElementById('total').textContent.replace('₱', ''))    || 0;
    const method = activeMethod;

    document.getElementById('cartInput').value          = JSON.stringify(cart);
    document.getElementById('subtotalInput').value      = sub;
    document.getElementById('taxInput').value           = tax;
    document.getElementById('totalInput').value         = tot;
    document.getElementById('paymentMethodInput').value = method;

    if (method === 'gcash') document.getElementById('referenceInput').value = document.getElementById('gcashRef').value;
    if (method === 'card') {
        document.getElementById('referenceInput').value = document.getElementById('cardLast4').value;
        document.getElementById('cardTypeInput').value  = document.getElementById('cardType').value;
    }
});
</script>

@endsection