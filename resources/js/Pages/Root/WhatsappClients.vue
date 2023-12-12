<script setup>
import { router, useForm } from '@inertiajs/vue3';

  const props = defineProps(['clients'])
  const defaultClient = {
    access_token: null,
    delivery_instance_id: null,
    comunity_instance_id: null,
    enabled: null,
  }

  const form = useForm({...defaultClient})

  function updateClient(client){
    form.defaults({...client})
    form.reset()
    form.put(`/admin/whatsapp_clients/${client.id}`)
  }
</script>  

<template>
  <div class="row">
    <div class="col-12 col-md-4" v-for="client in props.clients">
      <q-card>
        <q-card-section>
          {{ client.name }}
        </q-card-section>
        <q-card-section class="q-gutter-y-md">
          <q-input
            outlined
            stack-label
            label="URL Base"
            hide-bottom
            v-model="client.base_url"/>
          <q-input
            outlined
            stack-label
            label="Access Token"
            hide-bottom
            v-model="client.access_token"/>
          <q-input
            outlined
            stack-label
            label="ID Instancia de Encomiendas"
            hide-bottom
            v-model="client.delivery_instance_id">
            <template v-slot:append="props">
              <q-btn
                @click="router.visit(`/admin/whatsapp_clients/${client.id}/scan?instance_type=delivery_instance_id`)"
                flat
                round
                icon="sym_o_qr_code"/>
            </template>
          </q-input>
          <q-input
            outlined
            stack-label
            label="ID Instancia de Comunidad"
            hide-bottom
            v-model="client.comunity_instance_id">
          </q-input>
          <q-checkbox
            v-model="client.enabled"
            label="Habilitado"
            :true-value="1"
            :false-value="0"
          />
          <q-btn
            @click="updateClient(client)"
            color="dark"
            :loading="form.processing"
            style="width: 100%;">
            Actualizar
          </q-btn>
        </q-card-section>
      </q-card>
    </div>
  </div>
</template>