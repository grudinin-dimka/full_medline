<template>
   <div class="info-bar">
      <div class="info-bar-title">
         <slot></slot>
      </div>
   </div>
</template>

<script>
import { RouterLink } from 'vue-router';
export default {
   components: {
      RouterLink
   }
}
</script>

<style scoped>
   .info-bar {
      display: flex;
      justify-content: center;
      margin: 20px 30px;
   }

   .info-bar-title {
      width: 1250px;
   }

   @media screen and (max-width: 500px) {
      .info-bar {
         margin-top: 100px;
      }
   }
</style>