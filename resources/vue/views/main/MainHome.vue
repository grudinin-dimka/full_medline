<template>
	<block :minHeight="400">
		<template v-if="loading.sections.slider">
			<template v-if="slides.length > 0">
				<Slider :slides="slides" />
			</template>
			<Empty :minHeight="400" v-else />
		</template>

		<loader-child
			:isLoading="loading.loader.slider"
         :minHeight="403"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>

		<div class="section-info">
			<p class="section-info-title">Добро Пожаловать в "Медлайн"</p>
			<p class="section-info-sub-title">Индивидуальный подход и лучшие врачи</p>
		</div>
	</block>
</template>

<script>
import Slider from "../../components/modules/Slider.vue";
import Block from "../../components/ui/main/blocks/Block.vue";
import Empty from "../../components/modules/Empty.vue";
import LoaderChild from "../../components/modules/LoaderChild.vue";

import axios from "axios";
import sorted from "../../services/sorted.js";

export default {
	components: {
		Slider,
		Block,
		Empty,
		LoaderChild,
		axios,
	},
	data() {
		return {
			loading: {
				loader: {
					slider: true,
				},
				sections: {
					slider: false,
				},
			},
			slides: [],
		};
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.slider = true;
		},
	},
	mounted() {
		// Получение массива слайдов с сервера
		axios({
			method: "post",
			url: `${this.$store.getters.urlApi}` + `get-slides-not-hide`,
		})
			.then((response) => {
				this.slides = response.data;
				sorted.sortByOrder("up", this.slides);
			})
			.catch((error) => {
				let debbugStory = {
					title: "Ошибка.",
					body: "Произошла ошибка при получении данных о слайдере.",
					type: "Error",
				};
				this.$store.commit("debuggerState", debbugStory);
			})
			.finally(() => {
				this.loading.loader.slider = false;
			});
	},
};
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
	font-size: 55px;
}

.section-info-sub-title {
	font-size: 35px;
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
}
</style>
