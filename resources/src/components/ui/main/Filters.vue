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
   data() {
      return {
         filters: [
            {
               id: 1,
               name: 'Все',
            },
            {
               id: 2,
               name: 'Терапевт',
            },
            {
               id: 3,
               name: 'Невролог',
            },
            {
               id: 4,
               name: 'Уролог',
            },
            {
               id: 5,
               name: 'Хирург',
            },
            {
               id: 6,
               name: 'Нейрохирург',
            },
            {
               id: 7,
               name: 'Ортопед',
            },
         ]
      }
   },
}
</script>

<style scoped>
.filters {
   display: flex;
   justify-content: center;
   align-items: center;
}

.filters-body {
   margin: 0px 30px;
   width: 900px;
   display: inline-flex;
   flex-wrap: wrap;
   gap: 10px;
}

.filters-body-item {
   cursor: pointer;
   padding: 5px;
   border: 2px solid var(--input-border-color-inactive);
   border-radius: 10px;
   transition: all 0.15s;
}

.filters-body-item:hover {
	border: 2px solid var(--input-border-color-active);
	background-color: #f2feff;
}
</style>