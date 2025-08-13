<template>
	<VueWikiTitle>
		<VueIcon name="Edit" width="24" height="24" fill="var(--primary-color)" />
		Обновление расписания
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Расписание обновляется автоматические 3 раза в день. Время обновления:
		<span :style="{ color: 'var(--primary-color)' }">00:30</span>,
		<span :style="{ color: 'var(--primary-color)' }">12:30</span>,
		<span :style="{ color: 'var(--primary-color)' }">19:30</span>.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для ручного обновления расписания нажмите на кнопку «Сохранить».
	</VueWikiParagraph>
</template>

<script>
import VueWikiTitle from "../../VueWikiTitle.vue";
import VueWikiParagraph from "../../VueWikiParagraph.vue";

import VueIcon from "../../../icon/VueIcon.vue";

export default {
	components: { VueWikiTitle, VueWikiParagraph, VueIcon },
};
</script>

<style scoped></style>
