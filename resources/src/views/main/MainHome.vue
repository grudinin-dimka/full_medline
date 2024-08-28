<template>
  <section>
    <Slider />
    <div class="section-info">
      <p class="section-info-title">Добро Пожаловать в "Медлайн"</p>
      <p class="section-info-sub-title">Индивидуальный подход и лучшие врачи</p>
    </div>
  </section>
</template>

<script>
import Slider from "../../components/includes/Slider.vue";

export default {
  components: {
    Slider
  }
}
</script>

<style scoped>
p {
  margin: 0;
  padding: 0;
  font-size: 30px;
  font-weight: 700;
}

.section-info {

  text-align: center;
  text-transform: uppercase;
}

.section-info-title {
  color: #00abbd;
  font-size: 50px;
}

.section-info-sub-title {
  font-size: 30px;
}

@media screen and (max-width: 900px) {
  .section-info-title {
    font-size: 40px;
  }

  .section-info-sub-title {
    font-size: 25px;
  }
}

@media screen and (max-width: 500px) {
  .section-info-title {
    font-size: 30px;
  }

  .section-info-sub-title {
    font-size: 20px;
  }
  
  section {
    margin-top: 100px;
  }

}
</style>
