<script setup>
import {Head, Link, router, useForm, usePage} from '@inertiajs/vue3';
import {computed, onBeforeMount, onBeforeUnmount, onMounted, onUpdated, ref} from "vue";

import Layout from "@/Layouts/Layout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

import { useToast } from 'primevue/usetoast';

import ScrollPanel from 'primevue/scrollpanel';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';
import Toast from 'primevue/toast';
import Dialog from 'primevue/dialog';
import Textarea from 'primevue/textarea';
import ProgressSpinner from 'primevue/progressspinner';
import Card from 'primevue/card';


defineProps({
    messages : {
        type: Array
    },
    rooms: {
        type: Array
    },
    userInRooms: {
        type: Array
    },
    errors: {

    }
})

// VUE DIRECTIVES
onBeforeMount(() => {
    Echo.private('chat')
        .listen('MessageSent', (e) => {
            router.reload()
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
const createRoomDialogVisible = ref(false);

const createRoomForm = useForm({
    name: null,
    count: 1,
    description: null,
})

// METHODS
const sendMessage = () => {
    if (!message.value || user.value.muted) return;

    isSending.value = true;
    window.axios.post('/message',{
        message: message.value,
        room_id: (new URLSearchParams(window.location.search)).get('id')
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

const deleteMessage = (id) => {
    if (!user.value.admin) return;

    window.axios.delete('/message',{
        data: {
            id: id,
        }
    })
        .then(response => {
            router.reload({
                only: ['messages']
            })
        })
        .catch(error => {
            window.toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.message, life: 5000 });
        })

}

const createRoom = () => {
    createRoomForm.post('/room', {
        onSuccess: () => {
            window.toast.add({ severity: 'success', summary: 'Success', detail: "New Chatroom created", life: 5000 });
            createRoomDialogVisible.value = false;
            createRoomForm.name = null;
            createRoomForm.count = 1;
            createRoomForm.description = null;
        },
        onFinish: () => {

        }
    })
}

const deleteRoom = id => {
    window.axios.delete('room',{
        data: {
            id: id,
        }
    })
        .then(response => {
            router.reload({
                only: ['rooms']
            })
            router.get('/');
        })
        .catch(error => {
            window.toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.message, life: 5000 });
        })
}

const joinRoom = (id) => {
    router.get('/',{
        id: id,
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
                <div v-if="user">
                    <div class="flex">
                        <Button icon="pi pi-inbox" class="mx-auto mt-6" severity="warning" label="Create new Room"
                                @click="createRoomDialogVisible = true"/>
                    </div>
                    <div class="text-center mt-4">
                        Current Rooms: {{rooms.length}}
                    </div>
                    <ScrollPanel  style="width: 100%; height: 33rem">
                        <div class="mt-4 w-3/4 mx-auto">
                            <Card class="mt-2" v-for="room in rooms" :key="room.id">
                                <template #title> {{room.name}} ({{userInRooms[room.id] || 0}}/{{room.count}}) </template>
                                <template #content>
                                    <Button class="w-full" @click="joinRoom(room.id)" icon="pi pi-arrow-right" label="Join Room"></Button>
                                    <Button class="w-full mt-1" @click="deleteRoom(room.id)" v-if="room.creator_id === user.id || user.admin" icon="pi pi-trash" severity="danger" label="Delete Room"></Button>
                                </template>
                            </Card>
                        </div>
                    </ScrollPanel>
                </div>
            </div>

            <!-- Chat -->
            <div class="m-auto text-center max-sm:hidden" v-if="!user">
                Please Login to access the Chat
            </div>
            <div class="my-auto" v-else>
                <ScrollPanel ref="scrollPanel" class="h-[40rem] w-[95%] self-center mx-auto bg-gray-800 rounded-t-lg">
                    <div class="relative group m-3 px-6 py-2 bg-gray-400 rounded-lg w-fit max-w-4xl"
                         :class="{'bg-gray-200/80 sm:ml-auto': message.user_id === user.id, 'hover:bg-opacity-10 hover:cursor-pointer': user.admin}" v-for="message in messages"
                         @click="deleteMessage(message.id)">
                        <div v-if="user.admin" class="absolute invisible left-1/2 top-1/2 group-hover:visible">
                            <span class="pi pi-trash text-red-600" style="font-size: x-large"></span>
                        </div>
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
                    <InputText ref="inputText" class="w-[95%] h-14 m-auto bg-gray-800 rounded-lg" :placeholder="user.muted ? 'You have been muted' : 'Type a Message'"
                               v-model="message" @keydown.enter="sendMessage" autofocus
                               :disabled="isSending || user.muted"/>
                </div>
            </div>
        </div>
    </Layout>

    <Dialog v-model:visible="createRoomDialogVisible" :closable="false" :draggable="false" modal header="Create new Room" :style="{ width: '50vw' }">
        <div class="mt-5">
            <div class="grid grid-cols-2 max-lg:grid-cols-1 gap-4">
                <div>
                    <span class="p-float-label">
                        <InputText v-model="createRoomForm.name" :disabled="createRoomForm.processing" class="w-full" />
                        <label>Name of the Room</label>
                    </span>
                    <div class="text-red-600 font-semibold" v-if="errors.name">
                        {{errors.name}}
                    </div>
                </div>
                <div>
                    <span class="p-float-label">
                        <InputNumber v-model="createRoomForm.count" :disabled="createRoomForm.processing" class="w-full" />
                        <label>Max. Participants</label>
                    </span>
                    <div class="text-red-600 font-semibold" v-if="errors.count">
                        {{errors.count}}
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <div class="grid grid-cols-2 my-4">
                    <div class="justify-center">
                        <ProgressSpinner style="width: 50px; height: 50px" strokeWidth="8" fill="var(--surface-ground)"
                                         animationDuration=".5s" aria-label="Custom ProgressSpinner" v-if="createRoomForm.processing"/>
                    </div>
                    <div class="flex justify-end" style="height: 3rem">
                        <primary-button class="mr-5 disabled:cursor-not-allowed"
                                        :disabled="createRoomForm.processing"
                                        @click="createRoom">
                            Submit
                        </primary-button>
                        <secondary-button @click="createRoomDialogVisible = false">Cancel</secondary-button>
                    </div>
                </div>
            </div>
        </div>
    </Dialog>
</template>

<style scoped>
</style>
