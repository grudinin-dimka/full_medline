<template>
	<General />

	<VueWikiTitle>
		<VueIcon name="Add" width="24" height="24" fill="var(--primary-color)" />
		Добавление новости.
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы добавить новость, вам необходимо нажать но кнопку «Добавить», которая
		находится справа сверху блока «Блок новостей».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. В случае, когда при нажатии на кнопку «Добавить» на полях ввода появляются сообщения об
		ошибке, это означает, что вы не заполнили обязательные поля, которые помечаются красным
		цветом.
	</VueWikiParagraph>
	<VueWikiParagraph>
		3. Для загрузки картинки необходимо навести на блока картинки, должен будет появиться плюсик.
		После нажатия вам необходимо будет загрузить файл форматов:
		<span :style="{ color: 'var(--primary-color)' }">.jpg</span>,
		<span :style="{ color: 'var(--primary-color)' }">.jpeg</span>,
		<span :style="{ color: 'var(--primary-color)' }">.png</span>,
		<span :style="{ color: 'var(--primary-color)' }">.webp</span>. Рекомендуется загружать файлы
		формата <span :style="{ color: 'var(--primary-color)' }">.webp</span>, так как они имеют
		высокое качество и небольшой размер.
	</VueWikiParagraph>
	<VueWikiParagraph>
		4. После соблюдения предыдущих пунктов, вы сможете добавить новость, нажав на кнопку
		«Добавить» сверху справа блока новостей.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Edit" width="24" height="24" fill="var(--primary-color)" />
		Изменение новости
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для изменения данных о специалисте вы можете отредактировать необходимые поля.
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
