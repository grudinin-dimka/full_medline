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

		<div class="news">
			<div class="news__head">
				<div class="news__head-title">Новости</div>
				<button class="news__head-button">Смотреть все</button>
			</div>
			<div class="news__body">
				<div class="news__item">
					<div class="news__item-date">15.04.2025</div>
					<img class="news__item-image" :src="`storage/default/image-none-default.png`"/>
					<div class="news__item-title">Прорыв в лечении Альцгеймера:</div>
					<div class="news__item-content">
						Ученые разработали новый препарат, который, кажется, замедляет прогрессирование
						болезни Альцгеймера на ранних стадиях. Это огромный шаг вперед, потому что до
						этого у нас не было лекарств, которые бы действительно влияли на ход болезни, а
						только ...
					</div>
				</div>
				<div class="news__item">
					<div class="news__item-date">14.04.2025</div>
					<img class="news__item-image" :src="`storage/default/image-none-default.png`"/>
					<div class="news__item-title">Прорыв в лечении Альцгеймера:</div>
					<div class="news__item-content">
						Ученые разработали новый препарат, который, кажется, замедляет прогрессирование
						болезни Альцгеймера на ранних стадиях. Это огромный шаг вперед, потому что до
						этого у нас не было лекарств, которые бы действительно влияли на ход болезни, а
						только ...
					</div>
				</div>
				<div class="news__item">
					<div class="news__item-date">13.04.2025</div>
					<img class="news__item-image" :src="`storage/default/image-none-default.png`"/>
					<div class="news__item-title">Прорыв в лечении Альцгеймера:</div>
					<div class="news__item-content">
						Ученые разработали новый препарат, который, кажется, замедляет прогрессирование
						болезни Альцгеймера на ранних стадиях. Это огромный шаг вперед, потому что до
						этого у нас не было лекарств, которые бы действительно влияли на ход болезни, а
						только ...
					</div>
				</div>
				<div class="news__item">
					<div class="news__item-date">12.04.2025</div>
					<img class="news__item-image" :src="`storage/default/image-none-default.png`"/>
					<div class="news__item-title">Прорыв в лечении Альцгеймера:</div>
					<div class="news__item-content">
						Ученые разработали новый препарат, который, кажется, замедляет прогрессирование
						болезни Альцгеймера на ранних стадиях. Это огромный шаг вперед, потому что до
						этого у нас не было лекарств, которые бы действительно влияли на ход болезни, а
						только ...
					</div>
				</div>
				<div class="news__item">
					<div class="news__item-date">11.04.2025</div>
					<img class="news__item-image" :src="`storage/default/image-none-default.png`"/>
					<div class="news__item-title">Прорыв в лечении Альцгеймера:</div>
					<div class="news__item-content">
						Ученые разработали новый препарат, который, кажется, замедляет прогрессирование
						болезни Альцгеймера на ранних стадиях. Это огромный шаг вперед, потому что до
						этого у нас не было лекарств, которые бы действительно влияли на ход болезни, а
						только ...
					</div>
				</div>
				<div class="news__item">
					<div class="news__item-date">10.04.2025</div>
					<img class="news__item-image" :src="`storage/default/image-none-default.png`"/>
					<div class="news__item-title">Прорыв в лечении Альцгеймера:</div>
					<div class="news__item-content">
						Ученые разработали новый препарат, который, кажется, замедляет прогрессирование
						болезни Альцгеймера на ранних стадиях. Это огромный шаг вперед, потому что до
						этого у нас не было лекарств, которые бы действительно влияли на ход болезни, а
						только ...
					</div>
				</div>
			</div>
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
			method: "get",
			url: `${this.$store.getters.urlApi}` + `get-slides-not-hide`,
		})
			.then((response) => {
				this.slides = response.data;
				sorted.sortByOrder("up", this.slides);
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
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

.news {
	display: flex;
	flex-direction: column;
	gap: 20px;

	width: 1350px;
}

.news__head {
	display: flex;
	justify-content: space-between;
	gap: 20px;
}

.news__head-title {
	font-size: 1.5rem;
	font-weight: bold;
	color: var(--primary-color);
}

.news__head-button {
	cursor: pointer;
	border: 0px;

	font-size: 1.25rem;

	background-color: rgba(0, 0, 0, 0);
	color: rgba(0, 0, 0, 0.5);
}

.news__head-button:hover {
	color: rgba(0, 0, 0, 1);
}

.news__body {
	display: grid;
	justify-content: space-between;
	gap: 20px;

	grid-template-columns: repeat(3, 1fr);
}

.news__item {
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	gap: 10px;

	padding: 20px;
	border: var(--input-border);
	border-radius: var(--default-border-radius);
}

.news__item-date {
	font-size: 1.125rem;
	color: rgba(0, 0, 0, 0.5);
}

.news__item-image {
	width: 100%;
	max-height: 200px;
	object-fit: contain;
	border-radius: var(--default-border-radius);
}

.news__item-title {
	font-size: 1.25rem;
}

.news__item-content {
	height: 100px;
	overflow-y: auto;

	font-size: 1.125rem;
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
