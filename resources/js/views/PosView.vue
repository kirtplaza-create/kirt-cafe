<template>
    <div class="flex gap-6 max-w-[1440px] mx-auto min-h-[90vh]">
        <Sidebar />

        <!-- Changed justify-between to justify-start -->
        <main class="flex-1 min-w-0 bg-white rounded-3xl p-6 shadow-sm flex flex-col justify-start">
            <!-- Header Bar -->
            <div class="flex items-center justify-between mb-6">
                <div class="text-xs text-gray-400 font-medium flex items-center gap-4">
                    <span>{{ currentDate }}</span>
                    <span>{{ currentTime }}</span>
                </div>

                <div class="flex items-center gap-4">
                    <input 
                        type="text" 
                        placeholder="Search Menu" 
                        class="w-64 border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none" 
                    />
                    <div class="flex items-center gap-2 text-xs font-semibold text-gray-600">
                        <span>Cashier 1</span>
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">👤</div>
                    </div>
                </div>
            </div>

            <!-- Category Filter Pills -->
            <div class="grid grid-cols-6 gap-3 mb-6">
                <button 
                    v-for="cat in store.categories" 
                    :key="cat"
                    @click="store.selectedCategory = cat"
                    :class="[
                        'py-3 px-4 rounded-2xl text-xs font-semibold border transition-all duration-200 cursor-pointer',
                        store.selectedCategory === cat 
                            ? 'bg-blue-50 border-blue-500 text-blue-600 shadow-sm font-bold' 
                            : 'bg-gray-50 border-transparent text-gray-500 hover:bg-gray-100'
                    ]"
                >
                    {{ cat }}
                </button>
            </div>

            <!-- Menu Grid: Keeps items aligned at the top left when filtering -->
            <div class="grid grid-cols-3 gap-4 auto-rows-max items-start">
                <div 
                    v-for="item in store.filteredItems" 
                    :key="item.id" 
                    class="border border-gray-100 rounded-3xl p-3 shadow-xs hover:shadow-md transition bg-white flex flex-col justify-between"
                >
                    <div>
                        <div class="w-full h-36 rounded-2xl overflow-hidden mb-3 bg-gray-100">
                            <img 
                                :src="item.image || 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=400'" 
                                :alt="item.name" 
                                class="w-full h-full object-cover" 
                            />
                        </div>
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-bold text-gray-800 text-sm">{{ item.name }}</h3>
                                <span class="text-[10px] text-gray-400">{{ item.category }}</span>
                            </div>
                            <span class="font-bold text-gray-800 text-sm">₱{{ Number(item.price).toFixed(2) }}</span>
                        </div>
                    </div>

                    <!-- Options & Counter -->
                    <div class="flex items-center justify-between mt-3 pt-2 border-t border-gray-50">
                        <div class="flex gap-1 flex-wrap">
                            <span 
                                v-for="opt in (item.options || [])" 
                                :key="opt" 
                                class="text-[10px] px-2 py-0.5 rounded-full border border-gray-200 text-gray-500"
                            >
                                {{ opt }}
                            </span>
                        </div>
                        <div class="flex items-center gap-1 bg-gray-100 rounded-full px-2 py-1">
                            <button 
                                @click="store.updateQuantity(item.id, -1)"
                                class="w-5 h-5 bg-white rounded-full flex items-center justify-center text-xs font-bold text-gray-600 shadow-xs hover:bg-gray-200 cursor-pointer"
                            >
                                -
                            </button>
                            <span class="text-xs font-bold px-1 min-w-[14px] text-center">
                                {{ store.cart.find(i => i.id === item.id)?.quantity || 0 }}
                            </span>
                            <button 
                                @click="store.addToCart(item)"
                                class="w-5 h-5 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold hover:bg-blue-700 shadow-xs cursor-pointer"
                            >
                                +
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty Filter Message -->
                <div v-if="store.filteredItems.length === 0" class="col-span-3 text-center py-12 text-gray-400 text-sm">
                    No items available in {{ store.selectedCategory }}
                </div>
            </div>
        </main>

        <OrderSummary />
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import Sidebar from '@/components/Sidebar.vue';
import OrderSummary from '@/components/OrderSummary.vue';
import { usePosStore } from '@/stores/posStore';

const store = usePosStore();

const currentDate = ref('');
const currentTime = ref('');
let timer = null;

const updateDateTime = () => {
    const now = new Date();
    
    // Format: Sun, 14 August 2025
    currentDate.value = now.toLocaleDateString('en-GB', {
        weekday: 'short',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    // Format: 17.20
    currentTime.value = now.toLocaleTimeString('en-GB', {
        hour: '2-digit',
        minute: '2-digit',
        hour12: false
    }).replace(':', '.');
};

onMounted(() => {
    store.fetchMenuItems();
    updateDateTime();
    timer = setInterval(updateDateTime, 1000); // Live clock update every second
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});
</script>