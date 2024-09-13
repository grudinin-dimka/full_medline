<template>
   <a @click.prevent v-for="link in links" :key="link.id" :href="link.link" @click="insertPage(link.name)">
      {{ link.title }}
   </a>
</template>

<script>
let linkPrev = null;
let linkActive = null;

export default {
   mounted() {

   },
   props: {
      links: {
         type: Array,
         required: true
      },
   },
   methods: {
      insertPage(value) {
         this.$store.state.burger.status = false;

         this.$router.push({ name: `${value}` });
         window.scrollTo({
				top: 0,
				left: 0,
				behavior: 'instant'
			});
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

a:active {
   background-color: rgba(255, 255, 255, 0.15);
}

aside a:hover {
   background-color: rgba(255, 255, 255, 0.15);
}
</style>