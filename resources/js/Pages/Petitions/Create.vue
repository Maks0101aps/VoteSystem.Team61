<template>
  <div class="relative min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 py-8">
    <!-- Decorative patterns -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-green-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
    <div class="absolute top-20 -left-10 w-72 h-72 bg-emerald-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-40 w-72 h-72 bg-green-100 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
    
    <div class="container mx-auto px-4 relative z-10 max-w-2xl">
      <Head :title="title" />
      
      <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-green-800 mb-2">{{ title }}</h1>
        <div class="h-1 w-24 bg-green-500 mx-auto mb-4 rounded-full"></div>
      </div>

      <form @submit.prevent="submit" class="bg-white p-8 rounded-xl shadow-md backdrop-blur-sm bg-opacity-90">
        <div class="mb-6">
          <label class="block text-green-700 font-medium mb-2" for="title">Назва петиції</label>
          <TextInput
            id="title"
            v-model="form.title"
            :error="form.errors.title"
            class="w-full"
            autocomplete="off"
          />
        </div>
        
        <div class="mb-6">
          <label class="block text-green-700 font-medium mb-2" for="description">Опис петиції</label>
          <TextareaInput
            id="description"
            v-model="form.description"
            :error="form.errors.description"
            class="w-full"
            rows="6"
          />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-green-700 font-medium mb-2" for="signatures_required">Необхідна кількість підписів</label>
                <TextInput
                    id="signatures_required"
                    v-model="form.signatures_required"
                    :error="form.errors.signatures_required"
                    class="w-full"
                    type="number"
                    min="1"
                />
            </div>
            <div>
                <label class="block text-green-700 font-medium mb-2" for="duration">Тривалість петиції (годин)</label>
                <select
                    id="duration"
                    v-model="form.duration"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                >
                    <option value="24">24 години</option>
                    <option value="48">48 годин</option>
                    <option value="72">72 години</option>
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-green-700 font-medium mb-2">Для кого ця петиція?</label>
            <div class="flex items-center space-x-6">
                <label class="flex items-center">
                    <input type="radio" v-model="form.target_type" value="class" class="form-radio text-green-500 h-5 w-5">
                    <span class="ml-2 text-gray-700">Тільки для мого класу</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" v-model="form.target_type" value="school" class="form-radio text-green-500 h-5 w-5">
                    <span class="ml-2 text-gray-700">Для всієї школи</span>
                </label>
            </div>
            <InputError :message="form.errors.target_type" class="mt-2"/>
        </div>

        <div class="flex items-center justify-end">
          <Link href="/petitions" class="text-green-700 hover:text-green-900 mr-4">Скасувати</Link>
          <LoadingButton :loading="form.processing" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
            Створити
          </LoadingButton>
        </div>
      </form>
    </div>
    
    <!-- Хвилясті узори внизу -->
    <div class="absolute bottom-0 left-0 right-0 h-20 overflow-hidden">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="rgba(52, 211, 153, 0.2)" fill-opacity="1" d="M0,128L48,138.7C96,149,192,171,288,154.7C384,139,480,85,576,85.3C672,85,768,139,864,165.3C960,192,1056,192,1152,165.3C1248,139,1344,85,1392,58.7L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
      </svg>
    </div>
  </div>
</template>

<script>
import { Head, Link, useForm } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import TextareaInput from '@/Shared/TextareaInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import InputError from '@/Shared/InputError.vue'

export default {
  components: {
    Head,
    Link,
    TextInput,
    TextareaInput,
    LoadingButton,
    InputError,
  },
  layout: Layout,
  props: {
    title: String,
  },
  setup() {
    const form = useForm({
      title: '',
      description: '',
      target_type: 'class',
      signatures_required: 1,
      duration: 24,
    });

    function submit() {
      form.post('/petitions');
    }

    return { form, submit };
  },
}
</script>

<style>
@keyframes blob {
  0% {
    transform: translate(0px, 0px) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
  100% {
    transform: translate(0px, 0px) scale(1);
  }
}

.animate-blob {
  animation: blob 7s infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}
</style>