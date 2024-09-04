<template>
	<div class="container-inputs">
      <slot></slot>
   </div>
</template>

<script setup></script>

<style scoped>
.container-inputs {
   display: flex;
   flex-direction: column;
   gap: 5px;
}
</style>
