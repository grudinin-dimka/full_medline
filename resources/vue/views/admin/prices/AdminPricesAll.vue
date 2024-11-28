<template>
	<info-bar>
		<template v-slot:title>Цены</template>
		<template v-slot:addreas>prices</template>
	</info-bar>

	<!--|‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|-->
	<!--|                    СПИСОК ЦЕН                     |-->
	<!--|___________________________________________________|-->
	<block-once :minHeight="400">
		<block-title>
			<template #title>СПИСОК ЦЕН</template>
		</block-title>

		<LoaderChild
			:isLoading="loading.loader.prices"
			:minHeight="300"
			@loaderChildAfterLeave=""
		></LoaderChild>
	</block-once>
</template>

<script>
import LoaderChild from "../../../components/includes/LoaderChild.vue";

import BlockOnce from "../../../components/ui/admin/blocks/BlockOnce.vue";
import BlockTitle from "../../../components/ui/admin/blocks/BlockTitle.vue";
import InfoBar from "../../../components/ui/admin/InfoBar.vue";

export default {
	components: {
		LoaderChild,
		BlockOnce,
		BlockTitle,
		InfoBar,
	},
	data() {
		return {
			loading: {
				loader: {
					prices: false,
				},
				sections: {
					prices: false,
				},
			},
		};
	},
};
</script>

<style scoped></style>
