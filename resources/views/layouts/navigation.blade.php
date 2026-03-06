<header
    x-data="{
        mobileMenuOpen: false,
        cartItemsCount: {{ \App\Helpers\Cart::getCartItemsCount() }},
    }"
    @cart-change.window="cartItemsCount = $event.detail.count"
    class="flex justify-between z-20 w-full px-4"
    id="navbar"
>
    <!-- Responsive Menu -->
    <div
        class="block fixed z-10 top-0 bottom-0 h-full w-full transition-all mobile-menu lg:hidden p-4"
        :class="mobileMenuOpen ? 'left-0' : 'left-full'"
    >
        <x-menu layout="col" gap="8" class="mobile-menu-inner" />
    </div>
    <div class="logo flex items-center lg:hidden">
        <x-application-logo/>
    </div>
    <div class="flex items-center gap-2.5 lg:hidden">    
        <div>
            <x-button href="/intake-form" class="btn btn-secondary">intake <x-icons.login /></x-button>
        </div>
        <!-- <div>
            <x-button href="https://wa.me/353852727422?text={{ urlencode('Hello! I would like more information about your treatments') }}" target="_blank" class="btn btn-primary">whatsapp <x-icons.whatsapp /></x-button>
        </div> -->
        <x-hamburguer />
    </div>
    <!--/ Responsive Menu -->

    <!-- Main Menu -->   
    
    <div
        :class="mobileMenuOpen ? 'hidden' : ''"
        class="hidden lg:flex w-full"
    >
        <x-menu class="desktop-menu-inner" />
    </div>
    <!--/ Main Menu -->
    
    
</header>

<script>
    var prevScrollpos = window.pageYOffset;
    var navbar = document.getElementById("navbar");
    // navbar.style.top = "5px";
    var scrollThreshold = 15; // Umbral de desplazamiento mínimo antes de ocultar el encabezado
    window.onscroll = function() {
        var currentScrollPos = window.pageYOffset;
        var scrollDifference = Math.abs(prevScrollpos - currentScrollPos);
        if (scrollDifference >= scrollThreshold) {
            if (prevScrollpos > currentScrollPos) {
                navbar.style.top = "0";
            } else {
                navbar.style.top = "-110px";
            }
        }
        prevScrollpos = currentScrollPos;

        var distanceFromTop = Math.abs(window.scrollY);
        if(distanceFromTop <= 5){
            document.getElementById("navbar").classList.remove("scrolled-bottom");
        }else{
            document.getElementById("navbar").classList.add("scrolled-bottom");
        }
    }
</script>