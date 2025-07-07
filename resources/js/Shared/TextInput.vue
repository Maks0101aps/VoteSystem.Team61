<template>
  <div :class="$attrs.class">
    <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
    <div class="relative">
      <div v-if="icon" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <component :is="iconComponent" class="h-5 w-5 text-gray-400" />
      </div>
      <input :id="id" ref="input" v-bind="{ ...$attrs, class: null }" class="form-input" :class="{ error: error, 'pl-10': icon }" :type="type" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" />
    </div>
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>

<script>
import { v4 as uuid } from 'uuid'
import {
  UserIcon,
  EnvelopeIcon,
  LockClosedIcon,
  BriefcaseIcon,
  AcademicCapIcon,
  BookOpenIcon,
  IdentificationIcon,
  MapIcon,
  BuildingOffice2Icon,
  MapPinIcon,
} from '@heroicons/vue/20/solid'

export default {
  components: {
    UserIcon,
    EnvelopeIcon,
    LockClosedIcon,
    BriefcaseIcon,
    AcademicCapIcon,
    BookOpenIcon,
    IdentificationIcon,
    MapIcon,
    BuildingOffice2Icon,
    MapPinIcon,
  },
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default: () => `text-input-${uuid()}`,
    },
    type: {
      type: String,
      default: 'text',
    },
    icon: String,
    error: String,
    label: String,
    modelValue: [String, Number],
  },
  emits: ['update:modelValue'],
  computed: {
    iconComponent() {
      const icons = {
        'user': 'UserIcon',
        'envelope': 'EnvelopeIcon',
        'lock-closed': 'LockClosedIcon',
        'briefcase': 'BriefcaseIcon',
        'academic-cap': 'AcademicCapIcon',
        'book-open': 'BookOpenIcon',
        'identification': 'IdentificationIcon',
        'map': 'MapIcon',
        'office-building': 'BuildingOffice2Icon',
        'location-marker': 'MapPinIcon',
      }
      return icons[this.icon]
    },
  },
  methods: {
    focus() {
      this.$refs.input.focus()
    },
    select() {
      this.$refs.input.select()
    },
    setSelectionRange(start, end) {
      this.$refs.input.setSelectionRange(start, end)
    },
  },
}
</script>
