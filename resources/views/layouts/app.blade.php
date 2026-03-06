<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        
            <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-VSERZWJ746"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-VSERZWJ746');
        </script>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta name="description" content="Chi Balance Therapies offers expert massage and holistic treatments in Ireland, helping you achieve relaxation, pain relief, and overall well-being. Our personalized therapies blend traditional and modern techniques to restore balance to your body and mind. Experience deep relaxation with our skilled therapist. Book your session today!">

        <meta name="keywords" content="Chi Balance Therapies, massage therapy, holistic treatments, relaxation massage, deep tissue massage, sports massage, wellness center, stress relief, therapeutic massage, Ireland massage, body balance, professional massage services, pain relief therapy, healing massage, mindfulness and wellness">

        
        <title inertia>{{ config('app.name', 'Chi Balance Therapies') }}</title>

        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('storage/common/chibalancetherapies.svg') }}">

        <!-- <script src="https://www.google.com/recaptcha/api.js" async defer></script> -->
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div id="loader-wrapper">
            <div class="logo">
                <x-application-logo/>
            </div>
            <div id="loader">
                <div id="progress-bar"></div>
            </div>
            <div id="loader-percentage">0%</div>
        </div>
        <div id="body-content">
            <!-- Toast -->
            <div
                x-data="toast"
                x-show="visible"
                x-transition
                x-cloak
                @notify.window="show($event.detail.message)"
                class="toast z-20 fixed w-full max-w-[350px] bottom-0 mb-8 right-0 mr-8 py-4 px-4 rounded-3xl"
            >
                <div class="flex justify-between w-full items-center z-10">
                    <div class="font-semibold" x-text="message"></div>
                    <button
                        @click="close"
                        class="w-[30px] h-[30px] flex items-center justify-center rounded-full hover:bg-black/10 transition-colors"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
                <!-- Progress -->
                <div>
                    <div
                        class="progress absolute left-0 bottom-0 right-0 h-full rounded-3xl"
                        :style="{'width': `${percent}%`}"
                    ></div>
                </div>
            </div>
            <!--/ Toast -->
            @include('layouts.navigation')
            <!-- <x-button href="https://wa.me/353852727422?text=Hello! I would like more information about your treatments" target="_blank" class="whatsapp-btn">
                <x-icons.whatsapp />
            </x-button> -->
            <main>
                {{ $slot }}
            </main>
            
            @include('layouts.footer')
        </div>
        <div id="booking-modal-root">
            <booking-modal
                v-if="showBookingModal"
                :product="selectedProduct"
                @close="closeBookingModal"
            ></booking-modal>
        </div>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let percentage = 0;
        const progressBar = document.getElementById('progress-bar');
        const interval = setInterval(function() {
            if (percentage < 100) {
                percentage += 1;
                document.getElementById('loader-percentage').innerText = percentage + '%';
                progressBar.style.width = percentage + '%';
            } else {
                clearInterval(interval);
                document.getElementById('loader-wrapper').style.display = 'none';
                const content = document.getElementById('body-content');
                content.style.display = 'block';
                setTimeout(function() {
                    content.classList.add('fade-in');
                }, 10);
            }
        });
    });
  
</script>