<template>
<Layout>
<div style="margin: -15px;">
  <q-tabs
    align="justify"
    v-model="tab"
    inline-label
    class="text-primary q-mb-md"
  >
    <q-tab
      @click="router.visit(extension ? `/extensions/${extension.id}/edit` : '')"
      name="apartment"
      icon="sym_o_meeting_room"
      label="Apartamento" />
    <q-tab
      @click="router.visit(extension ? `/extensions/${extension.id}/residents` : '')"
      name="residents"
      icon="sym_o_supervisor_account"
      label="Residentes"
      :disable="!extension || !extension.id"/>
    <q-tab
      @click="router.visit(extension ? `/extensions/${extension.id}/vehicles` : '')"
      name="vehicles"  icon="sym_o_directions_car" label="Vehículos" :disable="!extension || !extension.id"/>

    <q-tab
      @click="router.visit(extension ? `/descargar-estado-cuenta/${extension.id}` : '')"
      name="balance"
      icon="sym_o_receipt_long"
      label="Facturación"
      :disable="!extension || !extension.id"/>
  </q-tabs>
  <slot></slot>
</div>
</Layout>
</template>

<script setup>
import Layout from './Layout.vue';
import {ref} from 'vue'
import { router } from '@inertiajs/vue3';
const props = defineProps(['extension'])
const tab = ref('')
</script>