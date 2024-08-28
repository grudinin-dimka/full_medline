<template>
  <div class="block-title">
    <h3>
      <slot name="title"></slot>
    </h3>    
    <div class="block-title-buttons">
      <slot name="buttons"></slot>
    </div>
  </div>
</template>

<script>
export default {
  
}
</script>

<style scoped>
  .block-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  h3 {
    font-size: 24px;
    font-weight: 500;

    margin: 0px;
    padding: 0px;
  }
</style>