<template>
   <nav :class="{ 'active': $store.state.mainNav.status }">
      <a v-for="Element in menu" :key="Element.id" class="element" :class="{ hiden: isActiv }"
         @click="$router.push(Element.href)">{{ Element.name }}</a>
   </nav>
</template>

<script>
export default {
   data() {
      return {
         isActiv: true,
         menu: [
            { id: "1", name: "О Нас", href: "about" },
            { id: "2", name: "Специалисты", href: "specialists" },
            { id: "3", name: "Цены", href: "prices" },
            { id: "4", name: "Направления", href: "directions" },
            { id: "5", name: "Диагностика", href: "diagnostics" },
            { id: "6", name: "Лицензии", href: "licenses" },
            { id: "7", name: "Видео", href: "videos" },
            { id: "8", name: "Контакты", href: "contacts" },
         ],
      };
   },
};
</script>

<style scoped>
nav {
   display: grid;
   grid-template-columns: repeat(8, 1fr);

   padding: 10px;
   margin: 0px 30px;
   gap: 5px;

   background-color: #00abbd;
   color: white;
   border-radius: 10px;

   transition: all 0.5s ease-out;
}

nav a {
   padding: 18px 0px;
   border-radius: 10px;

   text-align: center;
   font-size: 20px;

   transition: all 0.15s ease-in-out;
}

nav a:hover {
   background-color: rgba(255, 255, 255, 0.15);
}

.element {
   text-transform: uppercase;

   cursor: pointer;
}

@media screen and (max-width: 1550px) {
   nav {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
   }

   nav a {
      width: auto;
   }
}

@media screen and (max-width: 800px) {
   nav {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
   }

   ;

   nav a {
      width: auto;
   }
}

@media screen and (max-width: 500px) {
   nav {
      z-index: 5;
      position: fixed;
      top: -100vh;
      bottom: 100vh;
      left: 0px;
      right: 0px;

      border-radius: 0px;

      display: flex;
      flex-direction: column;
      margin: 0px;
   }

   nav.active {
      padding-top: 100px;
      top: 0px;
      left: 0px;
      right: 0px;
      bottom: 0;
   }
}
</style>