<template>
  <div v-if="show" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex justify-center items-center z-50 transition-opacity duration-300" @click.self="$emit('cancel')">
    <div class="bg-white p-8 rounded-xl shadow-2xl max-w-md w-full transform transition-all duration-300 scale-95" :class="{'scale-100': show}">
      <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ title }}</h3>
      <p class="text-gray-600 mb-8">{{ message }}</p>
      <div class="flex justify-end space-x-4">
        <button @click="$emit('cancel')" class="px-6 py-2 rounded-lg bg-gray-200 text-gray-800 hover:bg-gray-300 font-semibold transition-colors">
          {{ cancelButtonText }}
        </button>
        <button @click="$emit('confirm')" class="px-6 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 font-semibold transition-colors">
          {{ confirmButtonText }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    show: {
      type: Boolean,
      default: false,
    },
    title: {
      type: String,
      default: 'Підтвердьте дію',
    },
    message: {
      type: String,
      default: 'Ви впевнені, що хочете продовжити?',
    },
    confirmButtonText: {
      type: String,
      default: 'Підтвердити',
    },
    cancelButtonText: {
      type: String,
      default: 'Скасувати',
    },
  },
};
</script>
