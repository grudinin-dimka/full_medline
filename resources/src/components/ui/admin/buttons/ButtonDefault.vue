<template>
  <button>
    <slot></slot>
  </button>
</template>

<script>
export default {
  
}
</script>

<style scoped>
  button {
    cursor: pointer;

    padding: 15px 30px;
    border: 0px solid black;
    border-radius: 10px;

    font-size: 20px;
    color: white;
    
    background-color: var(--button-default-color);
  }

  button:hover {
    background-color: var(--button-default-color-hover);
  }
</style>