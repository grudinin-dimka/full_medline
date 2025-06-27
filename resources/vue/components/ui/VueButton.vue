<template>
   <button
      class="button-default"
      :class="{ [look]: look, wide: wide }"
      :disabled="disabled"
   >
      <span class="loader" v-if="disabled" :class="{ [look]: look }"></span>
      <slot v-else></slot>
   </button>
</template>

<script>
export default {
   props: {
      wide: {
         type: Boolean,
         default: false,
      },
      look: {
         type: String,
         default: "",
      },
      disabled: {
         type: Boolean,
         default: false,
      },
   },
};
</script>

<style scoped>
/* Кнопка */
.button-default {
   cursor: pointer;
   display: flex;
   justify-content: center;
   align-items: center;
   gap: var(--button-gap);

   padding: var(--button-padding);
   max-height: 54px;
   min-height: 54px;
   min-width: 160px;
   border: var(--button-border);
   border-radius: var(--button-border-radius);

   font-size: var(--button-font-size);
   color: var(--button-font-color);
   background-color: var(--button-background-color);

   transition: all 0.2s;
}

.button-default:hover {
   color: var(--button-font-color-hover);
   background-color: var(--button-background-color-hover);
}

/* secondary */
.button-default.secondary {
   color: var(--button-secondary-font-color);
   background-color: var(--button-secondary-background-color);
}

.button-default.secondary:hover {
   color: var(--button-secondary-font-color-hover);
   background-color: var(--button-secondary-background-color-hover);
}

/* inverse */
.button-default.inverse {
   border: var(--button-inverse-border);

   color: var(--button-inverse-font-color);
   background-color: var(--button-inverse-background-color);
}

.button-default.inverse:hover {
   border: var(--button-inverse-border-hover);

   color: var(--button-inverse-font-color-hover);
   background-color: var(--button-inverse-background-color-hover);
}

/* edit */
.button-default.edit {
   color: var(--button-edit-font-color);
   background-color: var(--button-edit-background-color);
}

.button-default.edit:hover {
   color: var(--button-edit-font-color-hover);
   background-color: var(--button-edit-background-color-hover);
}

/* edit-secondary */
.button-default.edit-secondary {
   color: var(--button-edit-secondary-font-color);
   background-color: var(--button-edit-secondary-background-color);
}

.button-default.edit-secondary:hover {
   color: var(--button-edit-secondary-font-color-hover);
   background-color: var(--button-edit-secondary-background-color-hover);
}

/* delete */
.button-default.delete {
   color: var(--button-delete-font-color);
   background-color: var(--button-delete-background-color);
}

.button-default.delete:hover {
   color: var(--button-delete-font-color-hover);
   background-color: var(--button-delete-background-color-hover);
}

/* delete-secondary */
.button-default.delete-secondary {
   color: var(--button-delete-secondary-font-color);
   background-color: var(--button-delete-secondary-background-color);
}

.button-default.delete-secondary:hover {
   color: var(--button-delete-secondary-font-color-hover);
   background-color: var(--button-delete-secondary-background-color-hover);
}

.wide {
   width: 100%;
}

/* Загрузчик */
.loader {
   width: 26px;
   height: 26px;
   border: 3px solid #ffffff;
   border-bottom-color: transparent;
   border-radius: 50%;
   display: inline-block;
   box-sizing: border-box;
   animation: rotation 1s linear infinite;
}

.loader.inverse {
   width: 26px;
   height: 26px;
   border: 3px solid black;
   border-bottom-color: transparent;
   border-radius: 50%;
   display: inline-block;
   box-sizing: border-box;
   animation: rotation 1s linear infinite;
}

@keyframes rotation {
   0% {
      transform: rotate(0deg);
   }
   100% {
      transform: rotate(360deg);
   }
}
</style>
