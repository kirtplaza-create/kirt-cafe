<template>
    <div class="w-80 bg-white rounded-3xl p-6 flex flex-col justify-between shadow-sm">
        <div>
            <!-- Header -->
            <div class="text-center mb-4">
                <h2 class="text-lg font-bold text-gray-800">Order Summary</h2>
                <span class="text-blue-600 font-semibold text-sm">{{ store.orderNumber }}</span>
            </div>

            <!-- Customer & Table Controls -->
            <div class="space-y-3 mb-4">
                <input 
                    v-model="customerName"
                    type="text" 
                    placeholder="Customer Name" 
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-blue-500" 
                />
                <div class="flex gap-2">
                    <select 
                        v-model="store.selectedTable" 
                        class="w-1/2 border border-gray-200 rounded-xl px-2 py-2 text-xs focus:outline-none bg-white cursor-pointer"
                    >
                        <option value="" disabled>Select Table</option>
                        <option value="1">Table 01</option>
                        <option value="2">Table 02</option>
                        <option value="3">Table 03</option>
                    </select>
                    <select 
                        v-model="store.orderType" 
                        class="w-1/2 border border-gray-200 rounded-xl px-2 py-2 text-xs focus:outline-none bg-white cursor-pointer"
                    >
                        <option value="Dine In">Dine In</option>
                        <option value="Takeaway">Takeaway</option>
                    </select>
                </div>
            </div>

            <!-- Cart Items List -->
            <div class="space-y-4 max-h-56 overflow-y-auto pr-1">
                <div 
                    v-for="item in store.cart" 
                    :key="item.id" 
                    class="flex items-center justify-between border-b pb-3 border-gray-100"
                >
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0">
                            <img 
                                :src="item.image || 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=100'" 
                                :alt="item.name"
                                class="w-full h-full object-cover" 
                            />
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">{{ item.name }}</h4>
                            <span class="bg-orange-100 text-orange-500 text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                {{ item.tag }}
                            </span>
                        </div>
                    </div>

                    <div class="text-right">
                        <div class="font-bold text-sm text-gray-800">
                            ₱{{ (item.unitPrice * item.quantity).toFixed(2) }}
                        </div>
                        <div class="flex items-center gap-1 mt-1">
                            <button 
                                @click="store.updateQuantity(item.id, -1)" 
                                class="w-5 h-5 bg-gray-200 rounded-full flex items-center justify-center text-xs font-bold text-gray-600 hover:bg-gray-300 transition-colors cursor-pointer"
                            >
                                -
                            </button>
                            <span class="text-xs font-semibold px-1 min-w-[14px] text-center">
                                {{ item.quantity }}
                            </span>
                            <button 
                                @click="store.addToCart(item)" 
                                class="w-5 h-5 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold hover:bg-blue-700 transition-colors cursor-pointer"
                            >
                                +
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty Cart State -->
                <div v-if="store.cart.length === 0" class="text-center py-8 text-gray-400 text-xs">
                    Your cart is empty
                </div>
            </div>
        </div>

                <!-- Totals & Payment -->
            <div class="mt-4 pt-4 border-t border-gray-100 space-y-2 text-sm">
                <div class="flex justify-between text-gray-500">
                    <span>Sub Total</span>
                    <span class="font-bold text-gray-800">₱{{ store.subTotal.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between text-gray-500">
                    <span>Tax 12%</span>
                    <span class="font-bold text-gray-800">₱{{ store.taxAmount.toFixed(2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-gray-800 text-base pt-2 border-t border-gray-50">
                    <span>Total</span>
                    <span>₱{{ store.grandTotal.toFixed(2) }}</span>
                </div>

            <!-- Payment Methods -->
            <div class="grid grid-cols-3 gap-2 mt-4">
                <button 
                    v-for="method in ['Cash', 'Card', 'Scan']" 
                    :key="method"
                    @click="store.paymentMethod = method"
                    :class="[
                        'py-2 text-xs font-bold rounded-xl border transition-all cursor-pointer',
                        store.paymentMethod === method 
                            ? 'border-blue-500 bg-blue-50 text-blue-600' 
                            : 'border-gray-200 text-gray-500 hover:bg-gray-50'
                    ]"
                >
                    {{ method }}
                </button>
            </div>

            <!-- Checkout Button -->
            <button 
                @click="store.placeOrder(customerName)" 
                class="w-full bg-blue-600 text-white font-bold py-3 rounded-2xl hover:bg-blue-700 transition-colors mt-3 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="store.cart.length === 0"
            >
                Place Order
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { usePosStore } from '@/stores/posStore';

const store = usePosStore();
const customerName = ref('');
</script>