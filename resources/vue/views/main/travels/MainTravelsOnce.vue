<template>
	<info-bar>
		<router-link to="/">Главная</router-link>
		<span class="link-arrow"> / </span>
		<router-link to="/travels">Путевки</router-link>
		<span class="link-arrow"> / </span>
		<router-link :to="`/travels/${travel.url}`">
			<span class="travel__name" :class="{ skeleton: loading.loader.travel }">
				{{ travel.title }}
			</span>
		</router-link>
	</info-bar>

	<Block :minHeight="700">
		<div class="travels__once">
			<div class="travels__once-header">
				<div class="travels__once-image" :class="{ skeleton: loading.loader.travel }">
					<img v-if="travel.path" :src="travel.path" alt="Картинка" />
				</div>

				<div class="travels__once-info">
					<div class="travels__once-title" :class="{ skeleton: loading.loader.travel }">
						{{ travel.title }}
					</div>

					<div class="travels__once-duration" :class="{ skeleton: loading.loader.travel }">
						<VueIcon
							v-if="travel.duration"
							:name="'Calendar Month'"
							:fill="'var(--primary-color)'"
							:width="'32px'"
							:height="'32px'"
						/>

						{{ travel.duration }}
					</div>

					<div class="travels__once-description" :class="{ skeleton: loading.loader.travel }">
						<VueTiptap :editable="false" :limit="1_000" v-model="travel.description" />
					</div>
				</div>
			</div>

			<div class="travels__once-body">
				<div class="travels__once-services">
					<div class="travels__once-services-list">
						<template v-if="loading.loader.travel">
							<div v-for="value in 3" class="travels__once-services-item skeleton"></div>
						</template>

						<template v-else>
							<VueAccordeon v-for="service in travel.services">
								<template #name> {{ service.title }} </template>
								<template #body>
									<VueAccordeonBlock>
										<div class="travels__once-description-block">
											{{ service.description }}
										</div>
									</VueAccordeonBlock>
								</template>
							</VueAccordeon>
						</template>
					</div>
				</div>
			</div>
		</div>
	</Block>
</template>

<script>
import VueTiptap from "../../../components/modules/VueTiptap.vue";
import VueIcon from "../../../components/modules/icon/VueIcon.vue";
import VueAccordeon from "../../../components/modules/accordeon/VueAccordeon.vue";
import VueAccordeonBlock from "../../../components/modules/accordeon/VueAccordeonBlock.vue";

import InfoBar from "../../../components/ui/main/InfoBar.vue";
import Block from "../../../components/ui/main/Block.vue";
import LoadText from "../../../components/ui/main/LoadText.vue";

import api from "../../../mixin/api";
import sorted from "../../../services/sorted";

import fakeDelay from "../../../mixin/fake-delay";

export default {
	components: {
		VueTiptap,
		VueIcon,
		VueAccordeon,
		VueAccordeonBlock,

		InfoBar,
		Block,
		LoadText,
	},
	data() {
		return {
			/* Загрузка */
			loading: {
				loader: {
					travel: true,
				},
			},

			/* Путевка */
			travel: {
				title: "",
				description: "",
				duration: "",
				path: "",
				services: "",
				prices: "",
				name: "",
			},
		};
	},
	methods: {},
	mounted() {
		fakeDelay(this.$store.getters.timeout, () =>
			api({
				method: "post",
				url: this.$store.getters.urlApi + "get-travels-once",
				data: {
					url: this.$route.params.name,
				},
			})
		).then((response) => {
			if (!response) return;

			try {
				this.travel = response.data.result;

				this.loading.loader.travel = false;
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
.travels__once {
	display: flex;
	flex-direction: column;
	gap: 20px;

	margin: 0 auto;

	width: 1350px;
}

.travels__once-header {
	display: flex;
	justify-content: space-between;
	gap: 20px;
}

.travels__once-image {
	border-radius: var(--default-border-radius);
	height: 600px;

	aspect-ratio: 1 / 1.2;
}

.travels__once-image > img {
	border-radius: var(--default-border-radius);

	animation: show 0.5s ease-in-out;
}

.travels__once-info {
	display: flex;
	flex-direction: column;
	gap: var(--default-gap);

	width: 100%;
}

.travels__once-title {
	min-height: 30px;

	font-size: 1.5rem;
	font-weight: 500;
	border-radius: var(--default-border-radius);
}

.travels__once-duration {
	display: flex;
	align-items: center;
	gap: 5px;

	min-height: 30px;

	font-size: 1.125rem;
	border-radius: var(--default-border-radius);
}

.travels__once-description {
	font-size: 1.125rem;
	text-align: justify;

	line-height: 1.5;

	height: 100%;
}

.travels__once-description-block {
	font-size: 1.125rem;
	text-align: justify;

	line-height: 1.5;
}

.travels__once-description.skeleton {
	min-height: 200px;

	font-size: 1.125rem;
	border-radius: var(--default-border-radius);
}

.travels__once-services {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.travels__once-services-list {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.travels__once-services-item {
	height: 62px;
	border-radius: var(--accordeon-head-border-radius);
}
</style>
