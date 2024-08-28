<template>
   <a @click.prevent v-for="link in links" :key="link.id" :href="link.link" @click="insertPage(link.link)">
      {{ link.name }}
   </a>
</template>

<script>
let linkPrev = null;
let linkActive = null;

export default {
   mounted() {
      // Добавление стиля активное ссылке, получаемой из локального хранилища
      linkActive = document.querySelector(`[href='${localStorage.getItem('linkActive')}']`);
      linkActive.classList.add('active');

      // Добавление стиля активной ссылке, получаемой из локального хранилища
      localStorage.setItem('linkPrevios', localStorage.getItem('linkActive'));
      linkPrev = linkActive;
   },
   props: {
      links: {
         type: Array,
         required: true
      },
   },
   methods: {
      insertPage(link) {
         // Удаление стиля прошлой активной ссылки
         linkPrev.classList.remove('active');

         // Добавление стиля активной ссылке
         linkActive = document.querySelector(`[href='${link}']`);
         linkActive.classList.add('active');

         localStorage.setItem('linkPrevios', localStorage.getItem('linkActive'));
         localStorage.setItem('linkActive', link);

         // Обновление предыдущей активной ссылки
         linkPrev = linkActive;

         this.$store.state.burger.status = false;
         this.$router.push(link);
      },
   },
}
</script>

<style scoped>
a {
   padding: 18px 0px;
   border-radius: 10px;
   outline: white;

   text-align: center;
   font-size: 22px;
   text-decoration: none;

   color: white;

   transition: all 0.15s ease-in-out;
}

a.active {
   background-color: rgba(255, 255, 255, 0.15);
}

aside a:hover {
   background-color: rgba(255, 255, 255, 0.15);
}
</style>