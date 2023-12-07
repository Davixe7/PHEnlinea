<template>
  <div class="row justify-center">
    <div class="col-md-3">
      <q-card>
        <q-card-section>
          <div>{{ instance_id }}</div>
          <img v-if="base64" :src="base64" alt="">
          <q-spinner v-else-if="form.processing"></q-spinner>
        </q-card-section>
      </q-card>
    </div>
    <div class="col-md-7">
      <ul class="list-group m-t-25">
        <li class="list-group-item active bg-info text-uppercase">
          <i class="far fa-question-circle"></i>
          Para comenzar a usar la herramienta, debe conectar su número de teléfono.
        </li>
        <li class="list-group-item">Paso 1: Abre WhatsApp en tu teléfono</li>
        <li class="list-group-item">Paso 2: toca Menú o Configuración y selecciona WhatsApp Web</li>
        <li class="list-group-item">Paso 3: apunta tu teléfono a esta pantalla y captura el código de arriba</li>
        <li class="list-group-item text-danger">
          <video width="100%" height="320" autoplay="" muted="" loop="">
            <source src="https://asistbot.com/inc/public/whatsapp_profiles/assets/img/scan.mp4" type="video/mp4">
          </video>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import axios from 'axios'

const form  = useForm({retrying: true})
const props = defineProps(['instance_id', 'base64'])
onMounted(()=>{
  setInterval(() => {
    if( window.location.href != '/whatsapp/login' ) return
    form.get('/whatsapp/login')
  },31000)
  setInterval(()=>{
    axios.get('/whatsapp/status')
    .then(response => response.data.data ? location.href = '/whatsapp' : '')
  }, 5000)
})
</script>