<template>
    <!-- Cozy Pixel Outer Wrapper -->
    <div class="flex items-start gap-6 max-w-[1440px] mx-auto p-6 min-h-screen bg-[#f4eae1] font-['Pixelify_Sans',sans-serif]">
        <!-- 1. Left Sidebar -->
        <Sidebar />

        <!-- 2. Main Content Area (Cozy Pixel Card) -->
        <main class="flex-1 min-w-0 bg-[#fffcf7] border-4 border-[#6b4e3d] rounded-2xl p-6 shadow-[6px_6px_0px_#4a3b32] flex flex-col justify-start h-[calc(100vh-3rem)] overflow-y-auto">
            <!-- Header Bar -->
            <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-[#e6d8cb] flex-shrink-0">
                <div>
                    <h1 class="text-2xl font-bold text-[#4a3b32]">Order List</h1>
                    <p class="text-sm font-bold text-[#8c6d58]">Manage live kitchen and customer orders</p>
                </div>
                
                <button 
                    @click="fetchOrders"
                    class="px-4 py-2 bg-[#e2f0d9] border-2 border-[#539d6c] text-[#2d5a36] rounded-xl text-sm font-bold hover:bg-[#d0e6c2] shadow-[2px_2px_0px_#366036] transition-all cursor-pointer"
                >
                    Refresh List
                </button>
            </div>

            <!-- Empty State -->
            <div v-if="orders.length === 0" class="text-center py-20 text-[#8c6d58] text-lg font-bold my-auto">
                No active pending orders right now.
            </div>

            <!-- Orders Grid -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <div 
                    v-for="order in orders" 
                    :key="order.id" 
                    class="border-2 border-[#6b4e3d] rounded-2xl p-4 shadow-[3px_3px_0px_#4a3b32] bg-[#fbf6f0] flex flex-col justify-between"
                >
                    <div>
                        <!-- Order Header -->
                        <div class="flex justify-between items-center mb-3 pb-2 border-b-2 border-[#e6d8cb]">
                            <div>
                                <span class="font-bold text-[#539d6c] text-base">{{ order.order_number || '#000000' }}</span>
                                <span class="text-sm font-bold text-[#8c6d58] ml-2">({{ order.order_type || 'Dine In' }})</span>
                            </div>
                            <span class="bg-[#fce8d5] border border-[#c87d53] text-[#a0522d] text-xs font-bold px-2.5 py-1 rounded-lg uppercase">
                                {{ order.status || 'pending' }}
                            </span>
                        </div>

                        <!-- Customer Details -->
                        <div class="text-sm text-[#6b4e3d] mb-3 space-y-1 font-bold">
                            <div><span class="text-[#8c6d58]">Customer:</span> {{ order.customer_name || 'Guest' }}</div>
                            <div><span class="text-[#8c6d58]">Table:</span> {{ order.table_number || 'N/A' }}</div>
                            <div><span class="text-[#8c6d58]">Payment:</span> {{ order.payment_method || 'Cash' }}</div>
                        </div>

                        <!-- Items List -->
                        <div class="bg-[#fffcf7] rounded-xl p-3 space-y-2 mb-4 border-2 border-[#e6d8cb] max-h-36 overflow-y-auto">
                            <div 
                                v-for="item in (order.items || [])" 
                                :key="item.id"
                                class="flex justify-between items-center text-sm font-bold"
                            >
                                <span class="text-[#4a3b32]">
                                    {{ item.quantity }}x {{ item.product_name }}
                                </span>
                                <span class="text-[#539d6c]">
                                    ₱{{ formatPrice(item.subtotal) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Total & Actions -->
                    <div class="pt-2 border-t-2 border-[#e6d8cb] flex items-center justify-between">
                        <div>
                            <span class="text-xs text-[#8c6d58] block font-bold">Total Amount</span>
                            <span class="text-lg font-bold text-[#4a3b32]">
                                ₱{{ formatPrice(order.grand_total) }}
                            </span>
                        </div>

                        <div class="flex gap-2">
                            <button 
                                @click="updateStatus(order.id, 'cancelled')"
                                class="px-3 py-1.5 bg-[#fce8d5] border border-[#c87d53] text-[#a0522d] text-xs font-bold rounded-xl hover:bg-[#f8d4b8] cursor-pointer"
                            >
                                Cancel
                            </button>
                            <button 
                                @click="updateStatus(order.id, 'completed')"
                                class="px-3 py-1.5 bg-[#539d6c] border border-[#2d5a36] text-white text-xs font-bold rounded-xl hover:bg-[#366036] shadow-[2px_2px_0px_#244224] cursor-pointer"
                            >
                                Complete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import Sidebar from '@/components/Sidebar.vue';

const orders = ref([]);

const formatPrice = (val) => {
    const num = parseFloat(val);
    return isNaN(num) ? '0.00' : num.toFixed(2);
};

const fetchOrders = async () => {
    try {
        const response = await axios.get('/api/orders');
        orders.value = Array.isArray(response.data) ? response.data : [];
    } catch (error) {
        console.error('Failed to fetch orders:', error);
        orders.value = [];
    }
};

const updateStatus = async (orderId, status) => {
    try {
        await axios.patch(`/api/orders/${orderId}/status`, { status });
        orders.value = orders.value.filter(o => o.id !== orderId);
    } catch (error) {
        console.error('Failed to update status:', error);
        alert('Failed to update order status.');
    }
};

onMounted(() => {
    fetchOrders();
});
</script>