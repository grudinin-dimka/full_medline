<template>
	<VueWikiTitle>Общие сведения</VueWikiTitle>
	<VueWikiParagraph>
		1. Все изменения сохраняются только после нажатия на кнопку «Сохранить».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Если вы хотите сбросить все изменения, тогда перезагрузите страницу.
	</VueWikiParagraph>
	<VueWikiParagraph>
		3. Если вы хотите удалить добавленное значение, тогда для начала сохраните изменения.
	</VueWikiParagraph>
</template>

<script>
import VueWikiTitle from "../VueWikiTitle.vue";
import VueWikiParagraph from "../VueWikiParagraph.vue";

export default {
	components: { VueWikiTitle, VueWikiParagraph },
};
</script>
