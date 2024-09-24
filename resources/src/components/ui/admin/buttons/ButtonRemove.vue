<template>
  <button class="remove">
    <slot></slot>
  </button>
</template>

<script>
export default {
  
}
</script>

<style>
  button.remove {
    cursor: pointer;

    padding: 15px 30px;
    border: 0px solid black;
    border-radius: 10px;

    font-size: 20px;
    color: white;
    
    background-color: var(--button-remove-color)
  }

  button.remove:hover {
    background-color: var(--button-remove-color-hover);
  }
</style>