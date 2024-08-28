<template>
   <div class="not-found">
      <div class="not-found-container">
         <div class="not-found-container-status">
            404
         </div>
         <div class="not-found-container-title">
            Страница не найдена <code>;(</code>
         </div>
      </div>      
   </div>
</template>

<script>

</script>

<style scoped>
.not-found {
   display: flex;
   justify-content: center;
   align-items: center;
   
   min-height: 100dvh;
}

.not-found-container {
   text-align: center;
}

.not-found-container-status {
   font-size: 100px; 
   color: var(--primary-color);
   
}

.not-found-container-title {
   font-size: 20px; 
   color: var(--primary-color);   
}
</style>