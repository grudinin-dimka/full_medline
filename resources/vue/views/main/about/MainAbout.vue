<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/about">О нас</router-link>
	</info-bar>

	<block :minHeight="300">
		<MainAboutList :abouts="abouts" v-if="abouts.length > 0" />
		<Empty v-else />
	</block>

	<block :minHeight="300">
		<div class="files">
			<a
				v-for="file in infoFiles"
				class="files__item"
				:class="{ skeleton: loading.loader.infoFiles }"
				:href="file.path"
				target="_blank"
			>
				<div class="files__item-content">
					<div class="files__content-icon" v-if="file.path">
						<VueIcon
							v-if="getFiletype(file.path) === 'pdf'"
							:name="'Article'"
							:width="'30px'"
							:height="'30px'"
							:fill="'var(--primary-color)'"
							:cursor="'pointer'"
						/>

						<VueIcon
							v-else-if="getFiletype(file.path) === 'jpg' || getFiletype(file.path) === 'jpeg'"
							:name="'Image'"
							:width="'30px'"
							:height="'30px'"
							:fill="'var(--primary-color)'"
							:cursor="'pointer'"
						/>

						<VueIcon
							v-else
							:name="'Attach File'"
							:width="'30px'"
							:height="'30px'"
							:fill="'var(--primary-color)'"
							:cursor="'pointer'"
						/>
					</div>
					<div class="files__content-name">{{ file.path ? getFilename(file.path) : "" }}</div>
				</div>
				<div class="files__item-other" v-if="file.path">
					<button class="files__other-button">Смотреть</button>
				</div>
			</a>
		</div>
	</block>
</template>

<script>
import MainAboutList from "./MainAboutList.vue";

import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import Empty from "../../../components/modules/Empty.vue";

import api from "../../../mixin/api";
import files from "../../../services/files";

import fakeDelay from "../../../mixin/fake-delay";
import sorted from "../../../services/sorted";

export default {
	components: {
		MainAboutList,

		InfoBar,
		Block,
		Empty,
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

		getFiletype(value) {
			return files.basetype(value);
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

			abouts: [
				{
					id: 1,
					title: null,
					imageOne: null,
					imageTwo: null,
					imageThree: null,
					description: null,
					order: 1,
				},
				{
					id: 2,
					title: null,
					imageOne: null,
					imageTwo: null,
					imageThree: null,
					description: null,
					order: 2,
				},
				{
					id: 3,
					title: null,
					imageOne: null,
					imageTwo: null,
					imageThree: null,
					description: null,
					order: 3,
				},
			],

			infoFiles: [
				{
					id: 1,
					path: null,
				},
				{
					id: 2,
					path: null,
				},
				{
					id: 3,
					path: null,
				},
			],
		};
	},
	mounted() {
		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "get",
				url: `get-abouts-all`,
			})
		).then((response) => {
			try {
				if (!response) return;

				for (let i = 0; i < response.data.result.length; i++) {
					this.abouts[i] = response.data.result[i];
				}

				this.abouts.splice(response.data.result.length, this.abouts.length);

				sorted.sortNumberByKey("up", this.abouts, "order");

				this.loading.loader.about = false;
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
		});

		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "get",
				url: `get-info-files-all`,
			})
		).then((response) => {
			try {
				if (!response) return;

				for (let i = 0; i < response.data.result.length; i++) {
					this.infoFiles[i] = response.data.result[i];
				}

				this.infoFiles.splice(response.data.result.length, this.infoFiles.length);

				this.loading.loader.infoFiles = false;
			} catch (error) {
				this.$store.commit("addDebugger", {
					title: "Ошибка.",
					body: error,
					type: "error",
				});
			}
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

	padding: var(--default-padding);
	border: var(--default-border);
	border-radius: var(--default-border-radius);
	padding: 10px;

	min-height: 40px;
	font-size: 1.125rem;
	text-decoration: none;
	color: black;
	background-color: var(--skeleton-background-color);
}

.files__item-content {
	display: flex;
	justify-content: center;
	align-items: center;
	gap: 10px;
}

.files__content-icon {
	display: flex;
	justify-content: center;
	align-items: center;
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
