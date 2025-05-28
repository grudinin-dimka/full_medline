<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/about">О нас</router-link>
	</info-bar>

	<block>
		<template v-if="loading.sections.about">
			<MainAboutList :abouts="abouts" v-if="abouts.length > 0" />
			<Empty v-else />

			<div class="files">
				<a
					class="files__item"
					:href="'/storage/other/Уведомление о передачи отчета СОУТ во ФГИС.pdf'"
					target="_blank"
				>
					<div class="files__item-content">
						<div class="files__content-icon">
							<Icon
								:name="'pdf'"
								:width="'30px'"
								:height="'30px'"
								:fill="'var(--primary-color)'"
								:cursor="'pointer'"
							/>
						</div>
						<div class="files__content-name">Уведомление о передачи отчета СОУТ во ФГИС</div>
					</div>
					<div class="files__item-other">
						<button class="files__other-button">Смотреть</button>
					</div>
				</a>
			</div>
		</template>

		<loader-child
			:isLoading="loading.loader.about"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block>
</template>

<script>
import InfoBar from "../../../components/ui/main/InfoBar.vue";
import LoaderChild from "../../../components/modules/LoaderChild.vue";
import Block from "../../../components/ui/main/Block.vue";
import MainAboutList from "./MainAboutList.vue";

import Empty from "../../../components/modules/Empty.vue";

import api from "../../../services/api";
import Icon from "../../../components/modules/icon/Icon.vue";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		MainAboutList,
		Empty,

		Icon,
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			this.loading.sections.about = true;
		},
	},
	data() {
		return {
			loading: {
				loader: {
					about: true,
				},
				sections: {
					about: false,
				},
			},
			abouts: [],
		};
	},
	mounted() {
		this.loading.loader.about = false;

		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-abouts-all`,
		})
			.then((response) => {
				if (!response) return;

				this.abouts = response.data.result;

				this.abouts.sort((a, b) => {
					return a.order - b.order;
				});
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.specialists = false;
			});
	},
};
</script>

<style scoped>
.files {
	display: flex;
	flex-direction: column;
	gap: 20px;

	width: 1350px;
}

.files__item {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 10px;

	border: var(--default-border);
	border-radius: calc(var(--default-border-radius) / 1.5);
	padding: 10px;

	font-size: 1.125rem;
	text-decoration: none;
	color: black;
}

.files__item-content {
	display: flex;
	justify-content: center;
	align-items: center;
	gap: 10px;
}

.files__other-button {
	cursor: pointer;

	display: flex;
	justify-content: center;
	align-items: center;
	gap: 10px;

	border: 0px;
	border-radius: var(--input-border-radius);
	padding: 10px 20px;

	background-color: var(--primary-color);
	color: white;

	font-size: 1.125rem;
}

.files__other-button:hover {
	background-color: var(--primary-color-hover);
}
</style>
