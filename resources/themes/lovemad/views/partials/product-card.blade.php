{{-- Daraz-Style Product Card --}}
<div class="card border-0 card-hover bg-white h-100" style="border-radius: 0; overflow: hidden;">
    {{-- Image --}}
    <a href="{{ route('product.detail', $product->slug) }}" class="d-block position-relative" style="aspect-ratio: 1/1; overflow: hidden; background: #f5f5f5;">
        <img src="{{ $product->primary_image }}" alt="{{ $product->name }}"
             class="w-100 h-100" style="object-fit: cover; transition: transform .3s;"
             onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">


        {{-- Badges --}}
        <div class="position-absolute top-0 start-0 p-2 d-flex flex-column gap-1">
            @if($product->getDiscountPercentAttribute())
                <span class="badge-sale">-{{ $product->getDiscountPercentAttribute() }}%</span>
            @endif
            @if($product->is_featured)
                <span class="badge-hot">HOT</span>
            @endif
        </div>
    </a>

    {{-- Info --}}
    <div class="card-body p-3 d-flex flex-column">
        {{-- Product Name --}}
        <h6 class="mb-2" style="font-size: 13px; font-weight: 400; line-height: 1.5; min-height: 40px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
            <a href="{{ route('product.detail', $product->slug) }}" class="text-dark text-decoration-none">
                {{ $product->name }}
            </a>
        </h6>

        {{-- Price --}}
        <div class="mt-auto">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <span class="price-current">৳{{ number_format($product->price, 0) }}</span>
                @if($product->compare_price && $product->compare_price > $product->price)
                    <span class="price-old">৳{{ number_format($product->compare_price, 0) }}</span>
                    <span class="price-discount">-{{ $product->getDiscountPercentAttribute() }}%</span>
                @endif
            </div>

            {{-- Rating --}}
            <div class="d-flex align-items-center mt-2">
                <div class="d-flex align-items-center me-2">
                    @php $avgRating = $product->getAverageRatingAttribute(); @endphp
                    @for($i = 1; $i <= 5; $i++)
                        <i class="bi bi-star{{ $i <= round($avgRating) ? '-fill' : '' }}" style="font-size: 11px; color: var(--lm-star);"></i>
                    @endfor
                </div>
                <span style="font-size: 11px; color: var(--lm-text-muted);">({{ $product->reviews_count ?? $product->reviews->count() }})</span>
            </div>

            {{-- Location / Vendor --}}
            @if($product->vendor)
                <div class="mt-2" style="font-size: 11px; color: var(--lm-text-muted);">
                    <i class="bi bi-shop me-1"></i>{{ Str::limit($product->vendor->store_name, 20) }}
                </div>
            @endif
        </div>
    </div>

    {{-- Add to Cart --}}
    <div class="card-footer bg-transparent border-0 p-3 pt-0">
        <form action="{{ route('cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold" style="font-size: 12px; border-radius: 2px; padding: 8px;">
                <i class="bi bi-cart-plus me-1"></i> ADD TO CART
            </button>
        </form>
    </div>
</div>
