<template>
  <div class="row">
    <div class="col-12 col-md-8 q-px-md q-mb-md">
      <q-card>
        <q-card-section>
          <div class="text-h6 text-weight-regular">
            Información del apartamento
          </div>
        </q-card-section>
        <q-card-section class="q-gutter-y-md">
          <q-input
        hide-bottom-space
        outlined
        stack-label
        :error="Boolean(form.errors.name)"
        :error-message="form.errors.name ? form.errors.name : ''"
        label="Nro. Apto"
        v-model="form.name"/>

    <q-input
        hide-bottom-space
        outlined
        stack-label
        :error="Boolean(form.errors.owner_name)"
        :error-message="form.errors.owner_name ? form.errors.owner_name : ''"
        label="Nombre Propietario"
        v-model="form.owner_name"/>

    <q-input
        hide-bottom-space
        outlined
        stack-label
        type="tel"
        :error="Boolean(form.errors.owner_phone)"
        :error-message="form.errors.owner_phone ? form.errors.owner_phone : ''"
        label="Tel. Propietario"
        v-model="form.owner_phone"/>

      <q-input
        hide-bottom-space
        outlined
        stack-label
        type="email"
        :error="Boolean(form.errors.email)"
        :error-message="form.errors.email ? form.errors.email : ''"
        label="Email"
        v-model="form.email"/>

    <q-input
        hide-bottom-space
        outlined
        stack-label
        type="number"
        :error="Boolean(form.errors.pets_count)"
        :error-message="form.errors.pets_count ? form.errors.pets_count : ''"
        label="Cant. Mascotas"
        v-model="form.pets_count"/>

      <q-select
        outlined
        emit-value
        hide-bottom-space
        map-options
        label="Parqueadero"
        :error="Boolean(form.errors.has_own_parking)"
        :error-message="form.errors.has_own_parking ? form.errors.has_own_parking : ''"
        v-model="form.has_own_parking"
        :options="[{label:'Propio', value: 1}, {label: 'Arrendado',value: 0}]">
      </q-select>

      <q-input
        hide-bottom-space
        outlined
        stack-label
        type="number"
        :error="Boolean(form.errors.deposit)"
        :error-message="form.errors.deposit ? form.errors.deposit : ''"
        label="Cuarto Útil"
        v-model="form.deposit"/>

      <q-input
        hide-bottom-space
        outlined
        stack-label
        type="number"
        :error="Boolean(form.errors.parking_number1)"
        :error-message="form.errors.parking_number1 ? form.errors.parking_number1 : ''"
        label="Nro. Parqueadero 1"
        v-model="form.parking_number1"/>

        <q-input
        hide-bottom-space
        outlined
        stack-label
        type="number"
        :error="Boolean(form.errors.parking_number2)"
        :error-message="form.errors.parking_number2 ? form.errors.parking_number2 : ''"
        label="Nro. Parqueadero 2"
        v-model="form.parking_number2"/>
        </q-card-section>
      </q-card>
    </div>

    <div class="col-12 col-md-4 q-px-md">
      <q-card>
        <q-card-section>
          <div class="text-h6 text-weight-regular">
            Citofonía y Notas
          </div>
        </q-card-section>
        <q-card-section class="q-gutter-y-md">
          <q-input
            v-for="n in 4"
            hide-bottom-space
            outlined
            stack-label
            :error="Boolean(form.errors[`phone_${n}`])"
            :error-message="form.errors[`phone_${n}`] ? form.errors[`phone_${n}`] : ''"
            :label="`Línea ${n}`"
            v-model="form[`phone_${n}`]">
            <template v-slot:prepend="props" v-if="n < 3">
              <q-avatar size="40px">
                <q-img src="/img/icons8-whatsapp.svg"></q-img>
              </q-avatar>
            </template>
          </q-input>
        </q-card-section>
        <q-card-actions class="flex justify-end">
          <q-btn
            @click="store"
            flat
            color="primary"
            :loading="form.processing">
              {{ form.id ? 'Actualizar' : 'Registrar' }} extension
            </q-btn>
        </q-card-actions>
      </q-card>
    </div>
  </div>
</template>

<script setup>
import Layout from './../ExtensionLayout.vue'
defineOptions({ layout: Layout })
import { useForm } from '@inertiajs/vue3';
import { useQuasar } from 'quasar'

const $q = useQuasar()
const props = defineProps(['extension', 'errors'])
const form = useForm(props.extension ? {...props.extension} : {
  name: '',
  phone_1: null,
  phone_2: null,
  phone_3: null,
  phone_4: null,
  email: '',
  owner_phone: '',
  owner_name: '',
  emergency_contact: null,
  emergency_contact_name: null,
  pets_count: 0,
  has_own_parking: false,
  parking_number1: null,
  parking_number2: null,
  deposit: '',
  password: '',
  observation: '',
  resident_id: null,
  resident_id_2: null,
  resident_id_3: null,
  resident_id_4: null,
})

function store(){
  if( !form.id ){
    form.post('/extensions', {onSuccess: ()=>{
        form.id = props.extension.id
        $q.notify('Registrado con éxito')
      }
    });
    return
  }
  form.put(`/extensions/${form.id}`, {
    onSuccess:()=>$q.notify('Actualizado con éxito')
  });
}
</script>