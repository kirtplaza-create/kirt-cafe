<template>
    <!-- Cozy Pixel Layout Container with Pixelify Sans font -->
    <div class="flex items-start gap-6 max-w-[1440px] mx-auto p-6 min-h-screen bg-[#f4eae1] font-['Pixelify_Sans',sans-serif]">
        <Sidebar />
        
        <main class="flex-1 min-w-0 bg-[#fffcf7] border-4 border-[#6b4e3d] rounded-2xl p-6 shadow-[6px_6px_0px_#4a3b32] flex flex-col justify-start h-[calc(100vh-3rem)] overflow-y-auto">
            <!-- Header Bar -->
            <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-[#e6d8cb] flex-shrink-0">
                <div class="text-base text-[#6b4e3d] font-bold flex items-center gap-4">
                    <span>{{ currentDate }}</span>
                    <span>{{ currentTime }}</span>
                </div>

                <div class="flex items-center gap-4">
                    <input 
                        v-model="searchQuery" 
                        type="text" 
                        placeholder="Search Menu..." 
                        class="w-64 bg-[#fbf6f0] border-2 border-[#6b4e3d] rounded-xl px-3 py-2 text-base font-bold focus:outline-none focus:bg-white text-[#4a3b32] placeholder-[#a0826e]" 
                    />
                    <div class="flex items-center gap-2 text-base font-bold text-[#6b4e3d]">
                        <span>Cashier 1</span>
                        <div class="w-9 h-9 rounded-lg bg-[#e2f0d9] border-2 border-[#539d6c] flex items-center justify-center text-[#2d5a36] text-lg">
                            🌿
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Filter Pills (Compact Grid) -->
<div class="grid grid-cols-6 gap-2 mb-6 flex-shrink-0">
    <button 
        v-for="cat in store.categories" 
        :key="cat"
        @click="store.selectedCategory = cat"
        :class="[
            'py-2 px-1 text-center rounded-xl text-base font-bold border-2 transition-all duration-150 cursor-pointer leading-tight',
            store.selectedCategory === cat 
                ? 'bg-[#e2f0d9] border-[#539d6c] text-[#2d5a36] shadow-[2px_2px_0px_#366036]' 
                : 'bg-[#fbf6f0] border-[#6b4e3d] text-[#6b4e3d] hover:bg-[#f4eae1]'
        ]"
    >
        {{ cat }}
    </button>
</div>

            <!-- Menu Grid -->
            <div class="grid grid-cols-3 gap-4 auto-rows-max items-start">
                <div 
                    v-for="item in displayedItems" 
                    :key="item.id" 
                    class="border-2 border-[#6b4e3d] rounded-2xl p-3 bg-[#fffcf7] shadow-[3px_3px_0px_#4a3b32] flex flex-col justify-between"
                >
                    <div>
                        <div class="w-full h-36 rounded-xl overflow-hidden mb-3 border-2 border-[#e6d8cb] bg-[#fbf6f0]">
                            <img 
                                :src="item.image || 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=400'" 
                                :alt="item.name" 
                                class="w-full h-full object-cover" 
                            />
                        </div>
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-bold text-[#4a3b32] text-lg leading-tight">{{ item.name }}</h3>
                                <span class="text-sm font-bold text-[#8c6d58]">{{ item.category }}</span>
                            </div>
                            <span class="font-bold text-[#2d5a36] text-lg">₱{{ Number(item.price).toFixed(2) }}</span>
                        </div>
                    </div>

                    <!-- Options & Counter -->
                    <!-- Options & Counter Row -->
<!-- Options & Counter Section (Stacked & Uniform) -->
<div class="mt-3 pt-2 border-t-2 border-[#e6d8cb] space-y-2">
    
    <!-- Row 1: Option Pills (Left Aligned) -->
    <div class="flex items-center gap-1.5 flex-wrap min-h-[28px]">
        <button 
            v-for="opt in getParsedOptions(item.options)" 
            :key="opt" 
            @click.stop="selectOption(item.id, opt)"
            type="button"
            :class="[
                'h-7 px-2.5 text-base font-bold rounded-lg border-2 flex items-center justify-center transition-all cursor-pointer whitespace-nowrap font-[\'VT323\']',
                getActiveOption(item) === opt 
                    ? 'bg-[#e2f0d9] border-[#539d6c] text-[#2d5a36] shadow-[1px_1px_0px_#366036]' 
                    : 'bg-[#fbf6f0] border-[#6b4e3d] text-[#6b4e3d] hover:bg-[#f4eae1]'
            ]"
        >
            {{ opt }}
        </button>
    </div>

    <!-- Row 2: Quantity Counter (Aligned Right) -->
    <div class="flex items-center justify-end">
        <div class="flex items-center gap-1.5 bg-[#fbf6f0] border-2 border-[#6b4e3d] rounded-lg px-2 h-7">
            <button 
                @click.stop="handleUpdateQuantity(item, -1)"
                type="button"
                class="w-5 h-5 bg-[#fffcf7] border border-[#6b4e3d] rounded flex items-center justify-center text-base font-bold text-[#4a3b32] hover:bg-[#e2f0d9] cursor-pointer font-['VT323']"
            >
                -
            </button>
            <span class="text-base font-bold px-1 min-w-[16px] text-center text-[#4a3b32] font-['VT323']">
                {{ getItemQuantity(item) }}
            </span>
            <button 
                @click.stop="handleAddToCart(item)"
                type="button"
                class="w-5 h-5 bg-[#539d6c] border border-[#2d5a36] text-white rounded flex items-center justify-center text-base font-bold hover:bg-[#366036] cursor-pointer font-['VT323']"
            >
                +
            </button>
        </div>
    </div>

</div>
                </div>

                <!-- Empty Filter Message -->
                <div v-if="displayedItems.length === 0" class="col-span-3 text-center py-12 text-[#8c6d58] text-lg font-bold">
                    No items available matching "<span class="text-[#2d5a36]">{{ searchQuery }}</span>"
                </div>
            </div>
        </main>

        <OrderSummary />
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import Sidebar from '@/components/Sidebar.vue';
import OrderSummary from '@/components/OrderSummary.vue';
import { usePosStore } from '@/stores/posStore';

const store = usePosStore();
const searchQuery = ref('');

// Parse raw options column safely whether it's an Array, JSON String, or null
const getParsedOptions = (options) => {
    if (!options) return ['Default'];
    if (Array.isArray(options)) return options;
    if (typeof options === 'string') {
        try {
            const parsed = JSON.parse(options);
            return Array.isArray(parsed) ? parsed : [options];
        } catch (e) {
            return [options];
        }
    }
    return ['Default'];
};

// Track active option selection per product ID (e.g., { 1: 'Hot', 2: 'Big' })
const selectedOptions = ref({});

const selectOption = (itemId, option) => {
    selectedOptions.value[itemId] = option;
};

const getActiveOption = (item) => {
    const opts = getParsedOptions(item.options);
    return selectedOptions.value[item.id] || opts[0] || 'Default';
};

const getItemQuantity = (item) => {
    const activeOption = getActiveOption(item);
    const cartItem = store.cart.find(i => 
        i.id === item.id && i.tag === activeOption
    );
    return cartItem ? cartItem.quantity : 0;
};

const handleAddToCart = (item) => {
    const activeOption = getActiveOption(item);
    store.addToCart(item, activeOption);
};

const handleUpdateQuantity = (item, delta) => {
    const activeOption = getActiveOption(item);
    store.updateQuantity(item.id, delta, activeOption);
};

const displayedItems = computed(() => {
    const items = store.items || store.menuItems || [];
    const query = searchQuery.value.toLowerCase().trim();

    return items.filter((item) => {
        const matchesSearch = !query || 
            item.name?.toLowerCase().includes(query) || 
            item.category?.toLowerCase().includes(query);

        const matchesCategory = !store.selectedCategory || 
            store.selectedCategory === 'All Menu' || 
            item.category === store.selectedCategory;

        return matchesSearch && matchesCategory;
    });
});

const currentDate = ref('');
const currentTime = ref('');
let timer = null;

const updateDateTime = () => {
    const now = new Date();
    currentDate.value = now.toLocaleDateString('en-GB', {
        weekday: 'short', day: 'numeric', month: 'long', year: 'numeric'
    });
    currentTime.value = now.toLocaleTimeString('en-US', {
        hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true
    });
};

onMounted(() => {
    store.fetchMenuItems();
    updateDateTime();
    timer = setInterval(updateDateTime, 1000);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});
</script>