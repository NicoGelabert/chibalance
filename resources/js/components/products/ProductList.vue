<template>
    <div class="relative product-list">
        <!-- Indicador de carga -->
        <div v-if="loading" class="spinner-overlay">
            <div class="spinner"></div>
        </div>
        <!-- Mensaje de error -->
        <div v-if="error" class="error">{{ error }}</div>

        <!-- Filtro de categorías y listado de productos -->
        <div class="container flex flex-col lg:flex-row gap-4">
            <aside class="w-full lg:w-1/5 flex flex-col gap-4">
                <h6>Filter By</h6>
                <hr class="divider-product_list">
                <p>Type</p>
                <ul class="flex flex-wrap justify-start gap-4">
                    <li class="" :class="{ 'active-category': selectedCategory === null }">
                        <button @click="filterByCategory(null)" class="btn btn-secondary btn-products_list">
                            All
                        </button>
                    </li>
                    <li v-for="category in categories" :key="category.id" class="" :class="{ 'active-category': selectedCategory === category.slug }">
                        <button @click="filterByCategory(category.slug)" class="btn btn-secondary btn-products_list">
                            {{ category.name }}
                        </button>
                    </li>
                </ul>
            </aside>

            <!-- Productos, con clase de opacidad condicional -->
            <div :class="{ 'loading-opacity': loading }" class="flex-1">
                <ul class="grid gap-4 grid-cols-2 sm:grid-cols-2 md:grid-cols-3">
                    <li v-for="product in products" :key="product.id" class="relative overflow-hidden flex flex-col">
                        <a :href="'/all/' + product.categories[0]?.slug + '/' + product.slug" class="aspect-w-3 aspect-h-2 block overflow-hidden relative">
                            <div v-if="product.categories && product.categories.length > 0" class="mt-2 text-xs flex gap-2 absolute">
                                <ul class="font-bold text-xs">
                                    <li class="bg-gray_200 text-gray_600 rounded-md py-1 px-2 ml-2" v-for="category in product.categories" :key="category.id">{{ category.name }}</li>
                                </ul>
                            </div>
                            <img :src="product.image_url" alt="" class="card-image object-cover aspect-square rounded-lg hover:scale-105 transition-transform" />
                        </a>
                        <div class="flex flex-col py-4 gap-2">
                            <div v-if="product.prices && product.prices.length > 0">
                                <ul class="font-bold text-sm text-gray_500">
                                    <li v-for="price in product.prices" :key="price.id">{{ price.size }}</li>
                                </ul>
                            </div>
                            <h5 class="w-fit">{{ product.title }}</h5>
                            <div class="flex gap-4 card-buttons">
                                <button @click="openBooking(product)" class="btn btn-primary btn-products_list">
                                    <span>Book </span><BookIcon />
                                </button>

                                <a :href="'/all/' + product.categories[0]?.slug + '/' + product.slug" class="btn btn-secondary btn-products_list"><span>See More </span><SendIcon /></a>
                            </div>
                        </div>
                    </li>
                </ul>
                <BookingModal 
                    v-if="showBookingModal" 
                    :product="selectedProduct" 
                    @close="closeBooking" 
                />

            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import BookIcon from '../icons/BookIcon.vue';
import SendIcon from '../icons/SendIcon.vue';
import BookingModal from '../BookingModal.vue';

export default {
    components: {
        // Registra el componente
        BookIcon,
        SendIcon,
        BookingModal,
    },
    data() {
        return {
            products: [],  // Para almacenar los productos
            categories: [],
            loading: true,  // Para manejar el estado de carga
            error: null,    // Para manejar errores de la API
            selectedCategory: null,
            showBookingModal: false,
            selectedProduct: null,
            };
        },
    mounted() {
        this.fetchProducts();
        this.fetchCategories();
    },
    methods: {
        async fetchProducts() {
            const categorySlug = this.selectedCategory ? this.selectedCategory : '';  // Si hay categoría seleccionada, la pasamos
            try {
                const response = await axios.get('/all', {
                    params: {
                        category: categorySlug,  // Filtro por categoría
                        },
                    });
                this.products = response.data.products;
                this.loading = false;  // Cambiamos el estado de carga
            } catch (error) {
                console.error('Error fetching products:', error);
                this.loading = false;  // Cambiamos el estado de carga
            }
        },
        async fetchCategories() {
            try {
                const response = await axios.get('/categories');
                this.categories = response.data.categories;
            } catch (error) {
                console.error('Error fetching categories:', error);
            }
        },
        filterByCategory(slug) {
            this.selectedCategory = slug;
            this.loading = true;  // Activamos el estado de carga
            this.fetchProducts();
        },
        openBooking(product) {
            this.selectedProduct = product;
            this.showBookingModal = true;
        },
        closeBooking() {
            this.showBookingModal = false;
            this.selectedProduct = null;
        }
    }
};
</script>
