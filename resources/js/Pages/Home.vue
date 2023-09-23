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
                only: ['messages']
            })
        });

    window.toast = useToast();
})

onMounted(() => {
    user.value = page.props.auth.user;
})

onBeforeUnmount(() => {
    Echo.leave('chat');
})

onUpdated(() => {
    user.value = page.props.auth.user;
})

// VARIABLES
const page = usePage();

const message = ref(null);
const user = ref(null);
const isSending = ref(false);


// METHODS
const sendMessage = () => {
    if (!message.value) return;

    isSending.value = true;
    window.axios.post('/message',{
        message: message.value
    })
        .then(response => {
            router.reload({
                only: ['messages']
            })
        })
        .catch(error => {
            window.toast.add({ severity: 'error', summary: 'Error', detail: error.response.data.message, life: 5000 });
        })
        .then(() => {
            message.value = null;
            isSending.value = false;
        })
}
</script>

<template>
    <Head title="Home" />

    <Toast />

    <Layout>
        <div class="grid grid-cols-[20%,80%] max-2xl:grid-cols-[35%,65%] max-md:grid-cols-[45%,55%] min-h-screen">
            <!-- Left Sidebar -->
            <div :class="{'grid-rows-[20%,75%]' : user}" class="grid grid-rows-[25%,75%] max-sm:grid-rows-[50%,50%] bg-gray-800/50 border-r-4 border-gray-400">
                <div class="border-b-4 border-gray-400">
                    <div class="flex flex-wrap h-full" v-if="!user">
                        <Button class="w-3/4 m-auto" label="Sign in with Google" severity="secondary" icon="pi pi-google" outlined style="background-color: white"/>
                        <Button class="w-3/4 m-auto" label="Sign in with Github" severity="secondary" icon="pi pi-github" outlined style="background-color: white"/>
                        <Link class="flex w-full" href="/login">
                            <Button class="w-3/4 m-auto" label="Alt Login" severity="secondary" icon="pi pi-user" outlined style="background-color: white"/>
                        </Link>
                    </div>
                    <div class="flex h-full w-full" v-else>
                        <div class="grid grid-rows-2 w-full">
                            <div class="grid grid-cols-2 self-center mx-auto">
                                <Avatar :label="user.name[0]" class="mr-2" size="xlarge" />
                                <div class="grid grid-rows-2">
                                    <div class="font-bold">{{user.name}}</div>
                                    <div>
                                        <Link class="underline" href="/profile">
                                            <button class="border rounded-lg p-0.5 bg-gray-700">Edit Profile</button>
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
            <div class="m-auto text-center" v-if="!user">
                Please Login to access the Chat
            </div>
            <div class="my-auto" v-else>
                <ScrollPanel class="h-[48rem] w-[95%] self-center mx-auto bg-gray-800 rounded-t-lg">

                </ScrollPanel>
                <div class="flex w-[95%] h-32 mx-auto bg-gray-800 rounded-b-lg">
                    <InputText class="w-[95%] h-14 m-auto bg-gray-800 rounded-lg" placeholder="Type a Message"
                               v-model="message" @keydown.enter="sendMessage"
                               :disabled="isSending"/>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>
</style>
