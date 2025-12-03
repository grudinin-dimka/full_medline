<template>
	<General />

	<VueWikiTitle>
		<VueIcon name="Add" width="24" height="24" fill="var(--primary-color)" />
		Добавление специалиста.
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для добавления специалиста, нажмите на кнопку «Добавить».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После нажатия на кнопку «Добавить» вы будете перенаправлены на страницу добавления.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Delete" width="24" height="24" fill="var(--primary-color)" />
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
		<VueIcon name="Edit" width="24" height="24" fill="var(--primary-color)" />
		Изменение специалиста
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для изменения специалиста, нажмите на кнопку «Изменить» в строке таблица с нужным
		специалистом.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После нажатия на кнопку «Изменить» вы будете перенаправлены на страницу изменения.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Hide" width="24" height="24" fill="var(--primary-color)" />
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

export default {
	components: { VueWikiTitle, VueWikiParagraph, General },
};
</script>

<style scoped></style>
