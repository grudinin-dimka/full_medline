<template>
	<div class="wiki" :class="{ 'wiki--open': $store.getters.getWikiShow }">
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
</template>

<script>
import HomeFooter from "./components/home/HomeFooter.vue";
import HomeSlider from "./components/home/HomeSlider.vue";

import VueIcon from "../icon/VueIcon.vue";

export default {
	components: {
      HomeFooter,
      HomeSlider,

		VueIcon,
	},
};
</script>

<style scoped>
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
</style>
