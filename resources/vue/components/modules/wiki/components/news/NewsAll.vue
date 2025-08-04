<template>
	<General />

	<VueWikiTitle>
		<VueIcon name="add" width="14" height="14" fill="var(--primary-color)" />
		Добавление новости.
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для добавления новости, нажмите на кнопку «Добавить».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После нажатия на кнопку «Добавить» вы будете перенаправлены на страницу добавления.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="remove" width="16" height="16" fill="var(--primary-color)" />
		Удаление новости.
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для удаления новости, нажмите на кнопку «Удалить» в строке таблица с нужной новостью.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для сохранения изменений, нажмите на кнопку «Сохранить».
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="edit" width="18" height="18" fill="var(--primary-color)" />
		Изменение новости
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для изменения новости, нажмите на кнопку «Изменить» в строке таблица с нужной
		новостью.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После нажатия на кнопку «Изменить» вы будете перенаправлены на страницу изменения.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="publish" width="18" height="18" fill="var(--primary-color)" />
		Публикация новости.
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для пукбликации новостми, нажмите на значение в столбике «Публикация».
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
