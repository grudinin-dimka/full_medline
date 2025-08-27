<template>
	<div
		class="wiki-wrapper"
		@click.self="$store.commit('closeWiki')"
		:class="{ 'wiki-wrapper--open': $store.getters.getWikiShow }"
	>
		<div class="wiki" ref="wiki" :class="{ 'wiki--open': $store.getters.getWikiShow }">
			<div class="wiki__header">
				<div class="wiki__header-title">
					{{ $store.getters.getWikiTitle }}
				</div>
				<div class="wiki__header-buttons">
					<button
						class="wiki__header-buttons-button wiki__header-buttons-button--close"
						@click="$store.commit('closeWiki')"
					>
						<VueIcon :name="'close'" :fill="'black'" :width="'20px'" :height="'20px'" />
					</button>
				</div>
			</div>

			<div class="wiki__body">
				<template v-if="$store.getters.getWikiComponent">
					<component :is="$store.getters.getWikiComponent" />
				</template>
			</div>
		</div>
	</div>
</template>

<script>
import HomeFooter from "./components/home/HomeFooter.vue";
import HomeSlider from "./components/home/HomeSlider.vue";
import SpecialistsAll from "./components/specialists/SpecialistsAll.vue";
import SpecialistsProfile from "./components/specialists/SpecialistsProfile.vue";
import SpecialistsClinics from "./components/specialists/SpecialistsClinics.vue";
import SpecialistsSpecializations from "./components/specialists/SpecialistsSpecializations.vue";
import PricesAll from "./components/prices/PricesAll.vue";
import ScheduleAll from "./components/schedule/ScheduleAll.vue";
import NewsAll from "./components/news/NewsAll.vue";
import NewsOnce from "./components/news/NewsOnce.vue";
import VideoAll from "./components/video/VideoAll.vue";
import ContactsAll from "./components/contacts/ContactsAll.vue";
import AboutAll from "./components/about/AboutAll.vue";
import AboutFiles from "./components/about/AboutFiles.vue";
import VacanciesAll from "./components/vacancies/VacanciesAll.vue";
import TravelsAll from "./components/travels/TravelsAll.vue";

import VueIcon from "../icon/VueIcon.vue";

export default {
	components: {
		HomeFooter,
		HomeSlider,
		SpecialistsAll,
		SpecialistsProfile,
		SpecialistsClinics,
		SpecialistsSpecializations,
		PricesAll,
		ScheduleAll,
		NewsAll,
		NewsOnce,
		VideoAll,
		ContactsAll,
		AboutAll,
		AboutFiles,
		VacanciesAll,
		TravelsAll,

		VueIcon,
	},
};
</script>

<style scoped>
.wiki-wrapper {
	position: fixed;
	z-index: var(--wiki-z-index);
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;

	background-color: rgba(0, 0, 0, 0.3);

	visibility: hidden;
	opacity: 0;

	transition: all 0.3s ease;
}

.wiki-wrapper--open {
	visibility: visible;
	opacity: 1;
}

.wiki {
	box-sizing: border-box;
	position: var(--wiki-position);
	z-index: var(--wiki-z-index);
	top: var(--wiki-top);
	right: var(--wiki-right);
	bottom: var(--wiki-bottom);
	left: var(--wiki-left);

	display: flex;
	flex-direction: column;
	gap: var(--wiki-gap);

	padding: var(--wiki-padding);

	opacity: var(--wiki-opacity);
	visibility: var(--wiki-visibility);

	width: var(--wiki-width);
	height: var(--wiki-height);

	background-color: var(--wiki-background-color);
	box-shadow: var(--wiki-box-shadow);

	transition: var(--wiki-transition);
}

.wiki--open {
	top: var(--wiki-open-top);
	right: var(--wiki-open-right);
	bottom: var(--wiki-open-bottom);
	left: var(--wiki-open-left);

	opacity: var(--wiki-open-opacity);
	visibility: var(--wiki-open-visibility);
}

.wiki__header {
	display: flex;
	justify-content: space-between;
	align-items: center;
}

.wiki__header-title {
	font-size: 1.5rem;
	font-weight: 500;
	text-transform: uppercase;
	color: black;
}

.wiki__header-buttons-button {
	cursor: pointer;

	border: 0px;
	background-color: transparent;
}

.wiki__header-buttons-button--close {
	width: 30px;
	height: 30px;
}

.wiki__body {
	overflow-y: auto;
}
</style>
