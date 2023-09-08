<script setup>
import { Head } from '@inertiajs/vue3';
import Layout from "@/Layouts/Layout.vue";

</script>

<template>
    <Head title="Home" />

    <Layout>
        <div class="grid grid-cols-[15%,85%] min-h-screen">
            <div class="border-r border-gray-400">
                
            </div>
            <div class="m-auto">
                <div v-if="!$page.props.auth.user">
                    Please Login to access the Chat
                </div>
                <div v-else>

                </div>
            </div>
        </div>
    </Layout>
</template>
