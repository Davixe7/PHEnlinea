<script setup>
import { useForm } from '@inertiajs/vue3';
const props = defineProps(['admin'])
  const form = useForm({
    extension_name: '',
    name: '',
    phone: '',
    description: '',
    media: [],
    admin_id: props.admin.id
  })

  function store(){
    form.post('/pqrs')
  }
</script>
<template>
  <div class="row justify-center">
    <div class="col-12 col-md-4">
      <q-card>
    <q-card-section>
      Registrar PQRS
    </q-card-section>
    <q-card-section class="q-gutter-y-md">
      <q-input
        outlined
        stack-label
        label="Nombres y apellidos"
        hide-bottom
        v-model="form.name"
        :error="Boolean(form.errors.name)"
        :error-message="form.errors.name ? form.errors.name : ''"/>
      
      <q-input
        outlined
        stack-label
        label="Número de apartamento"
        hide-bottom
        v-model="form.extension_name"
        :error="Boolean(form.errors.extension_name)"
        :error-message="form.errors.extension_name ? form.errors.extension_name : ''"/>

      <q-input
        outlined
        stack-label
        label="Movil (A este móvil se envía el seguimiento del PQRS)"
        hide-bottom
        v-model="form.phone"
        :error="Boolean(form.errors.phone)"
        :error-message="form.errors.phone ? form.errors.phone : ''"/>

      <q-input
        outlined
        stack-label
        label="Descripción"
        hide-bottom
        type="textarea"
        v-model="form.description"
        :error="Boolean(form.errors.description)"
        :error-message="form.errors.description ? form.errors.description : ''"/>

      <div class="row q-col-gutter-md">
        <div class="col" v-for="n in 3">
          <q-file
            outlined
            stack-label
            :label="`Adjunto ${n}`"
            hide-bottom
            v-model="form.media[n-1]"
            :error="Boolean(form.errors.media)"
            :error-message="form.errors.media ? form.errors.media : ''"/>
        </div>
      </div>
      <div class="flex justify-end">
        <q-btn
          @click="store"
          color="dark"
          :loading="form.processing"
          class="q-ml-auto">
          Enviar PQRS
        </q-btn>
      </div>
    </q-card-section>
    </q-card>
    </div>
  </div>
</template>