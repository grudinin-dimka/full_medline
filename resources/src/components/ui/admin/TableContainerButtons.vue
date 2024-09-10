<template>
   <div class="table-container-buttons">
      <slot></slot>
   </div>
</template>

<script>

export default {

};
</script>

<style scoped>
.table-container-buttons {
   min-width: 150px;
   display: flex;
   gap: 10px;
   justify-content: justify;
   flex-wrap: wrap;
}
</style>
