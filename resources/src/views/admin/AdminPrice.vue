<template>
  <info-bar>
    <template v-slot:title>Цены</template>
    <template v-slot:addreas>price</template>
  </info-bar>
</template>

<script>
import InfoBar from '../../components/ui/admin/InfoBar.vue';

export default {
  components: {
    InfoBar
  }
}
</script>

<style scoped>
  
</style>