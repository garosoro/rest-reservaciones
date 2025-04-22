<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen justify-between flex-col items-center bg-[#97f4b7] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
        <header class="not-has-[nav]:hidden mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                Dashboard
                </Link>
                <template v-else>
                    <Link :href="route('login')"
                        class="inline-block rounded-sm border border-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1b1b18] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]">
                    Log in
                    </Link>
                    <Link :href="route('register')"
                        class="inline-block rounded-sm border border-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1b1b18] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                    Register
                    </Link>
                </template>
            </nav>
        </header>
        <div
            class="duration-750 starting:opacity-0 flex w-full items-center justify-center opacity-100 transition-opacity lg:grow">
            <main
                class="flex justify-between w-full max-w-[335px] flex-col-reverse overflow-hidden rounded-lg lg:max-w-4xl lg:flex-row">
                <AppLogoIcon class="w-full md:w-300 h-auto text-white dark:text-black" />
                <div
                    class="flex flex-col items-center justify-center gap-4 rounded-lg bg-[#97f4b7] p-6 text-[#1b1b18] dark:bg-[#0a0a0a]">
                    <h1
                        class="flex jusitfy-start items-center font-bold text-[33px] md:text-[52px] leading-[33px] md:leading-[52px]">
                        <span class="text-[82px] md:text-[124px]">R</span>
                        <span>
                            est<br>eservaciones
                        </span>
                    </h1>
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                        class="inline-block rounded-sm border border-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1b1b18] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                    Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="route('login')"
                            class="inline-block rounded-sm border border-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1b1b18] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]">
                        Log in
                        </Link>
                        <Link :href="route('register')"
                            class="inline-block rounded-sm border border-[#1b1b18] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
                        Register
                        </Link>
                    </template>

                </div>
            </main>
        </div>
        <footer class="text-center">
            Build with Laravel 12 and Vue 3 by Gabriel Roberto Soto Rojas
        </footer>
    </div>
</template>
