<template>
  <q-layout view="hHh lpR fFf">

    <q-header elevated class="bg-primary text-white">
      <q-toolbar class="q-px-md">
        <q-btn flat round icon="sym_o_menu"></q-btn>
        <q-toolbar-title @click="router.visit('/home')">
          Root
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

      <q-tabs align="left">
        <q-route-tab href="/admin/users" label="Roots" />
        <q-route-tab href="/admin/admins" label="Administradores" />
        <q-route-tab href="/admin/porterias" label="Porterias" />
        <q-route-tab href="/admin/invoices/upload" label="Facturas" />
        <q-route-tab href="/admin/whatsapp_clients" label="Whatsapp" />
      </q-tabs>
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