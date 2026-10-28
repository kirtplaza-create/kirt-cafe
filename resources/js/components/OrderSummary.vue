<template>
    <!-- Cozy Pixel Sidebar Container -->
    <aside class="w-80 flex-shrink-0 bg-[#fffcf7] border-4 border-[#6b4e3d] rounded-2xl p-6 shadow-[6px_6px_0px_#4a3b32] flex flex-col justify-between h-[calc(100vh-3rem)] sticky top-6">
        <div>
            <!-- Header -->
            <div class="text-center mb-4">
                <h2 class="text-3xl font-bold text-[#4a3b32]">Order Summary</h2>
                <!-- Strips extra '#' to prevent ##632954 -->
                <span class="text-[#2d5a36] font-bold text-2xl">
                    {{ store.orderNumber?.startsWith('#') ? store.orderNumber : `#${store.orderNumber || '000000'}` }}
                </span>
            </div>

            <!-- Customer & Table Controls -->
            <div class="space-y-3 mb-4">
                <input 
                    v-model="customerName"
                    type="text" 
                    placeholder="Customer Name" 
                    class="w-full bg-[#fbf6f0] border-2 border-[#6b4e3d] rounded-xl px-3 py-1.5 font-bold focus:outline-none focus:bg-white text-[#4a3b32] placeholder-[#a0826e]" 
                />
                <div class="flex gap-2">
                    <select 
                        v-model="store.selectedTable" 
                        class="w-1/2 bg-[#fbf6f0] border-2 border-[#6b4e3d] rounded-xl px-2 py-1 font-bold focus:outline-none text-[#4a3b32] cursor-pointer"
                    >
                        <option value="" disabled>Select Table</option>
                        <option value="1">Table 01</option>
                        <option value="2">Table 02</option>
                        <option value="3">Table 03</option>
                    </select>
                    <select 
                        v-model="store.orderType" 
                        class="w-1/2 bg-[#fbf6f0] border-2 border-[#6b4e3d] rounded-xl px-2 py-1 font-bold focus:outline-none text-[#4a3b32] cursor-pointer"
                    >
                        <option value="Dine In">Dine In</option>
                        <option value="Takeaway">Takeaway</option>
                    </select>
                </div>
            </div>

            <!-- Cart Items List -->
            <div class="space-y-3 max-h-[calc(100vh-25rem)] overflow-y-auto pr-1">
                <div 
                    v-for="item in store.cart" 
                    :key="item.id" 
                    class="flex items-center justify-between border-b-2 border-[#e6d8cb] pb-2"
                >
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg border-2 border-[#6b4e3d] bg-[#fbf6f0] overflow-hidden flex-shrink-0">
                            <img 
                                :src="item.image || 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=100'" 
                                :alt="item.name"
                                class="w-full h-full object-cover pixel-img" 
                            />
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-[#4a3b32] leading-none">{{ item.name }}</h4>
                            <span v-if="item.tag" class="bg-[#fce8d5] border border-[#c87d53] text-[#a0522d] text-sm font-bold px-1.5 py-0.5 rounded-md">
                                {{ item.tag }}
                            </span>
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="font-bold text-xl text-[#2d5a36]">
                            ₱{{ ((item.unitPrice || item.price || 0) * item.quantity).toFixed(2) }}
                        </div>
                        <div class="flex items-center gap-1 mt-1">
                            <button 
                                @click="store.updateQuantity(item.id, -1)" 
                                class="w-6 h-6 bg-[#fffcf7] border border-[#6b4e3d] rounded flex items-center justify-center font-bold text-[#4a3b32] hover:bg-[#e2f0d9] cursor-pointer"
                            >
                                -
                            </button>
                            <span class="text-xl font-bold px-1 min-w-[14px] text-center text-[#4a3b32]">
                                {{ item.quantity }}
                            </span>
                            <button 
                                @click="store.addToCart(item)" 
                                class="w-6 h-6 bg-[#539d6c] border border-[#2d5a36] text-white rounded flex items-center justify-center font-bold hover:bg-[#366036] cursor-pointer"
                            >
                                +
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty Cart State -->
                <div v-if="store.cart.length === 0" class="text-center py-12 text-[#8c6d58] text-2xl font-bold">
                    Your cart is empty
                </div>
            </div>
        </div>

        <!-- Totals & Payment -->
        <div class="mt-4 pt-3 border-t-2 border-[#e6d8cb] space-y-1 text-2xl">
            <div class="flex justify-between text-[#8c6d58] font-bold">
                <span>Sub Total</span>
                <span class="text-[#4a3b32]">₱{{ (store.subTotal || 0).toFixed(2) }}</span>
            </div>
            <div class="flex justify-between font-bold text-[#4a3b32] text-3xl pt-1 border-t-2 border-[#e6d8cb]">
                <span>Total</span>
                <span class="text-[#2d5a36]">₱{{ (store.grandTotal || store.total || 0).toFixed(2) }}</span>
            </div>

            <!-- Payment Methods -->
            <div class="grid grid-cols-3 gap-2 mt-3">
                <button 
                    v-for="method in ['Cash', 'Card', 'Scan']" 
                    :key="method"
                    @click="store.paymentMethod = method"
                    :class="[
                        'py-1 text-xl font-bold rounded-xl border-2 transition-all cursor-pointer',
                        store.paymentMethod === method 
                            ? 'border-[#539d6c] bg-[#e2f0d9] text-[#2d5a36] shadow-[2px_2px_0px_#366036]' 
                            : 'border-[#6b4e3d] bg-[#fbf6f0] text-[#6b4e3d] hover:bg-[#f4eae1]'
                    ]"
                >
                    {{ method }}
                </button>
            </div>

            <!-- Checkout Button -->
            <button 
                @click="handleCheckout" 
                class="w-full bg-[#539d6c] border-2 border-[#2d5a36] text-white font-bold py-2 rounded-xl text-3xl hover:bg-[#366036] shadow-[3px_3px_0px_#1e3d24] transition-all mt-3 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none"
                :disabled="store.cart.length === 0"
            >
                Place Order
            </button>
        </div>

        <!-- Pixel Floating Modal -->
        <PixelModal 
            :show="showModal" 
            title="Success!"
            :message="modalMessage" 
            @close="handleModalClose" 
        />
    </aside>
</template>

<script setup>
import { ref } from 'vue';
import { usePosStore } from '@/stores/posStore';
import PixelModal from './PixelModal.vue'; // Adjust path if PixelModal is located elsewhere in components

const store = usePosStore();
const customerName = ref('');

// Modal Reactive State
const showModal = ref(false);
const modalMessage = ref('');

const handleCheckout = async () => {
    // Current order number before resetting
    const orderNum = store.orderNumber?.startsWith('#') 
        ? store.orderNumber 
        : `#${store.orderNumber || '000000'}`;

    // Execute order logic from posStore
    if (typeof store.placeOrder === 'function') {
        await store.placeOrder(customerName.value);
    }

    // Set message & trigger retro floating modal
    modalMessage.value = `Order ${orderNum} successfully placed!`;
    showModal.value = true;
};

const handleModalClose = () => {
    showModal.value = false;
    customerName.value = ''; // Reset customer field on close
};
</script>

<style scoped>
.pixel-img {
    image-rendering: pixelated;
    image-rendering: crisp-edges;
}
</style>