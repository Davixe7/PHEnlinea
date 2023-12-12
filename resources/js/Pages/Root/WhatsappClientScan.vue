<script setup>
import { router, useForm } from '@inertiajs/vue3';

  const props = defineProps([
    'whatsapp_client',
    'instance_id',
    'base64',
    'instance_type',
    'labels'
  ])

  const form = useForm({
    delivery_instance_id: props.instance_id
  })

  function updateInstance(){
    let data = {}
    data[props.instance_type] = props.instance_id
    form.defaults({...data})
    form.reset()
    form.put(`/admin/whatsapp_clients/${props.whatsapp_client.id}`)
  }
</script>  

<template>
  <div class="row">
    <div class="col-12 col-md-4 q-mx-auto">
      <q-card>
        <q-card-section>
          <div class="text-h6 q-mb-md text-center">
            Configurar instancia de {{ labels[instance_type] }}
          </div>
          <q-separator></q-separator>
        </q-card-section>
        <q-card-section>
          <div class="q-my-mx text-center">
            {{ instance_id }}
          </div>
          <div class="flex">
            <q-img :src="base64" width="300px" class="q-mx-auto"></q-img>
          </div>
          <q-btn
            @click="updateInstance"
            color="dark"
            style="width: 100%;">
            Asignar Instancia
          </q-btn>
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>