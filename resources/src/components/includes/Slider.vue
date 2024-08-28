<template>
	<carousel
		:items-to-show="carouselSettings.itemsToShow"
		:wrap-around="true"
		:transition="750"
		:autoplay="5000"
		:mouseDrag="false"
		:touchDrag="false"
		:pauseAutoplayOnHover="true"
	>
		<slide v-for="slide in slides" :key="slide">
			<a :href="slide.link">
				<img
					:src="getImagePath(slide.path)"
					:alt="slide.name"
					width="300px"
				/>
			</a>
		</slide>

		<template #addons>
			<navigation />
			<pagination />
		</template>
	</carousel>
</template>

<script>
// If you are using PurgeCSS, make sure to whitelist the carousel CSS classes
import "vue3-carousel/dist/carousel.css";
import { Carousel, Slide, Pagination, Navigation } from "vue3-carousel";

import axios from "axios";

export default {
	name: "Slider",
	components: {
		axios,
		Carousel,
		Slide,
		Pagination,
		Navigation,
	},
	data() {
		return {
			slides: [],
			carouselSettings: {
				itemsToShow: 5,
			},
		};
	},
	mounted() {
		// Получение массива слайдов с сервера
		axios({
			method: "post",
			url: `${this.$store.state.axios.urlApi}` + `get-slides`,
		})
			.then((response) => {
				this.slides = response.data;
				this.sortSlider();
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных о слайдере.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			});

		window.addEventListener("resize", this.changeCarousel);
		window.addEventListener("load", this.changeCarousel);
		window.addEventListener("reload", this.changeCarousel);
	},
	unmounted() {
		window.removeEventListener("resize", this.changeCarousel);
		window.removeEventListener("load", this.changeCarousel);
		window.removeEventListener("reload", this.changeCarousel);
	},
	methods: {
		/* Сортировка списка слайдов по порядку */
		sortSlider() {
			this.slides.sort((a, b) => a.order - b.order);
		},
		/* Получение ссылки к динамичному изображению */
		getImagePath(path) {
			return new URL(`../../../../storage/app/public/${path}`, import.meta.url).href;
		},
      /* Изменение настроек слайдера */
		changeCarousel() {
			let windowWidth = window.document.documentElement.clientWidth;

			if ((windowWidth >= 1600) & (windowWidth <= 1920)) {
				this.carouselSettings.itemsToShow = 5;
			} else if ((windowWidth >= 1300) & (windowWidth <= 1600)) {
				this.carouselSettings.itemsToShow = 4;
			} else if ((windowWidth >= 960) & (windowWidth <= 1300)) {
				this.carouselSettings.itemsToShow = 3;
			} else if ((windowWidth >= 660) & (windowWidth <= 960)) {
				this.carouselSettings.itemsToShow = 2;
			} else if ((windowWidth >= 0) & (windowWidth <= 660)) {
				this.carouselSettings.itemsToShow = 1;
			}
		},
	},
};
</script>

<style>
.carousel__prev {
	color: rgba(0, 171, 189, 0.5);
}

.carousel__prev:hover {
	color: rgba(0, 171, 189, 1);
}

.carousel__next {
	color: rgba(0, 171, 189, 0.5);
}

.carousel__next:hover {
	color: rgba(0, 171, 189, 1);
}

.carousel__slide {
	transition: all 0.5s;
}

.carousel__slide:hover {
	transform: scale(1.05);
}

.carousel__pagination-button::after {
	background-color: rgba(0, 171, 189, 0.5);
}

.carousel__pagination-button:hover::after {
	background-color: rgba(0, 171, 189, 1);
}

.carousel__pagination-button.carousel__pagination-button--active::after {
	background-color: rgba(0, 171, 189, 1);
}
</style>
