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
        
        <div class="mb-8">
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

        <div class="mb-8">
          <label class="block text-green-700 font-medium mb-2" for="duration">Тривалість петиції (годин)</label>
          <select
            id="duration"
            v-model="form.duration"
            :error="form.errors.duration"
            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
          >
            <option value="24">24 години</option>
            <option value="48">48 годин</option>
            <option value="72">72 години</option>
          </select>
        </div>

        <div class="mb-6">
          <label class="block text-green-700 font-medium mb-2">Цільова аудиторія</label>
          <div class="flex items-center space-x-4">
            <label class="flex items-center">
              <input type="radio" v-model="form.target_type" value="school" class="form-radio h-4 w-4 text-green-600 transition duration-150 ease-in-out">
              <span class="ml-2 text-gray-700">Для всієї школи</span>
            </label>
            <label class="flex items-center">
              <input type="radio" v-model="form.target_type" value="class" class="form-radio h-4 w-4 text-green-600 transition duration-150 ease-in-out">
              <span class="ml-2 text-gray-700">Для класу</span>
            </label>
          </div>
        </div>

        <div v-if="form.target_type === 'class'" class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
          <label class="block text-green-700 font-medium mb-2">Вкажіть клас</label>
          <div class="flex items-start space-x-4">
            <div class="flex-1">
              <select
                id="class_number"
                v-model="form.class_number"
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                :class="{ 'border-red-500': form.errors.class_number }"
              >
                <option :value="''" disabled>Номер</option>
                <option v-for="number in Object.keys(classData)" :key="number" :value="number">
                  {{ number }}
                </option>
              </select>
              <div v-if="form.errors.class_number" class="text-red-500 text-sm mt-1">{{ form.errors.class_number }}</div>
            </div>
            <div class="flex-1">
              <select
                id="class_letter"
                v-model="form.class_letter"
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                :class="{ 'border-red-500': form.errors.class_letter }"
                :disabled="!form.class_number"
              >
                <option :value="''" disabled>Буква</option>
                <option v-for="letter in availableLetters" :key="letter" :value="letter">
                  {{ letter }}
                </option>
              </select>
              <div v-if="form.errors.class_letter" class="text-red-500 text-sm mt-1">{{ form.errors.class_letter }}</div>
            </div>
          </div>
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

export default {
  components: {
    Head,
    Link,
    TextInput,
    TextareaInput,
    LoadingButton,
  },
  layout: Layout,
  props: {
    title: String,
    classData: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        title: '',
        description: '',
        signatures_required: 1,
        duration: 24,
        target_type: 'school',
        class_number: '',
        class_letter: '',
      }),
    }
  },
  computed: {
    availableLetters() {
      if (this.form.class_number && this.classData) {
        return this.classData[this.form.class_number] || []
      }
      return []
    },
  },
  watch: {
    'form.class_number'() {
      this.form.class_letter = ''
    },
    'form.target_type'(newValue) {
      if (newValue === 'school') {
        // Clear class selections and errors when switching to school-wide petition
        this.form.class_number = '';
        this.form.class_letter = '';
        // Clear any validation errors for class fields
        delete this.form.errors.class_number;
        delete this.form.errors.class_letter;
      }
    },
  },
  methods: {
    submit() {
      this.form.post('/petitions')
    },
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