<div class="container about">
    <h3>About Me</h3>
    @foreach ($abouts as $about)
    <div class="about-content">
        <div class="about-sub-content">
            <div class="about-short-description text-large">
                {!! $about->short_description !!}
            </div>
            <div class="flex justify-center items-center md:hidden">
                <img src="{{ $about->image }}" alt="">
            </div>
            <div>
                {!! $about->signature !!}
            </div>
            <x-button class="btn btn-primary">see more <x-icons.send /></x-button>
        </div>
        <div class="about-large-image">
            <img src="{{ $about->image }}" alt="">
        </div>
    </div>
    @endforeach
</div>