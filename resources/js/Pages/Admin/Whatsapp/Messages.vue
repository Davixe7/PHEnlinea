<template>
  <div class="row">
    <div class="col-md-3">
      <div>
        Selecciona destinatarios
      </div>
      <q-list>
        <q-item tag="label" v-ripple>
          <q-item-section side top>
            <q-checkbox :modelValue="allSelected" @click="toggleReceivers"/>
          </q-item-section>

          <q-item-section>
            <q-item-label>{{ form.receivers.length }} Seleccionados</q-item-label>
          </q-item-section>
        </q-item>
        <q-item tag="label" v-ripple v-for="extension in extensions">
          <q-item-section side top>
            <q-checkbox :val="extension.id" v-model="form.receivers" />
          </q-item-section>

          <q-item-section>
            <q-item-label>{{ extension.name }}</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </div>

    <div class="col-md-6">
      <q-card flat class="bg-blue-2 q-mb-md text-blue-10">
        <q-card-section>
          <q-icon name="sym_o_info" class="q-mr-sm" style="font-size: 1.5rem;"></q-icon>
          PHPenlínea SAS no se hace responsable por el uso inapropiado de este servicio.
        </q-card-section>
      </q-card>
      <q-input outlined type="textarea" v-model="form.message"></q-input>

      <div class="flex q-mt-md items-center q-mb-lg">
        <q-file
          style="width: 50%;"
          outlined
          bottom-slots
          v-model="form.attachment"
          accept=".jpg, .png, .pdf">
          <template v-slot:prepend>
            <q-icon name="attach_file" />
          </template>
          <template v-slot:hint>
            Solo archivos JPG, PNG y PDF
          </template>
        </q-file>

        <q-btn
          flat
          :loading="form.processing"
          icon="sym_o_send"
          @click="send"
          class="q-ml-auto">
          Enviar
        </q-btn>
      </div>

      <q-table bordered flat :rows="rows" :columns="columns"></q-table>
    </div>

    <div class="col-md-3 column items-end q-pa-md">
      <div class="flex items-center q-mb-sm">
        <span class="lightbulb lightbulb--on"></span>
        <div class="q-ml-sm inline-block">
          Online
        </div>
      </div>
      <div style="font-family: monospace;">
        {{ whatsapp_instance_id }}
      </div>
      <div>
        <a href="/whatsapp/logout" class="text-red">Cerrar sesión</a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import {useQuasar} from 'quasar'
import { watch, ref, computed } from 'vue';

const props = defineProps(['extensions', 'mode', 'whatsapp_instance_id'])
const form  = useForm({
  receivers: [],
  message: '',
  attachment: null
})
const rows = ref([
  {id: 1, message: 'Lorem ipsum dolor sit amet...', created_at: '2023-12-10', numbers: '4147912134,4147942833'},
  {id: 2, message: 'Lorem ipsum dolor sit amet...', created_at: '2023-12-10', numbers: '4147912134,4147942833'},
  {id: 3, message: 'Lorem ipsum dolor sit amet...', created_at: '2023-12-10', numbers: '4147912134,4147942833'},
]);
const columns = ref([
  {name: 'id', field: 'id', label: 'ID', align: 'left'},
  {name: 'message', field: 'message', label: 'Resumen', align: 'left'},
  {name: 'numbers', field: row => row.mode != 'comunity' ? row.numbers.split(',').length : 1, label: 'Cant.', align: 'left'},
  {name: 'created_at', field: 'created_at', label: 'Enviado', align: 'right'},
])

const $q = useQuasar()
const allSelected = computed(() => {
  if (form.receivers.length == 0) return false
  if( props.extensions.length == form.receivers.length ){
    return true;
  }
  return null;
})


function toggleReceivers(){
  if( allSelected.value == false ){
    form.receivers = props.extensions.map(ext => ext.id)
    return
  }
  form.receivers = []
}

function send() {
  if (!window.confirm('Seguro que desea enviar el mensaje?')) return
  if ((props.mode != 'comunity') && !form.receivers.length) { alert('Debe incluir al menos un destinatario'); return; }
  if (!form.message) { alert('Debe incluir un mensaje'); return; }

  form.post('whatsapp/send', {
    onSuccess:()=>{
      form.reset()
      $q.notify('Mensaje enviado exitosamente')
    }
  })
}
</script>

<style lang="scss">
.lightbulb {
  display: inline-block;
  background-clip:content-box;
  border-width: 5px;
  border-style: solid;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  box-sizing: border-box;
}
.lightbulb--off {
  background-color: red;
  border-color: pink;
}
.lightbulb--on {
  background-color: green;
  border-color: greenyellow;
}
</style>