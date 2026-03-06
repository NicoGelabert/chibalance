<x-app-layout>
    <div x-data="productItem({{ json_encode([
        'id' => $product->id,
        'slug' => $product->slug,
        'image' => $product->image,
        'title' => $product->title,
        'addToCartUrl' => route('cart.add', $product),
        'categories' => $product->categories->pluck('name'),
        'prices' => $product->prices->map(function ($price) {
            return [
                'number' => $price->number,
                'size' => $price->size,
            ];
        }),
        'benefits' => $product->benefits->map(function ($benefit) {
            return [
                'text' => $benefit->text,
            ];
        }),
        'images' => $product->images->pluck('url') 
        ]) }})"
        class="mx-auto product-view"
    >
        <x-icons.first_leave class="absolute pointer-events-none" />
        <x-icons.second_leave class="absolute pointer-events-none right-0 top-40 sm:top-28 z-10" />
        <div class="product_view_hero">
            <h2>
                {{$product->title}}
            </h2>
        </div>
        <div class="product-content">
            <div class="flex flex-col md:flex-row gap-3 md:gap-12 container">
                @if ($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->title }}" class="product-view-img">
                    @else
                    <img src="{{ asset('storage/common/noimage.png') }}" alt="" class="product-view-img">
                @endif
                <div class="flex flex-col gap-3 md:w-1/2">
                    <div class="flex gap-4">
                        <ul>
                            <template x-for="category in product.categories" :key="category">
                                <li class="product-view-category" x-text="category"></li>
                            </template>
                        </ul>
                    </div>
                    <ul>
                        <template x-for="price in product.prices" :key="price.number">
                            <li class="flex justify-between">
                                <h4 x-text="'€ ' + price.number"></h4>
                                <div class="flex gap-2 items-center product-view-prices">
                                    <x-icons.clock class="fill-gray_400" />
                                    <h4 class="text-gray_400" x-text="price.size"></h4>
                                </div>
                            </li>
                        </template>
                    </ul>
                    <div x-data="{expanded: false}">
                        <div
                            x-show="expanded"
                            x-collapse.min.120px
                            class="text-gray-500 wysiwyg-content"
                        >
                            {!! $product->description !!}
                        </div>
                        <p class="text-right">
                            <a
                                @click="expanded = !expanded"
                                href="javascript:void(0)"
                                class="text-purple-500 hover:text-purple-700"
                                x-text="expanded ? 'Read Less' : 'Read More'"
                            ></a>
                        </p>
                    </div>
                    
                    <x-button class="btn btn-primary" href="https://wa.me/353852727422?text={{ urlencode('Hello! I would like to make an appointment for ' . $product->title) }}" target="_blank">Whatsapp <x-icons.whatsapp /></x-button>
                </div>
            </div>
        </div>
        @if ($product->benefits && $product->benefits->isNotEmpty())
            <x-benefits :benefits="$product->benefits"/>
        @endif
        <x-products :products="$products" header_title="More Treatments"/>
    </div>
</x-app-layout>