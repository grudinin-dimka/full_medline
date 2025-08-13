<template>
	<General />

	<VueWikiTitle>
		<VueIcon name="Add" width="24" height="24" fill="var(--primary-color)" />
		Добавление файла
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы добавить файла, вам необходимо нажать но кнопку «Добавить».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После нажатия на кнопку «Добавить», откроется модальное окно, в котором вы сможете добавить
		новый файл.
	</VueWikiParagraph>
	<VueWikiParagraph>
		3. В случае, когда при нажатии на кнопку «Добавить» внизу модального окна появляется сообщение
		об ошибке, это означает, что вы не заполнили обязательные поля, которые помечаются красным
		цветом.
	</VueWikiParagraph>
	<VueWikiParagraph>
		4. Для файлов поддерживаются следующие форматы:
		<span :style="{ color: 'var(--primary-color)' }">.pdf</span>,
		<span :style="{ color: 'var(--primary-color)' }">.doc</span>,
		<span :style="{ color: 'var(--primary-color)' }">.docx</span>,
		<span :style="{ color: 'var(--primary-color)' }">.xlsx</span>,
		<span :style="{ color: 'var(--primary-color)' }">.xls</span>,
		<span :style="{ color: 'var(--primary-color)' }">.ods</span>,
		<span :style="{ color: 'var(--primary-color)' }">.jpg</span>,
		<span :style="{ color: 'var(--primary-color)' }">.jpeg</span>,
		<span :style="{ color: 'var(--primary-color)' }">.png</span>,
		<span :style="{ color: 'var(--primary-color)' }">.webp</span>.
	</VueWikiParagraph>
	<VueWikiParagraph>
		5. После соблюдения предыдущих пунктов, вы сможете добавить новый файл, нажав на кнопку
		«Добавить» внизу модального окна.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Delete" width="24" height="24" fill="var(--primary-color)" />
		Удаление файла
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы удалить файл, вам необходимо нажать на кнопку удалить в строке с выбранными
		файлом, тем самым пометить на удаление.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для полного удаления файлов, которые помечены на удаление, нажмите на кнопку «Сохранить».
	</VueWikiParagraph>

   <VueWikiTitle>
		Просмотр файлов
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для просмотра файлов достаточно нажать на кнопку изменить, после чего откроется выбранный файл.
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
