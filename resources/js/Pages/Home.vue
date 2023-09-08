<script setup>
import { Head, Link } from '@inertiajs/vue3';
import {ref} from "vue";

import Layout from "@/Layouts/Layout.vue";

import ScrollPanel from 'primevue/scrollpanel';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';

const message = ref(null);

const sendMessage = () => {
    message.value = null;
}
</script>

<template>
    <Head title="Home" />

    <Layout>
        <div class="grid grid-cols-[15%,85%] min-h-screen">
            <!-- Left Sidebar -->
            <div class="border-r border-gray-400">

            </div>

            <!-- Chat -->
            <div class="m-auto" v-if="!$page.props.auth.user">
                Please Login to access the Chat
            </div>
            <div class="grid grid-rows-[88%,12%]" v-else>
                <ScrollPanel class="h-[48rem] w-[95%] self-center mx-auto bg-gray-800 rounded-lg">

                </ScrollPanel>
                <div class="flex">
                    <InputText class="w-[95%] h-16 mx-auto bg-gray-800 rounded-lg" v-model="message" @keydown.enter="sendMessage" placeholder="Type a Message"/>
                </div>
            </div>
        </div>
    </Layout>
</template>
