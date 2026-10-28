<template>
  <Teleport to="body">
    <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-[2px]">
      <div class="pixel-card bg-[#fffcf7] p-6 max-w-sm w-full shadow-2xl text-center border-4 border-[#4a3b32]">
        <!-- Header -->
        <h3 class="text-2xl font-bold text-[#3c2a1a] mb-2">
          {{ title }}
        </h3>
        
        <!-- Message -->
        <p class="text-lg text-[#5c4433] mb-6">
          {{ message }}
        </p>
        
        <!-- Retro Button -->
        <button 
          @click="$emit('close')" 
          class="pixel-button-primary px-6 py-2 rounded-lg text-lg cursor-pointer hover:scale-105 active:translate-y-0.5 transition-transform"
        >
          OK
        </button>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
defineProps({
  show: Boolean,
  title: {
    type: String,
    default: 'Notification'
  },
  message: String
});

defineEmits(['close']);
</script>