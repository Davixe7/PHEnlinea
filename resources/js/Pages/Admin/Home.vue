<template>
  <div class="flex justify-center">
    <div
      class="menu-option"
      @click="router.visit(route.route)"
      v-for="route in routes">
      <img :src="`/img/iconos/${route.icon}`">
    </div>
  </div>

  <div class="row justify-center">
    <q-img src="/img/iconos/logo_comunidad.png" ratio="1" height="125px" fit="scale-down"></q-img>
  </div>

  <div class="flex justify-center">
    <div
      class="menu-option"
      @click="router.visit(route.route)"
      v-for="route in routes_2">
      <img :src="`/img/iconos/${route.icon}`">
    </div>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps(['routes', 'routes_2'])
</script>

<style lang="scss" scoped>
  .menu-option {
    display: flex;
    justify-content: center;
    align-items: center;
    width: fit-content;
  }
  img {
    width: 200px;
  }
</style>