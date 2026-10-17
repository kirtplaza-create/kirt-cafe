<template>
    <!-- Cozy Pixel Left Sidebar -->
    <aside class="w-64 flex-shrink-0 bg-[#fffcf7] border-4 border-[#6b4e3d] rounded-2xl p-6 shadow-[6px_6px_0px_#4a3b32] flex flex-col justify-between h-[calc(100vh-3rem)] sticky top-6 font-['VT323'] tracking-wide">
        <div>
            <!-- Brand Logo / Header -->
            <div class="flex items-center gap-3 mb-8 pb-4 border-b-2 border-[#e6d8cb]">
                <div class="w-10 h-10 rounded-xl bg-[#539d6c] border-2 border-[#2d5a36] flex items-center justify-center text-white font-bold text-2xl shadow-[2px_2px_0px_#1e3d24]">
                    K
                </div>
                <span class="text-2xl font-bold text-[#4a3b32]">Kirt Cafe</span>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-2">
                <router-link 
                    v-for="item in navItems" 
                    :key="item.name" 
                    :to="item.path"
                    :class="[
                        'flex items-center gap-3 px-4 py-2.5 rounded-xl text-xl font-bold border-2 transition-all cursor-pointer',
                        $route.path === item.path 
                            ? 'bg-[#e2f0d9] border-[#539d6c] text-[#2d5a36] shadow-[2px_2px_0px_#366036]' 
                            : 'border-transparent text-[#6b4e3d] hover:bg-[#fbf6f0] hover:border-[#6b4e3d]'
                    ]"
                >
                    <component :is="item.icon" :size="20" class="stroke-[2.5]" />
                    <span>{{ item.name }}</span>
                </router-link>
            </nav>
        </div>

        <!-- Bottom Actions & Widget -->
        <div class="space-y-4">
            <!-- Promo / Recipe Card -->
            <div class="bg-[#fbf6f0] border-2 border-[#6b4e3d] rounded-xl p-4 text-center shadow-[2px_2px_0px_#4a3b32]">
                <h4 class="text-xl font-bold text-[#4a3b32] mb-1">Create New Recipe</h4>
                <p class="text-sm font-bold text-[#8c6d58] mb-3">Manage Your Culinary Creations</p>
                <button class="w-full bg-[#539d6c] border-2 border-[#2d5a36] text-white font-bold py-1.5 rounded-lg text-lg hover:bg-[#366036] shadow-[2px_2px_0px_#1e3d24] transition-all cursor-pointer">
                    Get Started
                </button>
            </div>

            <!-- Logout Link -->
            <button 
                @click="handleLogout"
                class="flex items-center gap-2 text-xl font-bold text-[#a0522d] hover:text-[#c87d53] transition-colors cursor-pointer w-full px-2 py-1"
            >
                <LogOut :size="20" class="stroke-[2.5]" />
                <span>Logout</span>
            </button>
        </div>
    </aside>
</template>

<script setup>
import { useRoute, useRouter } from 'vue-router';
import { 
    Coffee, 
    ClipboardList, 
    History, 
    Receipt, 
    LogOut 
} from 'lucide-vue-next';

const route = useRoute();
const router = useRouter();

const navItems = [
    { name: 'Menu', path: '/', icon: Coffee },
    { name: 'Order List', path: '/orders', icon: ClipboardList },
    { name: 'History', path: '/history', icon: History },
    { name: 'Bills', path: '/bills', icon: Receipt }
];

const handleLogout = () => {
    // Add logout logic or router navigation here
    router.push('/login');
};
</script>