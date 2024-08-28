<template>
  <info-bar>
    <template v-slot:title>О нас</template>
    <template v-slot:addreas>about</template>
  </info-bar>
</template>

<script>
import InfoBar from '../../components/ui/InfoBar.vue';

export default {
  components: {
    InfoBar
  }
}
</script>

<style scoped>
  
</style>