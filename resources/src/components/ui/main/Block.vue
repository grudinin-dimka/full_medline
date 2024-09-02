<template>
   <section>
      <slot></slot>
   </section>
</template>

<script>

</script>

<style scoped>
   section {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      gap: 20px;

      margin: 20px 30px;
   }
</style>