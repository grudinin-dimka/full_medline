<template>
	<General />

	<VueWikiTitle>
		<VueIcon name="Add" width="24" height="24" fill="var(--primary-color)" />
		Добавление контакта
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы добавить контакт, вам необходимо нажать но кнопку «Добавить», которая
		находится внизу блока «Контакты».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После нажатия на кнопку «Добавить», откроется модальное окно, в котором вы сможете добавить
		новый контакт.
	</VueWikiParagraph>
	<VueWikiParagraph>
		3. В случае, когда при нажатии на кнопку «Добавить» внизу модального окна появляется сообщение
		об ошибке, это означает, что вы не заполнили обязательные поля, которые помечаются красным
		цветом.
	</VueWikiParagraph>
	<VueWikiParagraph>
		4. После соблюдения предыдущих пунктов, вы сможете добавить новый контакт, нажав на кнопку
		«Добавить» внизу модального окна.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Delete" width="24" height="24" fill="var(--primary-color)" />
		Удаление контакта
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы удалить контакт, вам необходимо открыть выбранный контакт, нажав на него
		левой кнопкой мышки. После этого, появится кнопка для удаления выбранного контакта внизу
		модального окна, нажмите на неё.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для полного удаления контактов, которые помечены на удаление, нажмите на кнопку «Сохранить».
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Edit" width="24" height="24" fill="var(--primary-color)" />
		Изменение контакта
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы изменить контакт, вам необходимо открыть выбранный контакт, нажав на
		него левой кнопкой мышки. После этого, вы можете редактировать его содержимое. После этого,
		нажмите на кнопку Обновить».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для обновления значений контакта нажмите на кнопку «Обновить».
	</VueWikiParagraph>
	<VueWikiParagraph> 
      3. Для сохранения изменений нажмите на кнопку «Сохранить». 
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
