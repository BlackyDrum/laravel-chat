<script setup>
import {Head, Link, router, usePage} from '@inertiajs/vue3';
import {onMounted, ref} from "vue";

import Layout from "@/Layouts/Layout.vue";

import ScrollPanel from 'primevue/scrollpanel';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';

onMounted(() => {
    user.value = page.props.auth.user;
})


const page = usePage();

const message = ref(null);
const user = ref(null);

const sendMessage = () => {
    message.value = null;
}
</script>

<template>
    <Head title="Home" />

    <Layout>
        <div class="grid grid-cols-[15%,85%] min-h-screen">
            <!-- Left Sidebar -->
            <div class="grid grid-rows-[25%,75%] bg-gray-800 border-r border-gray-400">
                <div class="border-b border-gray-400">
                    <div class="flex flex-wrap h-full" v-if="!user">
                        <Button class="w-3/4 m-auto" label="Sign in with Google" severity="secondary" icon="pi pi-google" outlined style="background-color: white"/>
                        <Button class="w-3/4 m-auto" label="Sign in with Github" severity="secondary" icon="pi pi-github" outlined style="background-color: white"/>
                        <Link class="flex w-full" href="/login">
                            <Button class="w-3/4 m-auto" label="Alt Login" severity="secondary" icon="pi pi-user" outlined style="background-color: white"/>
                        </Link>
                    </div>
                    <div class="flex h-full w-full" v-else>
                        <Button class="w-3/4 mt-auto mx-auto mb-2" label="Logout" severity="secondary" icon="pi pi-arrow-left" outlined style="background-color: white"
                                @click="router.post('/logout')"/>
                    </div>
                </div>
            </div>

            <!-- Chat -->
            <div class="m-auto" v-if="!user">
                Please Login to access the Chat
            </div>
            <div class="my-auto" v-else>
                <ScrollPanel class="h-[48rem] w-[95%] self-center mx-auto bg-gray-800 rounded-t-lg">

                </ScrollPanel>
                <div class="flex w-[95%] h-32 mx-auto bg-gray-800 rounded-b-lg">
                    <InputText class="w-[95%] h-14 m-auto bg-gray-800 rounded-lg" placeholder="Type a Message"
                               v-model="message" @keydown.enter="sendMessage"/>
                </div>
            </div>
        </div>
    </Layout>
</template>

<style scoped>
</style>
