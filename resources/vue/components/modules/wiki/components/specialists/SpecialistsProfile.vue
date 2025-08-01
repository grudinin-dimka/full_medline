<template>
	<General />

	<VueWikiTitle>
		<VueIcon name="add" width="14" height="14" fill="var(--primary-color)" />
		Добавление специалиста.
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для добавления специалиста, нажмите на кнопку «Добавить».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После нажатия на кнопку «Добавить» вы будете перенаправлены на страницу добавления.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="remove" width="16" height="16" fill="var(--primary-color)" />
		Удаление специалиста.
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для удаления специалиста, нажмите на кнопку «Удалить» в строке таблица с нужным
		специалистом.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для сохранения изменений, нажмите на кнопку «Сохранить».
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="hide" width="18" height="18" fill="var(--primary-color)" />
		Скрытие специалиста.
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для скрытия специалиста, нажмите на значение в столбике «Скрытие».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для сохранения изменений, нажмите на кнопку «Сохранить».
	</VueWikiParagraph>
</template>

<script>
import VueWikiTitle from "../../VueWikiTitle.vue";
import VueWikiParagraph from "../../VueWikiParagraph.vue";

import General from "../General.vue";

import VueIcon from "../../../icon/VueIcon.vue";

export default {
	components: { VueWikiTitle, VueWikiParagraph, General, VueIcon },
};
</script>

<style scoped></style>
