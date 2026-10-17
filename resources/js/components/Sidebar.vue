<template>
    <aside class="w-64 bg-white rounded-3xl p-6 flex flex-col justify-between shadow-sm">
        <div>
            <!-- Brand Logo -->
            <div class="flex items-center gap-3 mb-8">
                <div class="bg-blue-600 text-white p-2 rounded-xl flex items-center justify-center font-bold">
                    C
                </div>
                <span class="text-2xl font-bold text-gray-800">Cotafe</span>
            </div>

            <!-- Navigation Links -->
            <nav class="space-y-2">
    <router-link 
        to="/" 
        class="flex items-center gap-3 px-4 py-3 rounded-2xl font-semibold transition-colors"
        :class="$route.path === '/' ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50'"
    >
        <span>Menu</span>
    </router-link>

    <router-link 
        to="/orders" 
        class="flex items-center justify-between px-4 py-3 rounded-2xl transition-colors"
        :class="$route.path === '/orders' ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-gray-500 hover:bg-gray-50'"
    >
        <span>Order List</span>
    </router-link>
    
    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 rounded-2xl">History</a>
    <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:bg-gray-50 rounded-2xl">Bills</a>
</nav>
        </div>

        <div>
            <div class="bg-blue-50 p-4 rounded-2xl mb-6 text-center">
                <h4 class="font-bold text-gray-800 text-sm">Create New Recipe</h4>
                <p class="text-xs text-gray-400 mt-1 mb-3">Manage Your Culinary Creations</p>
                <button class="w-full bg-blue-600 text-white text-xs font-semibold py-2.5 rounded-xl hover:bg-blue-700 transition">Get Started</button>
            </div>
            <button class="flex items-center gap-2 text-red-500 font-semibold text-sm px-4">
                <span>Logout</span>
            </button>
        </div>
    </aside>
</template>