<template>
	<General />

	<VueWikiTitle>
		<VueIcon name="Add" width="24" height="24" fill="var(--primary-color)" />
		Добавление путевки
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы добавить путевку, вам необходимо нажать но кнопку «Добавить», которая
		находится внизу блока с вакансиями.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После нажатия на кнопку «Добавить», откроется модальное окно, в котором вы сможете добавить
		новую путевку.
	</VueWikiParagraph>
	<VueWikiParagraph>
		3. В случае, когда при нажатии на кнопку «Добавить» внизу модального окна появляется сообщение
		об ошибке, это означает, что вы не заполнили обязательные поля, которые помечаются красным
		цветом.
	</VueWikiParagraph>
	<VueWikiParagraph>
		4. После соблюдения предыдущих пунктов, вы сможете добавить новую путевку, нажав на кнопку
		«Добавить» внизу модального окна.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Delete" width="24" height="24" fill="var(--primary-color)" />
		Удаление путевки
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы удалить путевку, вам необходимо открыть выбранную путевку, нажав на нее
		левой кнопкой мышки. После этого, появится кнопка для удаления выбранной путевки внизу
		модального окна, нажмите на неё.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для полного удаления путевок, которые помечены на удаление, нажмите на кнопку «Сохранить».
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Edit" width="24" height="24" fill="var(--primary-color)" />
		Изменение путевки
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы изменить путевку, вам необходимо открыть выбранную путевку, нажав на нее левой
		кнопкой мышки. После этого, вы можете редактировать содержимое путевки. После этого, нажмите на
		кнопку «Обновить».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для обновления значений путевки нажмите на кнопку «Обновить».
	</VueWikiParagraph>
	<VueWikiParagraph>
		3. Для сохранения изменений нажмите на кнопку «Сохранить».
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
