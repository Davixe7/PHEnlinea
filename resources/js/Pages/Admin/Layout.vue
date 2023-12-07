<template>
  <q-layout view="hHh lpR fFf">

    <q-header elevated class="bg-primary text-white">
      <q-toolbar class="q-px-md">
        <q-btn flat round icon="sym_o_menu"></q-btn>
        <q-toolbar-title @click="router.visit('/home')">
          PHenlínea
        </q-toolbar-title>

        {{ user.name }}
        <q-btn
          flat
          round
          href="/logout"
          icon="sym_o_exit_to_app"
          class="q-ml-sm"
        />

      </q-toolbar>
    </q-header>

    <q-page-container>
      <q-page class="q-pa-md">
        <slot></slot>
      </q-page>
    </q-page-container>

  </q-layout>
</template>

<script setup>
import axios from 'axios'
import { usePage, router } from '@inertiajs/vue3';
import { computed } from 'vue';
const page = usePage()
const user = computed(() => page.props.user)
function logout(){
  axios.post('/logout').then(() => window.location.href = '')
}
</script>