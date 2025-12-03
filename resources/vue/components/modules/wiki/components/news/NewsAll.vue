<template>
	<General />

	<VueWikiTitle>
		<VueIcon name="Add" width="24" height="24" fill="var(--primary-color)" />
		Добавление новости.
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для добавления новости, нажмите на кнопку «Добавить».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После нажатия на кнопку «Добавить» вы будете перенаправлены на страницу добавления.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Delete" width="24" height="24" fill="var(--primary-color)" />
		Удаление новости.
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для удаления новости, нажмите на кнопку «Удалить» в строке таблица с нужной новостью.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для сохранения изменений, нажмите на кнопку «Сохранить».
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Edit" width="24" height="24" fill="var(--primary-color)" />
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
		<VueIcon name="Publish" width="24" height="24" fill="var(--primary-color)" />
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

export default {
	components: { VueWikiTitle, VueWikiParagraph, General},
};
</script>

<style scoped></style>
