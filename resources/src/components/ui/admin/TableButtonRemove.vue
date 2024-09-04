<template>
   <button>
     <slot></slot>
   </button>
 </template>
 
 <script>
 export default {
   
 }
 </script>
 
 <style scoped>
   button {
     cursor: pointer;
 
     padding: 5px 10px;
     border: 0px solid black;
     border-radius: 5px;
 
     font-size: 16px;
     color: white;
     
     background-color: var(--button-remove-color);
   }
 
   button:hover {
     background-color: var(--button-remove-color-hover);
   }
 </style>