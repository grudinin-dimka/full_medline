<template>
	<carousel
		:itemsToShow="calcItemsToShow"
		:clamp="true"
		:wrapAround="true"
		:transition="750"
		:autoplay="5000"
		:mouseDrag="true"
		:touchDrag="true"
		:pauseAutoplayOnHover="true"
	>
		<slide v-for="slide in slides" :key="slide">
			<a :href="slide.link">
				<div
					:style="{
						backgroundImage: `url(${slide.path})`,
						height: '375px',
						width: '300px',
						backgroundSize: 'contain',
						backgroundPosition: 'center center',
						backgroundRepeat: 'no-repeat',
					}"
				></div>
			</a>
		</slide>

		<template #addons>
			<navigation />
			<!-- <pagination /> -->
		</template>
	</carousel>
</template>

<script>
// If you are using PurgeCSS, make sure to whitelist the carousel CSS classes
import "vue3-carousel/dist/carousel.css";
import { Carousel, Slide, Pagination, Navigation } from "vue3-carousel";

import axios from "axios";

export default {
	components: {
		axios,
		Carousel,
		Slide,
		Pagination,
		Navigation,
	},
	props: {
		slides: {
			type: Array,
			required: true,
			default: [],
		},
	},
	data() {
		return {
			windowWidth: null,
			carouselSettings: {
				itemsToShow: 1,
			},
		};
	},
	mounted() {
		window.addEventListener("resize", this.setWidth);
		window.addEventListener("load", this.setWidth);
		window.addEventListener("reload", this.setWidth);
	},
	unmounted() {
		window.removeEventListener("resize", this.setWidth);
		window.removeEventListener("load", this.setWidth);
		window.removeEventListener("reload", this.setWidth);
	},
	computed: {
		/* Изменение настроек слайдера */
		calcItemsToShow() {
			this.windowWidth = window.document.documentElement.clientWidth;

			if (this.windowWidth >= 1600 || this.windowWidth == null) {
				return 5;
			} else if ((this.windowWidth >= 1300) & (this.windowWidth <= 1600)) {
				return 4;
			} else if ((this.windowWidth >= 960) & (this.windowWidth <= 1300)) {
				return 3;
			} else if ((this.windowWidth >= 660) & (this.windowWidth <= 960)) {
				return 2;
			} else if ((this.windowWidth >= 0) & (this.windowWidth <= 660)) {
				return 1;
			}
		},
	},
	methods: {
		setWidth() {
			this.windowWidth = window.document.documentElement.clientWidth;
		},
	},
};
</script>

<style>
:is(.carousel__prev, .carousel__next) {
	color: var(--primary-color);
}

:is(.carousel__prev, .carousel__next):hover {
	color: var(--primary-color-hover);
}

.carousel__slide {
	transition: all 0.5s;
}

.carousel__slide:hover {
	transform: scale(1.05);
}

.carousel__pagination-button::after {
	border-radius: 30px;
	margin: 0px 5px;

	height: 10px;
	width: 10px;
	background-color: rgba(0, 171, 189, 0.5);
}

.carousel__pagination-button:hover::after {
	background-color: rgba(0, 171, 189, 1);
}

.carousel__pagination-button.carousel__pagination-button--active::after {
	background-color: rgba(0, 171, 189, 1);
}

.carousel {
	width: 100%;

	animation: show 0.5s ease-out;
}
</style>
