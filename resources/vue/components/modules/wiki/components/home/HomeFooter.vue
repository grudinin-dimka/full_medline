<template>
	<General />

	<VueWikiTitle>1. Изменение футера</VueWikiTitle>
	<VueWikiParagraph>
		1.1. Работа с содержимым футера похожа на работу с вордовским документом, поэтому вам не нужно выполнять какие-либо дополнительные действия.
	</VueWikiParagraph>
	<VueWikiParagraph>
		1.2. Если вы хотите вернуться на шаг назад при изменении содержимого футера, тогда нажмите на кнопку ↺.
	</VueWikiParagraph>
	<VueWikiParagraph>
		1.2. Если вы хотите отмотать на шаг вперед при изменении содержимого футера, тогда нажмите на кнопку ↻.
	</VueWikiParagraph>
</template>

<script>
import VueWikiTitle from "../../VueWikiTitle.vue";
import VueWikiParagraph from "../../VueWikiParagraph.vue";

import General from "../General.vue";

export default {
	components: { VueWikiTitle, VueWikiParagraph, General },
};
</script>

<style scoped></style>
