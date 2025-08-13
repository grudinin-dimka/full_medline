<template>
	<General />

	<VueWikiTitle>
		<VueIcon name="Add" width="24" height="24" fill="var(--primary-color)" />
		Добавление блока
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы добавить блок, вам необходимо нажать но кнопку «Добавить», которая
		находится внизу блока «ИНФОРМАЦИОННЫЕ БЛОКИ».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После нажатия на кнопку «Добавить», откроется модальное окно, в котором вы сможете добавить
		новый блок.
	</VueWikiParagraph>
	<VueWikiParagraph>
		3. В случае, когда при нажатии на кнопку «Добавить» внизу модального окна появляется сообщение
		об ошибке, это означает, что вы не заполнили обязательные поля, которые помечаются красным
		цветом.
	</VueWikiParagraph>
	<VueWikiParagraph>
		4. Для картинок поддерживаются следующие форматы:
		<span :style="{ color: 'var(--primary-color)' }">.jpg</span>,
		<span :style="{ color: 'var(--primary-color)' }">.jpeg</span>,
		<span :style="{ color: 'var(--primary-color)' }">.png</span>,
		<span :style="{ color: 'var(--primary-color)' }">.webp</span>. Рекомендуется загружать файлы
		формата <span :style="{ color: 'var(--primary-color)' }">.webp</span>, так как они имеют
		высокое качество и небольшой размер.
	</VueWikiParagraph>
	<VueWikiParagraph>
		5. После соблюдения предыдущих пунктов, вы сможете добавить новый блок, нажав на кнопку
		«Добавить» внизу модального окна.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Delete" width="24" height="24" fill="var(--primary-color)" />
		Удаление блока
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы удалить блок, вам необходимо открыть выбранный блок, нажав на него левой
		кнопкой мышки. После этого, появится кнопка для удаления выбранного блока внизу модального
		окна, нажмите на неё.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для полного удаления блоков, которые помечены на удаление, нажмите на кнопку «Сохранить».
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Edit" width="24" height="24" fill="var(--primary-color)" />
		Изменение блока
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы изменить блок, вам необходимо открыть выбранный блок, нажав на него левой
		кнопкой мышки. После этого, вы можете редактировать его содержимое. После этого, нажмите на
		кнопку Обновить».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для обновления значений контакта нажмите на кнопку «Обновить».
	</VueWikiParagraph>
	<VueWikiParagraph> 3. Для сохранения изменений нажмите на кнопку «Сохранить». </VueWikiParagraph>
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
