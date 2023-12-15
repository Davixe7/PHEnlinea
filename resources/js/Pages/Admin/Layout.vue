<template>
  <q-layout view="hHh LpR fFf">

    <q-header elevated class="bg-primary text-white">
      <q-toolbar class="q-px-md">
        <q-btn flat round @click="leftDrawerOpen = !leftDrawerOpen" icon="sym_o_menu"></q-btn>
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

    <q-drawer v-model="leftDrawerOpen" side="left" behavior="desktop" bordered>
      <q-list>
        <q-item clickable v-ripple class="q-py-md">
          <q-item-section avatar>
            <q-avatar rounded>
              <img src="https://cdn.quasar.dev/img/mountains.jpg">
            </q-avatar>
          </q-item-section>
          <q-item-section>
            <div style="font-size: 20px;">
              {{ user.name }}
            </div>
          </q-item-section>
        </q-item>

        <template v-for="link in links">
          <q-item-label
            v-if="!link.route"
            header>
            {{link.label}}
          </q-item-label>
          <q-item
            v-else
            :active="route().current(link.route)"
            active-class="bg-blue-1 text-primary"
            @click="visit(link.route)"
            clickable
            v-ripple>
            <q-item-section>
              {{ link.label }}
            </q-item-section>
            <q-item-section side v-if="link.new">
              <q-badge color="red" label="nuevo"/>
            </q-item-section>
          </q-item>
        </template>
      </q-list>
    </q-drawer>

  </q-layout>
</template>

<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

const links = ref([
{
  label: 'Navegación',
},
{
  label: 'Citofonía y Censo',
  route: 'extensions.index',
},
{
  label: 'Visitas',
  route: 'visits.index',
},
{
  label: 'Novedades',
  route: 'novelties.index',
},
{
  label: 'Mis facturas',
  route: 'invoices.index',
},
{
  label: 'Facturación de Residentes',
  route: 'resident_invoice_batches.index',
  new: true
},
{
  label: 'Comunidad QR',
},
{
  label: 'PQRS',
  route: 'petitions.index',
},
{
  label: 'Mensajería masíva',
  route: 'whatsapp.index',
},
{
  label: 'Comunidad QR',
  route: 'whatsapp.comunity'
},
])

const leftDrawerOpen = ref(true)
const page = usePage()
const user = computed(() => page.props.user)

function visit(routeName){
  router.visit( route(routeName) )
}

onMounted(()=>{
  console.log( route().current() )
})
</script>