<template>
  <article>
    <slot></slot>
  </article>
</template>

<script>
export default {
  
}
</script>

<style scoped>
  article {
    display: flex;
    flex-direction: column;
    gap: 20px;

    background-color: white;
    height: auto;
    border-radius: 10px;

    padding: 20px;

    max-width: 100%;

    box-shadow: 0px 4px 10px -10px rgba(0, 0, 0, 0.99);

    animation: show 0.5s ease-out;
  }

  @keyframes show {
    from {
      transform: translateY(50px);
    }
    to {
      transform: translateY(0px);
    }
  }

</style>