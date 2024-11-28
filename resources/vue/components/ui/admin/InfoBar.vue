<template>
  <div class="block-info">
    <div class="info-bar">
      <span>Название:</span> <slot name="title"></slot>
    </div>
    <div class="info-bar">
      <span>Адрес:</span> https:/medline/<slot name="addreas"></slot>
    </div>
  </div>
</template>

<script>

</script>

<style scoped>
  .block-info {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;

    gap: 20px;
  }

  .info-bar {
    flex: 1 0 350px;

    padding: 15px 20px;
    border-radius: 10px;

    font-size: 20px;
    background-color: white;    

    box-shadow: 0px 4px 10px -10px rgba(0, 0, 0, 0.99);
  }

  .info-bar > span {
    color: rgb(176, 176, 176);    
  }

  @media screen and (max-width: 430px) {
    .block-info {
      gap: 10px;
    }

    .info-bar {
      flex-basis: 150px;
    }
  }
</style>