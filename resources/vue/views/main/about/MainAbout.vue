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
							:name="'Document'"
							:width="'30px'"
							:height="'30px'"
							:fill="'var(--primary-color)'"
							:cursor="'pointer'"
						/>

						<svg
							v-else-if="
								getFiletype(file.path) === 'jpg' || getFiletype(file.path) === 'jpeg'
							"
							width="30"
							height="30"
							viewBox="0 0 24 24"
							fill="var(--primary-color)"
							xmlns="http://www.w3.org/2000/svg"
						>
							<g clip-path="url(#clip0_768_2)">
								<path
									d="M2.66667 24C1.93333 24 1.30556 23.7389 0.783333 23.2167C0.261111 22.6944 0 22.0667 0 21.3333V2.66667C0 1.93333 0.261111 1.30556 0.783333 0.783333C1.30556 0.261111 1.93333 0 2.66667 0H21.3333C22.0667 0 22.6944 0.261111 23.2167 0.783333C23.7389 1.30556 24 1.93333 24 2.66667V21.3333C24 22.0667 23.7389 22.6944 23.2167 23.2167C22.6944 23.7389 22.0667 24 21.3333 24H2.66667ZM2.66667 21.3333H21.3333V2.66667H2.66667V21.3333ZM4 18.6667H20L15 12L11 17.3333L8 13.3333L4 18.6667Z"
								/>
							</g>
							<defs>
								<clipPath id="clip0_768_2">
									<rect width="24" height="24" />
								</clipPath>
							</defs>
						</svg>

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
				url: this.$store.getters.urlApi + `get-abouts-all`,
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
				url: this.$store.getters.urlApi + `get-info-files-all`,
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

	border-radius: calc(var(--default-border-radius) / 1.5);
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
