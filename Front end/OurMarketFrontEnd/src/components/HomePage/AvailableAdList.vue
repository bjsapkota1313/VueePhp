<template>
    <div class="container">
      <div class="row py-3 text-center" id="containerRowContainerAvailableAds">
        <AvailableAdItem v-for="ad in ads" :key="ad.id" :ad="ad"></AvailableAdItem>
      </div>
    </div>
  </template>
  
  <script>
  import AvailableAdItem from './AvailableAdItem.vue';
  export default {
    name: "AvailableAdList",
    components: {
      AvailableAdItem
    },
    props: {
      ads: {
        type: Array
      }
    }

  }
  </script>
  
  <style scoped></style>
  