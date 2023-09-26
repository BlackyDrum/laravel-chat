<script setup>
import {Head, Link, router, usePage} from '@inertiajs/vue3';
import {onBeforeMount, onBeforeUnmount, onMounted, onUpdated, ref} from "vue";

import Layout from "@/Layouts/Layout.vue";

import { useToast } from 'primevue/usetoast';

import ScrollPanel from 'primevue/scrollpanel';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';
import Toast from 'primevue/toast';
import Card from 'primevue/card';


defineProps({
    messages : {
        type: Array
    }
})

// VUE DIRECTIVES
onBeforeMount(() => {
    Echo.private('chat')
        .listen('MessageSent', (e) => {
            router.reload({
                only: ['messages'],
            })
        });

    window.toast = useToast();
})

onMounted(() => {
    user.value = page.props.auth.user;
    setTimeout(() => {
        if (user.value)
            scrollToBottom();
    },10)
})

onBeforeUnmount(() => {
    Echo.leave('chat');
})

onUpdated(() => {
    user.value = page.props.auth.user;
    inputText.value.$el.focus()
    scrollToBottom();
})

// VARIABLES
const page = usePage();

const message = ref(null);
const user = ref(null);
const isSending = ref(false);
const scrollPanel = ref();
const inputText = ref();

// METHODS
const sendMessage = () => {
    if (!message.value) return;

    isSending.value = true;
    window.axios.post('/message',{
        message: message.value
    })
        .then(response => {
            if (user.value.admin && response.data.message) {
                window.toast.add({ severity: 'success', summary: 'Success', detail: response.data.message, life: 5000 });
            }
        })
        .catch(error => {
            window.toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.message, life: 5000 });
        })
        .then(() => {
            message.value = null;
            isSending.value = false;
        })
}

const formatDate = date => {
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const seconds = date.getSeconds().toString().padStart(2, '0');

    return `${day}.${month}.${year} ${hours}:${minutes}:${seconds}`;
}

function scrollToBottom() {
    scrollPanel.value.scrollTop(Number.MAX_SAFE_INTEGER)
}
</script>

<template>
    <Head title="Home" />

    <Toast />

    <Layout>
        <div class="grid grid-cols-[20%,80%] max-sm:gap-4 max-2xl:grid-cols-[35%,65%] max-sm:grid-cols-1 min-h-screen">
            <!-- Left Sidebar -->
            <div :class="{'grid-rows-[20%,75%]' : user}" class="grid grid-rows-[25%,75%] max-sm:grid-rows-1 bg-gray-800/50 sm:border-r-4 border-gray-400">
                <div class="sm:border-b-4 border-gray-400">
                    <div class="flex flex-wrap h-full" v-if="!user">
                        <a class="m-auto" href="/auth/google/verify">
                            <Button label="Sign in with Google" severity="secondary" icon="pi pi-google" outlined style="background-color: white"/>
                        </a>
                        <a class="m-auto" href="/auth/github/verify">
                            <Button label="Sign in with Github" severity="secondary" icon="pi pi-github" outlined style="background-color: white"/>
                        </a>
                        <Link class="flex w-full" href="/login">
                            <Button class="m-auto" label="Alt Login" severity="secondary" icon="pi pi-user" outlined style="background-color: white"/>
                        </Link>
                    </div>
                    <div class="flex h-full w-full" v-else>
                        <div class="grid grid-rows-2 w-full">
                            <div class="grid grid-cols-2 self-center mx-auto">
                                <Avatar v-if="user.avatar" :image="user.avatar" class="mr-2" size="xlarge" />
                                <Avatar v-else :label="user.name[0]" class="mr-2" size="xlarge" />
                                <div class="grid grid-rows-2">
                                    <div class="font-bold">{{user.name}}</div>
                                    <div>
                                        <Link class="underline" href="/profile">
                                            <button class="border rounded-lg p-1 transition-all bg-gray-700 hover:bg-gray-500">Edit Profile</button>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                            <div class="flex w-full self-center">
                                <Button class="w-3/4 mx-auto" label="Logout" severity="secondary" icon="pi pi-arrow-left" outlined style="background-color: white"
                                        @click="router.post('/logout')"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat -->
            <div class="m-auto text-center max-sm:hidden" v-if="!user">
                Please Login to access the Chat
            </div>
            <div class="my-auto" v-else>
                <ScrollPanel ref="scrollPanel" class="h-[40rem] w-[95%] self-center mx-auto bg-gray-800 rounded-t-lg">
                    <div class="m-3 px-6 py-2 bg-gray-400 rounded-lg w-fit max-w-4xl" :class="{'bg-gray-200/80 sm:ml-auto': message.user_id === user.id}" v-for="message in messages">
                        <div class="flex-1">
                            <div class="my-auto font-bold text-orange-700" :class="{'ml-auto': message.user_id === user.id}">
                                <div class="flex">
                                    <div class="ml-auto mr-2">
                                        <Avatar v-if="message.user_avatar" :image="message.user_avatar" size="large" shape="circle" />
                                        <Avatar v-else :label="message.name[0]" size="large" shape="circle" />
                                    </div>
                                    <div class="self-center">
                                        {{user.admin ? `${message.name} id:${message.user_id}` : message.name}}
                                        <span class="text-gray-600/50">{{formatDate(new Date(message.created_at))}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="font-semibold text-black break-all my-auto" :class="{'ml-auto': message.user_id === user.id}">
                                {{message.message}}
                            </div>
                        </div>
                    </div>
                </ScrollPanel>
                <div class="flex w-[95%] h-32 mx-auto bg-gray-800 rounded-b-lg">
                    <InputText ref="inputText" class="w-[95%] h-14 m-auto bg-gray-800 rounded-lg" placeholder="Type a Message"
                               v-model="message" @keydown.enter="sendMessage" autofocus
                               :disabled="isSending"/>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>
</style>
