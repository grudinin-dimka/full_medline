<template>
   <div class="filters">
      <div class="filters-body"> 
         <div class="filters-body-item" v-for="filter in filters" :key="filter.id">
            <div>{{ filter.name }}</div>
         </div>
      </div>
   </div>
</template>

<script>
export default {
   props: {
      filters: {
         type: Array,
         required: true,
         default: [],
      }     
   },
}
</script>

<style scoped>
.filters {
   display: flex;
   justify-content: center;
   align-items: center;

   animation: show 0.5s ease-out;
}

.filters-body {
   margin: 0px 30px;
   width: 1000px;
   display: inline-flex;
   flex-wrap: wrap;
   gap: 10px;
}

.filters-body-item {
   cursor: pointer;

   padding: 5px;
   border: 2px solid var(--input-border-color-inactive);
   border-radius: 10px;

   font-size: 18px;

   transition: all 0.15s;
}

.filters-body-item:hover {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}

@keyframes show {
   0% {
      opacity: 0;
   }
   100% {
      opacity: 1;
   }
}
</style>