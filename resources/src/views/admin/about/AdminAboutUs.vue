<template>
   <info-bar>
      <template v-slot:title>О нас</template>
      <template v-slot:addreas>about</template>
   </info-bar>

   <block-once>
      <block-title>
         <template #title>ИНФОРМАЦИОННЫЕ БЛОКИ</template>
         <template #buttons>
         </template>
      </block-title>     

      <div class="about">
         <div></div>
      </div>
   </block-once>
</template>

<script>
import InfoBar from '../../../components/ui/admin/InfoBar.vue';

import BlockOnce from '../../../components/ui/admin/blocks/BlockOnce.vue';
import BlockTwo from '../../../components/ui/admin/blocks/BlockTwo.vue';
import BlockTitle from '../../../components/ui/admin/blocks/BlockTitle.vue';

import LoaderChild from '../../../components/includes/LoaderChild.vue';

export default {
   components: {
      InfoBar,
      BlockOnce,
      BlockTwo,
      BlockTitle,
      LoaderChild,
   }
}
</script>

<style scoped>

</style>