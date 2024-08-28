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

/* Кастомизированный скролбар. */
textarea::-webkit-scrollbar {
  width: 18px;
}

textarea::-webkit-scrollbar-track {
  background-color: #f5f5f5;
  border-radius: 10px;
}

textarea::-webkit-scrollbar-thumb {
  background-color: rgb(220, 220, 220);
  border: 6px solid #f5f5f5;
  border-radius: 10px;
}

textarea::-webkit-scrollbar-thumb:hover {
  background-color: rgb(210, 210, 210);
  cursor: all-scroll;
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