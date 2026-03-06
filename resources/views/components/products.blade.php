@props(['header_title' => 'Popular Treatments'])
<div class="bg-blue_light relative">
    <x-icons.second_leave class="absolute pointer-events-none right-0 -top-12 sm:-top-16 md:-top-20" />
    <div class="container flex flex-col gap-6 py-12">
        <div class="treatments splide">
            <h3>{{ $header_title }}</h3>
            <p class="text-large">Book professional massage from monday to friday.</p>
            <div class="splide__track">
                <ul class="splide__list">
                    @foreach ($products->filter(fn($product) => $product->categories->contains('name', 'Treatments')) as $product)
                    <li class="splide__slide">
                        <div class="card">
                            <div class="card-image">
                            @if ($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->title }}">
                                @else
                                <img src="{{ asset('storage/common/noimage.png') }}" alt="">
                            @endif
                            </div>
                            <div class="card-content">
                                <div class="card-content-text">
                                    <h5>{{ $product->title }}</h5>
                                    <div class="trunkated-text">
                                        {!! $product->description !!}
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <x-button href="https://wa.me/353852727422?text={{ urlencode('Hello! I would like more information about ' . $product->title) }}" class="btn btn-primary" target="_blank">whatsapp <x-icons.whatsapp /></x-button>
                                    <x-button href="{{ route('product.view', ['category' => $product->categories->first()->slug, 'product' => $product->slug]) }}" class="btn btn-secondary">See more <x-icons.send /></x-button>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <x-icons.third_leave class="absolute pointer-events-none right-0 top-[60%]" />
        <div class="blends">
            <h4>Blends</h4>
            <p class="text-large">Improve your experience combining treatments.</p>
            <div class="blend-list">
                <ul>
                    @foreach ($products->filter(fn($product) => $product->categories->contains('name', 'Blends')) as $product)
                    <li>
                        <div class="blend-card">
                            <div class="blend-card-content">
                            @if ($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->title }}">
                                @else
                                <img src="{{ asset('storage/common/noimage.png') }}" alt="">
                            @endif
                                <h6>{{ $product->title }}</h6>
                            </div>
                            <div class="flex">
                                <x-button class="btn btn-primary px-2" href="https://wa.me/353852727422?text={{ urlencode('Hello! I would like more information about ' . $product->title) }}"> <x-icons.whatsapp /></x-button>
                                <x-button class="btn" href="{{ route('product.view', ['category' => $product->categories->first()->slug, 'product' => $product->slug]) }}"> <x-icons.send /></x-button>
                            </div>
                        </div>
                        <hr class="divider">
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>