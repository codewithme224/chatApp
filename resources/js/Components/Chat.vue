<template>
    <div class="flex justify-center mb-3 card">
      Chat With
      <span class="pl-2 font-bold">{{ chatPerson.name }}</span>
    </div>
    <div class="flex flex-col h-full overflow-hidden bg-white rounded-lg shadow-lg">
      <div ref="messageContainer" class="flex-1 p-4 overflow-y-auto bg-gray-50">
        <!-- Dynamic messages will be displayed here -->
        <div v-for="(message, index) in filteredMessages" :key="index" class="mb-4">
          <!-- Chat Person's Message -->
          <div v-if="message.sender_id === chatPerson.id" class="flex items-start">
            <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-gray-300 rounded-full">
              <i class="pi pi-user" style="color: #FFFFFF"></i>
            </div>
            <div class="ml-4">
              <div class="p-2 text-gray-800 bg-gray-300 rounded-lg">
                <p class="inline p-2 text-sm text-gray-800">{{ message.text }}</p>
              </div>
              <span class="text-xs text-gray-500">{{ message.time }}</span>
            </div>
          </div>
          <!-- Auth User's Message -->
          <div v-else class="flex items-start justify-end">
            <div class="mr-4">
              <div class="p-2 bg-blue-500 rounded-lg">
                <p class="inline p-2 text-sm text-gray-200">{{ message.text }}</p>
              </div>
              <span class="text-xs text-gray-500">{{ message.time }}</span>
            </div>
            <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-blue-500 rounded-full">
              <i class="pi pi-user" style="color: #FFFFFF"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="p-4 bg-gray-100 border-t border-gray-200">
        <div class="flex">
          <input v-model="newMessage"
                 type="text"
                 class="flex-1 p-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                 placeholder="Type a message..."
                 @keyup.enter="sendMessage"
                 @keydown="sendTypingEvent"
                 >
          <Button label="Send"
                  icon="pi pi-send"
                  class="p-2 ml-2"
                  @click="sendMessage" />
        </div>
        <small v-if="isFriendTyping" class="text-gray-700">
            {{ chatPerson.name }} is typing...
        </small>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted, inject, computed, watch, nextTick } from 'vue';
  import Button from 'primevue/button';
  import axios from 'axios';

  const dialogRef = inject('dialogRef');
  const chatPerson = ref({});
  const authUser = ref({});
  const messages = ref([]);
  const newMessage = ref('');
  const messageContainer = ref(null);
  const isFriendTyping = ref(false);
  const isFriendTypingTimer = ref(null);

  const sendMessage = async () => {
    try {
      if (newMessage.value.trim() !== '') {
        await axios.post('/send-message', {
          sender_id: authUser.value.id,
          receiver_id: chatPerson.value.id,
          text: newMessage.value
        });
        newMessage.value = '';
        fetchMessages(); // Refresh messages after sending
      }
    } catch (error) {
      console.error("Error sending message: ", error);
    }
  };


watch(messages, () => {

            nextTick(() => {
                messageContainer.value.scrollTop({
                top: messageContainer.value.scrollHeight,
                behavior:'smooth'
             })
            })

    }, { deep: true});





  const fetchData = async () => {
    try {
      const userParams = dialogRef.value.data.user;
      const authUserParams = dialogRef.value.data.authUser;

      chatPerson.value = userParams;
      authUser.value = authUserParams;

      await fetchMessages();
    } catch (error) {
      console.error("Error fetching data: ", error);
    }
  };

  const fetchMessages = async () => {
    try {
      const response = await axios.get(`/messages/${chatPerson.value.id}`);
      const filtered = response.data.filter(msg =>
        (msg.sender_id === authUser.value.id && msg.receiver_id === chatPerson.value.id) ||
        (msg.sender_id === chatPerson.value.id && msg.receiver_id === authUser.value.id)
      );
      messages.value = filtered;
    } catch (error) {
      console.error("Error fetching messages: ", error);
    }
  };

   //send typing event
   const sendTypingEvent =  () => {
       Echo.private(`chat.${chatPerson.value.id}`)
        .whisper('typing', {
            name: authUser.value.name
        });
    };

  // Fetch initial data on component mount
  onMounted(() => {
    fetchData();
    Echo.private(`chat.${authUser.value.id}`)
      .listen('MessageSent', (e) => {
        messages.value.push(e.message);
      })
      .listenForWhisper('typing', (e) => {
        isFriendTyping.value = e.name === chatPerson.value.name;

        if (isFriendTypingTimer.value) {
            clearTimeout(isFriendTypingTimer.value);
        }
        isFriendTypingTimer.value = setTimeout(() => {
                    isFriendTyping.value = false;
                }, 3000);
            });
        });


  // Computed property to filter and sort messages
  const filteredMessages = computed(() => {
    return messages.value.sort((a, b) => new Date(a.time) - new Date(b.time));
  });
  </script>

  <style scoped>
  /* Add any necessary styles here */
  </style>
