<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/about">О нас</router-link>
	</info-bar>

	<block :minHeight="300">
		<template v-if="loading.sections.about">
			<MainAboutList :abouts="abouts" v-if="abouts.length > 0" />
			<Empty v-else />
		</template>

		<loader-child
			:minHeight="300"
			:isLoading="loading.loader.about"
			@loaderChildAfterLeave="loaderChildAfterLeave"
		/>
	</block>

	<block :minHeight="300">
		<template v-if="loading.sections.infoFiles">
			<div class="files">
				<a v-for="file in infoFiles" class="files__item" :href="file.path" target="_blank">
					<div class="files__item-content">
						<div class="files__content-icon">
							<VueIcon
								:name="'pdf'"
								:width="'30px'"
								:height="'30px'"
								:fill="'var(--primary-color)'"
								:cursor="'pointer'"
							/>
						</div>
						<div class="files__content-name">{{ getFilename(file.path) }}</div>
					</div>
					<div class="files__item-other">
						<button class="files__other-button">Смотреть</button>
					</div>
				</a>
			</div>
		</template>

		<loader-child
			:minHeight="300"
			:isLoading="loading.loader.infoFiles"
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
import VueIcon from "../../../components/modules/icon/VueIcon.vue";
import files from "../../../services/files";

export default {
	components: {
		InfoBar,
		LoaderChild,
		Block,
		MainAboutList,
		Empty,

		VueIcon,
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                   Загрузчик                       |*/
		/* |___________________________________________________|*/
		/* После скрытия элементы */
		loaderChildAfterLeave() {
			for (let key in this.loading.loader) {
				if (!this.loading.loader[key]) {
					this.loading.sections[key] = true;
				}
			}
		},

		getFilename(value) {
			return files.basename(value);
		},
	},
	data() {
		return {
			loading: {
				loader: {
					about: true,
					infoFiles: true,
				},
				sections: {
					about: false,
					infoFiles: false,
				},
			},

			abouts: [],
			infoFiles: [],
		};
	},
	mounted() {
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
				this.loading.loader.about = false;
			});

		api({
			method: "get",
			url: this.$store.getters.urlApi + `get-info-files-all`,
		})
			.then((response) => {
				if (!response) return;

				this.infoFiles = response.data.result;
			})
			.catch((error) => {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			})
			.finally(() => {
				this.loading.loader.infoFiles = false;
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

	animation: show-bottom-to-top-15 0.5s ease-in-out;
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

@media screen and (width <= 1425px) {
	.files {
		width: 100%;
	}
}

@media screen and (width <= 850px) {
	.files__item {
		flex-direction: column;
		justify-content: stretch;
		align-items: stretch;
	}

	.files__other-button {
		width: 100%;
	}
}
</style>
