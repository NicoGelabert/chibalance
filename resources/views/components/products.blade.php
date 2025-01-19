<div class="bg-blue_light relative">
    <x-icons.second_leave class="absolute right-0 -top-12 sm:-top-16 md:-top-20" />
    <div class="container flex flex-col gap-6 py-12">
        <div class="treatments splide">
            <h3>Popular Treatments</h3>
            <p class="text-large">Book professional massage from monday to friday.</p>
            <div class="splide__track">
                <ul class="splide__list">
                    @foreach ($products->filter(fn($product) => $product->categories->contains('name', 'Treatments')) as $product)
                    <li class="splide__slide">
                        <div class="card">
                            <div class="card-image">
                                <img src="{{ $product->image }}" alt="{{ $product->title }}">
                            </div>
                            <div class="card-content">
                                <div class="card-content-text">
                                    <h5>{{ $product->title }}</h5>
                                    <div class="trunkated-text">
                                        {!! $product->description !!}
                                    </div>
                                </div>
                                <x-button href="{{ $product->link }}" class="btn btn-primary" target="_blank">book <x-icons.booking /></x-button>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <x-icons.third_leave class="absolute right-0 top-[60%]" />
        <div class="blends">
            <h4>Blends</h4>
            <p class="text-large">Improve your experience combining treatments.</p>
            <div class="blend-list">
                <ul>
                    @foreach ($products->filter(fn($product) => $product->categories->contains('name', 'Blends')) as $product)
                    <li>
                        <div class="blend-card">
                            <div class="blend-card-content">
                                <img src="{{ $product->image }}" alt="{{ $product->title }}">
                                <h6>{{ $product->title }}</h6>
                            </div>
                            <x-button class="btn" href="{{ $product->link }}"> <x-icons.send /></x-button>
                        </div>
                        <hr class="divider">
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>