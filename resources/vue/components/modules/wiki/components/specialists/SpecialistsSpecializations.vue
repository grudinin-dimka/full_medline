<template>
	<General />

	<VueWikiTitle>
		<VueIcon name="Add" width="24" height="24" fill="var(--primary-color)" />
		Добавление специализации
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы добавить специализацию, вам необходимо нажать но кнопку «Добавить», которая
		находится внизу блока «Специализации».
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. После нажатия на кнопку «Добавить», откроется модальное окно, в котором вы сможете добавить
		новую специализацию.
	</VueWikiParagraph>
	<VueWikiParagraph>
		3. В случае, когда при нажатии на кнопку «Добавить» внизу модального окна появляется сообщение
		об ошибке, это означает, что вы не заполнили обязательные поля, которые помечаются красным
		цветом.
	</VueWikiParagraph>
	<VueWikiParagraph>
		4. После соблюдения предыдущих пунктов, вы сможете добавить новую специализацию, нажав на
		кнопку «Добавить» внизу модального окна.
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Delete" width="24" height="24" fill="var(--primary-color)" />
		Удаление специализации
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы удалить специализацию, вам необходимо нажать на кнопку удалить в строке с
		выбранной специализацией, тем самым пометить на удаление.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для полного удаления специализации, которые помечены на удаление, нажмите на кнопку
		«Сохранить».
	</VueWikiParagraph>

	<VueWikiTitle>
		<VueIcon name="Edit" width="24" height="24" fill="var(--primary-color)" />
		Изменение специализации
	</VueWikiTitle>
	<VueWikiParagraph>
		1. Для того, чтобы изменить специализацию, вам необходимо нажать на кнопку «Изменить» левой
		кнопкой мышки. После этого, вы можете редактировать его содержимое.
	</VueWikiParagraph>
	<VueWikiParagraph>
		2. Для обновления значений слайда нажмите на кнопку «Обновить.
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
