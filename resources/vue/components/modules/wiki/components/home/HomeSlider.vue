<template>
	<General />

	<VueWikiTitle>
		<VueIcon name="Add" width="24" height="24" fill="var(--primary-color)" />
		Добавление слайда
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы добавить слайдер, вам необходимо нажать но кнопку «Добавить», которая
		находится внизу блока слайдера.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После нажатия на кнопку «Добавить», откроется модальное окно, в котором вы сможете добавить
		новый слайдер.
	</VueWikiParagraph>
	<VueWikiParagraph>
		3. В случае, когда при нажатии на кнопку «Добавить» внизу модального окна появляется сообщение
		об ошибке, это означает, что вы не заполнили обязательные поля, которые помечаются красным
		цветом.
	</VueWikiParagraph>
	<VueWikiParagraph>
		4. После соблюдения предыдущих пунктов, вы сможете добавить новый слайдер, нажав на кнопку
		«Добавить» внизу модального окна.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Delete" width="24" height="24" fill="var(--primary-color)" />
		Удаление слайда
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы удалить слайдер, вам необходимо открыть выбранный слайд, нажав на него
		левой кнопкой мышки. После этого, появится кнопка для удаления выбранного слайдера внизу
		модального окна, нажмите на неё.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для полного удаления слайдов, которые помечены на удаление, нажмите на кнопку «Сохранить».
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Edit" width="24" height="24" fill="var(--primary-color)" />
		Изменение слайда
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы изменить слайд, вам необходимо открыть выбранный слайд, нажав на него левой
		кнопкой мышки. После этого, вы можете редактировать его содержимое. После этого, нажмите на
		кнопку «Обновить».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для обновления значений слайда нажмите на кнопку «Обновить».
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
