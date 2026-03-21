/**
 * Mondals Default Theme - JavaScript
 */
document.addEventListener('DOMContentLoaded', function () {

    // ── Add to Cart (AJAX) ──
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', async function () {
            const productId = this.dataset.product;
            const qty = document.getElementById('qty')?.value || 1;
            const variantId = document.querySelector('.variant-btn.active')?.dataset.value || null;

            btn.textContent = 'Adding...';
            btn.disabled = true;

            try {
                const token = localStorage.getItem('auth_token');
                const res = await fetch('/api/v1/cart/items', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        ...(token ? { 'Authorization': 'Bearer ' + token } : {})
                    },
                    body: JSON.stringify({ product_id: productId, quantity: parseInt(qty), variant_id: variantId })
                });

                const data = await res.json();
                if (res.ok) {
                    btn.textContent = '✓ Added!';
                    updateCartCount();
                    setTimeout(() => { btn.textContent = 'Add to Cart'; btn.disabled = false; }, 2000);
                } else {
                    btn.textContent = data.message || 'Error';
                    setTimeout(() => { btn.textContent = 'Add to Cart'; btn.disabled = false; }, 2000);
                }
            } catch (e) {
                btn.textContent = 'Add to Cart';
                btn.disabled = false;
            }
        });
    });

    // ── Wishlist Toggle ──
    document.querySelectorAll('.wishlist-btn').forEach(btn => {
        btn.addEventListener('click', async function () {
            const productId = this.dataset.product;
            const token = localStorage.getItem('auth_token');

            const res = await fetch('/api/v1/wishlists/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    ...(token ? { 'Authorization': 'Bearer ' + token } : {})
                },
                body: JSON.stringify({ product_id: productId })
            });

            if (res.ok) {
                this.classList.toggle('active');
            }
        });
    });

    // ── Variant Selector ──
    document.querySelectorAll('.variant-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            // Deselect siblings
            this.closest('.variant-options')
                .querySelectorAll('.variant-btn')
                .forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // ── Cart Count ──
    function updateCartCount() {
        const token = localStorage.getItem('auth_token');
        if (!token) return;

        fetch('/api/v1/cart', {
            headers: { 'Accept': 'application/json', 'Authorization': 'Bearer ' + token }
        })
        .then(r => r.json())
        .then(data => {
            const count = data?.data?.items?.length || 0;
            document.querySelectorAll('.cart-count').forEach(el => el.textContent = count);
        })
        .catch(() => {});
    }

    updateCartCount();

});
