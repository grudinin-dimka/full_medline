<template>
  <main>
    <RouterView />
  </main>
</template>

<script>
import { RouterLink, RouterView } from 'vue-router';

export default {
}
</script>

<style scoped>
main {
  margin-top: 80px;
  margin-left: 350px;

  height: auto;
  min-height: 100dvh;

  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: stretch;

  padding: 30px;
  gap: 20px;

  background-color: #ECECEC;
}

@media screen and (max-width: 1000px) {
  main {
    margin-top: 80px;
    margin-left: 0px;
    padding: 20px;
    gap: 20px;
  }
}
</style>