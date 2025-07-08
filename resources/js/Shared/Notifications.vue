<template>
  <div class="fixed top-4 right-4 z-50">
    <div v-for="notification in notifications" :key="notification.id" 
         class="mb-2 p-4 shadow-lg rounded-md text-white transition-all duration-500 transform"
         :class="[notification.type === 'voting' ? 'bg-blue-600' : 'bg-green-600']">
      <div class="flex justify-between items-start">
        <div>
          <div class="font-bold">
            {{ notification.type === 'voting' ? 'Нове голосування' : 'Нова петиція' }}
          </div>
          <div class="text-sm mt-1">{{ notification.title }}</div>
        </div>
        <button @click="removeNotification(notification.id)" class="text-white">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </div>
      <div class="mt-2 text-sm">
        <a :href="getLink(notification)" class="text-white underline">Перейти</a>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, onUnmounted } from 'vue';
import { v4 as uuidv4 } from 'uuid';

export default {
  setup() {
    const notifications = ref([]);
    
    const addNotification = (type, data) => {
      const notification = {
        id: uuidv4(),
        type,
        title: data.title,
        data
      };
      
      notifications.value.push(notification);
      
      // Автоматически удаляем уведомление через 5 секунд
      setTimeout(() => {
        removeNotification(notification.id);
      }, 5000);
    };
    
    const removeNotification = (id) => {
      const index = notifications.value.findIndex(n => n.id === id);
      if (index !== -1) {
        notifications.value.splice(index, 1);
      }
    };
    
    const getLink = (notification) => {
      if (notification.type === 'voting') {
        return route('voting.index');
      } else {
        return route('petitions');
      }
    };
    
    let echoChannel;
    
    onMounted(() => {
      // Подписываемся на канал всех пользователей
      echoChannel = window.Echo.channel('all-users');
      
      // Слушаем события создания новых голосований
      echoChannel.listen('.voting.created', (data) => {
        addNotification('voting', data);
      });
      
      // Слушаем события создания новых петиций
      echoChannel.listen('.petition.created', (data) => {
        addNotification('petition', data);
      });
    });
    
    onUnmounted(() => {
      // Отписываемся от канала при размонтировании компонента
      if (echoChannel) {
        window.Echo.leaveChannel('all-users');
      }
    });
    
    return {
      notifications,
      removeNotification,
      getLink
    };
  }
};
</script> 